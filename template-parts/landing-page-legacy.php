<?php
$stad      = wc_meta('wc_stad','Nederland');
$regio     = wc_meta('wc_regio',$stad);
$is_lokaal = ($stad !== 'Nederland' && $stad !== 'uw regio');
$hero_foto = wc_meta('wc_hero_foto','https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/698669fbf18164e5304e45ef_DSC00884-2.webp');
$hero_t    = wc_meta('wc_hero_titel', $is_lokaal ? "Warmtepomp offerte aanvragen in $stad" : 'Gratis warmtepomp offerte aanvragen');
$hero_s    = wc_meta('wc_hero_subtitel',"Vakvriend is uw gecertificeerde installateur voor Qvantum en Nibe warmtepompen." . ($is_lokaal ? " Actief in $regio en omgeving." : " Actief door heel Nederland.") . " ISDE-subsidie — wij regelen alles voor u.");
$vv_intro  = wc_meta('wc_vv_intro',"Vakvriend is een allround installatiebedrijf met jarenlange ervaring in warmtepompen, CV-ketels, sanitair, vloerverwarming en meer. Gecertificeerd voor Qvantum en Nibe. Eerlijk advies, geen verkooppraatjes — wij kijken wat technisch verstandig is voor uw situatie.");
$telefoon  = wc_meta('wc_telefoon','075 234 0001');
$whatsapp  = wc_meta('wc_whatsapp','31752340001');
$topbar    = wc_meta('wc_topbar_tekst',"🔥 Gratis warmtepomp offerte" . ($is_lokaal ? " in $stad" : "") . " — Qvantum & Nibe specialist — ISDE-subsidie");
$hero_kicker = wc_meta('wc_hero_kicker', $is_lokaal ? "Gratis subsidiecheck en advies in $stad" : 'Gratis subsidiecheck en warmtepomp advies');
$form_titel = wc_meta('wc_form_titel', 'Ontvang uw gratis warmtepomp advies');
$form_subtitel = wc_meta('wc_form_subtitel', 'Bereken direct uw besparing en ontvang binnen 24 uur reactie van Vakvriend.');
$form_benefits = wc_meta_rows('wc_form_benefits', [
  ['Gratis woningcheck'],
  ['Subsidiebedrag berekend'],
  ['Offerte binnen 24 uur'],
], 1);
$campaign_proof = wc_meta_rows('wc_campaign_proof', [
  ['Binnen 24 uur','reactie op uw aanvraag'],
  ['ISDE','subsidiecheck inbegrepen'],
  ['200+','installaties uitgevoerd'],
], 2);
$vv_u1     = wc_meta('wc_vv_usp1','Gecertificeerd Qvantum & Nibe installateur');
$vv_u2     = wc_meta('wc_vv_usp2','200+ warmtepomp installaties');
$vv_u3     = wc_meta('wc_vv_usp3','4,6 / 5 sterren beoordeling');
$vv_u4     = wc_meta('wc_vv_usp4','24/7 bereikbaar — door heel Nederland');
$faq_extra = wc_meta('wc_faq_extra','');
$tel_clean = preg_replace('/\s+/','',$telefoon);
$qvantum_types = wc_meta_rows('wc_qvantum_types', [
  ['💨','Qvantum QA — Lucht/water warmtepomp','Extraheert warmte uit de buitenlucht. R290 koudemiddel (GWP=3). Inclusief QH-XL hydromodule met thermische batterij. Geen aparte boiler nodig. COP tot 4,5.'],
  ['🌬️','Qvantum QE — Ventilatie warmtepomp met WTW','Extraheert warmte uit de afvoerventilatielucht. Geen buitenunit nodig — ideaal voor appartementen. Geïntegreerde thermische batterij laadt op tot 90°C bij goedkope stroom. In combinatie met de Qvantum QS ook geschikt als volwaardige WTW-unit.'],
  ['🌍','Qvantum QG — Water/water warmtepomp','Voor bodem- of brontoepassingen. Hoogste rendement (COP 4-6). Ook dit Qvantum-systeem werkt met de thermische batterij voor slimme warmteopslag. Vakvriend verzorgt de grondboringen en vergunning.'],
], 3);
$qvantum_usps = wc_meta_rows('wc_qvantum_usps', [
  ['FlexReady®','Past zich automatisch aan aan stroomprijzen'],
  ['90% gemeenschappelijke onderdelen','Goedkoper in onderhoud'],
  ['Automatische software-updates','Via internet'],
  ['Netcongestie-oplossing','Communiceren als slim netwerk'],
], 2);
$nibe_types = wc_meta_rows('wc_nibe_types', [
  ['💨','Nibe F2040 / S2125 — Lucht/water warmtepomp','Fluisterstille buitenunit, hoog rendement. Geschikt voor vloerverwarming en radiatoren. Energielabel A+++.'],
  ['🌍','Nibe S-serie — Bodem/water warmtepomp','Hoogste COP, stabiele warmtebron het hele jaar. Maximale ISDE-subsidie. Vakvriend verzorgt boring en vergunning.'],
  ['⚡','Nibe F730 — Hybride warmtepomp','Combineert een bestaande CV-ketel met een warmtepomp. Ideale tussenstap naar volledig gasloos.'],
], 3);
$warmtepomp_types = wc_meta_rows('wc_warmtepomp_types', [
  ['💨','Populairste keuze','Lucht/water warmtepomp','Extraheert warmte uit de buitenlucht en geeft dit via water af aan uw verwarmingssysteem (radiatoren of vloerverwarming). Geen boring nodig.','Geen grondboringen nodig;Snel te installeren;COP 3 tot 4,5;ISDE-subsidie gem. €2.000-€4.000;Geschikt voor radiatoren en vloerverwarming','Qvantum QA of Nibe F2040 / S2125'],
  ['🌬️','Compact — geen buitenunit','Ventilatie warmtepomp','Haalt warmte uit de afvoerventilatielucht van uw woning. Ideaal voor goed geïsoleerde woningen en appartementen. De Qvantum QE werkt in combinatie met de QS-unit ook als volwaardige WTW.','Geen buitenunit nodig;Ook als WTW met Qvantum QS;Thermische batterij tot 90°C;Ideaal voor appartementen;Geen vergunning nodig','Qvantum QE + optioneel QS'],
  ['🌍','Hoogste rendement','Bodemwarmtepomp','Extraheert warmte uit de bodem via grondboringen. Stabielste bron het hele jaar. Hoogste COP en maximale ISDE-subsidie. Vakvriend verzorgt boring én vergunning.','Hoogste COP (4 tot 6);Stabiel, ook in de winter;ISDE-subsidie tot €8.000+;Vakvriend regelt boring;Vakvriend regelt vergunning','Nibe S-serie of Qvantum QG'],
], 6);
$vv_props = wc_meta_rows('wc_vv_props', [
  ['🏗️','Allround installatiebedrijf','Warmtepompen, CV-ketels, sanitair, vloerverwarming, dakwerk en ventilatie. Alles in eigen hand.'],
  ['🚗','Volledig elektrisch wagenpark','Vakvriend rijdt de KIA PV5 — bewust duurzaam in elk opzicht.'],
  ['📋','Eerlijk advies, geen verkooppraatjes','Wij adviseren wat technisch verstandig is voor uw woning — ook als dat soms een goedkopere oplossing is.'],
  ['💶','ISDE-subsidie van A tot Z geregeld','Vakvriend begeleidt de volledige aanvraag bij de RVO. U hoeft er niets voor te doen.'],
], 3);
$voordelen = wc_meta_rows('wc_voordelen', [
  ['🔥','Geen boiler nodig (Qvantum)','De Qvantum levert verwarming én warm tapwater in één systeem met geïntegreerde thermische batterij. Compact en efficiënt.'],
  ['⚡','Tot 60% lagere energiekosten','COP tot 4,5 — per kW stroom krijgt u tot 4,5 kW warmte terug. De thermische batterij benut goedkope daluurstroom optimaal.'],
  ['💶','ISDE-subsidie tot €8.000+','Van €2.125 voor lucht/water tot meer dan €8.000 voor een bodemwarmtepomp. Vakvriend regelt alles bij de RVO.'],
  ['🌿','100% gasloos wonen','Onafhankelijk van gas en de gasprijs. Toekomstbestendig en klaar voor de energietransitie.'],
  ['🔇','Fluisterstil ook in woonwijken','Speciaal ontworpen voor gebruik in dichtbebouwde gebieden. Nauwelijks hoorbaar voor u en uw buren.'],
  ['🌍','Boringen door heel Nederland','Vakvriend verzorgt grondboringen voor bodemwarmtepompen in heel Nederland, inclusief vergunningsaanvraag.'],
], 3);
$werkwijze = wc_meta_rows('wc_werkwijze', [
  ['01','Gratis advies aan huis','Vakvriend komt langs, beoordeelt uw woning en adviseert welk systeem (Qvantum of Nibe) het beste past. Volledig vrijblijvend.'],
  ['02','Heldere offerte','Transparante prijs inclusief ISDE-subsidie, eventuele boringen en vergunningskosten. Geen verborgen kosten.'],
  ['03','Boring & installatie','Vakvriend verzorgt grondboringen en vergunning indien nodig. Daarna vakkundige installatie in 1-2 werkdagen.'],
  ['04','Subsidie & uitleg','Wij regelen de ISDE-subsidieaanvraag bij de RVO en leggen alles uit. U hoeft er niets voor te doen.'],
], 3);
$reviews = wc_meta_rows('wc_reviews', [
  ['"Vakmensen. En denken mee als je een probleem hebt. Ook prima nazorg. Aanrader!"','Michiel','Warmtepomp installatie','5'],
  ['"Erg prettige samenwerking. Uitgebreide offerte, houdt je op de hoogte van planning. Goed werk en goede nazorg."','Berend','Qvantum installatie','5'],
  ['"Eerlijk, transparant en snelle service! Zal ze zeker aanbevelen aan vrienden."','Maud','Waterleiding & sanitair','5'],
  ['"Vakvriend heeft een mooie klus afgeleverd. Onder de indruk van hun extra inzet om alles op tijd af te krijgen."','Steffen','Vloerverwarming','5'],
], 4);
?>

<!-- HERO -->
<section class="vk-hero" style="--hbg:url('<?=esc_url($hero_foto)?>')">
  <div class="vk-hero-overlay"></div>
  <div class="vk-hero-inner">
    <!-- Links: Tekst -->
    <div class="vk-hero-content">
      <div class="vk-pill">🌿 <?=esc_html($hero_kicker)?></div>
      <h1><?=esc_html($hero_t)?></h1>
      <p class="vk-hero-sub"><?=esc_html($hero_s)?></p>
      <div class="vk-trust-row">
        <span class="vk-trust">Gratis & vrijblijvend</span>
        <span class="vk-trust">ISDE-subsidie aangevraagd</span>
        <span class="vk-trust">Merkonafhankelijk advies</span>
        <span class="vk-trust"><?=$is_lokaal?"Actief in $stad":"Door heel Nederland"?></span>
      </div>
      <div class="vk-proof-row">
        <?php foreach($campaign_proof as [$number,$label]): ?>
          <div class="vk-proof">
            <strong><?=esc_html($number)?></strong>
            <span><?=esc_html($label)?></span>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="vk-hero-contact">
        <a href="tel:<?=esc_attr($tel_clean)?>" class="vk-tel-link">📞 <?=esc_html($telefoon)?></a>
        <a href="https://wa.me/<?=esc_attr($whatsapp)?>" class="vk-wa-link">💬 WhatsApp</a>
      </div>
    </div>

    <!-- Rechts: Formulier -->
    <div class="vk-form-wrap" id="formulier">
      <div class="vk-form-card">
        <div class="vk-form-head">
          <h2><?=esc_html($form_titel)?></h2>
          <p><?=esc_html($form_subtitel)?></p>
        </div>
        <div class="vk-form-trust-bar">
          <span>🔒 Veilig</span><span>⚡ Binnen 24u reactie</span><span>✅ Gratis & vrijblijvend</span>
        </div>
        <div class="vk-progress">
          <div class="vk-prog-dot active"></div>
          <div class="vk-prog-dot"></div>
          <div class="vk-prog-dot"></div>
          <div class="vk-prog-dot"></div>
        </div>
        <div class="vk-prog-labels"><span>Woning</span><span>Systeem</span><span>Verbruik</span><span>Gegevens</span></div>

        <!-- Stap 1: Woningtype -->
        <div class="vk-stap active" id="stap-1">
          <h3 class="vk-stap-titel">Wat voor woning heeft u?</h3>
          <p class="vk-stap-sub">Stap 1 van 4</p>
          <div class="vk-keuze-grid">
            <button type="button" class="vk-keuze" onclick="vkKies(this,'woningtype','Vrijstaande woning')"><span class="vk-keuze-ico">🏡</span>Vrijstaand</button>
            <button type="button" class="vk-keuze" onclick="vkKies(this,'woningtype','Tussenwoning')"><span class="vk-keuze-ico">🏘️</span>Tussenwoning</button>
            <button type="button" class="vk-keuze" onclick="vkKies(this,'woningtype','Hoekwoning')"><span class="vk-keuze-ico">🏠</span>Hoekwoning</button>
            <button type="button" class="vk-keuze" onclick="vkKies(this,'woningtype','Appartement')"><span class="vk-keuze-ico">🏢</span>Appartement</button>
          </div>
          <button class="vk-btn vk-btn-groen vk-btn-full" onclick="vkStap(2)">Volgende →</button>
        </div>

        <!-- Stap 2: Systeem voorkeur -->
        <div class="vk-stap" id="stap-2">
          <h3 class="vk-stap-titel">Welk systeem heeft uw voorkeur?</h3>
          <p class="vk-stap-sub">Stap 2 van 4</p>
          <div class="vk-keuze-grid">
            <button type="button" class="vk-keuze" onclick="vkKies(this,'systeem','Lucht/water (Qvantum QA / Nibe)')"><span class="vk-keuze-ico">💨</span>Lucht/water</button>
            <button type="button" class="vk-keuze" onclick="vkKies(this,'systeem','Ventilatie (Qvantum QE)')"><span class="vk-keuze-ico">🌬️</span>Ventilatie</button>
            <button type="button" class="vk-keuze" onclick="vkKies(this,'systeem','Bodemwarmtepomp (Nibe / Qvantum QG)')"><span class="vk-keuze-ico">🌍</span>Bodem</button>
            <button type="button" class="vk-keuze" onclick="vkKies(this,'systeem','Hybride (Intergas Xtend Eco)')"><span class="vk-keuze-ico">⚡</span>Hybride</button>
            <button type="button" class="vk-keuze" onclick="vkKies(this,'systeem','Weet ik nog niet')"><span class="vk-keuze-ico">💡</span>Advies nodig</button>
          </div>
          <div class="vk-subsidie-tip" id="subsidie-tip" style="display:none"></div>
          <button class="vk-btn vk-btn-groen vk-btn-full" onclick="vkStap(3)">Volgende →</button>
          <div class="vk-terug"><button onclick="vkStap(1)">← Terug</button></div>
        </div>

        <!-- Stap 3: Gasverbruik -->
        <div class="vk-stap" id="stap-3">
          <h3 class="vk-stap-titel">Huidig gasverbruik?</h3>
          <p class="vk-stap-sub">Stap 3 van 4</p>
          <label class="vk-slider-lbl">Gasverbruik per jaar</label>
          <input type="range" class="vk-slider" id="vk-gas" min="500" max="4000" step="100" value="1800" oninput="vkUpdateGas()">
          <div class="vk-slider-vals"><span>500 m³</span><strong id="vk-gas-val">1.800 m³</strong><span>4.000 m³</span></div>
          <div class="vk-mini-res">
            <div class="vk-mini-lbl">Geschatte besparing per jaar</div>
            <div class="vk-mini-val" id="vk-mini-besp">€1.350</div>
            <div class="vk-mini-sub" id="vk-mini-sub">Max. ISDE-subsidie: <strong id="vk-mini-isde">max. €2.125</strong></div>
          <div style="font-size:11px;opacity:.7;margin-top:4px">* Schatting op basis van gemiddelden. Exacte subsidie afhankelijk van merk en vermogen.</div>
          </div>
          <button class="vk-btn vk-btn-groen vk-btn-full" onclick="vkStap(4)">Volgende →</button>
          <div class="vk-terug"><button onclick="vkStap(2)">← Terug</button></div>
        </div>

        <!-- Stap 4: Contactgegevens -->
        <div class="vk-stap" id="stap-4">
          <h3 class="vk-stap-titel">Uw contactgegevens</h3>
          <p class="vk-stap-sub">Stap 4 van 4 — ontvang uw gratis subsidiecheck en advies</p>
          <div class="vk-veld-grid">
            <div class="vk-veld"><label>Naam *</label><input type="text" id="vk-naam" placeholder="Jan de Vries"></div>
            <div class="vk-veld"><label>E-mail *</label><input type="email" id="vk-email" placeholder="jan@voorbeeld.nl"></div>
            <div class="vk-veld"><label>Telefoon</label><input type="tel" id="vk-tel" placeholder="06 12345678"></div>
            <div class="vk-veld"><label>Postcode</label><input type="text" id="vk-pc" placeholder="1234 AB"></div>
          </div>
          <div class="vk-final-proof">
            <span>Geen verplichting</span>
            <span>Advies van installateur</span>
            <span>Reactie binnen 24 uur</span>
          </div>
          <button class="vk-btn vk-btn-oranje vk-btn-full vk-btn-lg" onclick="vkVerstuur()">Ontvang gratis subsidiecheck →</button>
          <p class="vk-disclaimer"><span>Gratis en vrijblijvend</span><span>Reactie binnen 24 uur</span><span>ISDE-hulp inbegrepen</span><br>Door te versturen gaat u akkoord met onze <a href="<?=home_url('/privacy')?>">privacyverklaring</a>.</p>
          <div class="vk-terug"><button onclick="vkStap(3)">← Terug</button></div>
        </div>

        <!-- Succes -->
        <div class="vk-stap vk-succes" id="stap-succes">
          <div class="vk-succes-mark">✓</div>
          <div class="vk-succes-pill">Aanvraag compleet</div>
          <h3>Gelukt, uw subsidiecheck is aangevraagd</h3>
          <p>Vakvriend heeft uw gegevens ontvangen. We bekijken uw woning, systeemvoorkeur en gasverbruik en nemen binnen 24 uur contact op met praktisch advies.</p>
          <div class="vk-succes-next">
            <div><strong>1</strong><span>Aanvraag ontvangen</span></div>
            <div><strong>2</strong><span>Vakvriend rekent mee</span></div>
            <div><strong>3</strong><span>U krijgt advies</span></div>
          </div>
          <a href="tel:<?=esc_attr($tel_clean)?>" class="vk-btn vk-btn-groen vk-btn-full vk-btn-space">📞 Direct bellen: <?=esc_html($telefoon)?></a>
          <a href="https://wa.me/<?=esc_attr($whatsapp)?>" class="vk-btn vk-btn-wa vk-btn-full vk-btn-space-sm">💬 WhatsApp sturen</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- MERKENBALK -->
<div class="vk-merken">
  <div class="vk-merken-inner">
    <?php $logos=[
      ['https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e60fe6fe0053da7edd61e_Qvantum-logo.avif','Qvantum'],
      ['https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e60fe021869f07fdf55a8_nibe-logo-png_seeklogo-188975%20(1).avif','Nibe'],
      ['https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e60fe13061ed53e151aec_itho-daalderop-logo-png_seeklogo-266199%20(1).avif','Itho Daalderop'],
      ['https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e633eae700c523a886c9c_Viega-Logo%20(1).png','Viega'],
      ['https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e60fe006d86c9d751cc13_Geberit-Logo.svg.avif','Geberit'],
      ['https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e60fe6dc67fa33f17b0a6_Uponor-Logo.svg.avif','Uponor'],
    ];
    for ($set = 0; $set < 4; $set++): ?>
      <div class="vk-merken-set" <?php if ($set > 0): ?>aria-hidden="true"<?php endif; ?>>
        <?php foreach($logos as [$src,$alt]): ?>
          <img src="<?=esc_url($src)?>" alt="<?=esc_attr($alt)?>" height="22" loading="lazy">
        <?php endforeach; ?>
      </div>
    <?php endfor; ?>
  </div>
</div>

<!-- QVANTUM SPOTLIGHT -->
<section class="vk-section vk-brand-section vk-brand-qvantum">
  <div class="vk-container">
    <div class="vk-cols-2 vk-brand-grid">
      <div class="vk-reveal">
        <div class="vk-product-foto-wrap vk-qvantum-product">
          <img class="vk-product-img" src="https://www.qvantum.com/wp-content/uploads/2024/02/QAQH_2000px-e1737129072551.jpg" alt="Qvantum warmtepomp" loading="lazy">
          <div class="vk-product-badge">
            <div class="vk-badge-groot">COP 4,5</div>
            <div class="vk-badge-klein">Max. rendement</div>
          </div>
          <div class="vk-product-badge vk-product-badge-top">
            <div class="vk-badge-klein">Thermische batterij</div>
            <div class="vk-badge-groot">90°C</div>
          </div>
          <div class="vk-product-ribbon">Vers warm tapwater via platenwisselaar</div>
        </div>
      </div>
      <div class="vk-reveal">
        <div class="vk-brand-head">
          <img src="https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e60fe6fe0053da7edd61e_Qvantum-logo.avif" alt="Qvantum" height="24">
          <div class="vk-eyebrow"><?=esc_html(wc_meta('wc_qvantum_eyebrow','Aanbevolen systeem · Qvantum'))?></div>
        </div>
        <h2><?=wp_kses_post(wc_meta('wc_qvantum_titel','Warmtepomp <em>zonder boiler</em> — én thermische batterij'))?></h2>
        <p class="vk-lead"><?=esc_html(wc_meta('wc_qvantum_lead','Qvantum biedt drie typen warmtepompen — allemaal gebouwd rond hetzelfde principe: de ingebouwde buffertank fungeert als een thermische batterij. Net als een thuisbatterij slaat de warmtepomp goedkope energie op en gebruikt die op momenten dat stroom duur is.'))?></p>

        <div class="vk-brand-metrics">
          <span>Geen groot boilervat</span>
          <span>QA · QE · QG</span>
          <span>Slim met stroomprijzen</span>
        </div>

        <div class="vk-qv-types">
          <?php foreach($qvantum_types as [$ico,$title,$text]): ?>
            <div class="vk-qv-type">
              <div class="vk-qv-type-ico"><?=esc_html($ico)?></div>
              <div>
                <strong><?=esc_html($title)?></strong>
                <p><?=esc_html($text)?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="vk-qv-explain">
          <div class="vk-qv-explain-ico">💧</div>
          <div>
            <strong><?=esc_html(wc_meta('wc_qvantum_uitleg_titel','Warm water zonder boilervat'))?></strong>
            <p><?=esc_html(wc_meta('wc_qvantum_uitleg_tekst','Denk aan een warme kachelsteen: de Qvantum bewaart warmte in de thermische batterij, niet in een vat vol douchewater. Zet u de kraan open, dan stroomt koud leidingwater langs een platenwisselaar. Die haalt warmte uit de batterij en maakt het water direct warm. U doucht dus met vers verwarmd water, zonder groot boilervat met stilstaand tapwater.'))?></p>
          </div>
        </div>

        <div class="vk-usp-list vk-brand-checks">
          <?php foreach($qvantum_usps as [$title,$text]): ?>
            <div><span class="vk-check">✓</span><div><strong><?=esc_html($title)?></strong> — <?=esc_html($text)?></div></div>
          <?php endforeach; ?>
        </div>
        <a href="#formulier" class="vk-btn vk-btn-groen"><?=esc_html(wc_meta('wc_qvantum_cta','Qvantum offerte aanvragen →'))?></a>
      </div>
    </div>
  </div>
</section>

<!-- NIBE SECTIE -->
<section class="vk-section vk-brand-section vk-brand-nibe">
  <div class="vk-container">
    <div class="vk-cols-2 vk-cols-flip vk-brand-grid">
      <div class="vk-reveal">
        <div class="vk-brand-head">
          <img src="https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e60fe021869f07fdf55a8_nibe-logo-png_seeklogo-188975%20(1).avif" alt="Nibe" height="24">
          <div class="vk-eyebrow"><?=esc_html(wc_meta('wc_nibe_eyebrow','Betrouwbare kwaliteit · Nibe'))?></div>
        </div>
        <h2><?=wp_kses_post(wc_meta('wc_nibe_titel','Nibe — 30 jaar bewezen betrouwbaarheid'))?></h2>
        <p class="vk-lead"><?=esc_html(wc_meta('wc_nibe_lead','Nibe is een Scandinavisch merk met meer dan 30 jaar ervaring in warmtepompen. Bekend om lange levensduur, uitstekende service en hoog rendement. Vakvriend installeert het complete Nibe assortiment.'))?></p>

        <div class="vk-brand-metrics vk-nibe-metrics">
          <span>Scandinavische techniek</span>
          <span>Hybride en all-electric</span>
          <span>Bodemwarmte mogelijk</span>
        </div>

        <div class="vk-qv-types">
          <?php foreach($nibe_types as [$ico,$title,$text]): ?>
            <div class="vk-qv-type">
              <div class="vk-qv-type-ico"><?=esc_html($ico)?></div>
              <div>
                <strong><?=esc_html($title)?></strong>
                <p><?=esc_html($text)?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <a href="#formulier" class="vk-btn vk-btn-outline-groen"><?=esc_html(wc_meta('wc_nibe_cta','Nibe offerte aanvragen →'))?></a>
      </div>
      <div class="vk-reveal">
        <div class="vk-vergelijk-kaart">
          <div class="vk-compare-head">
            <span>Merkkeuze</span>
            <h3>Qvantum vs Nibe</h3>
            <p>Niet de winnaar kiezen, maar het systeem dat past bij woning, tapwater en ruimte.</p>
          </div>
          <div class="vk-vergelijk">
            <div class="vk-vergelijk-col vk-vergelijk-qvantum">
              <div class="vk-vergelijk-merk">⭐ Qvantum</div>
              <ul>
                <li>✓ Thermische batterij</li>
                <li>✓ Geen aparte boiler</li>
                <li>✓ COP tot 4,5</li>
                <li>✓ QE zonder buitenunit</li>
                <li>✓ QS voor WTW ventilatie</li>
                <li>✓ R290 koudemiddel</li>
              </ul>
              <div class="vk-vergelijk-tag">Onze topkeuze</div>
            </div>
            <div class="vk-vergelijk-col">
              <div class="vk-vergelijk-merk">Nibe</div>
              <ul>
                <li>✓ 30+ jaar ervaring</li>
                <li>✓ Hybride optie</li>
                <li>✓ Bodemwarmtepomp</li>
                <li>✓ Breed assortiment</li>
                <li>✓ Energielabel A+++</li>
                <li>✓ Bewezen betrouwbaar</li>
              </ul>
              <div class="vk-vergelijk-tag vk-tag-grijs">Uitstekende keuze</div>
            </div>
          </div>
          <p class="vk-vergelijk-tip">Vakvriend adviseert welk systeem het beste past bij uw woning en situatie — gratis aan huis.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- WARMTEPOMP TYPES -->
<section class="vk-section">
  <div class="vk-container">
    <div class="vk-eyebrow vk-center"><?=esc_html(wc_meta('wc_types_eyebrow','Kenniscentrum warmtepompen'))?></div>
    <h2 class="vk-center"><?=esc_html(wc_meta('wc_types_titel','Welk type warmtepomp past bij u?'))?></h2>
    <p class="vk-lead vk-center vk-lead-center"><?=esc_html(wc_meta('wc_types_lead','Er zijn drie typen warmtepompen voor woningen. Elk type heeft zijn eigen voordelen en is geschikt voor een andere situatie.'))?></p>
    <div class="vk-type-grid">
      <?php foreach($warmtepomp_types as [$ico,$badge,$title,$text,$items,$brands]): ?>
        <div class="vk-type-card vk-reveal">
          <div class="vk-type-icon"><?=esc_html($ico)?></div>
          <?php if($badge): ?><div class="vk-type-badge"><?=esc_html($badge)?></div><?php endif; ?>
          <h3><?=esc_html($title)?></h3>
          <p><?=esc_html($text)?></p>
          <ul class="vk-type-list">
            <?php foreach(array_filter(array_map('trim', explode(';',$items))) as $item): ?>
              <li>✓ <?=esc_html($item)?></li>
            <?php endforeach; ?>
          </ul>
          <?php if($brands): ?><div class="vk-type-merken"><?=esc_html($brands)?></div><?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- HYBRIDE SECTIE -->
<section class="vk-section vk-bg-licht vk-hybride-section">
  <div class="vk-container">
    <div class="vk-cols-2 vk-hybride-grid">
      <div class="vk-reveal">
        <div class="vk-eyebrow">Tussenstap naar gasloos</div>
        <h2>Hybride warmtepomp — <em>het beste van twee werelden</em></h2>
        <p class="vk-lead">Nog niet klaar voor volledig gasloos<?= $is_lokaal ? ' in ' . esc_html($stad) : '' ?>? Een hybride warmtepomp werkt samen met de cv-ketel. De warmtepomp levert het grootste deel van de warmte bij milde buitentemperaturen; de ketel blijft beschikbaar voor piekbelasting, tapwater en koude dagen. Vakvriend beoordeelt merkonafhankelijk of hybride verstandig is, of dat all-electric direct beter past.</p>
        <div class="vk-hybride-punten">
          <div><span class="vk-check">✓</span><div><strong>Lagere investering</strong> — vaak zonder grote aanpassingen aan radiatoren</div></div>
          <div><span class="vk-check">✓</span><div><strong>Direct besparen</strong> — interessant als tussenstap bij oudere woningen</div></div>
          <div><span class="vk-check">✓</span><div><strong>ISDE-subsidie</strong> — ook voor hybride systemen beschikbaar</div></div>
          <div><span class="vk-check">✓</span><div><strong>Geen eindstation</strong> — later alsnog door naar volledig elektrisch als de woning klaar is</div></div>
        </div>
        <a href="#formulier" class="vk-btn vk-btn-groen">Hybride offerte aanvragen →</a>
      </div>
      <div class="vk-reveal">
        <div class="vk-hybride-kaart">
          <div class="vk-hybride-logo">
            <img src="https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e60fe55c685e273a402fa_intergas-logo.avif" alt="Intergas" height="28">
            <span>Hybride optie</span>
          </div>
          <h3>Intergas Xtend Eco</h3>
          <p class="vk-hybride-intro">De Intergas Xtend Eco is een hybride lucht/water warmtepomp die samenwerkt met een Intergas HR-ketel. De warmtepomp verlaagt het gasverbruik bij normale warmtevraag; de ketel ondersteunt bij hoge temperatuurvraag, tapwater en koude perioden. Vooral interessant wanneer volledig elektrisch nog niet de juiste stap is.</p>
          <div class="vk-hybride-specs">
            <div class="vk-hybride-spec"><span>⚡</span><div><strong>COP tot 4,0</strong><p>Hoog rendement bij mild weer</p></div></div>
            <div class="vk-hybride-spec"><span>🔥</span><div><strong>Ketel ondersteunt</strong><p>Voor tapwater en piekvraag</p></div></div>
            <div class="vk-hybride-spec"><span>📦</span><div><strong>Compacte opstelling</strong><p>Beperkte technische ruimte nodig</p></div></div>
            <div class="vk-hybride-spec"><span>💶</span><div><strong>ISDE-subsidie</strong><p>Tot €2.000 beschikbaar</p></div></div>
          </div>
          <a href="#formulier" class="vk-btn vk-btn-outline-groen">Vrijblijvend advies aanvragen</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- VAKVRIEND SECTIE -->
<section id="vakvriend" class="vk-section vk-bg-licht">
  <div class="vk-container">
    <div class="vk-vv-wrap">
      <!-- Foto kolom -->
      <div class="vk-vv-fotos vk-reveal">
        <div class="vk-vv-groot">
          <img src="https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/698669fbf18164e5304e45ef_DSC00884-2.webp" alt="Vakvriend monteurs aan het werk" loading="lazy">
        </div>
        <div class="vk-vv-klein-rij">
          <div class="vk-vv-klein">
            <img src="https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/6995dd7d47d2227b3da4b62e_DSC00879.avif" alt="Vakvriend team" loading="lazy">
          </div>
          <div class="vk-vv-klein">
            <img src="https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/697e62c6417ae0b4b70477a6_pv5-crew-1b-mvakvriend.webp" alt="Vakvriend elektrisch wagenpark" loading="lazy">
          </div>
        </div>
        <div class="vk-rating-badge">
          <div class="vk-rating-n">4,6</div>
          <div class="vk-rating-sterren">★★★★★</div>
          <div class="vk-rating-b">200+ installaties</div>
        </div>
      </div>

      <!-- Info kolom -->
      <div class="vk-reveal">
        <div class="vk-eyebrow"><?=esc_html(wc_meta('wc_vv_eyebrow','Uw installateur'))?></div>
        <h2><?=esc_html(wc_meta('wc_vv_titel','Waarom kiezen voor Vakvriend?'))?></h2>
        <p class="vk-lead"><?=nl2br(esc_html($vv_intro))?></p>

        <div class="vk-vv-usps">
          <?php foreach([$vv_u1,$vv_u2,$vv_u3,$vv_u4] as $u): if(!$u) continue; ?>
            <div class="vk-vv-usp-item">
              <span class="vk-vinkje">✓</span>
              <span><?=esc_html($u)?></span>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="vk-vv-props">
          <?php foreach($vv_props as [$ico,$title,$text]): ?>
            <div class="vk-vv-prop">
              <div class="vk-vv-prop-ico"><?=esc_html($ico)?></div>
              <div class="vk-vv-prop-tekst">
                <strong><?=esc_html($title)?></strong>
                <p><?=esc_html($text)?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="vk-vv-cta">
          <a href="https://vakvriend.nl" target="_blank" class="vk-btn vk-btn-outline-groen">🌐 vakvriend.nl</a>
          <a href="#formulier" class="vk-btn vk-btn-groen">Gratis offerte →</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- VOORDELEN -->
<section id="waarom" class="vk-section">
  <div class="vk-container">
    <div class="vk-eyebrow vk-center"><?=esc_html(wc_meta('wc_voordelen_eyebrow','Voordelen'))?></div>
    <h2 class="vk-center"><?=esc_html(wc_meta('wc_voordelen_titel','Waarom overstappen op een warmtepomp?'))?></h2>
    <p class="vk-lead vk-center vk-lead-center"><?=esc_html(wc_meta('wc_voordelen_lead','Bespaar structureel, verlaag uw CO₂-uitstoot en maak uw woning klaar voor de toekomst.'))?></p>
    <div class="vk-voordelen-grid">
      <?php foreach($voordelen as [$ico,$t,$p]): ?>
        <div class="vk-voordeel vk-reveal">
          <div class="vk-voordeel-ico"><?=$ico?></div>
          <h3><?=esc_html($t)?></h3>
          <p><?=esc_html($p)?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- GALERIJ — Alleen vakvriend.nl foto's met mensen -->
<section class="vk-section vk-bg-licht">
  <div class="vk-container">
    <div class="vk-eyebrow vk-center">Ons werk</div>
    <h2 class="vk-center">Vakvriend aan het werk<?php if($is_lokaal) echo " in $regio"; ?></h2>
    <div class="vk-galerij vk-reveal">
      <div class="vk-gal-groot">
        <img src="https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/698669fbf18164e5304e45ef_DSC00884-2.webp" alt="Vakvriend monteurs aan het werk" loading="lazy">
      </div>
      <div class="vk-gal-sm"><img src="https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/6995dd7d47d2227b3da4b62e_DSC00879.avif" alt="Vakvriend team" loading="lazy"></div>
      <div class="vk-gal-sm"><img src="https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/697e62c6417ae0b4b70477a6_pv5-crew-1b-mvakvriend.webp" alt="Vakvriend elektrisch wagenpark" loading="lazy"></div>
      <div class="vk-gal-sm"><img src="https://cdn.prod.website-files.com/697e2b08c251f37c1879a2e6/6993df49fc20c715b47b509e_69866c04643ac05fa325bccd_DSC00999-2-p-2000.avif" alt="Warmtepomp installatie" loading="lazy"></div>
      <div class="vk-gal-sm"><img src="https://cdn.prod.website-files.com/697e2b08c251f37c1879a2e6/6993e076b7eec8e454e49559_DSC00699.avif" alt="Installatie detail" loading="lazy"></div>
    </div>
  </div>
</section>

<!-- CALCULATOR -->
<section id="calculator" class="vk-section">
  <div class="vk-container">
    <div class="vk-eyebrow vk-center">Bereken uw besparing</div>
    <h2 class="vk-center">Hoeveel bespaart u per jaar?</h2>
    <p class="vk-lead vk-center vk-lead-center">De besparing en subsidie hangen af van het type warmtepomp dat u kiest.</p>
    <div class="vk-calc vk-reveal">
      <h3>🧮 Persoonlijke berekening</h3>
      <p class="vk-calc-sub">Selecteer het systeem en uw gasverbruik</p>

      <div class="vk-calc-systeem">
        <label class="vk-slider-lbl">Type warmtepomp</label>
        <div class="vk-systeem-keuze" id="calc-systeem-keuze">
          <button type="button" class="vk-sys-btn actief" onclick="vkKiesSysteem(this,'lw')" data-sys="lw">Lucht/water</button>
          <button type="button" class="vk-sys-btn" onclick="vkKiesSysteem(this,'vent')" data-sys="vent">Ventilatie</button>
          <button type="button" class="vk-sys-btn" onclick="vkKiesSysteem(this,'bodem')" data-sys="bodem">Bodem</button>
          <button type="button" class="vk-sys-btn" onclick="vkKiesSysteem(this,'hybride')" data-sys="hybride">Hybride</button>
        </div>
      </div>

      <div class="vk-calc-slider">
        <label class="vk-slider-lbl">Huidig gasverbruik per jaar</label>
        <input type="range" class="vk-slider" id="calc-gas" min="500" max="4000" step="100" value="1800" oninput="vkCalc()">
        <div class="vk-slider-vals"><span>500 m³</span><strong id="calc-gas-v">1.800 m³</strong><span>4.000 m³</span></div>
      </div>
      <div class="vk-calc-slider">
        <label class="vk-slider-lbl">Gasprijs per m³</label>
        <input type="range" class="vk-slider" id="calc-gp" min="0.80" max="2.00" step="0.05" value="1.25" oninput="vkCalc()">
        <div class="vk-slider-vals"><span>€0,80</span><strong id="calc-gp-v">€1,25</strong><span>€2,00</span></div>
      </div>
      <div class="vk-calc-res">
        <div class="vk-calc-res-lbl" id="calc-sys-lbl">Lucht/water warmtepomp · Qvantum QA of Nibe F2040</div>
        <div class="vk-calc-res-grid">
          <div><div class="vk-res-n" id="c-besp">€1.350</div><div class="vk-res-u">Besparing/jaar</div></div>
          <div><div class="vk-res-n" id="c-sub">€2.125</div><div class="vk-res-u">ISDE-subsidie</div></div>
          <div><div class="vk-res-n" id="c-tvt">5,1 jr</div><div class="vk-res-u">Terugverdientijd</div></div>
        </div>
      </div>
      <div class="vk-calc-cta" style="text-align:center;margin-top:24px">
        <a href="#formulier" class="vk-btn vk-btn-groen vk-btn-lg">Vraag gratis offerte op maat aan →</a>
      </div>
    </div>
  </div>
</section>

<!-- STAPPEN -->
<section id="werkwijze" class="vk-section vk-bg-donker">
  <div class="vk-container">
    <div class="vk-eyebrow vk-eyebrow-wit vk-center"><?=esc_html(wc_meta('wc_werkwijze_eyebrow','Werkwijze Vakvriend'))?></div>
    <h2 class="vk-wit vk-center"><?=esc_html(wc_meta('wc_werkwijze_titel','Van aanvraag tot installatie in 4 stappen'))?></h2>
    <p class="vk-lead vk-center vk-lead-center" style="color:rgba(255,255,255,.7)"><?=esc_html(wc_meta('wc_werkwijze_lead','Vakvriend ontzorgt u volledig — van advies en vergunning tot installatie en subsidie.'))?></p>
    <div class="vk-stappen-grid">
      <?php foreach($werkwijze as [$n,$t,$p]): ?>
        <div class="vk-stap-blok vk-reveal">
          <div class="vk-stap-n"><?=$n?></div>
          <h3><?=esc_html($t)?></h3>
          <p><?=esc_html($p)?></p>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="vk-stats-rij">
      <?php foreach([['200+','Installaties'],['4,6 / 5','Beoordeling'],['24/7','Bereikbaar'],['Heel NL','Actief']] as [$n,$l]): ?>
        <div class="vk-stat"><div class="vk-stat-n"><?=$n?></div><div class="vk-stat-l"><?=$l?></div></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- BORINGEN SECTIE -->
<section class="vk-section">
  <div class="vk-container">
    <div class="vk-cols-2">
      <div class="vk-reveal">
        <div class="vk-eyebrow"><?=esc_html(wc_meta('wc_boringen_eyebrow','Complete ontzorging'))?></div>
        <h2><?=esc_html(wc_meta('wc_boringen_titel','Grondboringen door heel Nederland'))?></h2>
        <p class="vk-lead"><?=esc_html(wc_meta('wc_boringen_lead','Voor een bodemwarmtepomp zijn grondboringen nodig. Vakvriend verzorgt dit van A tot Z — inclusief de vergunningsaanvraag bij uw gemeente.'))?></p>
        <div class="vk-boring-steps">
          <div class="vk-boring-step"><div class="vk-boring-num">1</div><div><strong>Bodemonderzoek</strong><p>Vakvriend beoordeelt of uw locatie geschikt is voor een boring.</p></div></div>
          <div class="vk-boring-step"><div class="vk-boring-num">2</div><div><strong>Vergunningsaanvraag</strong><p>Wij verzorgen de vergunningsaanvraag bij uw gemeente — u hoeft er niets voor te doen.</p></div></div>
          <div class="vk-boring-step"><div class="vk-boring-num">3</div><div><strong>Professionele boring</strong><p>Gecertificeerde boorbedrijven voeren de boring uit onder toezicht van Vakvriend.</p></div></div>
          <div class="vk-boring-step"><div class="vk-boring-num">4</div><div><strong>Installatie warmtepomp</strong><p>Vakvriend koppelt het systeem aan en zorgt voor een perfecte installatie.</p></div></div>
        </div>
        <a href="#formulier" class="vk-btn vk-btn-groen" style="margin-top:32px"><?=esc_html(wc_meta('wc_boringen_cta','Meer info over boringen →'))?></a>
      </div>
      <div class="vk-reveal">
        <div class="vk-boring-kaart-licht">
          <div style="font-size:48px;margin-bottom:16px">🗺️</div>
          <h3><?=esc_html(wc_meta('wc_boringen_kaart_titel','Actief in alle provincies'))?></h3>
          <p style="color:var(--grijs);margin-bottom:24px;font-size:15px"><?=esc_html(wc_meta('wc_boringen_kaart_tekst','Vakvriend voert boringen uit in alle provincies van Nederland.'))?></p>
          <div class="vk-provincies-licht">
            <?php foreach(['Noord-Holland','Zuid-Holland','Utrecht','Noord-Brabant','Gelderland','Overijssel','Flevoland','Groningen','Friesland','Drenthe','Zeeland','Limburg'] as $p): ?>
              <span class="vk-provincie-licht"><?=$p?></span>
            <?php endforeach; ?>
          </div>
          <div class="vk-boring-subsidie">
            <strong>💶 ISDE-subsidie bodemwarmtepomp</strong>
            <p>Bij een bodemwarmtepomp ontvangt u significant meer subsidie dan bij een lucht/water warmtepomp. Vakvriend berekent het exacte bedrag in de offerte.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- REVIEWS -->
<section id="reviews" class="vk-section vk-bg-licht">
  <div class="vk-container">
    <div class="vk-eyebrow"><?=esc_html(wc_meta('wc_reviews_eyebrow','Klanten aan het woord'))?></div>
    <h2><?=esc_html(wc_meta('wc_reviews_titel','Wat onze klanten zeggen'))?></h2>
    <p class="vk-lead"><?=esc_html(wc_meta('wc_reviews_lead','Beoordeeld door echte klanten na installatie door Vakvriend.'))?></p>
    <div class="vk-reviews-grid">
      <?php foreach($reviews as [$t,$n,$k,$s]): ?>
        <div class="vk-review vk-reveal">
          <div class="vk-review-sterren"><?=str_repeat('★',$s)?></div>
          <blockquote><?=esc_html($t)?></blockquote>
          <div class="vk-review-naam">
            <strong><?=esc_html($n)?></strong>
            <span>· <?=esc_html($k)?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- LOKALE SECTIE - Uniek per stad -->
<?php
$lokale_h2      = wc_meta("wc_lokale_h2", ($is_lokaal ? "Warmtepompen in $stad" : "Warmtepompen in uw regio"));
$lokale_intro   = wc_meta("wc_lokale_intro", "");
$lokale_alinea1 = wc_meta("wc_lokale_alinea1", "");
$lokale_alinea2 = wc_meta("wc_lokale_alinea2", "");
$lokale_alinea3 = wc_meta("wc_lokale_alinea3", "");
$lokale_faq_v1  = wc_meta("wc_lokale_faq_v1", "");
$lokale_faq_a1  = wc_meta("wc_lokale_faq_a1", "");
$lokale_faq_v2  = wc_meta("wc_lokale_faq_v2", "");
$lokale_faq_a2  = wc_meta("wc_lokale_faq_a2", "");
$lokale_faq_v3  = wc_meta("wc_lokale_faq_v3", "");
$lokale_faq_a3  = wc_meta("wc_lokale_faq_a3", "");
if ($lokale_alinea1 || $lokale_alinea2):
?>
<section class="vk-section">
  <div class="vk-container">
    <div class="vk-lokaal-wrap">
      <div class="vk-lokaal-tekst">
        <div class="vk-eyebrow">Lokaal specialist</div>
        <h2><?php echo esc_html($lokale_h2); ?></h2>
        <?php if ($lokale_intro): ?><p class="vk-lead"><?php echo esc_html($lokale_intro); ?></p><?php endif; ?>
        <?php if ($lokale_alinea1): ?><p><?php echo esc_html($lokale_alinea1); ?></p><?php endif; ?>
        <?php if ($lokale_alinea2): ?><p><?php echo esc_html($lokale_alinea2); ?></p><?php endif; ?>
        <?php if ($lokale_alinea3): ?><p><?php echo esc_html($lokale_alinea3); ?></p><?php endif; ?>
        <a href="#formulier" class="vk-btn vk-btn-groen" style="margin-top:24px">Gratis offerte in <?php echo esc_html($stad); ?> →</a>
      </div>
      <?php if ($lokale_faq_v1 && $lokale_faq_a1): ?>
      <div class="vk-lokaal-faq">
        <h3>Veelgestelde vragen in <?php echo esc_html($stad); ?></h3>
        <details class="vk-faq" open>
          <summary><?php echo esc_html($lokale_faq_v1); ?><span class="vk-faq-ico">+</span></summary>
          <p class="vk-faq-ant"><?php echo esc_html($lokale_faq_a1); ?></p>
        </details>
        <?php if ($lokale_faq_v2 && $lokale_faq_a2): ?>
        <details class="vk-faq">
          <summary><?php echo esc_html($lokale_faq_v2); ?><span class="vk-faq-ico">+</span></summary>
          <p class="vk-faq-ant"><?php echo esc_html($lokale_faq_a2); ?></p>
        </details>
        <?php endif; ?>
        <?php if ($lokale_faq_v3 && $lokale_faq_a3): ?>
        <details class="vk-faq">
          <summary><?php echo esc_html($lokale_faq_v3); ?><span class="vk-faq-ico">+</span></summary>
          <p class="vk-faq-ant"><?php echo esc_html($lokale_faq_a3); ?></p>
        </details>
        <?php endif; ?>
        <div class="vk-lokaal-cta">
          <p>Heeft u een vraag voor <?php echo esc_html($stad); ?>?</p>
          <a href="tel:<?php echo esc_attr($tel_clean); ?>" class="vk-btn vk-btn-groen">📞 Bel direct</a>
          <a href="https://wa.me/<?php echo esc_attr($whatsapp); ?>" class="vk-btn vk-btn-wa">💬 WhatsApp</a>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- FAQ -->
<section id="faq" class="vk-section vk-bg-licht">
  <div class="vk-container">
    <div class="vk-eyebrow vk-center"><?=esc_html(wc_meta('wc_faq_eyebrow','Veelgestelde vragen'))?></div>
    <h2 class="vk-center"><?=esc_html(wc_meta('wc_faq_titel','Alles wat u wilt weten'))?></h2>
    <div class="vk-faq-lijst">
      <?php
      $faqs=[
        ["Wat is het verschil tussen Qvantum en Nibe?","Qvantum onderscheidt zich door de thermische batterij in alle systemen: QA, QE en QG. De warmtepomp slaat warmte op bij gunstige stroommomenten en gebruikt die later voor verwarming en warm tapwater. De QE werkt zonder buitenunit en kan met de QS-unit ook als WTW-oplossing dienen. Nibe biedt meer modelkeuze, waaronder hybride warmtepompen en een breed bodemwarmtepomp-assortiment. Vakvriend adviseert gratis welk merk en model het beste bij uw situatie past."],
        ["Hoeveel ISDE-subsidie kan ik ontvangen in 2026?","De subsidie hangt af van het type systeem. Voor een lucht/water warmtepomp (bijv. Qvantum QA of Nibe F2040) ontvangt u in 2026 een startbedrag van €1.025 + €225 per kW + €200 energielabelbonus A+++. Bijvoorbeeld een 5kW systeem geeft €2.125. Voor een bodemwarmtepomp is de subsidie significant hoger en kan oplopen tot €8.000 of meer. Vakvriend berekent het exacte bedrag in uw offerte en regelt de aanvraag bij de RVO."],
        ["Hoe werkt de thermische batterij van Qvantum?","Alle Qvantum-systemen werken met een thermische batterij: de QA lucht/water warmtepomp, de QE ventilatiewarmtepomp en de QG bodemwarmtepomp. Denk aan een grote warme kachelsteen in het systeem. De warmtepomp slaat warmte op wanneer stroom gunstig is en gebruikt die warmte later voor verwarming en warm tapwater. Uw douchewater zit dus niet opgeslagen in een groot boilervat. Koud leidingwater stroomt langs een platenwisselaar, die warmte uit de thermische batterij haalt en het water direct vers warm maakt."],
        ["Verzorgt Vakvriend ook grondboringen?","Ja. Vakvriend verzorgt grondboringen voor bodemwarmtepompen door heel Nederland. Wij regelen ook de vergunningsaanvraag bij uw gemeente. Een bodemwarmtepomp geeft het hoogste rendement en de hoogste ISDE-subsidie."],
        ["Is mijn woning" . ($is_lokaal ? " in $stad" : "") . " geschikt voor een warmtepomp?","De meeste woningen gebouwd voor 2019 komen in aanmerking. Vakvriend beoordeelt uw woning gratis tijdens een adviesgesprek aan huis. Goed geïsoleerde woningen met vloerverwarming zijn ideaal, maar ook woningen met radiatoren zijn vaak geschikt met de juiste aanpassingen."],
        ["Hoe lang duurt de installatie?","Een standaard lucht/water of ventilatie warmtepomp installatie duurt 1 tot 2 werkdagen. Voor een bodemwarmtepomp is meer tijd nodig vanwege de boring. Vakvriend plant alles zo in dat de overlast minimaal is."],
        ["Regelt Vakvriend de subsidieaanvraag?","Ja, Vakvriend begeleidt de volledige ISDE-subsidieaanvraag bij de RVO. U hoeft er niets voor te doen. De subsidie wordt verrekend zodat u direct inzicht heeft in de netto investering."],
      ];
      if($faq_extra){foreach(explode("\n",$faq_extra) as $r){if(strpos($r,':::')!==false){[$v,$a]=explode(':::',$r,2);$faqs[]=[trim($v),trim($a)];}}}
      foreach($faqs as [$v,$a]): ?>
        <details class="vk-faq">
          <summary><?=esc_html($v)?><span class="vk-faq-ico">+</span></summary>
          <p class="vk-faq-ant"><?=esc_html($a)?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<input type="hidden" id="js-stad" value="<?=esc_attr($stad)?>">
