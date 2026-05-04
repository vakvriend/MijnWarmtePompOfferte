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
var SUBSIDIE = {
  lw:      {label: 'Lucht/water warmtepomp · Qvantum QA of Nibe F2040', bedrag: 2800, installatie: 9500, cop: 3.8, dekking: 0.90},
  vent:    {label: 'Ventilatie warmtepomp · Qvantum QE', bedrag: 1800, installatie: 7500, cop: 3.2, dekking: 0.35},
  bodem:   {label: 'Bodemwarmtepomp · Nibe S-serie of Qvantum QG', bedrag: 4500, installatie: 16000, cop: 4.5, dekking: 0.95},
  hybride: {label: 'Hybride warmtepomp · Intergas Xtend Eco', bedrag: 1800, installatie: 5000, cop: 2.8, dekking: 0.70},
  boiler:  {label: 'Warmtepompboiler · tapwater-oplossing', bedrag: 725, installatie: 3500, cop: 2.9, dekking: 0.22}
};

function vkTrack(eventName, payload) {
  payload = payload || {};
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push(Object.assign({
    event: eventName,
    page_location: location.href,
    page_hostname: location.hostname,
    session_id: vkSessionId
  }, payload));
  vkTrackGa4(eventName, payload);
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


})();
