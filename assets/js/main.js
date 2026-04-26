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

var fd = {};
var calcSysteem = 'lw';
var SUBSIDIE = {
  lw:      {label: 'Lucht/water warmtepomp · Qvantum QA of Nibe F2040', bedrag: 2800, installatie: 9500},
  vent:    {label: 'Ventilatie warmtepomp · Qvantum QE', bedrag: 1800, installatie: 7500},
  bodem:   {label: 'Bodemwarmtepomp · Nibe S-serie of Qvantum QG', bedrag: 4500, installatie: 16000},
  hybride: {label: 'Hybride warmtepomp · Intergas Xtend Eco', bedrag: 1800, installatie: 5000}
};

function vkTrack(eventName, payload) {
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push(Object.assign({
    event: eventName,
    page_location: location.href,
    page_hostname: location.hostname
  }, payload || {}));
  vkTrackGa4(eventName, payload);
}

function vkTrackGa4(eventName, payload) {
  if (!window.vkGa4MeasurementId || typeof window.gtag !== 'function') return;

  var eventMap = {
    lead_form_choice: 'lead_form_choice',
    lead_form_step: 'lead_form_step',
    lead_form_submit_attempt: 'lead_form_submit_attempt',
    lead_form_success: 'generate_lead',
    lead_form_error: 'lead_form_error',
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

  if (eventName === 'lead_form_success') {
    params.value = 100;
    params.currency = 'EUR';
  }

  window.gtag('event', gaEventName, params);

  if (eventName === 'lead_form_success') {
    window.gtag('event', 'qualify_lead', params);
  }
}

function vkTrackAdsLeadConversion() {
  window.dataLayer = window.dataLayer || [];
  window.gtag = window.gtag || function() {
    window.dataLayer.push(arguments);
  };

  window.gtag('event', 'conversion', {
    send_to: 'AW-18103465341/9j_GCMTd-qIcEP3qs7hD',
    value: 100,
    currency: 'EUR'
  });
}

window.vkKies = function(btn, key, val) {
  btn.closest('.vk-keuze-grid').querySelectorAll('.vk-keuze').forEach(function(b) { b.classList.remove('actief'); });
  btn.classList.add('actief');
  fd[key] = val;
  vkTrack('lead_form_choice', {
    form_name: 'warmtepomp_offerte',
    field_name: key,
    field_value: val
  });
  if (key === 'systeem') {
    var tip = document.getElementById('subsidie-tip');
    if (tip) {
      var s = val.includes('Ventilatie') ? SUBSIDIE.vent : val.includes('Bodem') ? SUBSIDIE.bodem : val.includes('Hybride') ? SUBSIDIE.hybride : val.includes('Weet') ? null : SUBSIDIE.lw;
      if (s) { tip.innerHTML = 'Geschatte ISDE-subsidie: <strong>gem. ' + fmt(s.bedrag) + '</strong> — exacte bedrag hangt af van merk, vermogen en energielabel. Vakvriend berekent dit gratis voor u.'; tip.style.display = 'block'; }
      else { tip.innerHTML = 'Vakvriend adviseert gratis welk systeem past.'; tip.style.display = 'block'; }
    }
    // Herbereken mini-besparing direct
    vkUpdateGas();
  }
};

window.vkStap = function(n) {
  var fout = document.querySelector('.vk-fout');
  if (fout) fout.remove();
  if (n === 2 && !fd.woningtype) { vkFout('Selecteer eerst uw woningtype.'); return; }
  if (n === 3 && !fd.systeem) { vkFout('Selecteer eerst uw systeemvoorkeur.'); return; }
  document.querySelectorAll('.vk-stap').forEach(function(s) { s.classList.remove('active'); });
  var s = document.getElementById('stap-' + n);
  if (s) s.classList.add('active');
  document.body.classList.toggle('vk-form-step-4', n === 4);
  document.body.classList.remove('vk-form-success');
  document.querySelectorAll('.vk-prog-dot').forEach(function(d, i) { d.classList.toggle('active', i < n); });
  vkTrack('lead_form_step', {
    form_name: 'warmtepomp_offerte',
    step_number: n,
    woningtype: fd.woningtype || '',
    systeem: fd.systeem || ''
  });
  var f = document.getElementById('formulier');
  if (f && window.innerWidth < 1020) setTimeout(function() { f.scrollIntoView({behavior: 'smooth', block: 'start'}); }, 100);
};

window.vkVerstuur = async function() {
  var naam = (document.getElementById('vk-naam') || {}).value || '';
  naam = naam.trim();
  var email = (document.getElementById('vk-email') || {}).value || '';
  email = email.trim();
  var tel = (document.getElementById('vk-tel') || {}).value || '';
  var pc = (document.getElementById('vk-pc') || {}).value || '';
  if (!naam) { vkFout('Vul uw naam in.'); return; }
  if (!email || !email.includes('@')) { vkFout('Vul een geldig e-mailadres in.'); return; }
  vkTrack('lead_form_submit_attempt', {
    form_name: 'warmtepomp_offerte',
    woningtype: fd.woningtype || '',
    systeem: fd.systeem || '',
    gasverbruik: fd.gasverbruik || ''
  });
  var btn = document.querySelector('.vk-btn-oranje');
  if (btn) { btn.disabled = true; btn.textContent = 'Bezig...'; }
  var p = new URLSearchParams(location.search);
  var data = new FormData();
  data.append('action', 'wc_lead');
  data.append('nonce', typeof wcVars !== 'undefined' ? wcVars.nonce : '');
  data.append('naam', naam); data.append('email', email);
  data.append('telefoon', tel.trim()); data.append('postcode', pc.trim());
  data.append('woningtype', fd.woningtype || '');
  data.append('situatie', fd.systeem || '');
  data.append('gasverbruik', fd.gasverbruik || '');
  data.append('stad', (document.getElementById('js-stad') || {}).value || '');
  data.append('domein', location.hostname);
  data.append('utm_source', p.get('utm_source') || '');
  data.append('utm_medium', p.get('utm_medium') || '');
  data.append('utm_campaign', p.get('utm_campaign') || '');
  data.append('utm_term', p.get('utm_term') || '');
  data.append('utm_content', p.get('utm_content') || '');
  data.append('gclid', p.get('gclid') || '');
  data.append('gbraid', p.get('gbraid') || '');
  data.append('wbraid', p.get('wbraid') || '');
  data.append('landing_page', location.href);
  data.append('referrer', document.referrer || '');
  try {
    var url = typeof wcVars !== 'undefined' ? wcVars.ajaxUrl : '/wp-admin/admin-ajax.php';
    var res = await fetch(url, {method: 'POST', body: data});
    var json = await res.json();
    if (json.success) { vkSucces(); }
    else {
      var msg = json.data && json.data.message ? json.data.message : 'Er ging iets mis. Bel 075 234 0001.';
      vkFout(msg);
      if (btn) { btn.disabled = false; btn.textContent = 'Ontvang gratis subsidiecheck \u2192'; }
    }
  } catch(e) {
    vkFout('Er ging iets mis. Bel 075 234 0001 of probeer het opnieuw.');
    if (btn) { btn.disabled = false; btn.textContent = 'Ontvang gratis subsidiecheck \u2192'; }
  }
};

function vkSucces() {
  document.querySelectorAll('.vk-stap').forEach(function(s) { s.classList.remove('active'); });
  document.body.classList.remove('vk-form-step-4');
  document.body.classList.add('vk-form-success');
  var s = document.getElementById('stap-succes');
  if (s) s.classList.add('active');
  document.querySelectorAll('.vk-prog-dot').forEach(function(d) { d.classList.add('active'); });
  vkTrack('lead_form_success', {
    form_name: 'warmtepomp_offerte',
    woningtype: fd.woningtype || '',
    systeem: fd.systeem || '',
    gasverbruik: fd.gasverbruik || '',
    stad: (document.getElementById('js-stad') || {}).value || ''
  });
  vkTrackAdsLeadConversion();
}

function vkFout(t) {
  var e = document.querySelector('.vk-fout'); if (e) e.remove();
  var d = document.createElement('div');
  d.className = 'vk-fout'; d.textContent = '\u26a0\ufe0f ' + t;
  var a = document.querySelector('.vk-stap.active');
  if (a) a.insertBefore(d, a.firstChild);
  vkTrack('lead_form_error', {
    form_name: 'warmtepomp_offerte',
    error_message: t
  });
  setTimeout(function() { if(d.parentNode) d.remove(); }, 4000);
}

function vkBereken(g, gp, sys) {
  var s = SUBSIDIE[sys] || SUBSIDIE.lw;
  var cop = sys === 'bodem' ? 4.5 : sys === 'vent' ? 3.2 : sys === 'hybride' ? 2.8 : 3.8;
  var b = Math.max(0, g * gp - (g * 9.77 / cop) * 0.27);
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

window.vkUpdateGas = function() {
  var g = parseInt((document.getElementById('vk-gas') || {value: 1800}).value);
  var v = document.getElementById('vk-gas-val'); if (v) v.textContent = g.toLocaleString('nl-NL') + ' m\u00b3';
  var sys = 'lw';
  if (fd.systeem) {
    if (fd.systeem.includes('Ventilatie')) sys = 'vent';
    else if (fd.systeem.includes('Bodem')) sys = 'bodem';
    else if (fd.systeem.includes('Hybride')) sys = 'hybride';
  }
  var res = vkBereken(g, 1.25, sys);
  var m = document.getElementById('vk-mini-besp'); if (m) m.textContent = fmt(res.b) + ' *';
  var isde = document.getElementById('vk-mini-isde'); if (isde) isde.textContent = 'gem. ' + fmt(res.subsidie) + ' *';
  fd.gasverbruik = g.toLocaleString('nl-NL') + ' m\u00b3';
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

if (document.getElementById('vk-gas')) vkUpdateGas();
if (document.getElementById('calc-gas')) vkCalc();


})();
