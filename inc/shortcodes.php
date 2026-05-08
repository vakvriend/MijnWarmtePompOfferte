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

function wc_callback_form_markup($class = 'vk-callback-form') {
    ob_start();
    ?>
    <form class="<?php echo esc_attr($class); ?>" action="#" method="post" novalidate>
      <div class="vk-callback-head">
        <strong>Liever dat Vakvriend meekijkt?</strong>
        <span>Laat uw nummer achter, dan bellen we u over de woningcheck.</span>
      </div>
      <div class="vk-callback-fields">
        <label>
          <span>Naam</span>
          <input type="text" name="name" autocomplete="name" placeholder="Uw naam">
        </label>
        <label>
          <span>Telefoonnummer*</span>
          <input type="tel" name="phone" autocomplete="tel" placeholder="06 12345678" required>
        </label>
        <label>
          <span>Postcode</span>
          <input type="text" name="postcode" autocomplete="postal-code" placeholder="1234AB">
        </label>
      </div>
      <button type="submit">Bel mij terug</button>
      <p class="vk-callback-note">We gebruiken dit alleen om uw woningcheck op te volgen.</p>
      <p class="vk-callback-status" role="status" aria-live="polite"></p>
    </form>
    <?php
    return ob_get_clean();
}

function wc_homezero_scan_widget($show_head = true) {
    static $script_loaded = false;
    ob_start();
    ?>
    <div class="vk-form-wrap" id="formulier">
      <div class="vk-form-card">
        <?php if ($show_head): ?>
          <div class="vk-form-head">
            <div class="vk-scan-badge"><span></span> Vrijblijvende woningcheck</div>
            <h2>Bekijk binnen 1 minuut welke warmtepomp logisch is</h2>
            <p>Start met postcode en huisnummer. U ziet direct welke route past: lucht/water, hybride, ventilatie, bodem of warmtepompboiler.</p>
          </div>
          <div class="vk-form-adviser" aria-label="Vakvriend kijkt persoonlijk mee">
            <figure class="vk-form-adviser-portrait" aria-hidden="true">
              <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/vakvriend-chat-monteur.png'); ?>" alt="" loading="eager" fetchpriority="high">
            </figure>
            <div>
              <em>Persoonlijk advies</em>
              <strong>Vakvriend kijkt mee</strong>
              <span>Een vakman beoordeelt uw woningcheck met eerlijk en praktisch advies.</span>
            </div>
          </div>
          <div class="vk-result-preview" aria-label="Wat u krijgt na de woningcheck">
            <strong>Dit krijgt u direct inzichtelijk</strong>
            <ul>
              <li>Passende warmtepomproute</li>
              <li>ISDE-subsidie indicatie</li>
              <li>Praktische aandachtspunten</li>
              <li>Merkonafhankelijk advies</li>
            </ul>
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
            data-open-new-tab="false"
            data-show-phone="false"
            data-phone-required="false"
            data-show-email="false"
            data-email-required="false"
            data-button-text="Start woningcheck"
            data-button-radius="7px"
            data-color="#066939"
            data-title=""
            data-subtitle=""
        ></hz-embed>
      </div>
    </div>
    <?php
    return ob_get_clean();
}

function wc_shortcode_lead_form($atts = array()) {
    return wc_homezero_scan_widget(false);
}
add_shortcode('warmtepomp_lead_form', 'wc_shortcode_lead_form');

function wc_callback_lead_ajax() {
    check_ajax_referer('wc_callback_lead', 'nonce');

    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $phone = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
    $postcode = strtoupper(sanitize_text_field(wp_unslash($_POST['postcode'] ?? '')));
    $page_url = esc_url_raw(wp_unslash($_POST['page_url'] ?? ''));
    $hostname = sanitize_text_field(wp_unslash($_POST['hostname'] ?? ''));
    $session_id = sanitize_text_field(wp_unslash($_POST['session_id'] ?? ''));

    if ($phone === '' || strlen(preg_replace('/\D+/', '', $phone)) < 8) {
        wp_send_json_error(array('message' => 'Vul een geldig telefoonnummer in.'), 400);
    }

    $subject = 'Nieuwe terugbel lead - Vakvriend';
    if ($hostname) {
        $subject .= ' - ' . $hostname;
    }

    $message = "Er is een terugbelverzoek binnengekomen via de warmtepomp campagne.\n\n";
    $message .= "Naam: " . ($name ?: '-') . "\n";
    $message .= "Telefoonnummer: {$phone}\n";
    $message .= "Postcode: " . ($postcode ?: '-') . "\n";
    $message .= "Domein: " . ($hostname ?: '-') . "\n";
    $message .= "Pagina: " . ($page_url ?: '-') . "\n";
    $message .= "Sessie: " . ($session_id ?: '-') . "\n";
    $message .= "Tijd: " . current_time('mysql') . "\n";

    wp_mail(
        'tonny@vakvriend.nl',
        $subject,
        $message,
        array('Content-Type: text/plain; charset=UTF-8')
    );

    if (function_exists('wc_analytics_maybe_install') && function_exists('wc_analytics_table_name')) {
        wc_analytics_maybe_install();

        global $wpdb;
        $wpdb->insert(wc_analytics_table_name(), array(
            'event_name' => 'callback_lead_submit',
            'session_id' => $session_id ?: ('callback_' . wp_generate_uuid4()),
            'page_url' => $page_url,
            'page_path' => $page_url ? (string) wp_parse_url($page_url, PHP_URL_PATH) : '/',
            'hostname' => $hostname,
            'referrer' => '',
            'section' => 'callback_form',
            'device_type' => '',
            'browser' => '',
            'utm_source' => '',
            'utm_medium' => '',
            'utm_campaign' => '',
            'utm_content' => '',
            'utm_term' => '',
            'duration_ms' => 0,
            'scroll_depth' => 0,
            'meta_json' => wp_json_encode(array(
                'name' => $name,
                'phone' => $phone,
                'postcode' => $postcode,
                'source' => 'callback_form',
            )),
            'created_at' => current_time('mysql'),
        ), array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s'));
    }

    wp_send_json_success(array('message' => 'Top, we nemen zo snel mogelijk contact op.'));
}
add_action('wp_ajax_wc_callback_lead', 'wc_callback_lead_ajax');
add_action('wp_ajax_nopriv_wc_callback_lead', 'wc_callback_lead_ajax');
