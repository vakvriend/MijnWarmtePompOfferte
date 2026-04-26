/* Vakvriend AI Chatbot Widget */
(function(){
'use strict';

const tel = typeof vkChat !== 'undefined' ? vkChat.tel : '075 234 0001';
const wa  = typeof vkChat !== 'undefined' ? vkChat.wa  : '31752340001';
const telClean = tel.replace(/\s/g,'');

// Gespreksgeschiedenis
let geschiedenis = [];
let vensterOpen  = false;
let bezig        = false;

// Bouw widget HTML
const widget = document.getElementById('vk-chat-widget');
if (!widget) return;

widget.innerHTML = `
  <div class="vk-cw-venster" id="vk-cw-venster">
    <div class="vk-cw-header">
      <div class="vk-cw-avatar">V</div>
      <div class="vk-cw-header-info">
        <strong>Vakvriend Adviseur</strong>
        <span><span class="vk-cw-online">●</span> AI-assistent · direct antwoord</span>
      </div>
      <button class="vk-cw-sluiten" onclick="vkCwSluit()">✕</button>
    </div>
    <div class="vk-cw-msgs" id="vk-cw-msgs"></div>
    <div class="vk-cw-adviseur" id="vk-cw-adviseur" style="display:none">
      <p>💬 Wil je direct met een adviseur spreken?</p>
      <div class="vk-cw-adv-btns">
        <a href="tel:${telClean}" class="vk-cw-adv-btn">📞 ${tel}</a>
        <a href="https://wa.me/${wa}" class="vk-cw-adv-btn wa" target="_blank">💬 WhatsApp</a>
      </div>
    </div>
    <div class="vk-cw-input-wrap">
      <input class="vk-cw-input" id="vk-cw-input" type="text" placeholder="Stel uw vraag over warmtepompen..." maxlength="200">
      <button class="vk-cw-stuur" onclick="vkCwStuur()">➤</button>
    </div>
  </div>
  <button class="vk-cw-knop" id="vk-cw-knop" onclick="vkCwToggle()">
    💬
    <span class="vk-cw-badge" id="vk-cw-badge">1</span>
  </button>
`;

// Enter toets
document.getElementById('vk-cw-input')?.addEventListener('keydown', e => {
  if (e.key === 'Enter') vkCwStuur();
});

// Openingsbegroeting na 2 seconden
setTimeout(() => {
  voegBerichtToe('bot', 'Hallo! 👋 Ik ben de AI-adviseur van Vakvriend. Stel me gerust een vraag over warmtepompen, de Qvantum of Nibe, of ISDE-subsidie. Ik help u direct verder!');
}, 2000);

// Functies
window.vkCwToggle = function() {
  vensterOpen = !vensterOpen;
  const v = document.getElementById('vk-cw-venster');
  const b = document.getElementById('vk-cw-badge');
  if (v) v.classList.toggle('open', vensterOpen);
  if (b) b.style.display = vensterOpen ? 'none' : 'flex';
  if (vensterOpen) {
    setTimeout(() => document.getElementById('vk-cw-input')?.focus(), 300);
  }
};

window.vkCwSluit = function() {
  vensterOpen = false;
  document.getElementById('vk-cw-venster')?.classList.remove('open');
};

window.vkCwStuur = async function() {
  if (bezig) return;
  const input = document.getElementById('vk-cw-input');
  const tekst = input?.value.trim();
  if (!tekst) return;

  input.value = '';
  voegBerichtToe('user', tekst);

  // Voeg toe aan geschiedenis
  geschiedenis.push({ role: 'user', content: tekst });

  // Beperk geschiedenis tot laatste 10 berichten
  if (geschiedenis.length > 10) geschiedenis = geschiedenis.slice(-10);

  bezig = true;

  // Toon typing indicator
  const msgs = document.getElementById('vk-cw-msgs');
  const typing = document.createElement('div');
  typing.className = 'vk-cw-typing';
  typing.id = 'vk-cw-typing';
  typing.innerHTML = '<div class="vk-cw-dot"></div><div class="vk-cw-dot"></div><div class="vk-cw-dot"></div>';
  msgs?.appendChild(typing);
  scrollNaarOnder();

  try {
    const data = new FormData();
    data.append('action', 'vk_chat');
    data.append('nonce', typeof vkChat !== 'undefined' ? vkChat.nonce : '');
    data.append('messages', JSON.stringify(geschiedenis));

    const res  = await fetch(typeof vkChat !== 'undefined' ? vkChat.ajaxUrl : '/wp-admin/admin-ajax.php', {
      method: 'POST', body: data
    });
    const json = await res.json();

    // Verwijder typing
    document.getElementById('vk-cw-typing')?.remove();
    bezig = false;

    if (json.success) {
      const antwoord = json.data.message;
      voegBerichtToe('bot', antwoord);
      geschiedenis.push({ role: 'assistant', content: antwoord });

      // Toon doorverbinden knop als nodig
      if (json.data.doorverbinden) {
        const adv = document.getElementById('vk-cw-adviseur');
        if (adv) adv.style.display = 'block';
      }
    } else {
      voegBerichtToe('bot', json.data?.message || 'Sorry, er ging iets mis. Bel ons op 📞 ' + tel);
    }
  } catch(e) {
    document.getElementById('vk-cw-typing')?.remove();
    bezig = false;
    voegBerichtToe('bot', 'Verbindingsfout. Bel ons direct op 📞 ' + tel + ' of stuur een WhatsApp.');
    const adv = document.getElementById('vk-cw-adviseur');
    if (adv) adv.style.display = 'block';
  }

  scrollNaarOnder();
};

function voegBerichtToe(rol, tekst) {
  const msgs = document.getElementById('vk-cw-msgs');
  if (!msgs) return;
  const el = document.createElement('div');
  el.className = 'vk-cw-msg vk-cw-' + rol;
  el.innerHTML = tekst.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>');
  msgs.appendChild(el);
  scrollNaarOnder();
}

function scrollNaarOnder() {
  const msgs = document.getElementById('vk-cw-msgs');
  if (msgs) setTimeout(() => msgs.scrollTop = msgs.scrollHeight, 50);
}

})();
