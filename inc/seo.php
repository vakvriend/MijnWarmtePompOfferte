<?php
defined('ABSPATH') || exit;

function wc_current_public_origin() {
    $host = $_SERVER['HTTP_HOST'] ?? parse_url((string) get_option('home'), PHP_URL_HOST);
    $host = strtolower(preg_replace('/[^a-z0-9\.\-:]/', '', (string) $host));
    $host = preg_replace('/:\d+$/', '', $host);

    if (!$host) {
        return home_url('/');
    }

    return 'https://' . $host;
}

function wc_current_public_path() {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $path = '/' . ltrim((string) $path, '/');
    return $path === '' ? '/' : $path;
}

function wc_current_public_url($path = null) {
    $path = $path === null ? wc_current_public_path() : '/' . ltrim((string) $path, '/');
    return trailingslashit(wc_current_public_origin() . $path);
}

function wc_rewrite_url_to_current_host($url) {
    if (!$url || is_admin()) {
        return $url;
    }

    $current_origin = untrailingslashit(wc_current_public_origin());
    $current_host = parse_url($current_origin, PHP_URL_HOST);
    $site_host = parse_url((string) get_option('home'), PHP_URL_HOST);

    if (!$current_host || !$site_host || $current_host === $site_host) {
        return $url;
    }

    $parts = wp_parse_url($url);
    if (!$parts || empty($parts['host'])) {
        return $url;
    }

    if (strtolower($parts['host']) !== strtolower($site_host)) {
        return $url;
    }

    $path = $parts['path'] ?? '/';
    $query = isset($parts['query']) ? '?' . $parts['query'] : '';
    $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';

    return $current_origin . $path . $query . $fragment;
}

function wc_rewrite_frontend_home_url($url) {
    return wc_rewrite_url_to_current_host($url);
}
add_filter('home_url', 'wc_rewrite_frontend_home_url', 20);

function wc_current_canonical_url() {
    return wc_current_public_url();
}

function wc_output_canonical() {
    if (defined('WPSEO_VERSION')) {
        return;
    }

    if (!is_singular('page')) {
        return;
    }

    echo '<link rel="canonical" href="' . esc_url(wc_current_canonical_url()) . '">' . "\n";
}
add_action('wp_head', 'wc_output_canonical', 4);

function wc_redirect_primary_page_to_mapped_domain() {
    if (is_admin() || wp_doing_ajax() || !is_singular('page')) {
        return;
    }

    $site_host = parse_url((string) get_option('home'), PHP_URL_HOST);
    $current_host = parse_url(wc_current_public_origin(), PHP_URL_HOST);

    if (!$site_host || !$current_host || strtolower($site_host) !== strtolower($current_host)) {
        return;
    }

    global $wpdb;
    $mapping_table = $wpdb->prefix . 'dms_mappings';
    $values_table = $wpdb->prefix . 'dms_mapping_values';
    $post_id = get_queried_object_id();

    $host = $wpdb->get_var($wpdb->prepare(
        "SELECT m.host
         FROM {$mapping_table} m
         INNER JOIN {$values_table} v ON v.mapping_id = m.id
         WHERE v.object_type = %s
           AND v.object_id = %d
           AND m.host <> %s
           AND m.host NOT LIKE %s
         ORDER BY m.host ASC
         LIMIT 1",
        'post',
        $post_id,
        $site_host,
        'www.%'
    ));

    if (!$host) {
        return;
    }

    status_header(301);
    header('Location: https://' . $host . '/', true, 301);
    exit;
}
add_action('template_redirect', 'wc_redirect_primary_page_to_mapped_domain', 1);

function wc_buffer_rewrite_mapped_page_paths() {
    if (is_admin() || wp_doing_ajax() || !is_singular('page')) {
        return;
    }

    $post = get_post(get_queried_object_id());
    if (!$post || empty($post->post_name)) {
        return;
    }

    $origin = untrailingslashit(wc_current_public_origin());
    $site_host = parse_url((string) get_option('home'), PHP_URL_HOST);
    $current_host = parse_url($origin, PHP_URL_HOST);

    if (!$site_host || !$current_host || strtolower($site_host) === strtolower($current_host)) {
        return;
    }

    ob_start(function ($html) use ($origin, $post) {
        $from = trailingslashit($origin . '/' . $post->post_name);
        $to = trailingslashit($origin);
        $html = str_replace($from, $to, $html);
        $html = str_replace(str_replace('/', '\/', $from), str_replace('/', '\/', $to), $html);
        $html = str_replace(rawurlencode($from), rawurlencode($to), $html);
        return $html;
    });
}
add_action('template_redirect', 'wc_buffer_rewrite_mapped_page_paths', 2);

function wc_yoast_canonical($url) {
    if (!is_singular('page')) {
        return wc_rewrite_url_to_current_host($url);
    }

    return wc_current_canonical_url();
}
add_filter('wpseo_canonical', 'wc_yoast_canonical', 20);

function wc_yoast_opengraph_url($url) {
    if (!is_singular('page')) {
        return wc_rewrite_url_to_current_host($url);
    }

    return wc_current_canonical_url();
}
add_filter('wpseo_opengraph_url', 'wc_yoast_opengraph_url', 20);

function wc_rewrite_schema_urls($value, $post_id = null) {
    if (is_array($value)) {
        foreach ($value as $key => $item) {
            $value[$key] = wc_rewrite_schema_urls($item, $post_id);
        }
        return $value;
    }

    if (!is_string($value)) {
        return $value;
    }

    $current_origin = untrailingslashit(wc_current_public_origin());
    $site_origin = untrailingslashit((string) get_option('home'));
    $canonical = untrailingslashit(wc_current_canonical_url());

    if ($post_id) {
        $permalink = untrailingslashit(get_permalink($post_id));
        $value = str_replace($permalink, $canonical, $value);
        $value = str_replace(trailingslashit($permalink), trailingslashit($canonical), $value);

        $post = get_post($post_id);
        if ($post && !empty($post->post_name)) {
            $mapped_path = trailingslashit($current_origin . '/' . $post->post_name);
            $value = str_replace($mapped_path, trailingslashit($canonical), $value);
            $value = str_replace(untrailingslashit($mapped_path), $canonical, $value);
        }
    }

    return str_replace($site_origin, $current_origin, $value);
}

function wc_get_page_faqs($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $stad = wc_meta('wc_stad', 'Nederland', $post_id);
    $is_lokaal = ($stad !== 'Nederland' && $stad !== 'uw regio');

    $faqs = array(
        array(
            'question' => 'Wat is het verschil tussen Qvantum en Nibe?',
            'answer'   => 'Qvantum werkt met thermische opslag en vers tapwater via een platenwisselaar. Nibe heeft een breed programma voor lucht/water, bodem/water en hybride installaties. De beste keuze hangt af van warmteverlies, tapwater, geluid, ruimte en afgiftesysteem.',
        ),
        array(
            'question' => 'Hoeveel ISDE-subsidie kan ik ontvangen?',
            'answer'   => 'De subsidie hangt af van toesteltype, vermogen, meldcode en de actuele voorwaarden van RVO. Vakvriend neemt de verwachte ISDE-subsidie apart op in de offerte en helpt bij de aanvraagstukken.',
        ),
        array(
            'question' => 'Is mijn woning' . ($is_lokaal ? ' in ' . $stad : '') . ' geschikt voor een warmtepomp?',
            'answer'   => 'Dat hangt af van isolatie, warmteverlies, radiatoren of vloerverwarming, tapwatergebruik en beschikbare ruimte. Vakvriend beoordeelt eerst de woning en vergelijkt daarna hybride, lucht/water, ventilatie of bodemwarmte.',
        ),
        array(
            'question' => 'Regelt Vakvriend de subsidieaanvraag?',
            'answer'   => 'Ja. Vakvriend neemt de verwachte ISDE-subsidie mee in de offerte en helpt bij de aanvraagstukken. Het definitieve subsidiebedrag hangt af van toestel, vermogen, meldcode en beoordeling door RVO.',
        ),
    );

    $extra = wc_meta('wc_faq_extra', '', $post_id);
    if ($extra) {
        foreach (explode("\n", $extra) as $row) {
            if (strpos($row, ':::') === false) {
                continue;
            }

            list($question, $answer) = explode(':::', $row, 2);
            $question = trim($question);
            $answer = trim($answer);

            if ($question && $answer) {
                $faqs[] = array(
                    'question' => $question,
                    'answer'   => $answer,
                );
            }
        }
    }

    return $faqs;
}

function wc_output_structured_data() {
    if (defined('WPSEO_VERSION')) {
        return;
    }

    if (!is_singular('page')) {
        return;
    }

    $post_id = get_the_ID();
    $stad = wc_meta('wc_stad', 'Nederland', $post_id);
    $telefoon = wc_meta('wc_telefoon', '075 234 0001', $post_id);
    $meta_desc = wc_meta('wc_meta_desc', '', $post_id);
    $title = wc_meta('wc_meta_title', get_the_title($post_id), $post_id);

    $schema = array(
        '@context' => 'https://schema.org',
        '@graph'   => array(
            array(
                '@type'       => 'LocalBusiness',
                '@id'         => home_url('/#organisatie'),
                'name'        => 'Vakvriend Installatiebedrijf',
                'url'         => home_url('/'),
                'telephone'   => $telefoon,
                'address'     => array(
                    '@type'           => 'PostalAddress',
                    'streetAddress'   => 'Handelsweg 12K',
                    'addressLocality' => 'Wormerveer',
                    'addressCountry'  => 'NL',
                ),
                'areaServed'  => $stad === 'Nederland' ? 'Nederland' : $stad,
            ),
            array(
                '@type'       => 'WebPage',
                '@id'         => get_permalink($post_id) . '#webpage',
                'url'         => get_permalink($post_id),
                'name'        => $title,
                'description' => $meta_desc,
                'isPartOf'    => array('@id' => home_url('/#website')),
                'about'       => array('@id' => home_url('/#organisatie')),
            ),
            array(
                '@type'      => 'FAQPage',
                '@id'        => get_permalink($post_id) . '#faq',
                'mainEntity' => array_map(
                    function ($faq) {
                        return array(
                            '@type'          => 'Question',
                            'name'           => $faq['question'],
                            'acceptedAnswer' => array(
                                '@type' => 'Answer',
                                'text'  => $faq['answer'],
                            ),
                        );
                    },
                    wc_get_page_faqs($post_id)
                ),
            ),
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'wc_output_structured_data', 20);

function wc_yoast_metadesc($description) {
    if ($description || !is_singular('page')) {
        return $description;
    }

    if (get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true)) {
        return $description;
    }

    return wc_meta('wc_meta_desc', '');
}
add_filter('wpseo_metadesc', 'wc_yoast_metadesc');

function wc_yoast_title($title) {
    if (!is_singular('page')) {
        return $title;
    }

    if (get_post_meta(get_the_ID(), '_yoast_wpseo_title', true)) {
        return $title;
    }

    return wc_meta('wc_meta_title', $title);
}
add_filter('wpseo_title', 'wc_yoast_title');

function wc_yoast_schema_graph($graph) {
    if (!is_singular('page')) {
        return $graph;
    }

    $post_id = get_the_ID();
    $faqs = wc_get_page_faqs($post_id);

    if (!$faqs) {
        return $graph;
    }

    $graph[] = array(
        '@type'      => 'FAQPage',
        '@id'        => wc_current_canonical_url() . '#faq',
        'mainEntity' => array_map(
            function ($faq) {
                return array(
                    '@type'          => 'Question',
                    'name'           => $faq['question'],
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text'  => $faq['answer'],
                    ),
                );
            },
            $faqs
        ),
    );

    return wc_rewrite_schema_urls($graph, $post_id);
}
add_filter('wpseo_schema_graph', 'wc_yoast_schema_graph', 99);
