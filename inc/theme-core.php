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
    wp_enqueue_style('wc-main', get_template_directory_uri() . '/assets/css/main.css', ['wc-fonts'], '6.4');
    wp_enqueue_script('wc-main', get_template_directory_uri() . '/assets/js/main.js', [], '4.8', true);
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
        'Lead agent',
        'Lead agent',
        'manage_options',
        'wc-lead-agent',
        'wc_lead_agent_settings_page'
    );
}
add_action('admin_menu', 'wc_lead_agent_menu');

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
                    <th scope="row"><label for="wc-agent-twilio-from">Twilio afzender</label></th>
                    <td><input id="wc-agent-twilio-from" type="text" class="regular-text" name="wc_lead_agent_settings[twilio_from]" value="<?php echo esc_attr($settings['twilio_from']); ?>" placeholder="+31612345678"></td>
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

function wc_lead_agent_score($data) {
    $score = 35;
    $notes = array();

    if (!empty($data['telefoon'])) {
        $score += 20;
        $notes[] = 'telefoon aanwezig';
    }
    if (!empty($data['postcode'])) {
        $score += 10;
        $notes[] = 'postcode aanwezig';
    }
    if (!empty($data['gasverbruik'])) {
        $score += 10;
        $notes[] = 'gasverbruik ingevuld';
    }
    if (!empty($data['situatie']) && stripos($data['situatie'], 'weet') === false) {
        $score += 10;
        $notes[] = 'systeemvoorkeur gekozen';
    }
    if (!empty($data['gclid']) || !empty($data['gbraid']) || !empty($data['wbraid'])) {
        $score += 10;
        $notes[] = 'Google Ads klik';
    }
    if (!empty($data['woningtype'])) {
        $score += 5;
        $notes[] = 'woningtype bekend';
    }

    $score = max(0, min(100, $score));
    $priority = $score >= 75 ? 'hoog' : ($score < 55 ? 'laag' : 'normaal');

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
    $lines[] = 'Kanaal: ' . ($data['utm_source'] ?: '-') . ' / ' . ($data['utm_campaign'] ?: '-');
    if ($score_data['notes']) {
        $lines[] = 'Waarom deze score: ' . implode(', ', $score_data['notes']) . '.';
    }
    $lines[] = 'Advies opvolging: bel snel als telefoon aanwezig is. Anders mailen en vragen naar telefoonnummer, bouwjaar, isolatie en afgiftesysteem.';

    return implode("\n", $lines);
}

function wc_lead_customer_email_body($data) {
    $naam = $data['naam'] ?: 'daar';
    $stad = $data['stad'] ?: 'uw regio';
    $systeem = $data['situatie'] ?: 'de best passende warmtepompoplossing';

    return "Hallo " . $naam . ",\n\n"
        . "Bedankt voor uw aanvraag bij Vakvriend. We bekijken uw woningcheck voor " . $stad . " en nemen zo snel mogelijk contact op.\n\n"
        . "Dit nemen we alvast mee:\n"
        . "- Woningtype: " . ($data['woningtype'] ?: '-') . "\n"
        . "- Voorkeur: " . $systeem . "\n"
        . "- Gasverbruik: " . ($data['gasverbruik'] ?: '-') . "\n\n"
        . "Handig om alvast klaar te leggen: bouwjaar, huidige ketel, type radiatoren of vloerverwarming en eventuele foto's van de technische ruimte.\n\n"
        . "U kunt ons ook direct bereiken via 075 234 0001 of info@vakvriend.nl.\n\n"
        . "Met vriendelijke groet,\n"
        . "Team Vakvriend";
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
    $from = $settings['twilio_from'];
    if (!$sid || !$token || !$from) {
        update_post_meta($lead_id, 'sms_status', 'overgeslagen: Twilio niet ingesteld');
        wc_lead_agent_log($lead_id, 'SMS overgeslagen: Twilio niet ingesteld.');
        return false;
    }

    $response = wp_remote_post('https://api.twilio.com/2010-04-01/Accounts/' . rawurlencode($sid) . '/Messages.json', array(
        'timeout' => 15,
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode($sid . ':' . $token),
        ),
        'body' => array(
            'From' => $from,
            'To' => $to,
            'Body' => $message,
        ),
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
        $velden = array('naam','email','telefoon','postcode','woningtype','situatie','gasverbruik','domein','stad','utm_source','utm_medium','utm_campaign','utm_term','utm_content','gclid','gbraid','wbraid','landing_page','referrer');
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
            $sent = wp_mail($email, 'Uw woningcheck is ontvangen - Vakvriend', wc_lead_customer_email_body($lead_data), array('From: Vakvriend <info@vakvriend.nl>'));
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
