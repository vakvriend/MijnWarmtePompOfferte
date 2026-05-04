<?php
defined('ABSPATH') || exit;

function wc_ads_builder_defaults() {
    return array(
        'developer_token' => '',
        'client_id' => '',
        'client_secret' => '',
        'refresh_token' => '',
        'login_customer_id' => '',
        'customer_id' => '',
        'daily_budget' => '15',
        'api_version' => 'v22',
    );
}

function wc_ads_builder_settings() {
    $saved = get_option('wc_ads_builder_settings', array());
    return wp_parse_args(is_array($saved) ? $saved : array(), wc_ads_builder_defaults());
}

function wc_ads_builder_sanitize_settings($input) {
    $defaults = wc_ads_builder_defaults();
    $clean = array();
    foreach ($defaults as $key => $default) {
        $value = isset($input[$key]) ? trim((string) $input[$key]) : '';
        if ($key === 'daily_budget') {
            $clean[$key] = (string) max(1, min(500, (float) str_replace(',', '.', $value)));
            continue;
        }
        if (in_array($key, array('login_customer_id', 'customer_id'), true)) {
            $clean[$key] = preg_replace('/[^0-9]/', '', $value);
            continue;
        }
        if ($key === 'api_version') {
            $clean[$key] = preg_match('/^v[0-9]+$/', $value) ? $value : 'v22';
            continue;
        }
        $clean[$key] = sanitize_text_field($value);
    }
    return $clean;
}

function wc_ads_builder_admin_menu() {
    add_menu_page(
        'Vakvriend Ads Builder',
        'Ads Builder',
        'manage_options',
        'wc-ads-builder',
        'wc_ads_builder_page',
        'dashicons-megaphone',
        56
    );
}
add_action('admin_menu', 'wc_ads_builder_admin_menu');

function wc_ads_builder_register_settings() {
    register_setting('wc_ads_builder', 'wc_ads_builder_settings', 'wc_ads_builder_sanitize_settings');
}
add_action('admin_init', 'wc_ads_builder_register_settings');

function wc_ads_builder_domains() {
    return array(
        'warmtepompdenhaag.nl' => array('city' => 'Den Haag', 'region' => 'Haaglanden'),
        'warmtepomphilversum.nl' => array('city' => 'Hilversum', 'region' => 'het Gooi'),
        'warmtepompzaandam.nl' => array('city' => 'Zaandam', 'region' => 'de Zaanstreek'),
        'warmtepompalkmaar.nl' => array('city' => 'Alkmaar', 'region' => 'de regio Alkmaar'),
        'warmtepompalmere.nl' => array('city' => 'Almere', 'region' => 'Flevoland'),
        'haarlem-warmtepomp.nl' => array('city' => 'Haarlem', 'region' => 'Kennemerland'),
        'warmtepompamstelveen.nl' => array('city' => 'Amstelveen', 'region' => 'Amstelland'),
        'warmtepompaalsmeer.nl' => array('city' => 'Aalsmeer', 'region' => 'Amstelland'),
        'warmtepompbadhoevedorp.nl' => array('city' => 'Badhoevedorp', 'region' => 'Haarlemmermeer'),
        'warmtepompdiemen.nl' => array('city' => 'Diemen', 'region' => 'de regio Amsterdam'),
        'warmtepompouderkerk.nl' => array('city' => 'Ouderkerk', 'region' => 'Amstelland'),
        'warmtepompuithoorn.nl' => array('city' => 'Uithoorn', 'region' => 'Amstelland'),
        'warmtepomphoofddorp.nl' => array('city' => 'Hoofddorp', 'region' => 'Haarlemmermeer'),
        'warmtepompnieuwvennep.nl' => array('city' => 'Nieuw Vennep', 'region' => 'Haarlemmermeer'),
        'warmtepomppurmerend.nl' => array('city' => 'Purmerend', 'region' => 'Waterland'),
        'warmtepompkennemerland.nl' => array('city' => 'Kennemerland', 'region' => 'Kennemerland'),
        'warmtepompzaanstreek.nl' => array('city' => 'Zaanstreek', 'region' => 'de Zaanstreek'),
        'warmtepompheiloo.nl' => array('city' => 'Heiloo', 'region' => 'de regio Alkmaar'),
        'warmtepompcastricum.nl' => array('city' => 'Castricum', 'region' => 'Kennemerland'),
        'warmtepompbloemendaal.nl' => array('city' => 'Bloemendaal', 'region' => 'Kennemerland'),
        'warmtepompzandvoort.nl' => array('city' => 'Zandvoort', 'region' => 'Kennemerland'),
        'warmtepompijmuiden.nl' => array('city' => 'IJmuiden', 'region' => 'Kennemerland'),
        'warmtepompbeverwijk.nl' => array('city' => 'Beverwijk', 'region' => 'Kennemerland'),
        'warmtepompbussum.nl' => array('city' => 'Bussum', 'region' => 'het Gooi'),
        'warmtepompnaarden.nl' => array('city' => 'Naarden', 'region' => 'het Gooi'),
        'warmtepomphuizen.nl' => array('city' => 'Huizen', 'region' => 'het Gooi'),
        'warmtepomplaren.nl' => array('city' => 'Laren', 'region' => 'het Gooi'),
        'warmtepompblaricum.nl' => array('city' => 'Blaricum', 'region' => 'het Gooi'),
        'warmtepompweesp.nl' => array('city' => 'Weesp', 'region' => 'het Gooi'),
        'warmtepompgooi.nl' => array('city' => 'Gooi', 'region' => 'het Gooi'),
        'warmtepomplelystad.nl' => array('city' => 'Lelystad', 'region' => 'Flevoland'),
        'warmtepompdronten.nl' => array('city' => 'Dronten', 'region' => 'Flevoland'),
        'warmtepompgouda.nl' => array('city' => 'Gouda', 'region' => 'de regio Gouda'),
        'warmtepompdelft.nl' => array('city' => 'Delft', 'region' => 'de regio Delft'),
        'warmtepompzoetermeer.nl' => array('city' => 'Zoetermeer', 'region' => 'Haaglanden'),
        'warmtepompamersfoort.nl' => array('city' => 'Amersfoort', 'region' => 'de regio Amersfoort'),
        'warmtepompdordrecht.nl' => array('city' => 'Dordrecht', 'region' => 'de regio Dordrecht'),
        'warmtepomphoorn.nl' => array('city' => 'Hoorn', 'region' => 'West-Friesland'),
        'warmtepompden-helder.nl' => array('city' => 'Den Helder', 'region' => 'de Noordkop'),
        'warmtepompapeldoorn.nl' => array('city' => 'Apeldoorn', 'region' => 'de Veluwe'),
        'warmtepompzeist.nl' => array('city' => 'Zeist', 'region' => 'de regio Zeist'),
        'warmtepompdenbosch.nl' => array('city' => 'Den Bosch', 'region' => 'de regio Den Bosch'),
    );
}

function wc_ads_builder_focus_options() {
    return array(
        'advies' => 'Advies en woningcheck',
        'hybride' => 'Hybride warmtepomp',
        'subsidie' => 'Subsidie / ISDE',
        'qvantum-nibe' => 'Qvantum en Nibe',
        'boiler' => 'Warmtepompboiler',
    );
}

function wc_ads_builder_negative_keywords() {
    return array(
        'vacature', 'opleiding', 'cursus', 'stage', 'zelf installeren', 'doe het zelf',
        'marktplaats', 'tweedehands', 'gratis', 'handleiding', 'pdf', 'storing',
        'reparatie', 'onderdelen', 'werking', 'schema', 'forum', 'review werknemer',
        'salaris', 'monteur vacature', 'airco', 'zwembad', 'camper', 'boot',
    );
}

function wc_ads_builder_ab_variants($city, $focus) {
    $city = trim((string) $city);
    $base = array(
        'advies' => array(
            'label' => 'A - Advies',
            'utm_content' => 'a_advies_woningcheck',
            'angle' => 'advies',
            'headlines' => array(
                'Advies voor uw woning',
                'Vrijblijvende woningcheck',
                'Eerst checken, dan kiezen',
                'Merkonafhankelijk advies',
            ),
            'descriptions' => array(
                'Vakvriend kijkt eerst naar uw woning, verbruik en wensen. Daarna pas een passend advies.',
                'Vrijblijvende woningcheck voor ' . $city . '. Eerlijk en praktisch advies.',
            ),
        ),
        'subsidie' => array(
            'label' => 'B - Subsidie',
            'utm_content' => 'b_subsidie_isde',
            'angle' => 'subsidie',
            'headlines' => array(
                'ISDE subsidie meegenomen',
                'Subsidie helder uitgelegd',
                'Warmtepompcheck ' . $city,
                'Vakvriend rekent mee',
            ),
            'descriptions' => array(
                'Krijg inzicht in warmtepomp, ISDE-subsidie en praktische haalbaarheid voor uw woning.',
                'Wij rekenen subsidie en plaatsing mee, zodat u vooraf weet wat realistisch is.',
            ),
        ),
    );

    if ($focus === 'hybride') {
        $base['subsidie']['headlines'][] = 'Hybride subsidiecheck';
    } elseif ($focus === 'qvantum-nibe') {
        $base['advies']['headlines'][] = 'Qvantum of Nibe?';
    } elseif ($focus === 'boiler') {
        $base['advies']['headlines'][] = 'Tapwater slim regelen';
    }

    return $base;
}

function wc_ads_builder_final_url($plan, $group_name = '', $variant = array()) {
    $campaign = sanitize_title($plan['campaign']);
    $content = sanitize_title($variant['utm_content'] ?? 'basis');
    $term = sanitize_title($group_name ?: 'campagne');
    return add_query_arg(array(
        'utm_source' => 'google',
        'utm_medium' => 'cpc',
        'utm_campaign' => $campaign,
        'utm_content' => $content,
        'utm_term' => $term,
    ), $plan['url']);
}

function wc_ads_builder_plan($args) {
    $city = trim((string) ($args['city'] ?? 'Den Haag'));
    $domain = strtolower(trim((string) ($args['domain'] ?? 'warmtepompdenhaag.nl')));
    $budget = max(1, (float) str_replace(',', '.', (string) ($args['budget'] ?? 15)));
    $focus = sanitize_key($args['focus'] ?? 'advies');
    $campaign = 'Search | Warmtepomp ' . $city;
    $url = 'https://' . preg_replace('/^https?:\/\//', '', $domain) . '/';
    $groups = array(
        'Warmtepomp ' . $city => array(
            'keywords' => array(
                '"warmtepomp ' . strtolower($city) . '"',
                '[warmtepomp ' . strtolower($city) . ']',
                '"warmtepomp installateur ' . strtolower($city) . '"',
                '[warmtepomp installateur ' . strtolower($city) . ']',
                'warmtepomp kopen ' . strtolower($city),
            ),
            'headlines' => array(
                'Warmtepomp ' . $city,
                'Advies voor uw woning',
                'Vrijblijvende woningcheck',
                'ISDE subsidie meegenomen',
                'Merkonafhankelijk advies',
                'Ook Daikin en Itho',
                'Vakvriend helpt lokaal',
            ),
            'descriptions' => array(
                'Ontdek welke warmtepomp bij uw woning past. Eerst advies, daarna pas een offerte.',
                'Vakvriend kijkt naar woning, gasverbruik, subsidie en praktische plaatsing.',
                'Vrijblijvende woningcheck voor ' . $city . ' en omgeving. Eerlijk advies.',
            ),
        ),
        'Hybride warmtepomp ' . $city => array(
            'keywords' => array(
                '"hybride warmtepomp ' . strtolower($city) . '"',
                '[hybride warmtepomp ' . strtolower($city) . ']',
                '"intergas xtend ' . strtolower($city) . '"',
                'hybride warmtepomp kopen ' . strtolower($city),
            ),
            'headlines' => array(
                'Hybride warmtepomp ' . $city,
                'Ook naast bestaande ketel',
                'Intergas Xtend advies',
                'Minder gas verbruiken',
                'Woningcheck zonder druk',
            ),
            'descriptions' => array(
                'Laat controleren of hybride slim is voor uw woning en cv-ketel.',
                'We kijken naar gasverbruik, afgiftesysteem en ruimte voor de buitenunit.',
            ),
        ),
        'Subsidie warmtepomp ' . $city => array(
            'keywords' => array(
                '"warmtepomp subsidie ' . strtolower($city) . '"',
                '[warmtepomp subsidie ' . strtolower($city) . ']',
                '"isde warmtepomp ' . strtolower($city) . '"',
                'subsidie warmtepomp ' . strtolower($city),
            ),
            'headlines' => array(
                'Warmtepomp subsidie ' . $city,
                'ISDE meegenomen',
                'Eerlijke subsidiecheck',
                'Woning eerst beoordelen',
                'Vakvriend rekent mee',
            ),
            'descriptions' => array(
                'Wij nemen ISDE mee in de woningcheck en leggen uit wat realistisch is.',
                'Geen losse subsidiebelofte, maar een technische check van woning en systeem.',
            ),
        ),
    );

    if ($focus === 'qvantum-nibe') {
        $groups['Qvantum Nibe ' . $city] = array(
            'keywords' => array('"qvantum warmtepomp ' . strtolower($city) . '"', '"nibe warmtepomp ' . strtolower($city) . '"', '[nibe warmtepomp ' . strtolower($city) . ']'),
            'headlines' => array('Qvantum of Nibe?', 'Merkonafhankelijk advies', 'Warmtepomp ' . $city, 'Technische woningcheck'),
            'descriptions' => array('Qvantum, Nibe, Daikin en Itho vergeleken op woning, geluid, tapwater en subsidie.'),
        );
    }

    if ($focus === 'boiler') {
        $groups['Warmtepompboiler ' . $city] = array(
            'keywords' => array('"warmtepompboiler ' . strtolower($city) . '"', '[warmtepompboiler ' . strtolower($city) . ']', 'boiler zonder gas ' . strtolower($city)),
            'headlines' => array('Warmtepompboiler ' . $city, 'Tapwater zonder gasketel', 'Advies voor boilerkeuze', 'Vrijblijvende check'),
            'descriptions' => array('Laat beoordelen of een warmtepompboiler past bij uw tapwaterverbruik en opstelruimte.'),
        );
    }

    return array(
        'campaign' => $campaign,
        'city' => $city,
        'domain' => $domain,
        'url' => $url,
        'budget' => $budget,
        'focus' => $focus,
        'groups' => $groups,
        'variants' => wc_ads_builder_ab_variants($city, $focus),
        'negatives' => wc_ads_builder_negative_keywords(),
        'sitelinks' => array('Woningcheck', 'ISDE subsidie', 'Qvantum vs Nibe', 'Werkwijze'),
        'callouts' => array('Vrijblijvende woningcheck', 'Merkonafhankelijk advies', 'Ook Daikin en Itho', 'Eerlijk advies'),
    );
}

function wc_ads_builder_csv_rows($plan) {
    $rows = array();
    foreach ($plan['groups'] as $group_name => $group) {
        $rows[] = array('Type' => 'Campaign', 'Campaign' => $plan['campaign'], 'Ad group' => '', 'Keyword' => '', 'Headline' => '', 'Description' => '', 'Final URL' => $plan['url'], 'Budget' => number_format($plan['budget'], 2, '.', ''), 'Status' => 'Paused');
        $rows[] = array('Type' => 'Ad group', 'Campaign' => $plan['campaign'], 'Ad group' => $group_name, 'Keyword' => '', 'Headline' => '', 'Description' => '', 'Final URL' => $plan['url'], 'Budget' => '', 'Status' => 'Paused');
        foreach ($group['keywords'] as $keyword) {
            $rows[] = array('Type' => 'Keyword', 'Campaign' => $plan['campaign'], 'Ad group' => $group_name, 'Keyword' => $keyword, 'Headline' => '', 'Description' => '', 'Final URL' => $plan['url'], 'Budget' => '', 'Status' => 'Enabled');
        }
        foreach ($plan['variants'] as $variant_key => $variant) {
            $headlines = array_values(array_unique(array_merge($group['headlines'], $variant['headlines'])));
            $descriptions = array_values(array_unique(array_merge($group['descriptions'], $variant['descriptions'])));
            $rows[] = array('Type' => 'Responsive search ad', 'Campaign' => $plan['campaign'], 'Ad group' => $group_name, 'Keyword' => '', 'Headline' => implode(' | ', $headlines), 'Description' => implode(' | ', $descriptions), 'Final URL' => wc_ads_builder_final_url($plan, $group_name, $variant), 'Budget' => '', 'Status' => 'Paused');
        }
    }
    foreach ($plan['negatives'] as $negative) {
        $rows[] = array('Type' => 'Negative keyword', 'Campaign' => $plan['campaign'], 'Ad group' => '', 'Keyword' => $negative, 'Headline' => '', 'Description' => '', 'Final URL' => '', 'Budget' => '', 'Status' => 'Enabled');
    }
    return $rows;
}

function wc_ads_builder_download_csv() {
    if (!current_user_can('manage_options')) {
        wp_die('Geen toegang.');
    }
    check_admin_referer('wc_ads_builder_export');
    $plan = wc_ads_builder_plan($_POST);
    $rows = wc_ads_builder_csv_rows($plan);
    nocache_headers();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="ads-builder-' . sanitize_title($plan['city']) . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, array_keys($rows[0]));
    foreach ($rows as $row) {
        fputcsv($out, $row);
    }
    fclose($out);
    exit;
}
add_action('admin_post_wc_ads_builder_export', 'wc_ads_builder_download_csv');

function wc_ads_builder_access_token() {
    $settings = wc_ads_builder_settings();
    if (!$settings['client_id'] || !$settings['client_secret'] || !$settings['refresh_token']) {
        return new WP_Error('missing_oauth', 'OAuth gegevens ontbreken.');
    }
    $response = wp_remote_post('https://oauth2.googleapis.com/token', array(
        'timeout' => 20,
        'body' => array(
            'client_id' => $settings['client_id'],
            'client_secret' => $settings['client_secret'],
            'refresh_token' => $settings['refresh_token'],
            'grant_type' => 'refresh_token',
        ),
    ));
    if (is_wp_error($response)) {
        return $response;
    }
    $body = json_decode(wp_remote_retrieve_body($response), true);
    if (empty($body['access_token'])) {
        return new WP_Error('token_failed', 'Geen access token ontvangen: ' . wp_remote_retrieve_body($response));
    }
    return $body['access_token'];
}

function wc_ads_builder_api_headers($access_token, $needs_customer = true) {
    $settings = wc_ads_builder_settings();
    $headers = array(
        'Authorization' => 'Bearer ' . $access_token,
        'developer-token' => $settings['developer_token'],
        'Content-Type' => 'application/json',
    );
    if ($needs_customer && $settings['login_customer_id']) {
        $headers['login-customer-id'] = $settings['login_customer_id'];
    }
    return $headers;
}

function wc_ads_builder_api_get_accessible_customers() {
    $settings = wc_ads_builder_settings();
    if (!$settings['developer_token']) {
        return new WP_Error('missing_dev_token', 'Developer token ontbreekt.');
    }
    $token = wc_ads_builder_access_token();
    if (is_wp_error($token)) {
        return $token;
    }
    $url = 'https://googleads.googleapis.com/' . rawurlencode($settings['api_version']) . '/customers:listAccessibleCustomers';
    $response = wp_remote_get($url, array(
        'timeout' => 20,
        'headers' => wc_ads_builder_api_headers($token, false),
    ));
    if (is_wp_error($response)) {
        return $response;
    }
    $code = wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);
    if ($code < 200 || $code >= 300) {
        return new WP_Error('google_ads_api_error', 'Google Ads API HTTP ' . $code . ': ' . wp_remote_retrieve_body($response));
    }
    return $body;
}

function wc_ads_builder_api_search($query) {
    $settings = wc_ads_builder_settings();
    if (!$settings['developer_token'] || !$settings['customer_id']) {
        return new WP_Error('missing_google_ads_settings', 'Developer token of customer ID ontbreekt.');
    }
    $token = wc_ads_builder_access_token();
    if (is_wp_error($token)) {
        return $token;
    }
    $customer_id = preg_replace('/[^0-9]/', '', $settings['customer_id']);
    $url = 'https://googleads.googleapis.com/' . rawurlencode($settings['api_version']) . '/customers/' . rawurlencode($customer_id) . '/googleAds:searchStream';
    $response = wp_remote_post($url, array(
        'timeout' => 35,
        'headers' => wc_ads_builder_api_headers($token, true),
        'body' => wp_json_encode(array('query' => $query)),
    ));
    if (is_wp_error($response)) {
        return $response;
    }
    $code = wp_remote_retrieve_response_code($response);
    $body_raw = wp_remote_retrieve_body($response);
    $body = json_decode($body_raw, true);
    if ($code < 200 || $code >= 300) {
        return new WP_Error('google_ads_report_failed', 'Google Ads API HTTP ' . $code . ': ' . $body_raw);
    }
    $rows = array();
    foreach ((array) $body as $chunk) {
        foreach ((array) ($chunk['results'] ?? array()) as $row) {
            $rows[] = $row;
        }
    }
    return $rows;
}

function wc_ads_builder_fetch_ad_results($range_days = 14) {
    $range_days = max(1, min(90, (int) $range_days));
    $since = gmdate('Y-m-d', time() - $range_days * DAY_IN_SECONDS);
    $query = "
        SELECT
          segments.date,
          campaign.name,
          ad_group.name,
          ad_group_ad.ad.id,
          ad_group_ad.status,
          ad_group_ad.ad.final_urls,
          metrics.impressions,
          metrics.clicks,
          metrics.cost_micros,
          metrics.conversions,
          metrics.ctr
        FROM ad_group_ad
        WHERE segments.date >= '{$since}'
          AND campaign.advertising_channel_type = 'SEARCH'
        ORDER BY segments.date DESC
        LIMIT 200
    ";
    return wc_ads_builder_api_search($query);
}

function wc_ads_builder_fetch_keyword_results($range_days = 14) {
    $range_days = max(1, min(90, (int) $range_days));
    $since = gmdate('Y-m-d', time() - $range_days * DAY_IN_SECONDS);
    $query = "
        SELECT
          campaign.name,
          ad_group.name,
          ad_group_criterion.keyword.text,
          ad_group_criterion.keyword.match_type,
          metrics.impressions,
          metrics.clicks,
          metrics.cost_micros,
          metrics.conversions,
          metrics.ctr
        FROM keyword_view
        WHERE segments.date >= '{$since}'
          AND campaign.advertising_channel_type = 'SEARCH'
        ORDER BY metrics.cost_micros DESC
        LIMIT 200
    ";
    return wc_ads_builder_api_search($query);
}

function wc_ads_builder_refresh_results_action() {
    if (!current_user_can('manage_options')) {
        wp_die('Geen toegang.');
    }
    check_admin_referer('wc_ads_builder_refresh_results');
    $range = max(1, min(90, (int) ($_POST['range'] ?? 14)));
    $ads = wc_ads_builder_fetch_ad_results($range);
    $keywords = wc_ads_builder_fetch_keyword_results($range);
    $result = array(
        'ok' => !is_wp_error($ads) && !is_wp_error($keywords),
        'range' => $range,
        'fetched_at' => current_time('mysql'),
        'ads' => is_wp_error($ads) ? array() : $ads,
        'keywords' => is_wp_error($keywords) ? array() : $keywords,
        'message' => is_wp_error($ads) ? $ads->get_error_message() : (is_wp_error($keywords) ? $keywords->get_error_message() : 'Resultaten opgehaald.'),
    );
    update_option('wc_ads_builder_last_results', $result, false);
    wp_safe_redirect(admin_url('admin.php?page=wc-ads-builder&tab=results&range=' . $range));
    exit;
}
add_action('admin_post_wc_ads_builder_refresh_results', 'wc_ads_builder_refresh_results_action');

function wc_ads_builder_variant_from_url($url) {
    $query = parse_url((string) $url, PHP_URL_QUERY);
    if (!$query) {
        return 'zonder-variant';
    }
    parse_str($query, $params);
    return sanitize_text_field($params['utm_content'] ?? 'zonder-variant');
}

function wc_ads_builder_campaign_from_url($url) {
    $query = parse_url((string) $url, PHP_URL_QUERY);
    if (!$query) {
        return '';
    }
    parse_str($query, $params);
    return sanitize_text_field($params['utm_campaign'] ?? '');
}

function wc_ads_builder_report_number($value, $decimals = 0) {
    return number_format((float) $value, $decimals, ',', '.');
}

function wc_ads_builder_keyword_payload($keyword) {
    $keyword = trim((string) $keyword);
    $match_type = 'BROAD';
    if (preg_match('/^\[(.+)\]$/', $keyword, $m)) {
        $keyword = $m[1];
        $match_type = 'EXACT';
    } elseif (preg_match('/^"(.+)"$/', $keyword, $m)) {
        $keyword = $m[1];
        $match_type = 'PHRASE';
    }
    return array(
        'text' => $keyword,
        'matchType' => $match_type,
    );
}

function wc_ads_builder_ad_text_assets($items) {
    return array_map(function ($text) {
        return array('text' => mb_substr((string) $text, 0, 90));
    }, array_values($items));
}

function wc_ads_builder_api_create_campaign($plan) {
    $settings = wc_ads_builder_settings();
    if (!$settings['developer_token'] || !$settings['customer_id']) {
        return new WP_Error('missing_google_ads_settings', 'Developer token of customer ID ontbreekt.');
    }
    $token = wc_ads_builder_access_token();
    if (is_wp_error($token)) {
        return $token;
    }

    $customer_id = preg_replace('/[^0-9]/', '', $settings['customer_id']);
    $budget_resource = 'customers/' . $customer_id . '/campaignBudgets/-1';
    $campaign_resource = 'customers/' . $customer_id . '/campaigns/-2';
    $ops = array();
    $ops[] = array('campaignBudgetOperation' => array('create' => array(
        'resourceName' => $budget_resource,
        'name' => $plan['campaign'] . ' | Budget ' . gmdate('YmdHis'),
        'amountMicros' => (string) ((int) round($plan['budget'] * 1000000)),
        'deliveryMethod' => 'STANDARD',
        'explicitlyShared' => false,
    )));
    $ops[] = array('campaignOperation' => array('create' => array(
        'resourceName' => $campaign_resource,
        'name' => $plan['campaign'] . ' | ' . gmdate('Y-m-d H:i'),
        'status' => 'PAUSED',
        'advertisingChannelType' => 'SEARCH',
        'campaignBudget' => $budget_resource,
        'manualCpc' => array(),
        'networkSettings' => array(
            'targetGoogleSearch' => true,
            'targetSearchNetwork' => false,
            'targetContentNetwork' => false,
            'targetPartnerSearchNetwork' => false,
        ),
    )));

    $ad_group_temp = -10;
    foreach ($plan['groups'] as $group_name => $group) {
        $ad_group_resource = 'customers/' . $customer_id . '/adGroups/' . $ad_group_temp;
        $ops[] = array('adGroupOperation' => array('create' => array(
            'resourceName' => $ad_group_resource,
            'name' => mb_substr($group_name, 0, 255),
            'campaign' => $campaign_resource,
            'status' => 'PAUSED',
            'type' => 'SEARCH_STANDARD',
            'cpcBidMicros' => '2500000',
        )));
        foreach ($group['keywords'] as $keyword) {
            $ops[] = array('adGroupCriterionOperation' => array('create' => array(
                'adGroup' => $ad_group_resource,
                'status' => 'ENABLED',
                'keyword' => wc_ads_builder_keyword_payload($keyword),
            )));
        }
        foreach ($plan['variants'] as $variant) {
            $headlines = array_values(array_unique(array_merge($group['headlines'], $variant['headlines'])));
            $descriptions = array_values(array_unique(array_merge($group['descriptions'], $variant['descriptions'])));
            $ops[] = array('adGroupAdOperation' => array('create' => array(
                'adGroup' => $ad_group_resource,
                'status' => 'PAUSED',
                'ad' => array(
                    'finalUrls' => array(wc_ads_builder_final_url($plan, $group_name, $variant)),
                    'responsiveSearchAd' => array(
                        'headlines' => wc_ads_builder_ad_text_assets($headlines),
                        'descriptions' => wc_ads_builder_ad_text_assets($descriptions),
                        'path1' => 'woningcheck',
                        'path2' => sanitize_title($plan['city']),
                    ),
                ),
            )));
        }
        $ad_group_temp--;
    }

    foreach ($plan['negatives'] as $negative) {
        $ops[] = array('campaignCriterionOperation' => array('create' => array(
            'campaign' => $campaign_resource,
            'negative' => true,
            'keyword' => array(
                'text' => mb_substr($negative, 0, 80),
                'matchType' => 'BROAD',
            ),
        )));
    }

    $url = 'https://googleads.googleapis.com/' . rawurlencode($settings['api_version']) . '/customers/' . rawurlencode($customer_id) . '/googleAds:mutate';
    $response = wp_remote_post($url, array(
        'timeout' => 40,
        'headers' => wc_ads_builder_api_headers($token, true),
        'body' => wp_json_encode(array(
            'mutateOperations' => $ops,
            'partialFailure' => false,
            'validateOnly' => false,
        )),
    ));
    if (is_wp_error($response)) {
        return $response;
    }
    $code = wp_remote_retrieve_response_code($response);
    $body_raw = wp_remote_retrieve_body($response);
    $body = json_decode($body_raw, true);
    if ($code < 200 || $code >= 300) {
        return new WP_Error('google_ads_create_failed', 'Google Ads API HTTP ' . $code . ': ' . $body_raw);
    }
    return $body;
}

function wc_ads_builder_create_action() {
    if (!current_user_can('manage_options')) {
        wp_die('Geen toegang.');
    }
    check_admin_referer('wc_ads_builder_create');
    $plan = wc_ads_builder_plan($_POST);
    $result = wc_ads_builder_api_create_campaign($plan);
    set_transient('wc_ads_builder_create_result', is_wp_error($result) ? array('ok' => false, 'message' => $result->get_error_message()) : array('ok' => true, 'message' => wp_json_encode($result)), 300);
    wp_safe_redirect(admin_url('admin.php?page=wc-ads-builder&domain=' . rawurlencode($plan['domain']) . '&city=' . rawurlencode($plan['city']) . '&budget=' . rawurlencode($plan['budget']) . '&focus=' . rawurlencode($plan['focus'])));
    exit;
}
add_action('admin_post_wc_ads_builder_create', 'wc_ads_builder_create_action');

function wc_ads_builder_api_test_action() {
    if (!current_user_can('manage_options')) {
        wp_die('Geen toegang.');
    }
    check_admin_referer('wc_ads_builder_api_test');
    $result = wc_ads_builder_api_get_accessible_customers();
    set_transient('wc_ads_builder_api_last_test', is_wp_error($result) ? array('ok' => false, 'message' => $result->get_error_message()) : array('ok' => true, 'message' => wp_json_encode($result)), 300);
    wp_safe_redirect(admin_url('admin.php?page=wc-ads-builder&tab=api'));
    exit;
}
add_action('admin_post_wc_ads_builder_api_test', 'wc_ads_builder_api_test_action');

function wc_ads_builder_textarea($label, $items) {
    echo '<h3>' . esc_html($label) . '</h3>';
    echo '<textarea readonly rows="7" class="large-text code">' . esc_textarea(implode("\n", $items)) . '</textarea>';
}

function wc_ads_builder_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Geen toegang.');
    }
    $settings = wc_ads_builder_settings();
    $domains = wc_ads_builder_domains();
    $tab = sanitize_key($_GET['tab'] ?? 'builder');
    $domain = sanitize_text_field($_POST['domain'] ?? $_GET['domain'] ?? 'warmtepompdenhaag.nl');
    $city = sanitize_text_field($_POST['city'] ?? ($domains[$domain]['city'] ?? 'Den Haag'));
    $budget = sanitize_text_field($_POST['budget'] ?? $_GET['budget'] ?? $settings['daily_budget']);
    $focus = sanitize_key($_POST['focus'] ?? $_GET['focus'] ?? 'advies');
    $plan = wc_ads_builder_plan(array('city' => $city, 'domain' => $domain, 'budget' => $budget, 'focus' => $focus));
    ?>
    <div class="wrap">
        <h1>Vakvriend Ads Builder</h1>
        <h2 class="nav-tab-wrapper">
            <a class="nav-tab <?php echo $tab === 'builder' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url(admin_url('admin.php?page=wc-ads-builder')); ?>">Campagne bouwen</a>
            <a class="nav-tab <?php echo $tab === 'results' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url(admin_url('admin.php?page=wc-ads-builder&tab=results')); ?>">Resultaten & A/B</a>
            <a class="nav-tab <?php echo $tab === 'api' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url(admin_url('admin.php?page=wc-ads-builder&tab=api')); ?>">Google Ads API</a>
        </h2>

        <?php if ($tab === 'api'): ?>
            <form method="post" action="options.php" style="max-width:920px">
                <?php settings_fields('wc_ads_builder'); ?>
                <table class="form-table" role="presentation">
                    <tr><th><label>Developer token</label></th><td><input type="password" class="regular-text" name="wc_ads_builder_settings[developer_token]" value="<?php echo esc_attr($settings['developer_token']); ?>"></td></tr>
                    <tr><th><label>OAuth client ID</label></th><td><input type="text" class="large-text" name="wc_ads_builder_settings[client_id]" value="<?php echo esc_attr($settings['client_id']); ?>"></td></tr>
                    <tr><th><label>OAuth client secret</label></th><td><input type="password" class="regular-text" name="wc_ads_builder_settings[client_secret]" value="<?php echo esc_attr($settings['client_secret']); ?>"></td></tr>
                    <tr><th><label>Refresh token</label></th><td><input type="password" class="large-text" name="wc_ads_builder_settings[refresh_token]" value="<?php echo esc_attr($settings['refresh_token']); ?>"></td></tr>
                    <tr><th><label>Login customer ID</label></th><td><input type="text" class="regular-text" name="wc_ads_builder_settings[login_customer_id]" value="<?php echo esc_attr($settings['login_customer_id']); ?>"><p class="description">Alleen nodig als je via een manager account werkt. Zonder streepjes.</p></td></tr>
                    <tr><th><label>Customer ID</label></th><td><input type="text" class="regular-text" name="wc_ads_builder_settings[customer_id]" value="<?php echo esc_attr($settings['customer_id']); ?>"><p class="description">Het Ads account waarin campagnes moeten komen. Zonder streepjes.</p></td></tr>
                    <tr><th><label>Standaard dagbudget</label></th><td><input type="number" step="0.01" min="1" class="small-text" name="wc_ads_builder_settings[daily_budget]" value="<?php echo esc_attr($settings['daily_budget']); ?>"> euro</td></tr>
                    <tr><th><label>API versie</label></th><td><input type="text" class="small-text" name="wc_ads_builder_settings[api_version]" value="<?php echo esc_attr($settings['api_version']); ?>"></td></tr>
                </table>
                <?php submit_button('API instellingen opslaan'); ?>
            </form>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('wc_ads_builder_api_test'); ?>
                <input type="hidden" name="action" value="wc_ads_builder_api_test">
                <?php submit_button('Test API toegang', 'secondary'); ?>
            </form>
            <?php $last = get_transient('wc_ads_builder_api_last_test'); if ($last): ?>
                <div class="notice <?php echo !empty($last['ok']) ? 'notice-success' : 'notice-error'; ?>"><p><strong>API test:</strong> <code><?php echo esc_html($last['message']); ?></code></p></div>
            <?php endif; ?>
            <p>Automatisch campagne-aanmaken blijft bewust gepauzeerd-by-default. Zodra OAuth werkt, voegen we de knop toe om de gegenereerde structuur als gepauzeerde campagne te plaatsen.</p>
        <?php elseif ($tab === 'results'): ?>
            <?php
            $range = max(1, min(90, (int) ($_GET['range'] ?? 14)));
            $last_results = get_option('wc_ads_builder_last_results', array());
            $ads_rows = is_array($last_results) ? ($last_results['ads'] ?? array()) : array();
            $keyword_rows = is_array($last_results) ? ($last_results['keywords'] ?? array()) : array();
            $variant_metrics = array();
            foreach ($ads_rows as $row) {
                $final_urls = $row['adGroupAd']['ad']['finalUrls'] ?? array();
                $final_url = is_array($final_urls) ? (string) reset($final_urls) : '';
                $variant = wc_ads_builder_variant_from_url($final_url);
                $campaign_name = $row['campaign']['name'] ?? wc_ads_builder_campaign_from_url($final_url);
                if (!isset($variant_metrics[$variant])) {
                    $variant_metrics[$variant] = array('campaign' => $campaign_name, 'impressions' => 0, 'clicks' => 0, 'cost' => 0, 'conversions' => 0);
                }
                $metrics = $row['metrics'] ?? array();
                $variant_metrics[$variant]['impressions'] += (int) ($metrics['impressions'] ?? 0);
                $variant_metrics[$variant]['clicks'] += (int) ($metrics['clicks'] ?? 0);
                $variant_metrics[$variant]['cost'] += ((float) ($metrics['costMicros'] ?? 0)) / 1000000;
                $variant_metrics[$variant]['conversions'] += (float) ($metrics['conversions'] ?? 0);
            }
            ?>
            <form method="get" style="margin:16px 0 12px">
                <input type="hidden" name="page" value="wc-ads-builder">
                <input type="hidden" name="tab" value="results">
                <label>Periode:
                    <select name="range">
                        <?php foreach (array(1, 7, 14, 30, 90) as $days): ?>
                            <option value="<?php echo esc_attr($days); ?>" <?php selected($range, $days); ?>><?php echo esc_html($days); ?> dagen</option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <?php submit_button('Bekijken', 'secondary', '', false); ?>
            </form>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="margin-bottom:18px">
                <?php wp_nonce_field('wc_ads_builder_refresh_results'); ?>
                <input type="hidden" name="action" value="wc_ads_builder_refresh_results">
                <input type="hidden" name="range" value="<?php echo esc_attr($range); ?>">
                <?php submit_button('Google Ads resultaten ophalen', 'primary', 'submit', false); ?>
                <?php if (!empty($last_results['fetched_at'])): ?>
                    <span class="description">Laatst opgehaald: <?php echo esc_html($last_results['fetched_at']); ?>. <?php echo esc_html($last_results['message'] ?? ''); ?></span>
                <?php endif; ?>
            </form>

            <h2>A/B varianten uit Google Ads</h2>
            <table class="widefat striped" style="max-width:1100px;margin-bottom:24px">
                <thead><tr><th>Variant</th><th>Campagne</th><th>Vertoningen</th><th>Klikken</th><th>CTR</th><th>Kosten</th><th>Google conversies</th><th>Advies</th></tr></thead>
                <tbody>
                <?php if ($variant_metrics): foreach ($variant_metrics as $variant => $metric):
                    $ctr = $metric['impressions'] ? ($metric['clicks'] / $metric['impressions']) * 100 : 0;
                    $advice = $metric['clicks'] < 20 ? 'Nog te weinig data' : ($ctr < 3 ? 'Kop/zoekwoord aanscherpen' : 'Door laten lopen');
                    ?>
                    <tr>
                        <td><code><?php echo esc_html($variant); ?></code></td>
                        <td><?php echo esc_html($metric['campaign']); ?></td>
                        <td><?php echo esc_html((string) $metric['impressions']); ?></td>
                        <td><?php echo esc_html((string) $metric['clicks']); ?></td>
                        <td><?php echo esc_html(wc_ads_builder_report_number($ctr, 2)); ?>%</td>
                        <td>€<?php echo esc_html(wc_ads_builder_report_number($metric['cost'], 2)); ?></td>
                        <td><?php echo esc_html(wc_ads_builder_report_number($metric['conversions'], 1)); ?></td>
                        <td><?php echo esc_html($advice); ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="8">Nog geen Google Ads API-data opgehaald.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <h2>Zoekwoorden met kosten</h2>
            <table class="widefat striped" style="max-width:1200px">
                <thead><tr><th>Campagne</th><th>Advertentiegroep</th><th>Zoekwoord</th><th>Type</th><th>Vertoningen</th><th>Klikken</th><th>CTR</th><th>Kosten</th><th>Conversies</th><th>Actie</th></tr></thead>
                <tbody>
                <?php if ($keyword_rows): foreach (array_slice($keyword_rows, 0, 60) as $row):
                    $metrics = $row['metrics'] ?? array();
                    $clicks = (int) ($metrics['clicks'] ?? 0);
                    $cost = ((float) ($metrics['costMicros'] ?? 0)) / 1000000;
                    $conversions = (float) ($metrics['conversions'] ?? 0);
                    $ctr = isset($metrics['ctr']) ? ((float) $metrics['ctr']) * 100 : 0;
                    $action = ($clicks >= 5 && $conversions <= 0) ? 'Controleer zoekterm / bieding' : 'OK';
                    ?>
                    <tr>
                        <td><?php echo esc_html($row['campaign']['name'] ?? '-'); ?></td>
                        <td><?php echo esc_html($row['adGroup']['name'] ?? '-'); ?></td>
                        <td><code><?php echo esc_html($row['adGroupCriterion']['keyword']['text'] ?? '-'); ?></code></td>
                        <td><?php echo esc_html($row['adGroupCriterion']['keyword']['matchType'] ?? '-'); ?></td>
                        <td><?php echo esc_html((string) ($metrics['impressions'] ?? 0)); ?></td>
                        <td><?php echo esc_html((string) $clicks); ?></td>
                        <td><?php echo esc_html(wc_ads_builder_report_number($ctr, 2)); ?>%</td>
                        <td>€<?php echo esc_html(wc_ads_builder_report_number($cost, 2)); ?></td>
                        <td><?php echo esc_html(wc_ads_builder_report_number($conversions, 1)); ?></td>
                        <td><?php echo esc_html($action); ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="10">Nog geen zoekwoorddata opgehaald.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        <?php else: ?>
            <form method="post" style="display:grid;grid-template-columns:repeat(4,minmax(160px,1fr));gap:14px;max-width:1100px;align-items:end">
                <label>Domein<br><select name="domain" class="regular-text" onchange="this.form.submit()">
                    <?php foreach ($domains as $host => $meta): ?>
                        <option value="<?php echo esc_attr($host); ?>" <?php selected($domain, $host); ?>><?php echo esc_html($host); ?></option>
                    <?php endforeach; ?>
                </select></label>
                <label>Stad<br><input type="text" name="city" value="<?php echo esc_attr($city); ?>" class="regular-text"></label>
                <label>Dagbudget<br><input type="number" step="0.01" min="1" name="budget" value="<?php echo esc_attr($budget); ?>" class="small-text"> euro</label>
                <label>Focus<br><select name="focus" class="regular-text">
                    <?php foreach (wc_ads_builder_focus_options() as $key => $label): ?>
                        <option value="<?php echo esc_attr($key); ?>" <?php selected($focus, $key); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select></label>
                <?php submit_button('Plan genereren', 'primary', 'submit', false); ?>
            </form>
            <hr>
            <h2><?php echo esc_html($plan['campaign']); ?></h2>
            <p><strong>Final URL:</strong> <a href="<?php echo esc_url($plan['url']); ?>" target="_blank" rel="noopener"><?php echo esc_html($plan['url']); ?></a> · <strong>Budget:</strong> €<?php echo esc_html(number_format($plan['budget'], 2, ',', '.')); ?> per dag · <strong>Status:</strong> gepauzeerd bij aanmaak</p>
            <h2>A/B testopzet</h2>
            <table class="widefat striped" style="max-width:900px;margin-bottom:18px">
                <thead><tr><th>Variant</th><th>Hoek</th><th>UTM content</th><th>Voorbeeld URL</th></tr></thead>
                <tbody>
                <?php foreach ($plan['variants'] as $variant): ?>
                    <tr>
                        <td><?php echo esc_html($variant['label']); ?></td>
                        <td><?php echo esc_html($variant['angle']); ?></td>
                        <td><code><?php echo esc_html($variant['utm_content']); ?></code></td>
                        <td><code><?php echo esc_html(wc_ads_builder_final_url($plan, 'Warmtepomp ' . $plan['city'], $variant)); ?></code></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('wc_ads_builder_export'); ?>
                <input type="hidden" name="action" value="wc_ads_builder_export">
                <input type="hidden" name="domain" value="<?php echo esc_attr($plan['domain']); ?>">
                <input type="hidden" name="city" value="<?php echo esc_attr($plan['city']); ?>">
                <input type="hidden" name="budget" value="<?php echo esc_attr($plan['budget']); ?>">
                <input type="hidden" name="focus" value="<?php echo esc_attr($plan['focus']); ?>">
                <?php submit_button('Download CSV voor Google Ads Editor', 'secondary', 'submit', false); ?>
            </form>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return confirm('Campagne wordt gepauzeerd aangemaakt in Google Ads. Doorgaan?');">
                <?php wp_nonce_field('wc_ads_builder_create'); ?>
                <input type="hidden" name="action" value="wc_ads_builder_create">
                <input type="hidden" name="domain" value="<?php echo esc_attr($plan['domain']); ?>">
                <input type="hidden" name="city" value="<?php echo esc_attr($plan['city']); ?>">
                <input type="hidden" name="budget" value="<?php echo esc_attr($plan['budget']); ?>">
                <input type="hidden" name="focus" value="<?php echo esc_attr($plan['focus']); ?>">
                <?php submit_button('Maak gepauzeerd aan via Google Ads API', 'primary', 'submit', false); ?>
                <span class="description">Campagne, budget, advertentiegroepen, zoekwoorden, advertenties en negatieve zoekwoorden. Locatietargeting controleer je daarna in Google Ads.</span>
            </form>
            <?php $create = get_transient('wc_ads_builder_create_result'); if ($create): ?>
                <div class="notice <?php echo !empty($create['ok']) ? 'notice-success' : 'notice-error'; ?>"><p><strong>API aanmaak:</strong> <code><?php echo esc_html($create['message']); ?></code></p></div>
            <?php endif; ?>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:1200px">
                <?php foreach ($plan['groups'] as $group_name => $group): ?>
                    <div style="background:#fff;border:1px solid #ccd0d4;padding:16px;border-radius:6px">
                        <h2 style="margin-top:0"><?php echo esc_html($group_name); ?></h2>
                        <?php wc_ads_builder_textarea('Zoekwoorden', $group['keywords']); ?>
                        <?php wc_ads_builder_textarea('Koppen', $group['headlines']); ?>
                        <?php wc_ads_builder_textarea('Beschrijvingen', $group['descriptions']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div style="max-width:760px;margin-top:20px">
                <?php wc_ads_builder_textarea('Negatieve zoekwoorden', $plan['negatives']); ?>
                <?php wc_ads_builder_textarea('Highlights', $plan['callouts']); ?>
                <?php wc_ads_builder_textarea('Sitelinks', $plan['sitelinks']); ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
