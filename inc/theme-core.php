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
    wp_enqueue_style('wc-main', get_template_directory_uri() . '/assets/css/main.css', ['wc-fonts'], '4.6');
    wp_enqueue_script('wc-main', get_template_directory_uri() . '/assets/js/main.js', [], '4.4', true);
    wp_localize_script('wc-main', 'wcVars', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('wc_lead_nonce'),
        'siteUrl' => get_site_url(),
    ));
}
add_action('wp_enqueue_scripts', 'wc_enqueue_assets');

function wc_favicon_links() {
    $favicon = get_template_directory_uri() . '/assets/img/favicon.svg';
    echo '<link rel="icon" href="' . esc_url($favicon) . '" type="image/svg+xml">' . "\n";
    echo '<link rel="shortcut icon" href="' . esc_url($favicon) . '" type="image/svg+xml">' . "\n";
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
        $body = wc_lead_email_body($id, $lead_data);
        wp_mail('tonny@vakvriend.nl', 'Nieuwe warmtepomp lead: ' . $naam, $body, array('From: Vakvriend <info@vakvriend.nl>'));
        wp_mail($email, 'Bedankt voor uw aanvraag - Vakvriend', "Hallo $naam,\n\nBedankt! Wij nemen binnen 1 werkdag contact op.\n\nTel: 075 234 0001\nwww.vakvriend.nl\n\nTeam Vakvriend", array('From: Vakvriend <info@vakvriend.nl>'));
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
