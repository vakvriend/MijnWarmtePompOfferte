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
var vkUtmData = getVkUtmData();
var SUBSIDIE = {
  lw:      {label: 'Lucht/water warmtepomp · Qvantum QA of Nibe F2040', bedrag: 2800, installatie: 9500, cop: 3.8, dekking: 0.90},
  vent:    {label: 'Ventilatie warmtepomp · Qvantum QE', bedrag: 1800, installatie: 7500, cop: 3.2, dekking: 0.35},
  bodem:   {label: 'Bodemwarmtepomp · Nibe S-serie of Qvantum QG', bedrag: 4500, installatie: 16000, cop: 4.5, dekking: 0.95},
  hybride: {label: 'Hybride warmtepomp · Intergas Xtend Eco', bedrag: 1800, installatie: 5000, cop: 2.8, dekking: 0.70},
  boiler:  {label: 'Warmtepompboiler · tapwater-oplossing', bedrag: 725, installatie: 3500, cop: 2.9, dekking: 0.22}
};

function getVkUtmData() {
  var key = 'vk_campaign_attribution';
  var params = new URLSearchParams(location.search || '');
  var fields = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'gclid', 'gbraid', 'wbraid', 'gad_source', 'gad_campaignid', 'msclkid'];
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

function pushVkLeadEvent(eventName, details) {
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push(Object.assign({
    event: eventName,
    page_url: location.href,
    hostname: location.hostname
  }, vkUtmData, details || {}));
}

function initCookieConsent() {
  var banner = document.querySelector('[data-cookie-banner]');
  if (!banner) return;

  var storageKey = 'vk_cookie_consent_v2';
  var settings = banner.querySelector('[data-cookie-settings]');
  var analyticsInput = settings ? settings.querySelector('input[name="analytics"]') : null;
  var marketingInput = settings ? settings.querySelector('input[name="marketing"]') : null;
  var customButton = banner.querySelector('[data-cookie-custom]');
  var saveButton = banner.querySelector('[data-cookie-save]');
  var acceptButton = banner.querySelector('[data-cookie-accept]');
  var rejectButton = banner.querySelector('[data-cookie-reject]');
  var openLinks = document.querySelectorAll('[data-cookie-open]');

  function readConsent() {
    try {
      var raw = window.localStorage.getItem(storageKey);
      return raw ? JSON.parse(raw) : null;
    } catch (e) {
      return null;
    }
  }

  function writeConsent(consent) {
    try {
      window.localStorage.setItem(storageKey, JSON.stringify(consent));
    } catch (e) {}
  }

  function cleanupMarketingCookies() {
    var names = ['_ga', '_gid', '_gat', '_gcl_au', '_gcl_aw', '_gcl_dc', '_fbp', '_fbc'];
    var hostParts = location.hostname.split('.');
    var domains = [location.hostname];
    if (hostParts.length > 2) domains.push(hostParts.slice(-2).join('.'));
    domains.push('.' + hostParts.slice(-2).join('.'));

    names.forEach(function(name) {
      document.cookie = name + '=; Max-Age=0; path=/; SameSite=Lax';
      domains.forEach(function(domain) {
        document.cookie = name + '=; Max-Age=0; path=/; domain=' + domain + '; SameSite=Lax';
      });
    });
  }

  function updateGoogleConsent(consent) {
    var analytics = !!consent.analytics;
    var marketing = !!consent.marketing;
    window.dataLayer = window.dataLayer || [];
    window.gtag = window.gtag || function(){ window.dataLayer.push(arguments); };
    window.gtag('consent', 'update', {
      ad_storage: marketing ? 'granted' : 'denied',
      ad_user_data: marketing ? 'granted' : 'denied',
      ad_personalization: marketing ? 'granted' : 'denied',
      analytics_storage: analytics ? 'granted' : 'denied',
      personalization_storage: marketing ? 'granted' : 'denied',
      functionality_storage: 'granted',
      security_storage: 'granted'
    });
    window.dataLayer.push({
      event: 'vk_consent_update',
      consent_analytics: analytics ? 'granted' : 'denied',
      consent_marketing: marketing ? 'granted' : 'denied'
    });
    if (!analytics && !marketing) cleanupMarketingCookies();
  }

  function saveChoice(analytics, marketing) {
    var consent = {
      version: 2,
      necessary: true,
      analytics: !!analytics,
      marketing: !!marketing,
      updatedAt: new Date().toISOString()
    };
    writeConsent(consent);
    updateGoogleConsent(consent);
    banner.hidden = true;
    document.documentElement.classList.remove('vk-cookie-open');
  }

  function showBanner(expanded) {
    var current = readConsent() || {analytics: false, marketing: false};
    if (analyticsInput) analyticsInput.checked = !!current.analytics;
    if (marketingInput) marketingInput.checked = !!current.marketing;
    if (settings) settings.hidden = !expanded;
    if (saveButton) saveButton.hidden = !expanded;
    if (customButton) customButton.hidden = !!expanded;
    banner.hidden = false;
    document.documentElement.classList.add('vk-cookie-open');
  }

  var existing = readConsent();
  if (existing) {
    updateGoogleConsent(existing);
  } else {
    showBanner(false);
  }

  if (acceptButton) acceptButton.addEventListener('click', function() { saveChoice(true, true); });
  if (rejectButton) rejectButton.addEventListener('click', function() { saveChoice(false, false); });
  if (customButton) customButton.addEventListener('click', function() { showBanner(true); });
  if (saveButton) saveButton.addEventListener('click', function() {
    saveChoice(analyticsInput && analyticsInput.checked, marketingInput && marketingInput.checked);
  });
  openLinks.forEach(function(link) {
    link.addEventListener('click', function(event) {
      event.preventDefault();
      showBanner(true);
    });
  });
}

function bootCookieConsent() {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCookieConsent, {once: true});
    return;
  }

  initCookieConsent();
}

function initCallbackForms() {
  document.querySelectorAll('.vk-callback-form').forEach(function(form) {
    var status = form.querySelector('.vk-callback-status');
    var submitButton = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', function(event) {
      event.preventDefault();
      if (!window.vkFrontend || !window.vkFrontend.ajaxUrl || !window.vkFrontend.callbackNonce) return;

      var phone = (form.querySelector('[name="phone"]') || {}).value || '';
      var name = (form.querySelector('[name="name"]') || {}).value || '';
      var postcode = (form.querySelector('[name="postcode"]') || {}).value || '';
      if (!phone.trim()) {
        if (status) status.textContent = 'Vul uw telefoonnummer in, dan kunnen we u terugbellen.';
        return;
      }


      if (submitButton) {
        submitButton.disabled = true;
        submitButton.textContent = 'Versturen...';
      }
      if (status) status.textContent = '';

      var body = new URLSearchParams();
      body.append('action', 'wc_callback_lead');
      body.append('nonce', window.vkFrontend.callbackNonce);
      body.append('name', name);
      body.append('phone', phone);
      body.append('postcode', postcode);
      body.append('page_url', location.href);
      body.append('hostname', location.hostname);
      body.append('utm_source', vkUtmData.utm_source || '');
      body.append('utm_medium', vkUtmData.utm_medium || '');
      body.append('utm_campaign', vkUtmData.utm_campaign || '');
      body.append('utm_content', vkUtmData.utm_content || '');
      body.append('utm_term', vkUtmData.utm_term || '');
      body.append('gclid', vkUtmData.gclid || '');
      body.append('gbraid', vkUtmData.gbraid || '');
      body.append('wbraid', vkUtmData.wbraid || '');
      body.append('gad_source', vkUtmData.gad_source || '');
      body.append('gad_campaignid', vkUtmData.gad_campaignid || '');
      body.append('msclkid', vkUtmData.msclkid || '');

      fetch(window.vkFrontend.ajaxUrl, {
        method: 'POST',
        body: body,
        credentials: 'same-origin'
      })
        .then(function(response) { return response.json(); })
        .then(function(json) {
          if (!json || !json.success) throw new Error((json && json.data && json.data.message) || 'Niet gelukt');
          form.classList.add('is-success');
          form.reset();
          pushVkLeadEvent('form_submit', {
            form_name: 'callback_form',
            lead_type: 'callback',
            lead_status: 'success'
          });
          if (status) status.textContent = json.data.message || 'Top, we nemen zo snel mogelijk contact op.';
        })
        .catch(function(error) {
          if (status) status.textContent = error.message || 'Versturen lukt nu niet. Probeer het later nog eens.';
        })
        .finally(function() {
          if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = 'Bel mij terug';
          }
        });
    });
  });

  var leadWidget = document.querySelector('hz-embed');
  var trackedHomezeroSubmit = false;
  document.addEventListener('submit', function(event) {
    var form = event.target;
    if (!form || !form.closest || !form.closest('.vk-homezero-widget')) return;
    if (trackedHomezeroSubmit) return;

    var postcode = form.querySelector('#postcode');
    var huisnummer = form.querySelector('#huisnummer');
    var email = form.querySelector('#email');
    var postcodeValid = !postcode || /^[1-9][0-9]{3}\s?[A-Za-z]{2}$/.test((postcode.value || '').trim());
    var huisnummerValid = !huisnummer || /[a-zA-Z0-9]/.test((huisnummer.value || '').trim());
    var emailValid = !email || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test((email.value || '').trim());

    if (postcodeValid && huisnummerValid && emailValid) {
      trackedHomezeroSubmit = true;
      pushVkLeadEvent('form_submit', {
        form_name: 'homezero_airco_scan',
        lead_type: 'airco_scan_start',
        lead_status: 'valid_submit'
      });
    }
  }, true);

  if (leadWidget && 'IntersectionObserver' in window) {
    var leadObserver = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        document.body.classList.toggle('vk-form-in-view', entry.isIntersecting);
      });
    }, {threshold: 0.12});
    leadObserver.observe(leadWidget);
  }

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

function initWoningcheck() {
  document.querySelectorAll('.vk-woningcheck').forEach(function(form) {
    var card = form.closest('.vk-woningcheck-card');
    var fields = Array.prototype.slice.call(form.querySelectorAll('input,select'));
    var routeEl = form.querySelector('[data-wc-route]');
    var scoreEl = form.querySelector('[data-wc-score]');
    var homeEl = form.querySelector('[data-wc-home]');
    var heatEl = form.querySelector('[data-wc-heat]');
    var subsidyEl = form.querySelector('[data-wc-subsidy]');
    var checksEl = form.querySelector('[data-wc-checks]');

    function value(name, fallback) {
      var el = form.elements[name];
      return el && el.value !== '' ? el.value : fallback;
    }

    function num(name, fallback) {
      var parsed = parseFloat(String(value(name, fallback)).replace(',', '.'));
      return isNaN(parsed) ? fallback : parsed;
    }

    function labelScore(label) {
      if (/A\+\+|A\+|A/.test(label)) return 18;
      if (label === 'B') return 14;
      if (label === 'C') return 9;
      if (label === 'D') return 4;
      return 7;
    }

    function update() {
      var bouwjaar = num('bouwjaar', 2003);
      var woonruimte = num('woonruimte', 159);
      var gas = num('gas', 1910);
      var stroom = num('stroom', 4834);
      var personen = num('personen', 3);
      var label = value('label', 'A');
      var woningtype = value('woningtype', 'Tussenwoning');
      var afgifte = value('afgifte', 'Radiatoren en vloerverwarming');
      var ventilatie = value('ventilatie', 'Mechanische ventilatie');
      var zonnepanelen = value('zonnepanelen', 'Ja');
      var heat = Math.max(2800, Math.round((gas > 0 ? gas * 8.8 : woonruimte * 72) + personen * 720));
      var hasFloor = /vloer/i.test(afgifte);
      var isApartment = /appartement/i.test(woningtype);
      var isGoodLabel = /A|B/.test(label);
      var route = 'Lucht/water warmtepomp';
      var subsidy = '\u20ac2.800 - \u20ac4.500';
      var score = 52 + labelScore(label) + (hasFloor ? 10 : 2) + (bouwjaar >= 2000 ? 9 : 3) + (zonnepanelen === 'Ja' ? 5 : 0) + (stroom > 3500 ? 2 : 0);
      var checks = [
        'Buitenunitpositie en geluid controleren',
        'Afgiftesysteem nalopen op lage temperatuur',
        'Tapwaterprofiel en subsidie exact maken'
      ];

      if (isApartment || /mechanische/i.test(ventilatie) && gas < 1400) {
        route = 'Ventilatiewarmtepomp of hybride';
        subsidy = '\u20ac1.800 - \u20ac2.800';
        score += isApartment ? 3 : 0;
        checks = [
          'Ventilatiedebiet en kanaalwerk controleren',
          'Tapwater apart beoordelen',
          'Checken of buitenunit echt nodig is'
        ];
      } else if (!isGoodLabel || gas > 2400 || !hasFloor) {
        route = 'Hybride warmtepomp';
        subsidy = '\u20ac1.800 - \u20ac3.000';
        score -= !isGoodLabel ? 8 : 0;
        checks = [
          'Radiatoren controleren op lage temperatuur',
          'Isolatie en kierdichting meenemen',
          'Cv-ketel en hybride aansturing beoordelen'
        ];
      } else if (woonruimte > 190 && gas > 1900) {
        route = 'Bodemwarmtepomp onderzoeken';
        subsidy = '\u20ac4.000 - \u20ac6.500';
        score += 2;
        checks = [
          'Perceel, boring en vergunning controleren',
          'Bronvermogen en ruimteverwarming doorrekenen',
          'Investering vergelijken met lucht/water'
        ];
      }

      score = Math.max(38, Math.min(94, Math.round(score)));
      if (routeEl) routeEl.textContent = route;
      if (scoreEl) scoreEl.textContent = score + '%';
      if (homeEl) homeEl.textContent = woningtype + ' · ' + Math.round(woonruimte).toLocaleString('nl-NL') + ' m\u00b2 · label ' + label;
      if (heatEl) heatEl.textContent = 'ca. ' + heat.toLocaleString('nl-NL') + ' kWh/jaar';
      if (subsidyEl) subsidyEl.textContent = subsidy;
      if (checksEl) checksEl.innerHTML = checks.map(function(check) { return '<li>' + check + '</li>'; }).join('');
    }

    fields.forEach(function(field) {
      field.addEventListener('input', update);
      field.addEventListener('change', update);
    });

    form.addEventListener('submit', function(event) {
      event.preventDefault();
      update();
      var summary = [
        'Woningcheck',
        'Adres: ' + value('postcode', '-') + ' ' + value('huisnummer', ''),
        'Woning: ' + (homeEl ? homeEl.textContent : ''),
        'Verbruik: ' + Math.round(num('gas', 0)).toLocaleString('nl-NL') + ' m3 gas / ' + Math.round(num('stroom', 0)).toLocaleString('nl-NL') + ' kWh stroom',
        'Route: ' + (routeEl ? routeEl.textContent : ''),
        'Warmtevraag: ' + (heatEl ? heatEl.textContent : '')
      ].join('\n');
      var base = card ? card.getAttribute('data-whatsapp-url') : '';
      if (base) window.location.href = base.replace(/text=[^&]*/, 'text=' + encodeURIComponent(summary));
    });

    update();
  });
}

document.querySelectorAll('a[href^="#"]').forEach(function(a) {
  a.addEventListener('click', function(e) {
    var t = document.querySelector(this.getAttribute('href'));
    if (t) {
      e.preventDefault();
      window.scrollTo({top: t.getBoundingClientRect().top + window.scrollY - 120, behavior: 'smooth'});
    }
  });
});

document.querySelectorAll('a[href*="#"]').forEach(function(a) {
  a.addEventListener('click', function(e) {
    var href = this.getAttribute('href') || '';
    if (href.charAt(0) === '#') return;

    var url;
    try {
      url = new URL(href, window.location.href);
    } catch (err) {
      return;
    }

    if (!url.hash || url.origin !== window.location.origin || url.pathname.replace(/\/$/, '') !== window.location.pathname.replace(/\/$/, '')) {
      return;
    }

    var target = document.querySelector(url.hash);
    if (!target) return;

    e.preventDefault();
    history.pushState(null, '', url.hash);
    window.scrollTo({top: target.getBoundingClientRect().top + window.scrollY - 120, behavior: 'smooth'});
  });
});

function initScanVideoAutoplay() {
  var videos = document.querySelectorAll('.vv2-scan-video');
  if (!videos.length) return;

  function tryPlay(video) {
    video.muted = true;
    video.defaultMuted = true;
    video.playsInline = true;
    video.setAttribute('muted', '');
    video.setAttribute('playsinline', '');
    video.setAttribute('webkit-playsinline', '');
    var promise = video.play();
    if (promise && promise.catch) promise.catch(function() {});
  }

  videos.forEach(function(video) {
    video.addEventListener('loadedmetadata', function() { tryPlay(video); }, {once: true});
    video.addEventListener('canplay', function() { tryPlay(video); }, {once: true});
    tryPlay(video);
  });

  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) tryPlay(entry.target);
      });
    }, {threshold: 0.2});
    videos.forEach(function(video) { observer.observe(video); });
  }

  ['touchstart', 'pointerdown', 'scroll'].forEach(function(eventName) {
    window.addEventListener(eventName, function() {
      videos.forEach(tryPlay);
    }, {once: true, passive: true});
  });
}

function clarifyAircoWidgetLabels() {
  if (!document.body || !document.body.classList.contains('mkp-airco-site')) return;

  function updateLabels() {
    document.querySelectorAll('.vv2-form-panel label, .mkp-conversion-form label').forEach(function(label) {
      var text = (label.childNodes[0] && label.childNodes[0].textContent || label.textContent || '').trim();
      if (text === 'Toevoeging') {
        label.childNodes[0].textContent = 'Toevoeging (optioneel)';
      }
    });
  }

  updateLabels();
  if ('MutationObserver' in window) {
    var observer = new MutationObserver(updateLabels);
    var panel = document.querySelector('.vv2-form-panel, .mkp-conversion-form');
    if (panel) observer.observe(panel, {childList: true, subtree: true});
  }
}

if (document.getElementById('calc-gas')) vkCalc();

initWoningcheck();
initScanVideoAutoplay();
bootCookieConsent();
initCallbackForms();
clarifyAircoWidgetLabels();

})();
