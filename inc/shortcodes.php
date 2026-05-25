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
    $is_airco = function_exists('wc_is_airco_site') && wc_is_airco_site();
    ob_start();
    ?>
    <form class="<?php echo esc_attr($class); ?>" action="#" method="post" novalidate>
      <div class="vk-callback-head">
        <strong>Liever direct iemand spreken?</strong>
        <span><?php echo esc_html($is_airco ? 'Laat uw nummer achter. Dan belt Vakvriend u over de beste airco-oplossing, kosten en plaatsing.' : 'Laat uw nummer achter. Dan belt Vakvriend u over kosten, subsidie en de beste route.'); ?></span>
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
      <p class="vk-callback-note"><?php echo esc_html($is_airco ? 'We gebruiken dit alleen om uw airco-offerte op te volgen.' : 'We gebruiken dit alleen om uw woningcheck op te volgen.'); ?></p>
      <p class="vk-callback-status" role="status" aria-live="polite"></p>
    </form>
    <?php
    return ob_get_clean();
}

function wc_woningcheck_widget($show_head = true) {
    $ctx = wc_landing_context();
    $is_airco = function_exists('wc_is_airco_site') && wc_is_airco_site();
    $whatsapp_text = $is_airco ? 'Ik wil graag een airco-offerte bespreken.' : 'Ik wil graag mijn woningcheck bespreken.';
    $whatsapp_url = 'https://wa.me/' . preg_replace('/\D+/', '', $ctx['whatsapp']) . '?text=' . rawurlencode($whatsapp_text);
    ob_start();
    ?>
    <div class="vk-form-wrap" id="formulier">
      <div class="vk-form-card vk-woningcheck-card" data-whatsapp-url="<?php echo esc_url($whatsapp_url); ?>">
        <?php if ($show_head): ?>
          <div class="vk-form-head">
            <div class="vk-scan-badge"><span></span> <?php echo esc_html(wc_theme_setting('form_badge', $is_airco ? 'Gratis airco-offerte' : 'Vrijblijvende woningcheck')); ?></div>
            <h2><?php echo esc_html(wc_theme_setting('form_title', $is_airco ? 'Ontvang uw airco prijsvoorstel' : 'Ontdek welke warmtepomp past bij uw woning')); ?></h2>
            <p><?php echo esc_html(wc_theme_setting('form_intro', $is_airco ? 'Start met postcode, huisnummer en e-mail. Vakvriend koppelt merk, vermogen, montage en afwerking aan uw situatie.' : 'Vul uw adresgegevens in en ontvang uw woningcheck in een persoonlijk dashboard. Vakvriend controleert kosten, subsidie en technische haalbaarheid.')); ?></p>
          </div>
          <div class="vk-form-adviser" aria-label="Vakvriend kijkt persoonlijk mee">
            <figure class="vk-form-adviser-portrait" aria-hidden="true">
              <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/vakvriend-chat-monteur.png'); ?>" alt="" loading="eager" fetchpriority="high">
            </figure>
            <div>
              <em><?php echo esc_html(wc_theme_setting('form_adviser_badge', 'Persoonlijk dashboard')); ?></em>
              <strong><?php echo esc_html(wc_theme_setting('form_adviser_title', 'Een vakman kijkt met u mee')); ?></strong>
              <span><?php echo esc_html(wc_theme_setting('form_adviser_text', 'Bekijk uw warmtepomproute, aandachtspunten en vervolgstappen overzichtelijk op één plek.')); ?></span>
            </div>
          </div>
        <?php endif; ?>
        <div class="vk-homezero-widget" aria-label="<?php echo esc_attr($is_airco ? 'Vraag een airco-offerte aan' : 'Start de woningcheck'); ?>">
          <script defer src="https://homezerotech.github.io/Widget/Production/embed.js"></script>
          <hz-embed
            src="https://scan.vakvriend.nl/link/start?id=c2b0c1b7-4513-45c5-ad39-159e0f9b00e5"
            data-address-format="dutch"
            data-language="nl"
            data-open-new-tab="false"
            data-show-phone="false"
            data-phone-required="false"
            data-show-email="true"
            data-email-required="true"
            data-button-text="<?php echo esc_attr(wc_theme_setting('form_button_text', $is_airco ? 'Ontvang mijn airco-offerte' : 'Ontvang mijn woningcheck')); ?>"
            data-button-radius="7px"
            data-color="#066939"
            data-title=""
            data-subtitle="">
          </hz-embed>
        </div>
        <details class="vk-widget-backup">
          <summary><?php echo esc_html(wc_theme_setting('form_backup_summary', 'Lukt invullen niet? Laat Vakvriend u terugbellen')); ?></summary>
          <?php echo wc_callback_form_markup('vk-callback-form vk-widget-callback-form'); ?>
        </details>
      </div>
    </div>
    <?php
    return ob_get_clean();
}

function wc_homezero_scan_widget($show_head = true) {
    return wc_woningcheck_widget($show_head);
}

function wc_shortcode_woningcheck($atts = array()) {
    return wc_woningcheck_widget(true);
}
add_shortcode('woningcheck', 'wc_shortcode_woningcheck');

function wc_shortcode_lead_form($atts = array()) {
    return wc_woningcheck_widget(false);
}
add_shortcode('warmtepomp_lead_form', 'wc_shortcode_lead_form');
add_shortcode('airco_check', 'wc_shortcode_woningcheck');

function wc_callback_lead_ajax() {
    check_ajax_referer('wc_callback_lead', 'nonce');

    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $phone = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
    $postcode = strtoupper(sanitize_text_field(wp_unslash($_POST['postcode'] ?? '')));
    $page_url = esc_url_raw(wp_unslash($_POST['page_url'] ?? ''));
    $hostname = sanitize_text_field(wp_unslash($_POST['hostname'] ?? ''));
    $session_id = sanitize_text_field(wp_unslash($_POST['session_id'] ?? ''));
    $utm_source = sanitize_text_field(wp_unslash($_POST['utm_source'] ?? ''));
    $utm_medium = sanitize_text_field(wp_unslash($_POST['utm_medium'] ?? ''));
    $utm_campaign = sanitize_text_field(wp_unslash($_POST['utm_campaign'] ?? ''));
    $utm_content = sanitize_text_field(wp_unslash($_POST['utm_content'] ?? ''));
    $utm_term = sanitize_text_field(wp_unslash($_POST['utm_term'] ?? ''));
    $gclid = sanitize_text_field(wp_unslash($_POST['gclid'] ?? ''));
    $gbraid = sanitize_text_field(wp_unslash($_POST['gbraid'] ?? ''));
    $wbraid = sanitize_text_field(wp_unslash($_POST['wbraid'] ?? ''));
    $gad_source = sanitize_text_field(wp_unslash($_POST['gad_source'] ?? ''));
    $gad_campaignid = sanitize_text_field(wp_unslash($_POST['gad_campaignid'] ?? ''));
    $msclkid = sanitize_text_field(wp_unslash($_POST['msclkid'] ?? ''));

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
    $message .= "gclid: " . ($gclid ?: '-') . "\n";
    $message .= "wbraid: " . ($wbraid ?: '-') . "\n";
    $message .= "gbraid: " . ($gbraid ?: '-') . "\n";
    $message .= "msclkid: " . ($msclkid ?: '-') . "\n";
    $message .= "Tijd: " . current_time('mysql') . "\n";

    wp_mail(
        'tonny@vakvriend.nl',
        $subject,
        $message,
        array('Content-Type: text/plain; charset=UTF-8')
    );

    wp_send_json_success(array('message' => 'Top, we nemen zo snel mogelijk contact op.'));
}
add_action('wp_ajax_wc_callback_lead', 'wc_callback_lead_ajax');
add_action('wp_ajax_nopriv_wc_callback_lead', 'wc_callback_lead_ajax');
