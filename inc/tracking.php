<?php
defined('ABSPATH') || exit;

function wc_tracking_settings_init() {
    register_setting('wc_tracking', 'wc_gtm_container_id', array(
        'type'              => 'string',
        'sanitize_callback' => 'wc_sanitize_gtm_container_id',
        'default'           => 'GTM-NR62JDXS',
    ));

    register_setting('wc_tracking', 'wc_ga4_measurement_id', array(
        'type'              => 'string',
        'sanitize_callback' => 'wc_sanitize_ga4_measurement_id',
        'default'           => 'G-8EC1V5SCWD',
    ));

    add_settings_section(
        'wc_tracking_main',
        'Google Tag Manager',
        '__return_false',
        'wc-tracking'
    );

    add_settings_field(
        'wc_gtm_container_id',
        'GTM container ID',
        'wc_gtm_container_field',
        'wc-tracking',
        'wc_tracking_main'
    );

    add_settings_field(
        'wc_ga4_measurement_id',
        'GA4 Measurement ID',
        'wc_ga4_measurement_field',
        'wc-tracking',
        'wc_tracking_main'
    );
}
add_action('admin_init', 'wc_tracking_settings_init');

function wc_sanitize_gtm_container_id($value) {
    $value = strtoupper(trim((string) $value));

    if ($value === '') {
        return '';
    }

    return preg_match('/^GTM-[A-Z0-9]+$/', $value) ? $value : '';
}

function wc_sanitize_ga4_measurement_id($value) {
    $value = strtoupper(trim((string) $value));

    if ($value === '') {
        return '';
    }

    return preg_match('/^G-[A-Z0-9]+$/', $value) ? $value : '';
}

function wc_gtm_container_field() {
    $value = get_option('wc_gtm_container_id', '');
    echo '<input type="text" name="wc_gtm_container_id" value="' . esc_attr($value) . '" class="regular-text" placeholder="GTM-XXXXXXX">';
    echo '<p class="description">Plaats GA4, Google Ads conversies, remarketing en events in Google Tag Manager. Laat leeg om GTM niet te laden.</p>';
}

function wc_ga4_measurement_field() {
    $value = get_option('wc_ga4_measurement_id', '');
    echo '<input type="text" name="wc_ga4_measurement_id" value="' . esc_attr($value) . '" class="regular-text" placeholder="G-XXXXXXXXXX">';
    echo '<p class="description">Wordt gebruikt voor directe GA4 funnel-events zoals leadstappen, CTA-kliks en succesvolle leads. Er worden geen naam, e-mail of telefoonnummer meegestuurd.</p>';
}

function wc_tracking_menu() {
    add_options_page(
        'Campagne tracking',
        'Campagne tracking',
        'manage_options',
        'wc-tracking',
        'wc_tracking_page'
    );
}
add_action('admin_menu', 'wc_tracking_menu');

function wc_tracking_page() {
    ?>
    <div class="wrap">
        <h1>Campagne tracking</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('wc_tracking');
            do_settings_sections('wc-tracking');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function wc_get_gtm_container_id() {
    return wc_sanitize_gtm_container_id(get_option('wc_gtm_container_id', 'GTM-NR62JDXS'));
}

function wc_get_ga4_measurement_id() {
    return wc_sanitize_ga4_measurement_id(get_option('wc_ga4_measurement_id', 'G-8EC1V5SCWD'));
}

function wc_ga4_head() {
    $measurement_id = wc_get_ga4_measurement_id();
    if (!$measurement_id) {
        return;
    }
    ?>
<!-- Google Analytics 4 funnel events -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($measurement_id); ?>"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
window.vkGa4MeasurementId = '<?php echo esc_js($measurement_id); ?>';
gtag('js', new Date());
gtag('config', window.vkGa4MeasurementId, {'send_page_view': false});
</script>
<!-- End Google Analytics 4 funnel events -->
    <?php
}
add_action('wp_head', 'wc_ga4_head', 2);

function wc_gtm_head() {
    $container_id = wc_get_gtm_container_id();
    if (!$container_id) {
        return;
    }
    ?>
<!-- Google Tag Manager -->
<script>
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo esc_js($container_id); ?>');
</script>
<!-- End Google Tag Manager -->
    <?php
}
add_action('wp_head', 'wc_gtm_head', 1);

function wc_gtm_body() {
    $container_id = wc_get_gtm_container_id();
    if (!$container_id) {
        return;
    }
    ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr($container_id); ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <?php
}
add_action('wp_body_open', 'wc_gtm_body', 1);
