(function(){
'use strict';

var nb = document.getElementById('navbar');
if (nb) window.addEventListener('scroll', function() {
  nb.classList.toggle('scrolled', window.scrollY > 80);
}, {passive: true});

if ('IntersectionObserver' in window) {
  var obs = new IntersectionObserver(function(es) {
    es.forEach(function(e) {
      if (e.isIntersecting) { e.target.classList.add('zichtbaar'); obs.unobserve(e.target); }
    });
  }, {threshold: 0.1});
  document.querySelectorAll('.reveal,.vk-reveal').forEach(function(el) { obs.observe(el); });
}

var calcSysteem = 'lw';
var vkSessionId = getVkSessionId();
var vkPageStartedAt = Date.now();
var vkMaxScroll = 0;
var vkLastSection = 'hero';
var vkHeartbeatTimer = null;
var vkScrollMarks = {};
var vkClickHistory = [];
var vkLastClickKey = '';
var vkLastClickAt = 0;
var vkUtmData = getVkUtmData();
var SUBSIDIE = {
  lw:      {label: 'Lucht/water warmtepomp · Qvantum QA of Nibe F2040', bedrag: 2800, installatie: 9500, cop: 3.8, dekking: 0.90},
  vent:    {label: 'Ventilatie warmtepomp · Qvantum QE', bedrag: 1800, installatie: 7500, cop: 3.2, dekking: 0.35},
  bodem:   {label: 'Bodemwarmtepomp · Nibe S-serie of Qvantum QG', bedrag: 4500, installatie: 16000, cop: 4.5, dekking: 0.95},
  hybride: {label: 'Hybride warmtepomp · Intergas Xtend Eco', bedrag: 1800, installatie: 5000, cop: 2.8, dekking: 0.70},
  boiler:  {label: 'Warmtepompboiler · tapwater-oplossing', bedrag: 725, installatie: 3500, cop: 2.9, dekking: 0.22}
};

function vkTrack(eventName, payload) {
  payload = payload || {};
  payload.duration_ms = payload.duration_ms || (Date.now() - vkPageStartedAt);
  payload.scroll_depth = payload.scroll_depth || getVkScrollDepth();
  payload.section = payload.section || vkLastSection;
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push(Object.assign({
    event: eventName,
    page_location: location.href,
    page_hostname: location.hostname,
    session_id: vkSessionId
  }, payload));
  vkTrackGa4(eventName, payload);
  vkTrackWp(eventName, payload);
}

function getVkSessionId() {
  var key = 'vk_campaign_session_id';
  try {
    var existing = window.sessionStorage.getItem(key);
    if (existing) return existing;
    var id = 'vk_' + Date.now().toString(36) + '_' + Math.random().toString(36).slice(2, 10);
    window.sessionStorage.setItem(key, id);
    return id;
  } catch (e) {
    return 'vk_' + Date.now().toString(36) + '_' + Math.random().toString(36).slice(2, 10);
  }
}

function vkTrackGa4(eventName, payload) {
  if (!window.vkGa4MeasurementId || typeof window.gtag !== 'function') return;

  var eventMap = {
    calculator_system_select: 'calculator_system_select',
    lead_cta_click: 'lead_cta_click',
    phone_click: 'phone_click',
    whatsapp_click: 'whatsapp_click'
  };
  var gaEventName = eventMap[eventName];
  if (!gaEventName) return;

  var params = Object.assign({
    send_to: window.vkGa4MeasurementId,
    event_source: 'warmtepomp_campaign',
    page_location: location.href,
    page_hostname: location.hostname
  }, payload || {});

  window.gtag('event', gaEventName, params);
}

function vkTrackWp(eventName, payload) {
  if (!window.vkAnalytics || !window.vkAnalytics.ajaxUrl || !window.vkAnalytics.nonce) return;

  var data = Object.assign({
    event_name: eventName,
    session_id: vkSessionId,
    page_url: location.href,
    page_path: location.pathname || '/',
    hostname: location.hostname,
    referrer: document.referrer || '',
    section: vkLastSection,
    device_type: getVkDeviceType(),
    browser: getVkBrowser(),
    viewport_width: window.innerWidth || 0,
    viewport_height: window.innerHeight || 0,
    screen_width: window.screen ? window.screen.width : 0,
    screen_height: window.screen ? window.screen.height : 0,
    timezone: Intl.DateTimeFormat ? Intl.DateTimeFormat().resolvedOptions().timeZone : '',
    language: navigator.language || '',
    utm_source: vkUtmData.utm_source || '',
    utm_medium: vkUtmData.utm_medium || '',
    utm_campaign: vkUtmData.utm_campaign || '',
    utm_content: vkUtmData.utm_content || '',
    utm_term: vkUtmData.utm_term || '',
    gclid: vkUtmData.gclid || '',
    landing_page: vkUtmData.landing_page || '',
    duration_ms: Date.now() - vkPageStartedAt,
    scroll_depth: getVkScrollDepth()
  }, payload || {});

  var body = new URLSearchParams();
  body.append('action', 'wc_analytics_event');
  body.append('nonce', window.vkAnalytics.nonce);
  body.append('payload', JSON.stringify(data));

  if (navigator.sendBeacon && (eventName === 'page_exit' || eventName === 'heartbeat')) {
    navigator.sendBeacon(window.vkAnalytics.ajaxUrl, body);
    return;
  }

  fetch(window.vkAnalytics.ajaxUrl, {
    method: 'POST',
    body: body,
    credentials: 'same-origin',
    keepalive: eventName === 'page_exit'
  }).catch(function(){});
}

function getVkUtmData() {
  var key = 'vk_campaign_attribution';
  var params = new URLSearchParams(location.search || '');
  var fields = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'gclid', 'gbraid', 'wbraid'];
  var found = {};
  fields.forEach(function(field) {
    var value = params.get(field);
    if (value) found[field] = value.slice(0, 190);
  });
  try {
    var stored = JSON.parse(window.sessionStorage.getItem(key) || '{}');
    var data = Object.assign({}, stored, found);
    if (!data.landing_page) data.landing_page = location.href;
    if (Object.keys(found).length || !stored.landing_page) {
      window.sessionStorage.setItem(key, JSON.stringify(data));
    }
    return data;
  } catch (e) {
    found.landing_page = location.href;
    return found;
  }
}

function getVkDeviceType() {
  var w = window.innerWidth || 0;
  var ua = navigator.userAgent || '';
  if (/ipad|tablet/i.test(ua) || (w >= 768 && w <= 1180 && /mobile|android/i.test(ua))) return 'tablet';
  if (w < 768 || /mobi|iphone|android/i.test(ua)) return 'mobile';
  return 'desktop';
}

function getVkBrowser() {
  var ua = navigator.userAgent || '';
  if (/Edg\//.test(ua)) return 'Edge';
  if (/Chrome\//.test(ua) && !/Chromium|Edg\//.test(ua)) return 'Chrome';
  if (/Safari\//.test(ua) && !/Chrome\//.test(ua)) return 'Safari';
  if (/Firefox\//.test(ua)) return 'Firefox';
  return 'Other';
}

function getVkScrollDepth() {
  var doc = document.documentElement;
  var body = document.body;
  var scrollTop = window.scrollY || doc.scrollTop || body.scrollTop || 0;
  var height = Math.max(body.scrollHeight, doc.scrollHeight, body.offsetHeight, doc.offsetHeight) - window.innerHeight;
  var depth = height > 0 ? Math.round((scrollTop / height) * 100) : 100;
  vkMaxScroll = Math.max(vkMaxScroll, Math.min(100, Math.max(0, depth)));
  return vkMaxScroll;
}

function getVkSectionName(el) {
  if (!el) return 'unknown';
  if (el.id) return el.id;
  var heading = el.querySelector('h1,h2,h3');
  if (heading && heading.textContent) return heading.textContent.trim().slice(0, 90);
  if (el.className && typeof el.className === 'string') return el.className.split(/\s+/).slice(0, 2).join('.');
  return el.tagName ? el.tagName.toLowerCase() : 'unknown';
}

function getVkElementInfo(el, event) {
  var target = el && el.closest ? el.closest('a,button,input,select,textarea,summary,[role="button"],hz-embed,.vk-type-card,.vk-praktijk-card,.vk-faq') : el;
  target = target || el;
  var rect = target && target.getBoundingClientRect ? target.getBoundingClientRect() : {left: 0, top: 0, width: 0, height: 0};
  var text = '';
  if (target) {
    text = (target.getAttribute && (target.getAttribute('aria-label') || target.getAttribute('title') || target.getAttribute('data-analytics-label'))) || target.textContent || target.value || '';
  }
  text = String(text || '').replace(/\s+/g, ' ').trim().slice(0, 140);
  return {
    target_text: text,
    target_tag: target && target.tagName ? target.tagName.toLowerCase() : '',
    target_id: target && target.id ? target.id : '',
    target_classes: target && target.className && typeof target.className === 'string' ? target.className.split(/\s+/).slice(0, 5).join(' ') : '',
    link_url: target && target.href ? target.href : '',
    click_x: event ? Math.round(event.clientX || 0) : 0,
    click_y: event ? Math.round(event.clientY || 0) : 0,
    element_x: Math.round(rect.left || 0),
    element_y: Math.round(rect.top || 0),
    element_width: Math.round(rect.width || 0),
    element_height: Math.round(rect.height || 0)
  };
}

function initVkAnalytics() {
  vkTrack('page_view', {
    title: document.title || '',
    landing_referrer: document.referrer || ''
  });

  window.addEventListener('scroll', function() {
    var depth = getVkScrollDepth();
    [25, 50, 75, 90].forEach(function(mark) {
      if (depth >= mark && !vkScrollMarks[mark]) {
        vkScrollMarks[mark] = true;
        vkTrack('scroll_depth', {scroll_depth: mark});
      }
    });
  }, {passive: true});

  document.addEventListener('click', function(event) {
    var info = getVkElementInfo(event.target, event);
    var interactive = event.target.closest && event.target.closest('a,button,input,select,textarea,summary,[role="button"],hz-embed');
    var now = Date.now();
    var clickKey = [info.target_tag, info.target_text, Math.round(info.click_x / 20), Math.round(info.click_y / 20)].join('|');
    vkClickHistory = vkClickHistory.filter(function(click) { return now - click.time < 1600; });
    vkClickHistory.push({time: now, key: clickKey});
    var repeated = vkClickHistory.filter(function(click) { return click.key === clickKey; }).length;

    if (interactive) {
      vkTrack('element_click', info);
    } else {
      vkTrack('dead_click', info);
    }

    if (repeated >= 3 || (vkLastClickKey === clickKey && now - vkLastClickAt < 500)) {
      vkTrack('rage_click', Object.assign({clicks_in_burst: repeated}, info));
    }
    vkLastClickKey = clickKey;
    vkLastClickAt = now;

    if (info.link_url && info.link_url.indexOf(location.hostname) === -1 && /^https?:/i.test(info.link_url)) {
      vkTrack('outbound_click', info);
    }
  }, true);

  document.querySelectorAll('details.vk-faq, details').forEach(function(details) {
    details.addEventListener('toggle', function() {
      if (!details.open) return;
      vkTrack('faq_open', getVkElementInfo(details, null));
    });
  });

  document.querySelectorAll('input,select,textarea').forEach(function(field) {
    var tracked = false;
    field.addEventListener('focus', function() {
      if (tracked) return;
      tracked = true;
      vkTrack('field_focus', {
        field_name: field.name || field.id || field.getAttribute('aria-label') || '',
        field_type: field.type || field.tagName.toLowerCase()
      });
    });
  });

  document.querySelectorAll('hz-embed').forEach(function(widget) {
    vkTrack('homezero_widget_visible', {
      widget_title: widget.getAttribute('data-title') || '',
      widget_src: widget.getAttribute('src') || ''
    });
    widget.addEventListener('click', function(event) {
      vkTrack('homezero_widget_click', getVkElementInfo(widget, event));
    });
  });

  if ('IntersectionObserver' in window) {
    var sectionObserver = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (!entry.isIntersecting) return;
        vkLastSection = getVkSectionName(entry.target);
        vkTrack('section_view', {section: vkLastSection});
      });
    }, {threshold: 0.55});
    document.querySelectorAll('section,.vk-hero,.vk-form-card,.vk-product-section').forEach(function(el) {
      sectionObserver.observe(el);
    });
  }

  vkHeartbeatTimer = window.setInterval(function() {
    vkTrack('heartbeat', {
      engagement_score: getVkEngagementScore()
    });
  }, 15000);

  function sendExit() {
    if (vkHeartbeatTimer) window.clearInterval(vkHeartbeatTimer);
    vkTrack('page_exit', {
      duration_ms: Date.now() - vkPageStartedAt,
      scroll_depth: getVkScrollDepth(),
      section: vkLastSection
    });
  }
  window.addEventListener('pagehide', sendExit);
  document.addEventListener('visibilitychange', function() {
    if (document.visibilityState === 'hidden') sendExit();
  });
}

function getVkEngagementScore() {
  var seconds = Math.min(300, Math.round((Date.now() - vkPageStartedAt) / 1000));
  return Math.min(100, Math.round((seconds / 3) + (getVkScrollDepth() * 0.55)));
}

function vkBereken(g, gp, sys) {
  var s = SUBSIDIE[sys] || SUBSIDIE.lw;
  var gasBespaard = g * (s.dekking || 1);
  var b = Math.max(0, gasBespaard * gp - (gasBespaard * 9.77 / s.cop) * 0.27);
  var tvt = b > 0 ? (s.installatie - s.bedrag) / b : 0;
  return {b: b, subsidie: s.bedrag, tvt: tvt, label: s.label};
}
function fmt(n) { return '\u20ac' + Math.round(n).toLocaleString('nl-NL'); }

window.vkKiesSysteem = function(btn, sys) {
  document.querySelectorAll('.vk-sys-btn').forEach(function(b) { b.classList.remove('actief'); });
  btn.classList.add('actief');
  calcSysteem = sys;
  // Update calc-sys-lbl label
  var lb = document.getElementById('calc-sys-lbl'); if (lb && SUBSIDIE[sys]) lb.textContent = SUBSIDIE[sys].label;
  vkTrack('calculator_system_select', {
    calculator_name: 'warmtepomp_besparing',
    systeem: sys
  });
  vkCalc();
};

window.vkCalc = function() {
  var g = parseInt((document.getElementById('calc-gas') || {value: 1800}).value);
  var gp = parseFloat((document.getElementById('calc-gp') || {value: 1.25}).value);
  var gv = document.getElementById('calc-gas-v'); if (gv) gv.textContent = g.toLocaleString('nl-NL') + ' m\u00b3';
  var gpv = document.getElementById('calc-gp-v'); if (gpv) gpv.textContent = '\u20ac' + gp.toFixed(2).replace('.', ',');
  var res = vkBereken(g, gp, calcSysteem);
  var r1 = document.getElementById('c-besp'); if (r1) r1.textContent = fmt(res.b) + ' *';
  var r2 = document.getElementById('c-sub'); if (r2) r2.textContent = 'gem. ' + fmt(res.subsidie) + ' *';
  var r3 = document.getElementById('c-tvt'); if (r3) r3.textContent = res.tvt > 0 ? res.tvt.toFixed(1) + ' jr' : '\u2014';
  var lb = document.getElementById('calc-sys-lbl'); if (lb) lb.textContent = res.label;
  window.clearTimeout(window.vkCalcTrackTimer);
  window.vkCalcTrackTimer = window.setTimeout(function() {
    vkTrack('calculator_change', {
      calculator_name: 'warmtepomp_besparing',
      gas_usage: g,
      gas_price: gp,
      systeem: calcSysteem,
      savings_estimate: Math.round(res.b),
      subsidy_estimate: Math.round(res.subsidie),
      payback_years: res.tvt > 0 ? Number(res.tvt.toFixed(1)) : 0
    });
  }, 600);
};

var chatGeschiedenis = [];
var chatBezig = false;

window.vkChatStuur = function() {
  if (chatBezig) return;
  var input = document.getElementById('chat-input');
  if (!input || !input.value.trim()) return;
  var tekst = input.value.trim();
  input.value = '';
  var msgs = document.getElementById('chat-msgs');
  if (!msgs) return;
  var userEl = document.createElement('div');
  userEl.className = 'vk-chat-msg vk-chat-user';
  userEl.textContent = tekst;
  msgs.appendChild(userEl);
  msgs.scrollTop = msgs.scrollHeight;
  chatGeschiedenis.push({role: 'user', content: tekst});
  if (chatGeschiedenis.length > 10) chatGeschiedenis = chatGeschiedenis.slice(-10);
  chatBezig = true;
  var typing = document.createElement('div');
  typing.className = 'vk-chat-msg vk-chat-bot';
  typing.id = 'chat-typing';
  typing.textContent = '...';
  msgs.appendChild(typing);
  msgs.scrollTop = msgs.scrollHeight;
  var data = new FormData();
  data.append('action', 'vk_chat');
  data.append('nonce', typeof vkChat !== 'undefined' ? vkChat.nonce : '');
  data.append('messages', JSON.stringify(chatGeschiedenis));
  var url = typeof vkChat !== 'undefined' ? vkChat.ajaxUrl : '/wp-admin/admin-ajax.php';
  fetch(url, {method: 'POST', body: data})
    .then(function(r) { return r.json(); })
    .then(function(json) {
      var t = document.getElementById('chat-typing'); if (t) t.remove();
      chatBezig = false;
      var antwoord = json.success ? json.data.message : 'Bel ons op 075 234 0001.';
      var botEl = document.createElement('div');
      botEl.className = 'vk-chat-msg vk-chat-bot';
      botEl.innerHTML = antwoord.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
      msgs.appendChild(botEl);
      msgs.scrollTop = msgs.scrollHeight;
      if (json.success) chatGeschiedenis.push({role: 'assistant', content: antwoord});
    })
    .catch(function() {
      var t = document.getElementById('chat-typing'); if (t) t.remove();
      chatBezig = false;
      var botEl = document.createElement('div');
      botEl.className = 'vk-chat-msg vk-chat-bot';
      botEl.textContent = 'Bel ons: 075 234 0001';
      msgs.appendChild(botEl);
      msgs.scrollTop = msgs.scrollHeight;
    });
};

document.querySelectorAll('a[href^="#"]').forEach(function(a) {
  a.addEventListener('click', function(e) {
    var t = document.querySelector(this.getAttribute('href'));
    if (t) {
      e.preventDefault();
      if (this.getAttribute('href') === '#formulier') {
        vkTrack('lead_cta_click', {
          cta_text: (this.textContent || '').trim(),
          cta_location: this.closest('.vk-mobile-sticky') ? 'mobile_sticky' : this.closest('.vk-hero') ? 'hero' : this.closest('.navbar') ? 'nav' : 'page'
        });
      }
      window.scrollTo({top: t.getBoundingClientRect().top + window.scrollY - 120, behavior: 'smooth'});
    }
  });
});

document.querySelectorAll('a[href^="tel:"]').forEach(function(a) {
  a.addEventListener('click', function() {
    vkTrack('phone_click', {link_url: this.href});
  });
});

document.querySelectorAll('a[href*="wa.me"]').forEach(function(a) {
  a.addEventListener('click', function() {
    vkTrack('whatsapp_click', {link_url: this.href});
  });
});

if (document.getElementById('calc-gas')) vkCalc();

initVkAnalytics();

})();
