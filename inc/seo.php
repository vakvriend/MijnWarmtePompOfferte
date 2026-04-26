<?php
defined('ABSPATH') || exit;

function wc_current_canonical_url() {
    if (is_singular()) {
        return get_permalink();
    }

    return home_url(add_query_arg(array(), $GLOBALS['wp']->request ?? ''));
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

function wc_get_page_faqs($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $stad = wc_meta('wc_stad', 'Nederland', $post_id);
    $is_lokaal = ($stad !== 'Nederland' && $stad !== 'uw regio');

    $faqs = array(
        array(
            'question' => 'Wat is het verschil tussen Qvantum en Nibe?',
            'answer'   => 'Qvantum onderscheidt zich door de thermische batterij in alle systemen, zoals QA, QE en QG. Warm tapwater zit niet in een boilervat, maar wordt vers geleverd via een platenwisselaar die warmte uit de thermische batterij haalt. Nibe biedt meer modelkeuze inclusief hybride warmtepompen en bodemwarmtepompen. Vakvriend adviseert welk merk en model het beste past.',
        ),
        array(
            'question' => 'Hoeveel ISDE-subsidie kan ik ontvangen?',
            'answer'   => 'De subsidie hangt af van het type warmtepomp, merk, vermogen en energielabel. Vakvriend berekent het exacte bedrag in de offerte en begeleidt de aanvraag.',
        ),
        array(
            'question' => 'Is mijn woning' . ($is_lokaal ? ' in ' . $stad : '') . ' geschikt voor een warmtepomp?',
            'answer'   => 'De meeste woningen komen in aanmerking. Vakvriend beoordeelt tijdens een gratis adviesgesprek welk systeem technisch verstandig is voor uw situatie.',
        ),
        array(
            'question' => 'Regelt Vakvriend de subsidieaanvraag?',
            'answer'   => 'Ja, Vakvriend begeleidt de ISDE-subsidieaanvraag bij de RVO.',
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
            $faqs
        ),
    );

    return $graph;
}
add_filter('wpseo_schema_graph', 'wc_yoast_schema_graph');
