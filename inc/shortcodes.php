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
        <?php for ($set = 0; $set < 4; $set++) : ?>
          <div class="vk-merken-set" <?php if ($set > 0) : ?>aria-hidden="true"<?php endif; ?>>
            <?php foreach ($logos as $logo) : ?>
              <img src="<?php echo esc_url($logo[0]); ?>" alt="<?php echo esc_attr($logo[1]); ?>" height="22" loading="lazy">
            <?php endforeach; ?>
          </div>
        <?php endfor; ?>
      </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('warmtepomp_logos', 'wc_shortcode_logos');

function wc_homezero_scan_widget($show_head = true) {
    static $script_loaded = false;
    ob_start();
    ?>
    <div class="vk-form-wrap" id="formulier">
      <div class="vk-form-card">
        <?php if ($show_head): ?>
          <div class="vk-form-head">
            <div class="vk-scan-badge"><span></span> Vrijblijvende scan</div>
            <h2>Start uw verduurzamingsscan</h2>
            <p>Vul woningtype, systeemwens en verbruik in en ontvang een advies dat bij uw huis past.</p>
          </div>
          <div class="vk-form-adviser" aria-label="Vakvriend kijkt persoonlijk mee">
            <figure class="vk-form-adviser-portrait" aria-hidden="true">
              <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/vakvriend-chat-monteur.png'); ?>" alt="" loading="lazy" decoding="async">
            </figure>
            <div>
              <em>Persoonlijk advies</em>
              <strong>Vakvriend kijkt mee</strong>
              <span>Een vakman beoordeelt uw scan, zonder verkoopdruk.</span>
            </div>
          </div>
        <?php endif; ?>
        <?php if (!$script_loaded): ?>
          <script defer src="https://homezerotech.github.io/Widget/Production/embed.js"></script>
          <?php $script_loaded = true; ?>
        <?php endif; ?>
        <hz-embed
            src="https://scan.vakvriend.nl/link/start?id=22c68b1d-2179-4fbb-93a2-4991451ced41"
            data-address-format="dutch"
            data-language="nl"
            data-open-new-tab="true"
            data-show-phone="false"
            data-phone-required="false"
            data-show-email="true"
            data-email-required="false"
            data-button-text="Start verduurzamingsscan"
            data-button-radius="7px"
            data-color="#066939"
            data-title=""
            data-subtitle=""
        ></hz-embed>
        <div class="vk-scan-usps" aria-label="Voordelen van de scan">
          <span>ISDE meegenomen</span>
          <span>Merkonafhankelijk</span>
          <span>Geen verkoopdruk</span>
        </div>
      </div>
    </div>
    <?php
    return ob_get_clean();
}

function wc_shortcode_lead_form($atts = array()) {
    return wc_homezero_scan_widget(false);
}
add_shortcode('warmtepomp_lead_form', 'wc_shortcode_lead_form');
