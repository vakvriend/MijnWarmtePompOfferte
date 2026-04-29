<?php
defined('ABSPATH') || exit;

function wc_meta($key, $default = '', $post_id = null) {
    if (!$post_id) $post_id = get_the_ID();
    $val = get_post_meta($post_id, $key, true);
    return $val !== '' ? $val : $default;
}

function wc_meta_rows($key, $defaults = array(), $columns = 3, $post_id = null) {
    $raw = trim((string) wc_meta($key, '', $post_id));
    if ($raw === '') {
        return $defaults;
    }

    $rows = array();
    foreach (preg_split('/\r\n|\r|\n/', $raw) as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }

        $parts = array_map('trim', explode('|', $line));
        $parts = array_pad(array_slice($parts, 0, $columns), $columns, '');
        $rows[] = $parts;
    }

    return $rows ?: $defaults;
}

function wc_meta_faq_rows($key, $defaults = array(), $post_id = null) {
    $raw = trim((string) wc_meta($key, '', $post_id));
    if ($raw === '') {
        return $defaults;
    }

    $rows = array();
    foreach (preg_split('/\r\n|\r|\n/', $raw) as $line) {
        if (strpos($line, ':::') === false) {
            continue;
        }

        list($question, $answer) = explode(':::', $line, 2);
        $question = trim($question);
        $answer = trim($answer);
        if ($question && $answer) {
            $rows[] = array($question, $answer);
        }
    }

    return $rows ?: $defaults;
}

function wc_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
}
add_action('after_setup_theme', 'wc_theme_setup');

function wc_enqueue_assets() {
    wp_enqueue_style('wc-fonts',
        'https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700;9..40,800;9..40,900&display=swap',
        [], null
    );
    wp_enqueue_style('wc-main', get_template_directory_uri() . '/assets/css/main.css', ['wc-fonts'], '6.7');
    wp_enqueue_script('wc-main', get_template_directory_uri() . '/assets/js/main.js', [], '5.3', true);
    wp_localize_script('wc-main', 'wcVars', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('wc_lead_nonce'),
        'siteUrl' => get_site_url(),
    ));
}
add_action('wp_enqueue_scripts', 'wc_enqueue_assets');

function wc_favicon_links() {
    $base = get_template_directory_uri() . '/assets/img/';
    echo '<link rel="icon" href="/favicon-48x48.png" type="image/png" sizes="48x48">' . "\n";
    echo '<link rel="icon" href="/favicon-96x96.png" type="image/png" sizes="96x96">' . "\n";
    echo '<link rel="icon" href="/favicon.ico" type="image/x-icon" sizes="16x16 32x32">' . "\n";
    echo '<link rel="icon" href="' . esc_url($base . 'favicon.svg') . '" type="image/svg+xml">' . "\n";
    echo '<link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180">' . "\n";
    echo '<link rel="manifest" href="' . esc_url($base . 'site.webmanifest') . '">' . "\n";
    echo '<meta name="theme-color" content="#066839">' . "\n";
}
add_action('wp_head', 'wc_favicon_links', 5);
add_action('admin_head', 'wc_favicon_links', 5);

function wc_seo_title($title) {
    if (defined('WPSEO_VERSION')) {
        return $title;
    }

    if (is_singular('page')) {
        $m = get_post_meta(get_the_ID(), 'wc_meta_title', true);
        if ($m) $title['title'] = $m;
    }
    return $title;
}
add_filter('document_title_parts', 'wc_seo_title');

function wc_meta_desc() {
    if (defined('WPSEO_VERSION')) {
        return;
    }

    if (is_singular('page')) {
        $d = get_post_meta(get_the_ID(), 'wc_meta_desc', true);
        if ($d) echo '<meta name="description" content="' . esc_attr($d) . '">' . "\n";
    }
}
add_action('wp_head', 'wc_meta_desc');

function wc_register_leads() {
    register_post_type('wc_lead', array(
        'label'         => 'Leads',
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_icon'     => 'dashicons-email-alt',
        'menu_position' => 5,
        'supports'      => array('title'),
    ));
}
add_action('init', 'wc_register_leads');

// Lead kolommen in lijst overzicht
function wc_lead_columns($c) {
    return array(
        'cb'           => '<input type="checkbox">',
        'title'        => 'Naam',
        'lead_email'   => 'E-mail',
        'lead_tel'     => 'Telefoon',
        'lead_stad'    => 'Stad',
        'lead_systeem' => 'Systeem',
        'lead_score'   => 'Score',
        'lead_status'  => 'Status',
        'date'         => 'Datum'
    );
}
add_filter('manage_wc_lead_posts_columns', 'wc_lead_columns');

function wc_lead_col($col, $id) {
    if ($col === 'lead_email') {
        $e = get_post_meta($id, 'email', true);
        echo '<a href="mailto:' . esc_attr($e) . '">' . esc_html($e) . '</a>';
    }
    if ($col === 'lead_tel') {
        $t = get_post_meta($id, 'telefoon', true);
        echo $t ? '<a href="tel:' . esc_attr($t) . '">' . esc_html($t) . '</a>' : '-';
    }
    if ($col === 'lead_stad') {
        echo '<span style="background:#e8f5ec;color:#066839;padding:2px 8px;border-radius:4px;font-size:12px">' . esc_html(get_post_meta($id, 'stad', true)) . '</span>';
    }
    if ($col === 'lead_systeem') {
        echo esc_html(get_post_meta($id, 'situatie', true) ?: '-');
    }
    if ($col === 'lead_score') {
        $score = (int) get_post_meta($id, 'lead_score', true);
        $priority = get_post_meta($id, 'lead_priority', true) ?: 'normaal';
        $color = $priority === 'hoog' ? '#b45309' : ($priority === 'laag' ? '#64748b' : '#066839');
        echo '<span style="background:#f8fafc;color:' . esc_attr($color) . ';padding:2px 10px;border-radius:100px;font-size:12px;font-weight:800">' . esc_html($score) . '/100</span>';
    }
    if ($col === 'lead_status') {
        $s = get_post_meta($id, 'lead_status', true) ?: 'nieuw';
        echo '<span style="background:#dbeafe;color:#1d4ed8;padding:2px 10px;border-radius:100px;font-size:12px;font-weight:700">' . esc_html(wc_lead_status_label($s)) . '</span>';
    }
}
add_action('manage_wc_lead_posts_custom_column', 'wc_lead_col', 10, 2);

// Lead detail meta box
function wc_lead_detail_box() {
    add_meta_box('wc_lead_data', 'Lead gegevens', 'wc_lead_detail_render', 'wc_lead', 'normal', 'high');
}
add_action('add_meta_boxes', 'wc_lead_detail_box');

function wc_lead_detail_render($post) {
    wp_nonce_field('wc_lead_status_save', 'wc_lead_status_nonce');
    $velden = array(
        'naam'        => 'Naam',
        'email'       => 'E-mail',
        'telefoon'    => 'Telefoon',
        'postcode'    => 'Postcode',
        'stad'        => 'Stad',
        'woningtype'  => 'Woningtype',
        'situatie'    => 'Systeem voorkeur',
        'gasverbruik' => 'Gasverbruik',
        'termijn'     => 'Gewenste termijn',
        'domein'      => 'Domein',
        'landing_page'=> 'Landing page',
        'referrer'    => 'Referrer',
        'utm_source'  => 'UTM source',
        'utm_medium'  => 'UTM medium',
        'utm_campaign'=> 'UTM campaign',
        'utm_term'    => 'UTM term',
        'utm_content' => 'UTM content',
        'gclid'       => 'Google Ads click ID',
        'gbraid'      => 'Google Ads GBRAID',
        'wbraid'      => 'Google Ads WBRAID',
        'lead_score'  => 'Leadscore',
        'lead_priority' => 'Prioriteit',
        'lead_agent_summary' => 'Agent samenvatting',
        'customer_email_status' => 'Klantmail',
        'sms_status'  => 'SMS',
        'lead_agent_log' => 'Agent log',
        'lead_status' => 'Status',
    );
    $status_options = array(
        'nieuw'        => 'Nieuw',
        'gebeld'       => 'Gebeld',
        'gekwalificeerd' => 'Gekwalificeerd',
        'offerte'      => 'Offerte verstuurd',
        'gewonnen'     => 'Gewonnen',
        'verloren'     => 'Verloren',
        'geen_gehoor'  => 'Geen gehoor',
    );
    echo '<table style="width:100%;border-collapse:collapse">';
    foreach ($velden as $k => $l) {
        $v = get_post_meta($post->ID, $k, true);
        echo '<tr style="border-bottom:1px solid #eee">';
        echo '<td style="padding:10px;width:160px;font-weight:700;color:#333;font-size:13px">' . esc_html($l) . '</td>';
        echo '<td style="padding:10px;font-size:14px">';
        if ($k === 'email' && $v) echo '<a href="mailto:' . esc_attr($v) . '">' . esc_html($v) . '</a>';
        elseif ($k === 'telefoon' && $v) echo '<a href="tel:' . esc_attr($v) . '">' . esc_html($v) . '</a>';
        elseif ($k === 'lead_status') {
            $current = $v ?: 'nieuw';
            echo '<select name="lead_status" style="min-width:220px">';
            foreach ($status_options as $status_key => $status_label) {
                echo '<option value="' . esc_attr($status_key) . '"' . selected($current, $status_key, false) . '>' . esc_html($status_label) . '</option>';
            }
            echo '</select>';
        }
        elseif ($k === 'lead_agent_summary' || $k === 'lead_agent_log') {
            echo '<pre style="white-space:pre-wrap;margin:0;background:#f8fafc;border:1px solid #e5e7eb;border-radius:6px;padding:10px;max-height:220px;overflow:auto">' . esc_html($v ?: '-') . '</pre>';
        }
        else echo esc_html($v ?: '-');
        echo '</td></tr>';
    }
    echo '</table>';
    $email = get_post_meta($post->ID, 'email', true);
    $tel   = preg_replace('/\s+/', '', get_post_meta($post->ID, 'telefoon', true));
    echo '<p style="margin-top:16px">';
    if ($email) echo '<a href="mailto:' . esc_attr($email) . '" class="button button-primary">E-mail sturen</a>&nbsp;';
    if ($tel) echo '<a href="https://wa.me/31' . esc_attr(ltrim($tel, '0')) . '" target="_blank" class="button" style="background:#25D366;color:#fff;border-color:#25D366">💬 WhatsApp</a>';
    echo '</p>';
}

function wc_lead_status_save($post_id) {
    if (get_post_type($post_id) !== 'wc_lead') return;
    if (!isset($_POST['wc_lead_status_nonce']) || !wp_verify_nonce($_POST['wc_lead_status_nonce'], 'wc_lead_status_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (!isset($_POST['lead_status'])) return;

    $allowed = array('nieuw','gebeld','gekwalificeerd','offerte','gewonnen','verloren','geen_gehoor');
    $status = sanitize_key($_POST['lead_status']);
    if (!in_array($status, $allowed, true)) {
        $status = 'nieuw';
    }
    update_post_meta($post_id, 'lead_status', $status);
}
add_action('save_post_wc_lead', 'wc_lead_status_save');

function wc_lead_status_label($status) {
    $labels = array(
        'nieuw'        => 'Nieuw',
        'gebeld'       => 'Gebeld',
        'gekwalificeerd' => 'Gekwalificeerd',
        'offerte'      => 'Offerte verstuurd',
        'gewonnen'     => 'Gewonnen',
        'verloren'     => 'Verloren',
        'geen_gehoor'  => 'Geen gehoor',
    );
    return $labels[$status] ?? ucfirst(str_replace('_', ' ', $status));
}

function wc_lead_email_body($lead_id, $data) {
    $labels = array(
        'naam'         => 'Naam',
        'email'        => 'E-mail',
        'telefoon'     => 'Telefoon',
        'postcode'     => 'Postcode',
        'stad'         => 'Stad',
        'woningtype'   => 'Woningtype',
        'situatie'     => 'Systeem voorkeur',
        'gasverbruik'  => 'Gasverbruik',
        'termijn'      => 'Gewenste termijn',
        'domein'       => 'Domein',
        'landing_page' => 'Landing page',
        'referrer'     => 'Referrer',
        'utm_source'   => 'UTM source',
        'utm_medium'   => 'UTM medium',
        'utm_campaign' => 'UTM campaign',
        'utm_term'     => 'UTM term',
        'utm_content'  => 'UTM content',
        'gclid'        => 'Google Ads click ID',
        'gbraid'       => 'Google Ads GBRAID',
        'wbraid'       => 'Google Ads WBRAID',
    );

    $body = "Nieuwe warmtepomp lead\n\n";
    foreach ($labels as $key => $label) {
        $value = isset($data[$key]) ? trim((string) $data[$key]) : '';
        $body .= $label . ': ' . ($value !== '' ? $value : '-') . "\n";
    }
    $body .= "\nStatus: Nieuw\n";
    $body .= "Lead ID: " . $lead_id . "\n";
    $body .= "Beheer: " . admin_url('post.php?post=' . $lead_id . '&action=edit') . "\n";

    return $body;
}

function wc_lead_agent_settings_defaults() {
    return array(
        'admin_email' => 'tonny@vakvriend.nl',
        'customer_email_enabled' => '1',
        'smtp_enabled' => '0',
        'smtp_host' => '',
        'smtp_port' => '587',
        'smtp_encryption' => 'tls',
        'smtp_username' => '',
        'smtp_password' => '',
        'smtp_from_email' => 'info@vakvriend.nl',
        'smtp_from_name' => 'Vakvriend',
        'sms_enabled' => '0',
        'twilio_sid' => '',
        'twilio_token' => '',
        'twilio_messaging_service_sid' => '',
        'twilio_from' => '',
        'followup_reminder_enabled' => '1',
    );
}

function wc_lead_agent_settings() {
    $saved = get_option('wc_lead_agent_settings', array());
    return wp_parse_args(is_array($saved) ? $saved : array(), wc_lead_agent_settings_defaults());
}

function wc_lead_agent_menu() {
    add_submenu_page(
        'edit.php?post_type=wc_lead',
        'Lead dashboard',
        'Dashboard',
        'manage_options',
        'wc-lead-dashboard',
        'wc_lead_dashboard_page'
    );

    add_submenu_page(
        'edit.php?post_type=wc_lead',
        'Lead agent',
        'Lead agent',
        'manage_options',
        'wc-lead-agent',
        'wc_lead_agent_settings_page'
    );
}
add_action('admin_menu', 'wc_lead_agent_menu');

function wc_funnel_events_get() {
    $events = get_option('wc_funnel_events', array());
    return is_array($events) ? $events : array();
}

function wc_funnel_event_store($event) {
    $events = wc_funnel_events_get();
    $events[] = $event;
    if (count($events) > 2500) {
        $events = array_slice($events, -2500);
    }
    update_option('wc_funnel_events', $events, false);
}

function wc_ajax_funnel_event() {
    check_ajax_referer('wc_lead_nonce', 'nonce');

    $payload_raw = wp_unslash($_POST['payload'] ?? '{}');
    $payload = json_decode((string) $payload_raw, true);
    if (!is_array($payload)) {
        $payload = array();
    }

    $safe_payload = array();
    foreach ($payload as $key => $value) {
        if (is_scalar($value)) {
            $safe_payload[sanitize_key($key)] = sanitize_text_field((string) $value);
        }
    }

    $event = array(
        'time' => current_time('mysql'),
        'ts' => time(),
        'event' => sanitize_key($_POST['event_name'] ?? ''),
        'session_id' => sanitize_text_field($_POST['session_id'] ?? ''),
        'hostname' => sanitize_text_field($_POST['page_hostname'] ?? ''),
        'page' => esc_url_raw($_POST['page_location'] ?? ''),
        'referrer' => esc_url_raw($_POST['referrer'] ?? ''),
        'stad' => sanitize_text_field($_POST['stad'] ?? ''),
        'payload' => $safe_payload,
    );

    if (!$event['event'] || !$event['session_id']) {
        wp_send_json_error(array('message' => 'Ongeldig funnel event.'));
    }

    wc_funnel_event_store($event);
    wp_send_json_success(array('stored' => true));
}
add_action('wp_ajax_wc_funnel_event', 'wc_ajax_funnel_event');
add_action('wp_ajax_nopriv_wc_funnel_event', 'wc_ajax_funnel_event');

function wc_lead_dashboard_page() {
    $range_days = max(1, min(90, (int) ($_GET['range'] ?? 14)));
    $since = time() - ($range_days * DAY_IN_SECONDS);
    $events = array_values(array_filter(wc_funnel_events_get(), function ($event) use ($since) {
        return !empty($event['ts']) && (int) $event['ts'] >= $since;
    }));

    $sessions = array();
    $event_counts = array();
    $step_sessions = array(1 => array(), 2 => array(), 3 => array(), 4 => array());
    $domain_counts = array();

    foreach ($events as $event) {
        $session_id = $event['session_id'] ?? '';
        $event_name = $event['event'] ?? '';
        if (!$session_id || !$event_name) {
            continue;
        }

        if (!isset($sessions[$session_id])) {
            $sessions[$session_id] = array(
                'first' => $event['time'] ?? '',
                'last' => $event['time'] ?? '',
                'hostname' => $event['hostname'] ?? '',
                'stad' => $event['stad'] ?? '',
                'max_step' => 0,
                'submitted' => false,
                'success' => false,
                'error' => false,
            );
        }

        $sessions[$session_id]['last'] = $event['time'] ?? $sessions[$session_id]['last'];
        $event_counts[$event_name] = ($event_counts[$event_name] ?? 0) + 1;
        $host = $event['hostname'] ?? '';
        if ($host) {
            $domain_counts[$host] = ($domain_counts[$host] ?? 0) + 1;
        }

        $payload = isset($event['payload']) && is_array($event['payload']) ? $event['payload'] : array();
        if ($event_name === 'lead_form_step') {
            $step = (int) ($payload['step_number'] ?? 0);
            if ($step >= 1 && $step <= 4) {
                $sessions[$session_id]['max_step'] = max($sessions[$session_id]['max_step'], $step);
                $step_sessions[$step][$session_id] = true;
            }
        }
        if ($event_name === 'lead_form_choice') {
            $sessions[$session_id]['max_step'] = max($sessions[$session_id]['max_step'], 1);
            $step_sessions[1][$session_id] = true;
        }
        if ($event_name === 'lead_form_submit_attempt') {
            $sessions[$session_id]['submitted'] = true;
        }
        if ($event_name === 'lead_form_success') {
            $sessions[$session_id]['success'] = true;
        }
        if ($event_name === 'lead_form_error') {
            $sessions[$session_id]['error'] = true;
        }
    }

    $lead_query = new WP_Query(array(
        'post_type' => 'wc_lead',
        'post_status' => 'private',
        'posts_per_page' => 20,
        'date_query' => array(
            array('after' => $range_days . ' days ago'),
        ),
    ));

    $total_sessions = count($sessions);
    $submitted = count(array_filter($sessions, function ($s) { return !empty($s['submitted']); }));
    $successes = count(array_filter($sessions, function ($s) { return !empty($s['success']); }));
    $errors = count(array_filter($sessions, function ($s) { return !empty($s['error']); }));
    $conversion = $total_sessions ? round(($successes / $total_sessions) * 100, 1) : 0;

    arsort($domain_counts);
    $recent_events = array_slice(array_reverse($events), 0, 40);
    ?>
    <div class="wrap">
        <h1>Lead dashboard</h1>
        <p>Inzicht in leads, formulierstappen en afhakers. Data wordt lokaal in WordPress opgeslagen en gebruikt een anonieme sessie-id.</p>

        <form method="get" style="margin:16px 0 22px">
            <input type="hidden" name="post_type" value="wc_lead">
            <input type="hidden" name="page" value="wc-lead-dashboard">
            <label>Periode:
                <select name="range">
                    <?php foreach (array(1, 7, 14, 30, 90) as $days): ?>
                        <option value="<?php echo esc_attr($days); ?>" <?php selected($range_days, $days); ?>><?php echo esc_html($days); ?> dagen</option>
                    <?php endforeach; ?>
                </select>
            </label>
            <?php submit_button('Bekijken', 'secondary', '', false); ?>
        </form>

        <div style="display:grid;grid-template-columns:repeat(5,minmax(140px,1fr));gap:14px;margin:18px 0 24px">
            <?php
            $cards = array(
                'Sessies' => $total_sessions,
                'Verzendpogingen' => $submitted,
                'Leads' => $successes,
                'Fouten' => $errors,
                'Conversie' => $conversion . '%',
            );
            foreach ($cards as $label => $value): ?>
                <div style="background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:18px">
                    <div style="font-size:13px;color:#646970;font-weight:700"><?php echo esc_html($label); ?></div>
                    <div style="font-size:30px;font-weight:800;margin-top:8px;color:#1d2327"><?php echo esc_html((string) $value); ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <h2>Formulier funnel</h2>
        <table class="widefat striped" style="max-width:900px;margin-bottom:24px">
            <thead><tr><th>Stap</th><th>Sessies</th><th>Vanaf vorige stap</th><th>Indicatie</th></tr></thead>
            <tbody>
            <?php
            $rows = array(
                1 => 'Stap 1: woningtype gekozen / formulier gestart',
                2 => 'Stap 2: systeemvoorkeur',
                3 => 'Stap 3: gasverbruik',
                4 => 'Stap 4: contactgegevens',
                5 => 'Verzendpoging',
                6 => 'Succesvolle lead',
            );
            $previous = 0;
            foreach ($rows as $step => $label) {
                if ($step <= 4) {
                    $count = count($step_sessions[$step]);
                } elseif ($step === 5) {
                    $count = $submitted;
                } else {
                    $count = $successes;
                }
                $rate = $previous > 0 ? round(($count / $previous) * 100, 1) . '%' : '-';
                $hint = $previous > 0 && $count < $previous ? ($previous - $count) . ' afhakers' : 'OK';
                echo '<tr><td>' . esc_html($label) . '</td><td><strong>' . esc_html((string) $count) . '</strong></td><td>' . esc_html($rate) . '</td><td>' . esc_html($hint) . '</td></tr>';
                $previous = $count;
            }
            ?>
            </tbody>
        </table>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:22px;align-items:start">
            <div>
                <h2>Recente leads</h2>
                <table class="widefat striped">
                    <thead><tr><th>Naam</th><th>Stad</th><th>Systeem</th><th>Score</th><th>Status</th><th>Datum</th></tr></thead>
                    <tbody>
                    <?php if ($lead_query->have_posts()): while ($lead_query->have_posts()): $lead_query->the_post(); $id = get_the_ID(); ?>
                        <tr>
                            <td><a href="<?php echo esc_url(admin_url('post.php?post=' . $id . '&action=edit')); ?>"><?php echo esc_html(get_post_meta($id, 'naam', true) ?: get_the_title()); ?></a></td>
                            <td><?php echo esc_html(get_post_meta($id, 'stad', true) ?: '-'); ?></td>
                            <td><?php echo esc_html(get_post_meta($id, 'situatie', true) ?: '-'); ?></td>
                            <td><?php echo esc_html((string) ((int) get_post_meta($id, 'lead_score', true))); ?>/100</td>
                            <td><?php echo esc_html(wc_lead_status_label(get_post_meta($id, 'lead_status', true) ?: 'nieuw')); ?></td>
                            <td><?php echo esc_html(get_the_date('d-m H:i')); ?></td>
                        </tr>
                    <?php endwhile; wp_reset_postdata(); else: ?>
                        <tr><td colspan="6">Nog geen leads in deze periode.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div>
                <h2>Domeinen</h2>
                <table class="widefat striped">
                    <thead><tr><th>Domein</th><th>Events</th></tr></thead>
                    <tbody>
                    <?php if ($domain_counts): foreach (array_slice($domain_counts, 0, 12, true) as $host => $count): ?>
                        <tr><td><?php echo esc_html($host); ?></td><td><?php echo esc_html((string) $count); ?></td></tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="2">Nog geen funnel-data.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <h2 style="margin-top:28px">Recente funnel-events</h2>
        <table class="widefat striped">
            <thead><tr><th>Tijd</th><th>Event</th><th>Stap</th><th>Stad</th><th>Domein</th><th>Keuze/Fout</th><th>Sessie</th></tr></thead>
            <tbody>
            <?php if ($recent_events): foreach ($recent_events as $event):
                $payload = isset($event['payload']) && is_array($event['payload']) ? $event['payload'] : array();
                $detail = $payload['field_value'] ?? $payload['error_message'] ?? $payload['systeem'] ?? '';
                ?>
                <tr>
                    <td><?php echo esc_html($event['time'] ?? '-'); ?></td>
                    <td><?php echo esc_html($event['event'] ?? '-'); ?></td>
                    <td><?php echo esc_html($payload['step_number'] ?? '-'); ?></td>
                    <td><?php echo esc_html($event['stad'] ?? '-'); ?></td>
                    <td><?php echo esc_html($event['hostname'] ?? '-'); ?></td>
                    <td><?php echo esc_html($detail ?: '-'); ?></td>
                    <td><code><?php echo esc_html(substr((string) ($event['session_id'] ?? ''), 0, 18)); ?></code></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="7">Nog geen events. Nieuwe bezoekers worden vanaf nu gemeten.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}

function wc_lead_agent_register_settings() {
    register_setting('wc_lead_agent', 'wc_lead_agent_settings', 'wc_lead_agent_sanitize_settings');
}
add_action('admin_init', 'wc_lead_agent_register_settings');

function wc_lead_agent_sanitize_settings($input) {
    $defaults = wc_lead_agent_settings_defaults();
    $input = is_array($input) ? $input : array();

    return array(
        'admin_email' => sanitize_email($input['admin_email'] ?? $defaults['admin_email']),
        'customer_email_enabled' => !empty($input['customer_email_enabled']) ? '1' : '0',
        'smtp_enabled' => !empty($input['smtp_enabled']) ? '1' : '0',
        'smtp_host' => sanitize_text_field($input['smtp_host'] ?? ''),
        'smtp_port' => (string) max(1, min(65535, (int) ($input['smtp_port'] ?? 587))),
        'smtp_encryption' => in_array(($input['smtp_encryption'] ?? 'tls'), array('none', 'ssl', 'tls'), true) ? $input['smtp_encryption'] : 'tls',
        'smtp_username' => sanitize_text_field($input['smtp_username'] ?? ''),
        'smtp_password' => sanitize_text_field($input['smtp_password'] ?? ''),
        'smtp_from_email' => sanitize_email($input['smtp_from_email'] ?? $defaults['smtp_from_email']),
        'smtp_from_name' => sanitize_text_field($input['smtp_from_name'] ?? $defaults['smtp_from_name']),
        'sms_enabled' => !empty($input['sms_enabled']) ? '1' : '0',
        'twilio_sid' => sanitize_text_field($input['twilio_sid'] ?? ''),
        'twilio_token' => sanitize_text_field($input['twilio_token'] ?? ''),
        'twilio_messaging_service_sid' => sanitize_text_field($input['twilio_messaging_service_sid'] ?? ''),
        'twilio_from' => sanitize_text_field($input['twilio_from'] ?? ''),
        'followup_reminder_enabled' => !empty($input['followup_reminder_enabled']) ? '1' : '0',
    );
}

function wc_lead_agent_settings_page() {
    $settings = wc_lead_agent_settings();
    ?>
    <div class="wrap">
        <h1>Lead agent</h1>
        <p>Deze agent stuurt bevestigingen, scoort leads en kan SMS versturen via Twilio zodra de gegevens ingevuld zijn.</p>
        <?php if (!empty($_GET['wc_test_mail'])): ?>
            <?php if ($_GET['wc_test_mail'] === 'sent'): ?>
                <div class="notice notice-success"><p>Testmail aangeboden aan WordPress. Controleer de inbox en spammap.</p></div>
            <?php else: ?>
                <div class="notice notice-error"><p>Testmail kon niet worden verstuurd. Controleer SMTP host, poort, gebruikersnaam en wachtwoord.</p></div>
            <?php endif; ?>
        <?php endif; ?>
        <form method="post" action="options.php">
            <?php settings_fields('wc_lead_agent'); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="wc-agent-admin-email">Interne leadmail</label></th>
                    <td><input id="wc-agent-admin-email" type="email" class="regular-text" name="wc_lead_agent_settings[admin_email]" value="<?php echo esc_attr($settings['admin_email']); ?>"></td>
                </tr>
                <tr>
                    <th scope="row">Klantmail</th>
                    <td><label><input type="checkbox" name="wc_lead_agent_settings[customer_email_enabled]" value="1" <?php checked($settings['customer_email_enabled'], '1'); ?>> Stuur direct een bevestigingsmail naar de klant</label></td>
                </tr>
                <tr>
                    <th scope="row">Interne reminder</th>
                    <td><label><input type="checkbox" name="wc_lead_agent_settings[followup_reminder_enabled]" value="1" <?php checked($settings['followup_reminder_enabled'], '1'); ?>> Mail Tonny na 2 uur als de lead nog op Nieuw staat</label></td>
                </tr>
                <tr>
                    <th scope="row">SMTP mail</th>
                    <td><label><input type="checkbox" name="wc_lead_agent_settings[smtp_enabled]" value="1" <?php checked($settings['smtp_enabled'], '1'); ?>> Verstuur WordPress mail via SMTP</label></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wc-agent-smtp-host">SMTP host</label></th>
                    <td><input id="wc-agent-smtp-host" type="text" class="regular-text" name="wc_lead_agent_settings[smtp_host]" value="<?php echo esc_attr($settings['smtp_host']); ?>" placeholder="smtp.provider.nl"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wc-agent-smtp-port">SMTP poort</label></th>
                    <td><input id="wc-agent-smtp-port" type="number" class="small-text" name="wc_lead_agent_settings[smtp_port]" value="<?php echo esc_attr($settings['smtp_port']); ?>"> <span class="description">Meestal 587 met TLS, of 465 met SSL.</span></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wc-agent-smtp-encryption">Versleuteling</label></th>
                    <td>
                        <select id="wc-agent-smtp-encryption" name="wc_lead_agent_settings[smtp_encryption]">
                            <option value="tls" <?php selected($settings['smtp_encryption'], 'tls'); ?>>TLS</option>
                            <option value="ssl" <?php selected($settings['smtp_encryption'], 'ssl'); ?>>SSL</option>
                            <option value="none" <?php selected($settings['smtp_encryption'], 'none'); ?>>Geen</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wc-agent-smtp-username">SMTP gebruikersnaam</label></th>
                    <td><input id="wc-agent-smtp-username" type="text" class="regular-text" name="wc_lead_agent_settings[smtp_username]" value="<?php echo esc_attr($settings['smtp_username']); ?>" autocomplete="off"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wc-agent-smtp-password">SMTP wachtwoord</label></th>
                    <td><input id="wc-agent-smtp-password" type="password" class="regular-text" name="wc_lead_agent_settings[smtp_password]" value="<?php echo esc_attr($settings['smtp_password']); ?>" autocomplete="new-password"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wc-agent-smtp-from-email">Afzender e-mail</label></th>
                    <td><input id="wc-agent-smtp-from-email" type="email" class="regular-text" name="wc_lead_agent_settings[smtp_from_email]" value="<?php echo esc_attr($settings['smtp_from_email']); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wc-agent-smtp-from-name">Afzender naam</label></th>
                    <td><input id="wc-agent-smtp-from-name" type="text" class="regular-text" name="wc_lead_agent_settings[smtp_from_name]" value="<?php echo esc_attr($settings['smtp_from_name']); ?>"></td>
                </tr>
                <tr>
                    <th scope="row">SMS via Twilio</th>
                    <td><label><input type="checkbox" name="wc_lead_agent_settings[sms_enabled]" value="1" <?php checked($settings['sms_enabled'], '1'); ?>> Stuur direct een korte SMS naar de klant</label></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wc-agent-twilio-sid">Twilio Account SID</label></th>
                    <td><input id="wc-agent-twilio-sid" type="text" class="regular-text" name="wc_lead_agent_settings[twilio_sid]" value="<?php echo esc_attr($settings['twilio_sid']); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wc-agent-twilio-token">Twilio Auth Token</label></th>
                    <td><input id="wc-agent-twilio-token" type="password" class="regular-text" name="wc_lead_agent_settings[twilio_token]" value="<?php echo esc_attr($settings['twilio_token']); ?>" autocomplete="new-password"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wc-agent-twilio-messaging-service">Twilio Messaging Service SID</label></th>
                    <td>
                        <input id="wc-agent-twilio-messaging-service" type="text" class="regular-text" name="wc_lead_agent_settings[twilio_messaging_service_sid]" value="<?php echo esc_attr($settings['twilio_messaging_service_sid']); ?>" placeholder="MG...">
                        <p class="description">Gebruik dit veld als de SMS via een Twilio Messaging Service loopt. Dan is een los afzendernummer niet nodig.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wc-agent-twilio-from">Twilio afzender</label></th>
                    <td>
                        <input id="wc-agent-twilio-from" type="text" class="regular-text" name="wc_lead_agent_settings[twilio_from]" value="<?php echo esc_attr($settings['twilio_from']); ?>" placeholder="VAKVRIEND of +31612345678">
                        <p class="description">Alleen nodig als er geen Messaging Service SID is ingevuld.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Instellingen opslaan'); ?>
        </form>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="margin-top:18px">
            <?php wp_nonce_field('wc_lead_agent_test_mail'); ?>
            <input type="hidden" name="action" value="wc_lead_agent_test_mail">
            <?php submit_button('Testmail sturen naar interne leadmail', 'secondary', 'submit', false); ?>
        </form>
    </div>
    <?php
}

function wc_lead_agent_configure_phpmailer($phpmailer) {
    $settings = wc_lead_agent_settings();
    if ($settings['smtp_enabled'] !== '1' || !$settings['smtp_host']) {
        return;
    }

    $phpmailer->isSMTP();
    $phpmailer->Host = $settings['smtp_host'];
    $phpmailer->Port = (int) $settings['smtp_port'];
    $phpmailer->SMTPAuth = !empty($settings['smtp_username']);
    if ($phpmailer->SMTPAuth) {
        $phpmailer->Username = $settings['smtp_username'];
        $phpmailer->Password = $settings['smtp_password'];
    }
    if ($settings['smtp_encryption'] !== 'none') {
        $phpmailer->SMTPSecure = $settings['smtp_encryption'];
    }
    if (!empty($settings['smtp_from_email'])) {
        $phpmailer->setFrom($settings['smtp_from_email'], $settings['smtp_from_name'] ?: 'Vakvriend', false);
    }
}
add_action('phpmailer_init', 'wc_lead_agent_configure_phpmailer');

function wc_lead_agent_test_mail_action() {
    if (!current_user_can('manage_options')) {
        wp_die('Geen toegang.');
    }
    check_admin_referer('wc_lead_agent_test_mail');

    $settings = wc_lead_agent_settings();
    $to = $settings['admin_email'] ?: 'tonny@vakvriend.nl';
    $sent = wp_mail(
        $to,
        'Testmail Vakvriend lead agent',
        "Dit is een testmail van de Vakvriend lead agent.\n\nAls deze binnenkomt, werkt SMTP/WordPress mail.",
        array('From: ' . ($settings['smtp_from_name'] ?: 'Vakvriend') . ' <' . ($settings['smtp_from_email'] ?: 'info@vakvriend.nl') . '>')
    );

    wp_safe_redirect(admin_url('edit.php?post_type=wc_lead&page=wc-lead-agent&wc_test_mail=' . ($sent ? 'sent' : 'failed')));
    exit;
}
add_action('admin_post_wc_lead_agent_test_mail', 'wc_lead_agent_test_mail_action');

function wc_lead_agent_log($lead_id, $message) {
    $existing = (string) get_post_meta($lead_id, 'lead_agent_log', true);
    $line = '[' . current_time('mysql') . '] ' . $message;
    update_post_meta($lead_id, 'lead_agent_log', trim($existing . "\n" . $line));
}

function wc_lead_normalize_phone($phone) {
    $phone = preg_replace('/[^0-9+]/', '', (string) $phone);
    if (!$phone) {
        return '';
    }
    if (strpos($phone, '+') === 0) {
        return $phone;
    }
    if (strpos($phone, '00') === 0) {
        return '+' . substr($phone, 2);
    }
    if (strpos($phone, '06') === 0) {
        return '+31' . substr($phone, 1);
    }
    if (strpos($phone, '6') === 0 && strlen($phone) === 9) {
        return '+31' . $phone;
    }
    if (strpos($phone, '0') === 0) {
        return '+31' . substr($phone, 1);
    }
    return $phone;
}

function wc_lead_parse_gasverbruik($value) {
    $value = (string) $value;
    if ($value === '') {
        return 0;
    }

    if (!preg_match('/[0-9][0-9.,\s]*/', $value, $match)) {
        return 0;
    }

    $digits = preg_replace('/[^0-9]/', '', $match[0]);
    return $digits ? (int) $digits : 0;
}

function wc_lead_agent_score($data) {
    $score = 15;
    $notes = array();
    $telefoon = wc_lead_normalize_phone($data['telefoon'] ?? '');
    $postcode = strtoupper(preg_replace('/\s+/', '', (string) ($data['postcode'] ?? '')));
    $situatie = strtolower((string) ($data['situatie'] ?? ''));
    $woningtype = strtolower((string) ($data['woningtype'] ?? ''));
    $termijn = strtolower((string) ($data['termijn'] ?? ''));
    $gas = wc_lead_parse_gasverbruik($data['gasverbruik'] ?? '');

    if ($telefoon) {
        $score += 22;
        $notes[] = 'telefonisch bereikbaar';
        if (preg_match('/^\+316[0-9]{8}$/', $telefoon)) {
            $score += 3;
            $notes[] = 'mobiel nummer';
        }
    } else {
        $notes[] = 'geen telefoonnummer';
    }

    if ($postcode) {
        $score += 10;
        $notes[] = 'postcode bekend';
    }

    if ($gas >= 2200) {
        $score += 12;
        $notes[] = 'hoog gasverbruik';
    } elseif ($gas >= 1600) {
        $score += 8;
        $notes[] = 'gemiddeld gasverbruik';
    } elseif ($gas >= 1000) {
        $score += 6;
        $notes[] = 'lager gasverbruik';
    } elseif ($gas > 0) {
        $score += 2;
        $notes[] = 'laag gasverbruik';
    }

    if (strpos($situatie, 'bodem') !== false) {
        $score += 15;
        $notes[] = 'interesse in bodemwarmtepomp';
    } elseif (strpos($situatie, 'lucht/water') !== false || strpos($situatie, 'qvantum') !== false || strpos($situatie, 'nibe') !== false) {
        $score += 11;
        $notes[] = 'interesse in all-electric oplossing';
    } elseif (strpos($situatie, 'hybride') !== false || strpos($situatie, 'intergas') !== false) {
        $score += 7;
        $notes[] = 'hybride interesse';
    } elseif (strpos($situatie, 'ventilatie') !== false) {
        $score += 7;
        $notes[] = 'ventilatiewarmtepomp interesse';
    } elseif (strpos($situatie, 'boiler') !== false) {
        $score += 5;
        $notes[] = 'warmtepompboiler interesse';
    } elseif ($situatie && strpos($situatie, 'weet') !== false) {
        $score += 4;
        $notes[] = 'wil advies over systeemkeuze';
    }

    if (!empty($data['gclid']) || !empty($data['gbraid']) || !empty($data['wbraid'])) {
        $score += 10;
        $notes[] = 'Google Ads klik';
    }

    if (strpos($termijn, 'zo snel') !== false) {
        $score += 10;
        $notes[] = 'hoge urgentie';
    } elseif (strpos($termijn, '3 maanden') !== false) {
        $score += 8;
        $notes[] = 'korte termijn';
    } elseif (strpos($termijn, 'dit jaar') !== false) {
        $score += 5;
        $notes[] = 'planning dit jaar';
    } elseif (strpos($termijn, 'ori') !== false) {
        $score += 2;
        $notes[] = 'orienterende fase';
    }

    if (strpos($woningtype, 'vrijstaand') !== false) {
        $score += 10;
        $notes[] = 'vrijstaande woning';
    } elseif (strpos($woningtype, 'hoek') !== false) {
        $score += 8;
        $notes[] = 'hoekwoning';
    } elseif (strpos($woningtype, 'tussen') !== false) {
        $score += 6;
        $notes[] = 'tussenwoning';
    } elseif (strpos($woningtype, 'appartement') !== false) {
        $score += 3;
        $notes[] = 'appartement';
    } elseif ($woningtype) {
        $score += 5;
        $notes[] = 'woningtype bekend';
    }

    if (!empty($data['stad']) || !empty($data['domein'])) {
        $score += 2;
        $notes[] = 'regio bekend';
    }

    $score = max(0, min(100, $score));
    $priority = $score >= 80 ? 'hoog' : ($score < 60 ? 'laag' : 'normaal');

    return array(
        'score' => $score,
        'priority' => $priority,
        'notes' => $notes,
    );
}

function wc_lead_agent_summary($data, $score_data) {
    $lines = array();
    $lines[] = 'Prioriteit: ' . ucfirst($score_data['priority']) . ' (' . $score_data['score'] . '/100)';
    $lines[] = 'Klant: ' . ($data['naam'] ?: '-') . ', ' . ($data['stad'] ?: $data['domein'] ?: '-');
    $lines[] = 'Contact: ' . ($data['telefoon'] ?: '-') . ' / ' . ($data['email'] ?: '-');
    $lines[] = 'Woning: ' . ($data['woningtype'] ?: '-') . ', gasverbruik: ' . ($data['gasverbruik'] ?: '-');
    $lines[] = 'Voorkeur: ' . ($data['situatie'] ?: '-');
    $lines[] = 'Gewenste termijn: ' . ($data['termijn'] ?: '-');
    $lines[] = 'Kanaal: ' . ($data['utm_source'] ?: '-') . ' / ' . ($data['utm_campaign'] ?: '-');
    if ($score_data['notes']) {
        $lines[] = 'Waarom deze score: ' . implode(', ', $score_data['notes']) . '.';
    }
    $lines[] = 'Advies opvolging: bel snel als telefoon aanwezig is. Anders mailen en vragen naar telefoonnummer, bouwjaar, isolatie en afgiftesysteem.';

    return implode("\n", $lines);
}

function wc_lead_customer_email_body($data) {
    $naam = trim((string) ($data['naam'] ?? '')) ?: 'daar';
    $stad = trim((string) ($data['stad'] ?? '')) ?: 'uw regio';
    $systeem = trim((string) ($data['situatie'] ?? '')) ?: 'Nog te bepalen';
    $woningtype = trim((string) ($data['woningtype'] ?? '')) ?: 'Nog niet ingevuld';
    $gasverbruik = trim((string) ($data['gasverbruik'] ?? '')) ?: 'Nog niet ingevuld';
    $termijn = trim((string) ($data['termijn'] ?? '')) ?: 'Nog niet ingevuld';
    $postcode = trim((string) ($data['postcode'] ?? '')) ?: 'Nog niet ingevuld';
    $image_url = wc_lead_customer_email_image_url($data);

    $rows = array(
        'Woningtype' => $woningtype,
        'Postcode' => $postcode,
        'Voorkeur' => $systeem,
        'Gasverbruik' => $gasverbruik,
        'Termijn' => $termijn,
    );

    $details = '';
    foreach ($rows as $label => $value) {
        $details .= '<tr><td style="padding:10px 0;color:#647064;font-size:14px">' . esc_html($label) . '</td><td style="padding:10px 0;text-align:right;color:#102017;font-weight:700;font-size:14px">' . esc_html($value) . '</td></tr>';
    }

    return '<!doctype html><html><body style="margin:0;background:#f4f7f4;font-family:Arial,Helvetica,sans-serif;color:#102017">'
        . '<div style="display:none;max-height:0;overflow:hidden">We hebben uw woningcheck ontvangen. Vakvriend kijkt met u mee.</div>'
        . '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f7f4;padding:28px 12px"><tr><td align="center">'
        . '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #dfe9e3">'
        . '<tr><td style="padding:0;background:#dfe9e3"><img src="' . esc_url($image_url) . '" width="640" height="210" alt="Warmtepomp advies van Vakvriend" style="display:block;width:100%;max-width:640px;height:210px;object-fit:cover;border:0"></td></tr>'
        . '<tr><td style="background:#066839;padding:28px 30px;color:#ffffff">'
        . '<div style="font-size:14px;font-weight:800;letter-spacing:.02em;opacity:.9">Vakvriend woningcheck</div>'
        . '<h1 style="margin:12px 0 8px;font-size:30px;line-height:1.12">Gelukt. We gaan voor u aan de slag.</h1>'
        . '<p style="margin:0;color:#dcefe4;font-size:16px;line-height:1.6">We bekijken welke warmtepomp past bij uw woning in ' . esc_html($stad) . '. Gewoon rustig, praktisch en zonder verkooppraatjes.</p>'
        . '</td></tr>'
        . '<tr><td style="padding:30px">'
        . '<p style="margin:0 0 18px;font-size:17px;line-height:1.7">Hallo ' . esc_html($naam) . ',</p>'
        . '<p style="margin:0 0 20px;font-size:16px;line-height:1.75;color:#344238">Bedankt voor uw aanvraag. Een vakman van Vakvriend kijkt naar uw woningcheck en neemt zo snel mogelijk contact met u op. Vaak kunnen we al aan de telefoon goed inschatten welke richting logisch is.</p>'
        . '<div style="background:#f7faf7;border:1px solid #e1ebe4;border-radius:14px;padding:18px 20px;margin:24px 0">'
        . '<h2 style="margin:0 0 8px;font-size:18px;color:#102017">Dit nemen we alvast mee</h2>'
        . '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse">' . $details . '</table>'
        . '</div>'
        . '<h2 style="margin:26px 0 10px;font-size:20px;color:#102017">Wat gebeurt er nu?</h2>'
        . '<p style="margin:0 0 10px;font-size:16px;line-height:1.7;color:#344238"><strong>1. We controleren de aanvraag.</strong><br>We kijken naar woningtype, verbruik, systeemvoorkeur en praktische plaatsing.</p>'
        . '<p style="margin:0 0 10px;font-size:16px;line-height:1.7;color:#344238"><strong>2. We nemen contact op.</strong><br>Als er nog iets mist, vragen we dat kort na. Denk aan bouwjaar, isolatie of foto’s van de technische ruimte.</p>'
        . '<p style="margin:0 0 22px;font-size:16px;line-height:1.7;color:#344238"><strong>3. U krijgt helder advies.</strong><br>Niet alleen een prijs, maar vooral welke oplossing verstandig is: hybride, lucht/water, Qvantum, Nibe, warmtepompboiler of toch iets anders.</p>'
        . '<div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:14px;padding:16px 18px;margin:24px 0;color:#7c2d12;font-size:15px;line-height:1.65"><strong>Handig om klaar te leggen:</strong> bouwjaar, huidige ketel, type radiatoren of vloerverwarming en eventueel foto’s van de technische ruimte.</div>'
        . '<p style="margin:26px 0 0;font-size:16px;line-height:1.7;color:#344238">Wilt u ons alvast iets sturen of direct iemand spreken? Bel ons op <a href="tel:0752340001" style="color:#066839;font-weight:800;text-decoration:none">075 234 0001</a> of mail naar <a href="mailto:info@vakvriend.nl" style="color:#066839;font-weight:800;text-decoration:none">info@vakvriend.nl</a>.</p>'
        . '<p style="margin:28px 0 0;font-size:16px;line-height:1.7;color:#344238">Groet,<br><strong>Team Vakvriend</strong></p>'
        . '</td></tr>'
        . '<tr><td style="background:#102017;color:#b8c5bb;padding:20px 30px;font-size:13px;line-height:1.6">Vakvriend Installatiebedrijf · Handelsweg 12K, Wormerveer<br>Deze mail ontvangt u omdat u een vrijblijvende woningcheck heeft aangevraagd.</td></tr>'
        . '</table>'
        . '</td></tr></table>'
        . '</body></html>';
}

function wc_lead_customer_email_image_url($data) {
    return 'https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/698669fbf18164e5304e45ef_DSC00884-2.webp';
}

function wc_lead_customer_sms_body($data) {
    $stad = $data['stad'] ?: 'uw regio';
    return 'Vakvriend: bedankt voor uw warmtepomp woningcheck voor ' . $stad . '. We bekijken uw aanvraag en nemen snel contact op. Vragen? Bel 075 234 0001.';
}

function wc_lead_send_sms($lead_id, $phone, $message) {
    $settings = wc_lead_agent_settings();
    if ($settings['sms_enabled'] !== '1') {
        update_post_meta($lead_id, 'sms_status', 'overgeslagen: SMS staat uit');
        wc_lead_agent_log($lead_id, 'SMS overgeslagen: SMS staat uit.');
        return false;
    }

    $to = wc_lead_normalize_phone($phone);
    if (!$to) {
        update_post_meta($lead_id, 'sms_status', 'overgeslagen: geen telefoonnummer');
        wc_lead_agent_log($lead_id, 'SMS overgeslagen: geen telefoonnummer.');
        return false;
    }

    $sid = $settings['twilio_sid'];
    $token = $settings['twilio_token'];
    $messaging_service_sid = $settings['twilio_messaging_service_sid'] ?? '';
    $from = $settings['twilio_from'];
    if (!$sid || !$token || (!$messaging_service_sid && !$from)) {
        update_post_meta($lead_id, 'sms_status', 'overgeslagen: Twilio niet volledig ingesteld');
        wc_lead_agent_log($lead_id, 'SMS overgeslagen: Twilio niet volledig ingesteld.');
        return false;
    }

    $body = array(
        'To' => $to,
        'Body' => $message,
    );

    if ($messaging_service_sid) {
        $body['MessagingServiceSid'] = $messaging_service_sid;
    } else {
        $body['From'] = $from;
    }

    $response = wp_remote_post('https://api.twilio.com/2010-04-01/Accounts/' . rawurlencode($sid) . '/Messages.json', array(
        'timeout' => 15,
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode($sid . ':' . $token),
        ),
        'body' => $body,
    ));

    if (is_wp_error($response)) {
        update_post_meta($lead_id, 'sms_status', 'mislukt: ' . $response->get_error_message());
        wc_lead_agent_log($lead_id, 'SMS mislukt: ' . $response->get_error_message());
        return false;
    }

    $code = (int) wp_remote_retrieve_response_code($response);
    if ($code < 200 || $code >= 300) {
        update_post_meta($lead_id, 'sms_status', 'mislukt: HTTP ' . $code);
        wc_lead_agent_log($lead_id, 'SMS mislukt: Twilio HTTP ' . $code . '.');
        return false;
    }

    update_post_meta($lead_id, 'sms_status', 'verzonden naar ' . $to);
    wc_lead_agent_log($lead_id, 'SMS verzonden naar ' . $to . '.');
    return true;
}

function wc_lead_schedule_followup_reminder($lead_id) {
    $settings = wc_lead_agent_settings();
    if ($settings['followup_reminder_enabled'] !== '1') {
        return;
    }

    if (!wp_next_scheduled('wc_lead_followup_reminder', array($lead_id))) {
        wp_schedule_single_event(time() + 2 * HOUR_IN_SECONDS, 'wc_lead_followup_reminder', array($lead_id));
    }
}

function wc_lead_followup_reminder($lead_id) {
    $status = get_post_meta($lead_id, 'lead_status', true) ?: 'nieuw';
    if ($status !== 'nieuw') {
        return;
    }

    $settings = wc_lead_agent_settings();
    $email = $settings['admin_email'] ?: 'tonny@vakvriend.nl';
    $naam = get_post_meta($lead_id, 'naam', true) ?: 'onbekende lead';
    $summary = get_post_meta($lead_id, 'lead_agent_summary', true);

    wp_mail(
        $email,
        'Reminder: lead nog niet opgevolgd - ' . $naam,
        "Deze lead staat na 2 uur nog op Nieuw.\n\n" . $summary . "\n\nBeheer: " . admin_url('post.php?post=' . $lead_id . '&action=edit'),
        array('From: Vakvriend <info@vakvriend.nl>')
    );
    wc_lead_agent_log($lead_id, 'Interne 2-uurs reminder verstuurd.');
}
add_action('wc_lead_followup_reminder', 'wc_lead_followup_reminder');

// AJAX lead handler
function wc_ajax_lead() {
    check_ajax_referer('wc_lead_nonce', 'nonce');
    $naam  = sanitize_text_field($_POST['naam']  ?? '');
    $email = sanitize_email($_POST['email']      ?? '');
    if (!$naam || !$email) { wp_send_json_error(array('message' => 'Naam en e-mail zijn verplicht.')); }
    if (!is_email($email)) { wp_send_json_error(array('message' => 'Vul een geldig e-mailadres in.')); }
    $stad = sanitize_text_field($_POST['stad'] ?? $_POST['domein'] ?? '');
    $id = wp_insert_post(array(
        'post_title'  => date('d-m-Y H:i') . ' - ' . $naam . ' (' . $stad . ')',
        'post_type'   => 'wc_lead',
        'post_status' => 'private',
    ));
    if ($id && !is_wp_error($id)) {
        $velden = array('naam','email','telefoon','postcode','woningtype','situatie','gasverbruik','termijn','domein','stad','session_id','utm_source','utm_medium','utm_campaign','utm_term','utm_content','gclid','gbraid','wbraid','landing_page','referrer');
        $lead_data = array();
        foreach ($velden as $k) {
            $value = sanitize_text_field($_POST[$k] ?? '');
            update_post_meta($id, $k, $value);
            $lead_data[$k] = $value;
        }
        update_post_meta($id, 'lead_status', 'nieuw');
        $lead_data['naam'] = $naam;
        $lead_data['email'] = $email;
        $lead_data['stad'] = $stad;
        $score_data = wc_lead_agent_score($lead_data);
        $summary = wc_lead_agent_summary($lead_data, $score_data);
        update_post_meta($id, 'lead_score', $score_data['score']);
        update_post_meta($id, 'lead_priority', $score_data['priority']);
        update_post_meta($id, 'lead_agent_summary', $summary);
        wc_lead_agent_log($id, 'Lead agent gestart. Score: ' . $score_data['score'] . '/100, prioriteit: ' . $score_data['priority'] . '.');

        $settings = wc_lead_agent_settings();
        $body = wc_lead_email_body($id, $lead_data);
        $admin_body = "Lead agent samenvatting\n\n" . $summary . "\n\n" . $body;
        wp_mail($settings['admin_email'] ?: 'tonny@vakvriend.nl', 'Nieuwe warmtepomp lead [' . strtoupper($score_data['priority']) . '] ' . $naam, $admin_body, array('From: Vakvriend <info@vakvriend.nl>'));
        wc_lead_agent_log($id, 'Interne leadmail verstuurd.');

        if ($settings['customer_email_enabled'] === '1') {
            $sent = wp_mail($email, 'We hebben uw woningcheck ontvangen', wc_lead_customer_email_body($lead_data), array('From: Vakvriend <info@vakvriend.nl>', 'Content-Type: text/html; charset=UTF-8'));
            update_post_meta($id, 'customer_email_status', $sent ? 'verzonden' : 'mislukt');
            wc_lead_agent_log($id, $sent ? 'Klantmail verzonden.' : 'Klantmail mislukt.');
        } else {
            update_post_meta($id, 'customer_email_status', 'overgeslagen: uitgeschakeld');
            wc_lead_agent_log($id, 'Klantmail overgeslagen: uitgeschakeld.');
        }

        wc_lead_send_sms($id, $lead_data['telefoon'] ?? '', wc_lead_customer_sms_body($lead_data));
        wc_lead_schedule_followup_reminder($id);
    }
    wp_send_json_success(array('message' => 'Bedankt! Vakvriend neemt binnen 1 werkdag contact op.'));
}
add_action('wp_ajax_wc_lead', 'wc_ajax_lead');
add_action('wp_ajax_nopriv_wc_lead', 'wc_ajax_lead');

// Meta box voor campagne pagina instellingen
function wc_meta_box_register() {
    add_meta_box('wc_campagne', 'Campagne Instellingen', 'wc_meta_box_render', 'page', 'normal', 'high');
}
add_action('add_meta_boxes', 'wc_meta_box_register');

function wc_meta_box_render($post) {
    wp_nonce_field('wc_meta_nonce', 'wc_meta_nonce_field');
    $fields = array(
        'wc_stad'          => 'Stad',
        'wc_regio'         => 'Regio',
        'wc_hero_titel'    => 'Hero Titel',
        'wc_hero_subtitel' => 'Hero Subtitel',
        'wc_hero_kicker'   => 'Hero Kicker',
        'wc_hero_foto'     => 'Hero Foto URL',
        'wc_topbar_tekst'  => 'Topbar Tekst',
        'wc_form_titel'    => 'Formulier Titel',
        'wc_form_subtitel' => 'Formulier Subtitel',
        'wc_form_benefits' => 'Formulier voordelen (1 per regel)',
        'wc_campaign_proof'=> 'Hero proof points (nummer|label)',
        'wc_ai_content_status' => 'AI content status',
        'wc_vv_intro'      => 'Vakvriend Intro',
        'wc_vv_usp1'       => 'USP 1',
        'wc_vv_usp2'       => 'USP 2',
        'wc_vv_usp3'       => 'USP 3',
        'wc_vv_usp4'       => 'USP 4',
        'wc_telefoon'      => 'Telefoon',
        'wc_whatsapp'      => 'WhatsApp',
        'wc_email'         => 'E-mail',
        'wc_meta_title'    => 'SEO Titel',
        'wc_meta_desc'     => 'Meta Beschrijving',
        'wc_extra_tekst'   => 'Extra Tekst',
        'wc_faq_extra'     => 'Extra FAQ (Vraag:::Antwoord)',
        'wc_qvantum_eyebrow' => 'Qvantum Eyebrow',
        'wc_qvantum_titel' => 'Qvantum Titel',
        'wc_qvantum_lead'  => 'Qvantum Lead',
        'wc_qvantum_types' => 'Qvantum types (icoon|titel|tekst)',
        'wc_qvantum_uitleg_titel' => 'Qvantum warmwater uitleg titel',
        'wc_qvantum_uitleg_tekst' => 'Qvantum warmwater uitleg tekst',
        'wc_qvantum_usps'  => 'Qvantum USP’s (titel|tekst)',
        'wc_qvantum_cta'   => 'Qvantum CTA',
        'wc_nibe_eyebrow'  => 'Nibe Eyebrow',
        'wc_nibe_titel'    => 'Nibe Titel',
        'wc_nibe_lead'     => 'Nibe Lead',
        'wc_nibe_types'    => 'Nibe types (icoon|titel|tekst)',
        'wc_nibe_cta'      => 'Nibe CTA',
        'wc_types_eyebrow' => 'Types Eyebrow',
        'wc_types_titel'   => 'Types Titel',
        'wc_types_lead'    => 'Types Lead',
        'wc_warmtepomp_types' => 'Warmtepomp type cards (icoon|badge|titel|tekst|punten ; gescheiden|merken)',
        'wc_vv_eyebrow'    => 'Vakvriend Eyebrow',
        'wc_vv_titel'      => 'Vakvriend Titel',
        'wc_vv_props'      => 'Vakvriend props (icoon|titel|tekst)',
        'wc_voordelen_eyebrow' => 'Voordelen Eyebrow',
        'wc_voordelen_titel' => 'Voordelen Titel',
        'wc_voordelen_lead' => 'Voordelen Lead',
        'wc_voordelen'     => 'Voordelen cards (icoon|titel|tekst)',
        'wc_werkwijze_eyebrow' => 'Werkwijze Eyebrow',
        'wc_werkwijze_titel' => 'Werkwijze Titel',
        'wc_werkwijze_lead' => 'Werkwijze Lead',
        'wc_werkwijze'     => 'Werkwijze stappen (nummer|titel|tekst)',
        'wc_boringen_eyebrow' => 'Boringen Eyebrow',
        'wc_boringen_titel' => 'Boringen Titel',
        'wc_boringen_lead' => 'Boringen Lead',
        'wc_boringen_cta'  => 'Boringen CTA',
        'wc_boringen_kaart_titel' => 'Boringen kaart titel',
        'wc_boringen_kaart_tekst' => 'Boringen kaart tekst',
        'wc_reviews_eyebrow' => 'Reviews Eyebrow',
        'wc_reviews_titel' => 'Reviews Titel',
        'wc_reviews_lead'  => 'Reviews Lead',
        'wc_reviews'       => 'Reviews (quote|naam|project|sterren)',
        'wc_faq_eyebrow'   => 'FAQ Eyebrow',
        'wc_faq_titel'     => 'FAQ Titel',
        'wc_lokale_h2'     => 'Lokale H2',
        'wc_lokale_intro'  => 'Lokale intro',
        'wc_lokale_alinea1'=> 'Lokale alinea 1',
        'wc_lokale_alinea2'=> 'Lokale alinea 2',
        'wc_lokale_alinea3'=> 'Lokale alinea 3',
        'wc_lokale_faq_v1' => 'Lokale FAQ vraag 1',
        'wc_lokale_faq_a1' => 'Lokale FAQ antwoord 1',
        'wc_lokale_faq_v2' => 'Lokale FAQ vraag 2',
        'wc_lokale_faq_a2' => 'Lokale FAQ antwoord 2',
        'wc_lokale_faq_v3' => 'Lokale FAQ vraag 3',
        'wc_lokale_faq_a3' => 'Lokale FAQ antwoord 3',
    );
    $textarea = array('wc_hero_subtitel','wc_form_subtitel','wc_form_benefits','wc_campaign_proof','wc_vv_intro','wc_extra_tekst','wc_faq_extra','wc_qvantum_lead','wc_qvantum_types','wc_qvantum_uitleg_tekst','wc_qvantum_usps','wc_nibe_lead','wc_nibe_types','wc_types_lead','wc_warmtepomp_types','wc_vv_props','wc_voordelen_lead','wc_voordelen','wc_werkwijze_lead','wc_werkwijze','wc_boringen_lead','wc_boringen_kaart_tekst','wc_reviews_lead','wc_reviews','wc_lokale_intro','wc_lokale_alinea1','wc_lokale_alinea2','wc_lokale_alinea3','wc_lokale_faq_a1','wc_lokale_faq_a2','wc_lokale_faq_a3');
    echo '<table style="width:100%;border-collapse:collapse">';
    foreach ($fields as $k => $l) {
        $v = get_post_meta($post->ID, $k, true);
        echo '<tr><td style="padding:8px;width:180px;font-weight:600;font-size:13px;vertical-align:top">' . esc_html($l) . '</td><td style="padding:8px">';
        if (in_array($k, $textarea)) {
            echo '<textarea name="' . esc_attr($k) . '" style="width:100%;height:80px;border:1px solid #ddd;border-radius:4px;padding:6px;font-size:13px">' . esc_textarea($v) . '</textarea>';
        } else {
            echo '<input type="text" name="' . esc_attr($k) . '" value="' . esc_attr($v) . '" style="width:100%;border:1px solid #ddd;border-radius:4px;padding:6px;font-size:13px">';
        }
        echo '</td></tr>';
    }
    echo '</table>';
}

function wc_meta_box_save($id) {
    if (!isset($_POST['wc_meta_nonce_field'])) return;
    if (!wp_verify_nonce($_POST['wc_meta_nonce_field'], 'wc_meta_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    $fields = array('wc_stad','wc_regio','wc_hero_titel','wc_hero_subtitel','wc_hero_kicker','wc_hero_foto','wc_topbar_tekst','wc_form_titel','wc_form_subtitel','wc_form_benefits','wc_campaign_proof','wc_ai_content_status','wc_vv_intro','wc_vv_usp1','wc_vv_usp2','wc_vv_usp3','wc_vv_usp4','wc_telefoon','wc_whatsapp','wc_email','wc_meta_title','wc_meta_desc','wc_extra_tekst','wc_faq_extra','wc_qvantum_eyebrow','wc_qvantum_titel','wc_qvantum_lead','wc_qvantum_types','wc_qvantum_uitleg_titel','wc_qvantum_uitleg_tekst','wc_qvantum_usps','wc_qvantum_cta','wc_nibe_eyebrow','wc_nibe_titel','wc_nibe_lead','wc_nibe_types','wc_nibe_cta','wc_types_eyebrow','wc_types_titel','wc_types_lead','wc_warmtepomp_types','wc_vv_eyebrow','wc_vv_titel','wc_vv_props','wc_voordelen_eyebrow','wc_voordelen_titel','wc_voordelen_lead','wc_voordelen','wc_werkwijze_eyebrow','wc_werkwijze_titel','wc_werkwijze_lead','wc_werkwijze','wc_boringen_eyebrow','wc_boringen_titel','wc_boringen_lead','wc_boringen_cta','wc_boringen_kaart_titel','wc_boringen_kaart_tekst','wc_reviews_eyebrow','wc_reviews_titel','wc_reviews_lead','wc_reviews','wc_faq_eyebrow','wc_faq_titel','wc_lokale_h2','wc_lokale_intro','wc_lokale_alinea1','wc_lokale_alinea2','wc_lokale_alinea3','wc_lokale_faq_v1','wc_lokale_faq_a1','wc_lokale_faq_v2','wc_lokale_faq_a2','wc_lokale_faq_v3','wc_lokale_faq_a3');
    foreach ($fields as $k) {
        if (isset($_POST[$k])) update_post_meta($id, $k, sanitize_textarea_field($_POST[$k]));
    }
}
add_action('save_post', 'wc_meta_box_save');
