<?php
defined('ABSPATH') || exit;

function wc_analytics_table_name() {
    global $wpdb;
    return $wpdb->prefix . 'wc_analytics_events';
}

function wc_analytics_install() {
    global $wpdb;

    $table = wc_analytics_table_name();
    $charset_collate = $wpdb->get_charset_collate();

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    dbDelta("CREATE TABLE {$table} (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        event_name varchar(80) NOT NULL,
        session_id varchar(80) NOT NULL,
        page_url text NOT NULL,
        page_path varchar(255) NOT NULL DEFAULT '',
        hostname varchar(190) NOT NULL DEFAULT '',
        referrer text NULL,
        section varchar(190) NOT NULL DEFAULT '',
        device_type varchar(40) NOT NULL DEFAULT '',
        browser varchar(80) NOT NULL DEFAULT '',
        utm_source varchar(120) NOT NULL DEFAULT '',
        utm_medium varchar(120) NOT NULL DEFAULT '',
        utm_campaign varchar(190) NOT NULL DEFAULT '',
        utm_content varchar(190) NOT NULL DEFAULT '',
        utm_term varchar(190) NOT NULL DEFAULT '',
        duration_ms int(10) unsigned NOT NULL DEFAULT 0,
        scroll_depth tinyint(3) unsigned NOT NULL DEFAULT 0,
        meta_json longtext NULL,
        created_at datetime NOT NULL,
        PRIMARY KEY  (id),
        KEY event_name (event_name),
        KEY session_id (session_id),
        KEY hostname (hostname),
        KEY page_path (page_path),
        KEY device_type (device_type),
        KEY browser (browser),
        KEY utm_source (utm_source),
        KEY utm_campaign (utm_campaign),
        KEY created_at (created_at)
    ) {$charset_collate};");

    update_option('wc_analytics_db_version', '2');
}
add_action('after_switch_theme', 'wc_analytics_install');

function wc_analytics_maybe_install() {
    if (get_option('wc_analytics_db_version') !== '2') {
        wc_analytics_install();
    }
}
add_action('admin_init', 'wc_analytics_maybe_install');

function wc_analytics_ajax_track() {
    check_ajax_referer('wc_analytics_track', 'nonce');
    wc_analytics_maybe_install();

    global $wpdb;
    $table = wc_analytics_table_name();
    $payload = isset($_POST['payload']) ? json_decode(wp_unslash((string) $_POST['payload']), true) : array();
    if (!is_array($payload)) {
        wp_send_json_error(array('message' => 'Invalid payload'), 400);
    }

    $event_name = sanitize_key($payload['event_name'] ?? '');
    $session_id = sanitize_text_field($payload['session_id'] ?? '');
    if (!$event_name || !$session_id) {
        wp_send_json_error(array('message' => 'Missing event data'), 400);
    }

    $page_url = esc_url_raw($payload['page_url'] ?? '');
    $page_path = sanitize_text_field($payload['page_path'] ?? '');
    $hostname = sanitize_text_field($payload['hostname'] ?? '');
    $referrer = esc_url_raw($payload['referrer'] ?? '');
    $section = sanitize_text_field($payload['section'] ?? '');
    $device_type = sanitize_text_field($payload['device_type'] ?? '');
    $browser = sanitize_text_field($payload['browser'] ?? '');
    $utm_source = sanitize_text_field($payload['utm_source'] ?? '');
    $utm_medium = sanitize_text_field($payload['utm_medium'] ?? '');
    $utm_campaign = sanitize_text_field($payload['utm_campaign'] ?? '');
    $utm_content = sanitize_text_field($payload['utm_content'] ?? '');
    $utm_term = sanitize_text_field($payload['utm_term'] ?? '');
    $duration_ms = max(0, min(86400000, absint($payload['duration_ms'] ?? 0)));
    $scroll_depth = max(0, min(100, absint($payload['scroll_depth'] ?? 0)));

    $reserved = array('event_name', 'session_id', 'page_url', 'page_path', 'hostname', 'referrer', 'section', 'device_type', 'browser', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'duration_ms', 'scroll_depth');
    $meta = array_diff_key($payload, array_flip($reserved));

    $wpdb->insert($table, array(
        'event_name' => $event_name,
        'session_id' => $session_id,
        'page_url' => $page_url,
        'page_path' => $page_path,
        'hostname' => $hostname,
        'referrer' => $referrer,
        'section' => $section,
        'device_type' => $device_type,
        'browser' => $browser,
        'utm_source' => $utm_source,
        'utm_medium' => $utm_medium,
        'utm_campaign' => $utm_campaign,
        'utm_content' => $utm_content,
        'utm_term' => $utm_term,
        'duration_ms' => $duration_ms,
        'scroll_depth' => $scroll_depth,
        'meta_json' => wp_json_encode($meta),
        'created_at' => current_time('mysql'),
    ), array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s'));

    wp_send_json_success(array('stored' => true));
}
add_action('wp_ajax_wc_analytics_event', 'wc_analytics_ajax_track');
add_action('wp_ajax_nopriv_wc_analytics_event', 'wc_analytics_ajax_track');

function wc_analytics_menu() {
    add_menu_page(
        'Lead Analytics',
        'Lead Analytics',
        'manage_options',
        'wc-lead-analytics',
        'wc_analytics_dashboard_page',
        'dashicons-chart-area',
        57
    );
}
add_action('admin_menu', 'wc_analytics_menu');

function wc_analytics_days_filter() {
    $days = absint($_GET['days'] ?? 7);
    return in_array($days, array(1, 7, 14, 30, 90), true) ? $days : 7;
}

function wc_analytics_dashboard_page() {
    global $wpdb;
    wc_analytics_maybe_install();

    $table = wc_analytics_table_name();
    $days = wc_analytics_days_filter();
    $since = gmdate('Y-m-d H:i:s', time() - ($days * DAY_IN_SECONDS));

    $sessions = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(DISTINCT session_id) FROM {$table} WHERE created_at >= %s", $since));
    $pageviews = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE event_name = 'page_view' AND created_at >= %s", $since));
    $cta_clicks = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE event_name = 'lead_cta_click' AND created_at >= %s", $since));
    $contact_clicks = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE event_name IN ('phone_click', 'whatsapp_click') AND created_at >= %s", $since));
    $avg_duration = (int) $wpdb->get_var($wpdb->prepare("SELECT AVG(duration_ms) FROM {$table} WHERE event_name = 'page_exit' AND duration_ms > 0 AND created_at >= %s", $since));
    $avg_scroll = (int) $wpdb->get_var($wpdb->prepare("SELECT AVG(scroll_depth) FROM {$table} WHERE event_name = 'page_exit' AND scroll_depth > 0 AND created_at >= %s", $since));
    $rage_clicks = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE event_name = 'rage_click' AND created_at >= %s", $since));
    $dead_clicks = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE event_name = 'dead_click' AND created_at >= %s", $since));

    $top_pages = $wpdb->get_results($wpdb->prepare("SELECT hostname, page_path, COUNT(*) views, COUNT(DISTINCT session_id) sessions FROM {$table} WHERE event_name = 'page_view' AND created_at >= %s GROUP BY hostname, page_path ORDER BY views DESC LIMIT 10", $since));
    $dropoffs = $wpdb->get_results($wpdb->prepare("SELECT section, COUNT(*) exits, AVG(duration_ms) avg_duration, AVG(scroll_depth) avg_scroll FROM {$table} WHERE event_name = 'page_exit' AND created_at >= %s GROUP BY section ORDER BY exits DESC LIMIT 10", $since));
    $events = $wpdb->get_results($wpdb->prepare("SELECT event_name, COUNT(*) total FROM {$table} WHERE created_at >= %s GROUP BY event_name ORDER BY total DESC LIMIT 12", $since));
    $domains = $wpdb->get_results($wpdb->prepare("SELECT hostname, COUNT(DISTINCT session_id) sessions, COUNT(*) events FROM {$table} WHERE created_at >= %s GROUP BY hostname ORDER BY sessions DESC LIMIT 12", $since));
    $devices = $wpdb->get_results($wpdb->prepare("SELECT device_type, browser, COUNT(DISTINCT session_id) sessions, COUNT(*) events FROM {$table} WHERE created_at >= %s GROUP BY device_type, browser ORDER BY sessions DESC LIMIT 12", $since));
    $campaigns = $wpdb->get_results($wpdb->prepare("SELECT utm_source, utm_campaign, utm_content, COUNT(DISTINCT session_id) sessions, COUNT(*) events FROM {$table} WHERE created_at >= %s AND (utm_source <> '' OR utm_campaign <> '') GROUP BY utm_source, utm_campaign, utm_content ORDER BY sessions DESC LIMIT 12", $since));
    $click_targets = $wpdb->get_results($wpdb->prepare("SELECT JSON_UNQUOTE(JSON_EXTRACT(meta_json, '$.target_text')) target_text, JSON_UNQUOTE(JSON_EXTRACT(meta_json, '$.target_tag')) target_tag, COUNT(*) clicks FROM {$table} WHERE event_name IN ('element_click', 'lead_cta_click', 'outbound_click', 'dead_click', 'rage_click') AND created_at >= %s GROUP BY target_text, target_tag ORDER BY clicks DESC LIMIT 12", $since));
    $recent_sessions = $wpdb->get_results($wpdb->prepare("SELECT session_id, MAX(hostname) hostname, MAX(page_path) page_path, MAX(device_type) device_type, MAX(browser) browser, COUNT(*) events, MAX(scroll_depth) scroll_depth, MAX(duration_ms) duration_ms, MAX(created_at) last_seen FROM {$table} WHERE created_at >= %s GROUP BY session_id ORDER BY last_seen DESC LIMIT 12", $since));

    ?>
    <div class="wrap">
        <h1>Lead Analytics</h1>
        <p>Eigen WordPress-metingen voor sessies, scroll, CTA's, klikgedrag, devices, campagnes en afhakers. Heatmaps en recordings bekijk je in Contentsquare.</p>
        <p>
            <?php foreach (array(1, 7, 14, 30, 90) as $option) : ?>
                <a class="button <?php echo $days === $option ? 'button-primary' : ''; ?>" href="<?php echo esc_url(add_query_arg('days', $option)); ?>"><?php echo esc_html($option); ?> dagen</a>
            <?php endforeach; ?>
            <a class="button" href="https://app.contentsquare.com/" target="_blank" rel="noopener noreferrer">Open Contentsquare heatmaps</a>
        </p>

        <style>
            .wc-kpis{display:grid;grid-template-columns:repeat(8,minmax(0,1fr));gap:12px;margin:18px 0}
            .wc-kpi,.wc-panel{background:#fff;border:1px solid #dcdcde;border-radius:8px;padding:16px}
            .wc-kpi strong{display:block;font-size:24px;line-height:1.2}
            .wc-kpi span{color:#646970}
            .wc-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
            .wc-panel table{width:100%;border-collapse:collapse}
            .wc-panel th,.wc-panel td{padding:8px;border-bottom:1px solid #f0f0f1;text-align:left}
            @media(max-width:1400px){.wc-kpis{grid-template-columns:repeat(4,1fr)}.wc-grid{grid-template-columns:1fr}}
            @media(max-width:782px){.wc-kpis{grid-template-columns:repeat(2,1fr)}}
        </style>

        <div class="wc-kpis">
            <div class="wc-kpi"><strong><?php echo esc_html(number_format_i18n($sessions)); ?></strong><span>Sessies</span></div>
            <div class="wc-kpi"><strong><?php echo esc_html(number_format_i18n($pageviews)); ?></strong><span>Pageviews</span></div>
            <div class="wc-kpi"><strong><?php echo esc_html(gmdate('i:s', max(0, (int) ($avg_duration / 1000)))); ?></strong><span>Gem. sessieduur</span></div>
            <div class="wc-kpi"><strong><?php echo esc_html($avg_scroll); ?>%</strong><span>Gem. scroll</span></div>
            <div class="wc-kpi"><strong><?php echo esc_html(number_format_i18n($cta_clicks)); ?></strong><span>Woningcheck CTA's</span></div>
            <div class="wc-kpi"><strong><?php echo esc_html(number_format_i18n($contact_clicks)); ?></strong><span>Bel/WhatsApp</span></div>
            <div class="wc-kpi"><strong><?php echo esc_html(number_format_i18n($rage_clicks)); ?></strong><span>Rage clicks</span></div>
            <div class="wc-kpi"><strong><?php echo esc_html(number_format_i18n($dead_clicks)); ?></strong><span>Dead clicks</span></div>
        </div>

        <div class="wc-grid">
            <?php wc_analytics_table_panel('Populaire pagina’s', array('Domein', 'Pad', 'Views', 'Sessies'), $top_pages, array('hostname', 'page_path', 'views', 'sessions')); ?>
            <?php wc_analytics_table_panel('Afhaakmomenten', array('Laatste sectie', 'Exits', 'Gem. tijd', 'Gem. scroll'), $dropoffs, array('section', 'exits', 'avg_duration', 'avg_scroll'), true); ?>
            <?php wc_analytics_table_panel('Events', array('Event', 'Aantal'), $events, array('event_name', 'total')); ?>
            <?php wc_analytics_table_panel('Domeinen', array('Domein', 'Sessies', 'Events'), $domains, array('hostname', 'sessions', 'events')); ?>
            <?php wc_analytics_table_panel('Devices en browsers', array('Device', 'Browser', 'Sessies', 'Events'), $devices, array('device_type', 'browser', 'sessions', 'events')); ?>
            <?php wc_analytics_table_panel('Campagnes en UTM’s', array('Source', 'Campaign', 'Content', 'Sessies', 'Events'), $campaigns, array('utm_source', 'utm_campaign', 'utm_content', 'sessions', 'events')); ?>
            <?php wc_analytics_table_panel('Meest aangeklikt', array('Tekst', 'Element', 'Clicks'), $click_targets, array('target_text', 'target_tag', 'clicks')); ?>
            <?php wc_analytics_table_panel('Recente sessies', array('Sessie', 'Domein', 'Pad', 'Device', 'Browser', 'Events', 'Scroll', 'Tijd'), $recent_sessions, array('session_id', 'hostname', 'page_path', 'device_type', 'browser', 'events', 'scroll_depth', 'duration_ms'), true); ?>
        </div>
    </div>
    <?php
}

function wc_analytics_table_panel($title, $headers, $rows, $fields, $format_dropoff = false) {
    ?>
    <div class="wc-panel">
        <h2><?php echo esc_html($title); ?></h2>
        <table>
            <thead><tr><?php foreach ($headers as $header) : ?><th><?php echo esc_html($header); ?></th><?php endforeach; ?></tr></thead>
            <tbody>
            <?php if (!$rows) : ?>
                <tr><td colspan="<?php echo esc_attr(count($headers)); ?>">Nog geen data.</td></tr>
            <?php else : ?>
                <?php foreach ($rows as $row) : ?>
                    <tr>
                    <?php foreach ($fields as $field) : ?>
                        <?php
                        $value = $row->{$field} ?? '';
                        if ($format_dropoff && in_array($field, array('avg_duration', 'duration_ms'), true)) {
                            $value = gmdate('i:s', max(0, (int) ($value / 1000)));
                        } elseif ($format_dropoff && in_array($field, array('avg_scroll', 'scroll_depth'), true)) {
                            $value = round((float) $value) . '%';
                        }
                        ?>
                        <td><?php echo esc_html((string) ($value !== '' ? $value : 'Onbekend')); ?></td>
                    <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
