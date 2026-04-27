<?php
defined('ABSPATH') || exit;

function wc_landing_context($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $stad = wc_meta('wc_stad', 'Nederland', $post_id);
    $regio = wc_meta('wc_regio', $stad, $post_id);
    $telefoon = wc_meta('wc_telefoon', '075 234 0001', $post_id);
    $whatsapp = wc_meta('wc_whatsapp', '31752340001', $post_id);

    return array(
        'stad'      => $stad,
        'regio'     => $regio,
        'is_lokaal' => ($stad !== 'Nederland' && $stad !== 'uw regio'),
        'telefoon'  => $telefoon,
        'tel_clean' => preg_replace('/\s+/', '', $telefoon),
        'whatsapp'  => $whatsapp,
    );
}

function wc_shortcode_phone() {
    $ctx = wc_landing_context();
    return esc_html($ctx['telefoon']);
}
add_shortcode('warmtepomp_telefoon', 'wc_shortcode_phone');

function wc_shortcode_phone_url() {
    $ctx = wc_landing_context();
    return esc_url('tel:' . $ctx['tel_clean']);
}
add_shortcode('warmtepomp_telefoon_url', 'wc_shortcode_phone_url');

function wc_shortcode_city() {
    $ctx = wc_landing_context();
    return esc_html($ctx['stad']);
}
add_shortcode('warmtepomp_stad', 'wc_shortcode_city');

function wc_shortcode_whatsapp_url() {
    $ctx = wc_landing_context();
    return esc_url('https://wa.me/' . $ctx['whatsapp']);
}
add_shortcode('warmtepomp_whatsapp_url', 'wc_shortcode_whatsapp_url');

function wc_shortcode_logos() {
    $logos = array(
        array('https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e60fe6fe0053da7edd61e_Qvantum-logo.avif', 'Qvantum'),
        array('https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e60fe021869f07fdf55a8_nibe-logo-png_seeklogo-188975%20(1).avif', 'Nibe'),
        array('https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e60fe13061ed53e151aec_itho-daalderop-logo-png_seeklogo-266199%20(1).avif', 'Itho Daalderop'),
        array('https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e633eae700c523a886c9c_Viega-Logo%20(1).png', 'Viega'),
        array('https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e60fe006d86c9d751cc13_Geberit-Logo.svg.avif', 'Geberit'),
        array('https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e60fe6dc67fa33f17b0a6_Uponor-Logo.svg.avif', 'Uponor'),
    );

    ob_start();
    ?>
    <div class="vk-merken">
      <div class="vk-merken-inner">
        <?php foreach (array_merge($logos, $logos) as $logo) : ?>
          <img src="<?php echo esc_url($logo[0]); ?>" alt="<?php echo esc_attr($logo[1]); ?>" height="22" loading="lazy">
        <?php endforeach; ?>
      </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('warmtepomp_logos', 'wc_shortcode_logos');

function wc_shortcode_lead_form($atts = array()) {
    $ctx = wc_landing_context();
    $atts = shortcode_atts(array(
        'trust_1'       => 'Veilig',
        'trust_2'       => 'Binnen 24u reactie',
        'trust_3'       => 'Gratis en vrijblijvend',
        'step_1_title'  => 'Wat voor woning heeft u?',
        'step_1_sub'    => 'Stap 1 van 4',
        'step_2_title'  => 'Welk systeem heeft uw voorkeur?',
        'step_2_sub'    => 'Stap 2 van 4',
        'step_3_title'  => 'Huidig gasverbruik?',
        'step_3_sub'    => 'Stap 3 van 4',
        'step_4_title'  => 'Uw contactgegevens',
        'step_4_sub'    => 'Stap 4 van 4',
        'next_label'    => 'Volgende',
        'back_label'    => 'Terug',
        'submit_label'  => 'Gratis advies aanvragen',
        'success_title' => 'Aanvraag ontvangen',
        'success_text'  => 'Bedankt. Vakvriend neemt binnen één werkdag contact op voor een gratis adviesgesprek aan huis.',
    ), $atts, 'warmtepomp_lead_form');

    ob_start();
    ?>
    <div class="vk-form-wrap" id="formulier">
      <div class="vk-form-card">
        <div class="vk-form-trust-bar">
          <span><?php echo esc_html($atts['trust_1']); ?></span><span><?php echo esc_html($atts['trust_2']); ?></span><span><?php echo esc_html($atts['trust_3']); ?></span>
        </div>
        <div class="vk-progress">
          <div class="vk-prog-dot active"></div>
          <div class="vk-prog-dot"></div>
          <div class="vk-prog-dot"></div>
          <div class="vk-prog-dot"></div>
        </div>
        <div class="vk-prog-labels"><span>Woning</span><span>Systeem</span><span>Verbruik</span><span>Gegevens</span></div>

        <div class="vk-stap active" id="stap-1">
          <h3 class="vk-stap-titel"><?php echo esc_html($atts['step_1_title']); ?></h3>
          <p class="vk-stap-sub"><?php echo esc_html($atts['step_1_sub']); ?></p>
          <div class="vk-keuze-grid">
            <button type="button" class="vk-keuze" onclick="vkKies(this,'woningtype','Vrijstaande woning')"><span class="vk-keuze-ico">🏡</span>Vrijstaand</button>
            <button type="button" class="vk-keuze" onclick="vkKies(this,'woningtype','Tussenwoning')"><span class="vk-keuze-ico">🏘️</span>Tussenwoning</button>
            <button type="button" class="vk-keuze" onclick="vkKies(this,'woningtype','Hoekwoning')"><span class="vk-keuze-ico">🏠</span>Hoekwoning</button>
            <button type="button" class="vk-keuze" onclick="vkKies(this,'woningtype','Appartement')"><span class="vk-keuze-ico">🏢</span>Appartement</button>
          </div>
          <button class="vk-btn vk-btn-groen vk-btn-full" onclick="vkStap(2)"><?php echo esc_html($atts['next_label']); ?></button>
        </div>

        <div class="vk-stap" id="stap-2">
          <h3 class="vk-stap-titel"><?php echo esc_html($atts['step_2_title']); ?></h3>
          <p class="vk-stap-sub"><?php echo esc_html($atts['step_2_sub']); ?></p>
          <div class="vk-keuze-grid">
            <button type="button" class="vk-keuze" onclick="vkKies(this,'systeem','Lucht/water (Qvantum QA / Nibe)')"><span class="vk-keuze-ico">💨</span>Lucht/water</button>
            <button type="button" class="vk-keuze" onclick="vkKies(this,'systeem','Ventilatie (Qvantum QE)')"><span class="vk-keuze-ico">🌬️</span>Ventilatie</button>
            <button type="button" class="vk-keuze" onclick="vkKies(this,'systeem','Bodemwarmtepomp (Nibe / Qvantum QG)')"><span class="vk-keuze-ico">🌍</span>Bodem</button>
            <button type="button" class="vk-keuze" onclick="vkKies(this,'systeem','Hybride (Intergas Xtend Eco)')"><span class="vk-keuze-ico">⚡</span>Hybride</button>
            <button type="button" class="vk-keuze" onclick="vkKies(this,'systeem','Weet ik nog niet')"><span class="vk-keuze-ico">💡</span>Advies nodig</button>
          </div>
          <div class="vk-subsidie-tip" id="subsidie-tip" style="display:none"></div>
          <button class="vk-btn vk-btn-groen vk-btn-full" onclick="vkStap(3)"><?php echo esc_html($atts['next_label']); ?></button>
          <div class="vk-terug"><button onclick="vkStap(1)"><?php echo esc_html($atts['back_label']); ?></button></div>
        </div>

        <div class="vk-stap" id="stap-3">
          <h3 class="vk-stap-titel"><?php echo esc_html($atts['step_3_title']); ?></h3>
          <p class="vk-stap-sub"><?php echo esc_html($atts['step_3_sub']); ?></p>
          <label class="vk-slider-lbl">Gasverbruik per jaar</label>
          <input type="range" class="vk-slider" id="vk-gas" min="500" max="4000" step="100" value="1800" oninput="vkUpdateGas()">
          <div class="vk-slider-vals"><span>500 m³</span><strong id="vk-gas-val">1.800 m³</strong><span>4.000 m³</span></div>
          <div class="vk-mini-res">
            <div class="vk-mini-lbl">Geschatte besparing per jaar</div>
            <div class="vk-mini-val" id="vk-mini-besp">€1.350</div>
            <div class="vk-mini-sub">ISDE-subsidie: <strong id="vk-mini-isde">gem. €2.800</strong></div>
            <div class="vk-form-note">Schatting op basis van gemiddelden. Exacte subsidie afhankelijk van merk en vermogen.</div>
          </div>
          <button class="vk-btn vk-btn-groen vk-btn-full" onclick="vkStap(4)"><?php echo esc_html($atts['next_label']); ?></button>
          <div class="vk-terug"><button onclick="vkStap(2)"><?php echo esc_html($atts['back_label']); ?></button></div>
        </div>

        <div class="vk-stap" id="stap-4">
          <h3 class="vk-stap-titel"><?php echo esc_html($atts['step_4_title']); ?></h3>
          <p class="vk-stap-sub"><?php echo esc_html($atts['step_4_sub']); ?></p>
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
          <button class="vk-btn vk-btn-oranje vk-btn-full vk-btn-lg" onclick="vkVerstuur()"><?php echo esc_html($atts['submit_label']); ?></button>
          <p class="vk-disclaimer">Gratis en vrijblijvend. Door te versturen gaat u akkoord met onze <a href="<?php echo esc_url(home_url('/privacy')); ?>">privacyverklaring</a>.</p>
          <div class="vk-terug"><button onclick="vkStap(3)"><?php echo esc_html($atts['back_label']); ?></button></div>
        </div>

        <div class="vk-stap vk-succes" id="stap-succes">
          <div class="vk-succes-mark">✓</div>
          <div class="vk-succes-pill">Aanvraag compleet</div>
          <h3><?php echo esc_html($atts['success_title']); ?></h3>
          <p><?php echo esc_html($atts['success_text']); ?></p>
          <div class="vk-succes-next">
            <div><strong>1</strong><span>Aanvraag ontvangen</span></div>
            <div><strong>2</strong><span>Vakvriend rekent mee</span></div>
            <div><strong>3</strong><span>U krijgt advies</span></div>
          </div>
          <a href="tel:<?php echo esc_attr($ctx['tel_clean']); ?>" class="vk-btn vk-btn-groen vk-btn-full vk-btn-space">Direct bellen: <?php echo esc_html($ctx['telefoon']); ?></a>
          <a href="https://wa.me/<?php echo esc_attr($ctx['whatsapp']); ?>" class="vk-btn vk-btn-wa vk-btn-full vk-btn-space-sm">WhatsApp sturen</a>
        </div>
      </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('warmtepomp_lead_form', 'wc_shortcode_lead_form');
