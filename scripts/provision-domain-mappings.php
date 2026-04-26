<?php
/**
 * Provision warmtepomp campaign pages and Domain Mapping System entries.
 *
 * Run from the WordPress root:
 * php /tmp/provision-domain-mappings.php
 */

$wp_load = getcwd() . '/wp-load.php';
if (!file_exists($wp_load)) {
    fwrite(STDERR, "Run this script from the WordPress root.\n");
    exit(1);
}

require $wp_load;

global $wpdb;

$domains = array(
    'warmtepompzonderboiler.nl'          => array('title' => 'Warmtepomp Zonder Boiler', 'city' => 'Nederland', 'region' => 'heel Nederland', 'type' => 'without_boiler'),
    'warmtepompzaanstad.nl'              => array('title' => 'Warmtepomp Zaanstad', 'city' => 'Zaanstad', 'region' => 'de Zaanstreek'),
    'warmtepompkrommenie.nl'             => array('title' => 'Warmtepomp Krommenie', 'city' => 'Krommenie', 'region' => 'de Zaanstreek'),
    'warmtepompzaandam.nl'               => array('title' => 'Warmtepomp Zaandam', 'city' => 'Zaandam', 'region' => 'de Zaanstreek'),
    'warmtepompalkmaar.nl'               => array('title' => 'Warmtepomp Alkmaar', 'city' => 'Alkmaar', 'region' => 'de regio Alkmaar'),
    'warmtepompwormerveer.nl'            => array('title' => 'Warmtepomp Wormerveer', 'city' => 'Wormerveer', 'region' => 'de Zaanstreek'),
    'warmtepompdenhaag.nl'               => array('title' => 'Warmtepomp Den Haag', 'city' => 'Den Haag', 'region' => 'de regio Den Haag'),
    'warmtepomp-offertes-vergelijken.nl' => array('title' => 'Warmtepomp Vergelijken', 'city' => 'Nederland', 'region' => 'heel Nederland', 'type' => 'compare'),
    'mijnwarmtepompofferte.nl'           => array('title' => 'Mijn Warmtepomp Offerte', 'city' => 'Nederland', 'region' => 'heel Nederland', 'type' => 'brand'),
    'warmtepompalmere.nl'                => array('title' => 'Warmtepomp Almere', 'city' => 'Almere', 'region' => 'de regio Almere'),
    'warmtepomphilversum.nl'             => array('title' => 'Warmtepomp Hilversum', 'city' => 'Hilversum', 'region' => 'het Gooi'),
    'haarlem-warmtepomp.nl'              => array('title' => 'Warmtepomp Haarlem', 'city' => 'Haarlem', 'region' => 'Kennemerland'),
    'warmtepompqvantum.nl'               => array('title' => 'Warmtepomp Qvantum', 'city' => 'Nederland', 'region' => 'heel Nederland', 'type' => 'qvantum'),
    'warmtepompamstelveen.nl'            => array('title' => 'Warmtepomp Amstelveen', 'city' => 'Amstelveen', 'region' => 'de regio Amstelveen'),
    'warmtepompaalsmeer.nl'              => array('title' => 'Warmtepomp Aalsmeer', 'city' => 'Aalsmeer', 'region' => 'de regio Amstelveen'),
    'warmtepompbadhoevedorp.nl'          => array('title' => 'Warmtepomp Badhoevedorp', 'city' => 'Badhoevedorp', 'region' => 'Haarlemmermeer'),
    'warmtepompdiemen.nl'                => array('title' => 'Warmtepomp Diemen', 'city' => 'Diemen', 'region' => 'de regio Amsterdam'),
    'warmtepompouderkerk.nl'             => array('title' => 'Warmtepomp Ouderkerk', 'city' => 'Ouderkerk', 'region' => 'de regio Amstelland'),
    'warmtepompuithoorn.nl'              => array('title' => 'Warmtepomp Uithoorn', 'city' => 'Uithoorn', 'region' => 'de regio Amstelland'),
    'warmtepomphoofddorp.nl'             => array('title' => 'Warmtepomp Hoofddorp', 'city' => 'Hoofddorp', 'region' => 'Haarlemmermeer'),
    'warmtepompnieuwvennep.nl'           => array('title' => 'Warmtepomp Nieuw Vennep', 'city' => 'Nieuw Vennep', 'region' => 'Haarlemmermeer'),
    'warmtepomppurmerend.nl'             => array('title' => 'Warmtepomp Purmerend', 'city' => 'Purmerend', 'region' => 'de regio Purmerend'),
    'warmtepompkennemerland.nl'          => array('title' => 'Warmtepomp Kennemerland', 'city' => 'Kennemerland', 'region' => 'Kennemerland'),
    'warmtepompzaanstreek.nl'            => array('title' => 'Warmtepomp Zaanstreek', 'city' => 'Zaanstreek', 'region' => 'de Zaanstreek'),
    'warmtepompheiloo.nl'                => array('title' => 'Warmtepomp Heiloo', 'city' => 'Heiloo', 'region' => 'de regio Alkmaar'),
    'warmtepompcastricum.nl'             => array('title' => 'Warmtepomp Castricum', 'city' => 'Castricum', 'region' => 'Kennemerland'),
    'warmtepompbloemendaal.nl'           => array('title' => 'Warmtepomp Bloemendaal', 'city' => 'Bloemendaal', 'region' => 'Kennemerland'),
    'warmtepompzandvoort.nl'             => array('title' => 'Warmtepomp Zandvoort', 'city' => 'Zandvoort', 'region' => 'Kennemerland'),
    'warmtepompijmuiden.nl'              => array('title' => 'Warmtepomp IJmuiden', 'city' => 'IJmuiden', 'region' => 'Kennemerland'),
    'warmtepompbeverwijk.nl'             => array('title' => 'Warmtepomp Beverwijk', 'city' => 'Beverwijk', 'region' => 'Kennemerland'),
    'warmtepompheerhugowaard.nl'         => array('title' => 'Warmtepomp Heerhugowaard', 'city' => 'Heerhugowaard', 'region' => 'de regio Heerhugowaard'),
    'warmtepomplandsmeer.nl'             => array('title' => 'Warmtepomp Landsmeer', 'city' => 'Landsmeer', 'region' => 'de regio Landsmeer'),
    'warmtepompwormerland.nl'            => array('title' => 'Warmtepomp Wormerland', 'city' => 'Wormerland', 'region' => 'de Zaanstreek'),
    'warmtepompbussum.nl'                => array('title' => 'Warmtepomp Bussum', 'city' => 'Bussum', 'region' => 'het Gooi'),
    'warmtepompnaarden.nl'               => array('title' => 'Warmtepomp Naarden', 'city' => 'Naarden', 'region' => 'het Gooi'),
    'warmtepomphuizen.nl'                => array('title' => 'Warmtepomp Huizen', 'city' => 'Huizen', 'region' => 'het Gooi'),
    'warmtepomplaren.nl'                 => array('title' => 'Warmtepomp Laren', 'city' => 'Laren', 'region' => 'het Gooi'),
    'warmtepompblaricum.nl'              => array('title' => 'Warmtepomp Blaricum', 'city' => 'Blaricum', 'region' => 'het Gooi'),
    'warmtepompweesp.nl'                 => array('title' => 'Warmtepomp Weesp', 'city' => 'Weesp', 'region' => 'het Gooi'),
    'warmtepompgooi.nl'                  => array('title' => 'Warmtepomp Gooi', 'city' => 'Gooi', 'region' => 'het Gooi'),
    'warmtepomplelystad.nl'              => array('title' => 'Warmtepomp Lelystad', 'city' => 'Lelystad', 'region' => 'Flevoland'),
    'warmtepompdronten.nl'               => array('title' => 'Warmtepomp Dronten', 'city' => 'Dronten', 'region' => 'Flevoland'),
    'warmtepompgouda.nl'                 => array('title' => 'Warmtepomp Gouda', 'city' => 'Gouda', 'region' => 'de regio Gouda'),
    'warmtepompdelft.nl'                 => array('title' => 'Warmtepomp Delft', 'city' => 'Delft', 'region' => 'de regio Delft'),
    'warmtepompnieuwegein.nl'            => array('title' => 'Warmtepomp Nieuwegein', 'city' => 'Nieuwegein', 'region' => 'de regio Nieuwegein'),
    'warmtepompzoetermeer.nl'            => array('title' => 'Warmtepomp Zoetermeer', 'city' => 'Zoetermeer', 'region' => 'de regio Zoetermeer'),
    'warmtepompamersfoort.nl'            => array('title' => 'Warmtepomp Amersfoort', 'city' => 'Amersfoort', 'region' => 'de regio Amersfoort'),
    'warmtepompdordrecht.nl'             => array('title' => 'Warmtepomp Dordrecht', 'city' => 'Dordrecht', 'region' => 'de regio Dordrecht'),
    'warmtepomphoorn.nl'                 => array('title' => 'Warmtepomp Hoorn', 'city' => 'Hoorn', 'region' => 'de regio Hoorn'),
    'warmtepompden-helder.nl'            => array('title' => 'Warmtepomp Den Helder', 'city' => 'Den Helder', 'region' => 'de regio Den Helder'),
    'warmtepompapeldoorn.nl'             => array('title' => 'Warmtepomp Apeldoorn', 'city' => 'Apeldoorn', 'region' => 'de Veluwe'),
    'warmtepompzeist.nl'                 => array('title' => 'Warmtepomp Zeist', 'city' => 'Zeist', 'region' => 'de regio Zeist'),
    'warmtepompachterhoek.nl'            => array('title' => 'Warmtepomp Achterhoek', 'city' => 'Achterhoek', 'region' => 'de Achterhoek'),
    'warmtepompveluwe.nl'                => array('title' => 'Warmtepomp Veluwe', 'city' => 'Veluwe', 'region' => 'de Veluwe'),
    'warmtepompdenbosch.nl'              => array('title' => 'Warmtepomp Den Bosch', 'city' => 'Den Bosch', 'region' => 'de regio Den Bosch'),
);

function vk_slug_from_title($title) {
    return sanitize_title($title);
}

function vk_find_or_create_page($title) {
    $existing = get_page_by_path(vk_slug_from_title($title), OBJECT, 'page');
    if ($existing) {
        return (int) $existing->ID;
    }

    $by_title = get_page_by_title($title, OBJECT, 'page');
    if ($by_title) {
        return (int) $by_title->ID;
    }

    $post_id = wp_insert_post(array(
        'post_title'   => $title,
        'post_name'    => vk_slug_from_title($title),
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => '',
    ), true);

    if (is_wp_error($post_id)) {
        throw new RuntimeException($post_id->get_error_message());
    }

    return (int) $post_id;
}

function vk_page_meta($spec) {
    $city = $spec['city'];
    $region = $spec['region'];
    $type = $spec['type'] ?? 'local';
    $is_local = !in_array($city, array('Nederland'), true);
    $in_city = $is_local ? ' in ' . $city : '';
    $region_label = $is_local ? $region : 'heel Nederland';

    $meta = array(
        'wc_stad'              => $city,
        'wc_regio'             => $region,
        'wc_telefoon'          => '075 234 0001',
        'wc_whatsapp'          => '31752340001',
        'wc_email'             => 'info@vakvriend.nl',
        'wc_ai_content_status' => 'generated-domain-provision-v1',
        'wc_hero_kicker'       => $is_local ? 'Gratis subsidiecheck en advies in ' . $city : 'Gratis subsidiecheck en warmtepomp advies',
        'wc_hero_titel'        => $is_local ? 'Warmtepomp offerte aanvragen in ' . $city : 'Gratis warmtepomp offerte aanvragen',
        'wc_hero_subtitel'     => 'Ontdek welk warmtepompsysteem past bij uw woning' . $in_city . '. Vakvriend berekent uw verwachte besparing, ISDE-subsidie en installatiekosten gratis en vrijblijvend.',
        'wc_topbar_tekst'      => ($is_local ? $city . ': ' : '') . 'gratis warmtepomp subsidiecheck - reactie binnen 24 uur',
        'wc_form_titel'        => $is_local ? 'Gratis advies voor uw woning in ' . $city : 'Ontvang uw gratis warmtepomp advies',
        'wc_form_subtitel'     => 'Beantwoord 4 korte vragen en ontvang een praktische subsidiecheck met persoonlijk advies.',
        'wc_form_benefits'     => "Gratis woningcheck\nSubsidiebedrag berekend\nOfferte binnen 24 uur",
        'wc_campaign_proof'    => "Binnen 24 uur|reactie op uw aanvraag\nISDE|subsidiecheck inbegrepen\n200+|installaties uitgevoerd",
        'wc_meta_title'        => ($is_local ? 'Warmtepomp ' . $city : 'Warmtepomp offerte') . ' | Gratis subsidiecheck | Vakvriend',
        'wc_meta_desc'         => 'Vraag gratis warmtepomp advies aan' . $in_city . '. Vakvriend berekent subsidie, besparing en installatiekosten voor Qvantum, Nibe en hybride systemen.',
        'wc_focus_keyword'     => $is_local ? 'warmtepomp ' . strtolower($city) : 'warmtepomp offerte',
        'wc_vv_intro'          => 'Vakvriend helpt woningeigenaren' . $in_city . ' met eerlijk warmtepompadvies, vakkundige installatie en begeleiding bij ISDE-subsidie. We kijken eerst naar isolatie, warmteafgifte, verbruik en budget, zodat u een keuze maakt die technisch klopt.',
        'wc_vv_usp1'           => 'Gratis opname en subsidiecheck',
        'wc_vv_usp2'           => 'Qvantum, Nibe en hybride advies',
        'wc_vv_usp3'           => 'Heldere offerte zonder verrassingen',
        'wc_vv_usp4'           => 'Installatie en nazorg door Vakvriend',
        'wc_qvantum_uitleg_titel' => 'Warm water zonder boilervat',
        'wc_qvantum_uitleg_tekst' => 'Qvantum bewaart warmte in de thermische batterij, niet in een groot vat met douchewater. Zet u de kraan open, dan stroomt koud leidingwater langs een platenwisselaar. Die haalt warmte uit de batterij en verwarmt het tapwater direct.',
        'wc_lokale_h2'         => $is_local ? 'Warmtepomp installateur in ' . $city : 'Warmtepomp advies voor uw woning',
        'wc_lokale_intro'      => 'Elke woning vraagt om een andere warmtepompoplossing. Daarom start Vakvriend met een praktische opname en een berekening van subsidie, besparing en installatiekosten.',
        'wc_lokale_alinea1'    => $is_local ? 'In ' . $city . ' en ' . $region_label . ' zien we veel woningen waar een hybride of lucht/water warmtepomp direct interessant kan zijn. Bij goed geisoleerde woningen kan volledig elektrisch vaak ook een logische stap zijn.' : 'Voor veel Nederlandse woningen is een hybride of lucht/water warmtepomp een logische eerste stap naar minder gasverbruik. Bij goede isolatie kan volledig elektrisch vaak direct interessant zijn.',
        'wc_lokale_alinea2'    => 'Vakvriend rekent niet met standaardpraatjes, maar met uw situatie: huidig gasverbruik, radiatoren of vloerverwarming, beschikbare ruimte, geluidspositie en de actuele ISDE-regels.',
        'wc_lokale_alinea3'    => 'Na de aanvraag ontvangt u snel duidelijkheid over het passende systeem, de verwachte investering en de subsidie die u kunt aanvragen.',
        'wc_lokale_faq_v1'     => $is_local ? 'Welke warmtepomp past bij mijn woning in ' . $city . '?' : 'Welke warmtepomp past bij mijn woning?',
        'wc_lokale_faq_a1'     => 'Dat hangt af van isolatie, warmteafgifte, verbruik en beschikbare ruimte. Vakvriend beoordeelt dit gratis en adviseert een passend systeem.',
        'wc_lokale_faq_v2'     => 'Kan Vakvriend de ISDE-subsidie meenemen?',
        'wc_lokale_faq_a2'     => 'Ja. We berekenen het verwachte subsidiebedrag en begeleiden de aanvraag, zodat u weet wat de netto investering wordt.',
        'wc_lokale_faq_v3'     => 'Hoe snel krijg ik reactie?',
        'wc_lokale_faq_a3'     => 'Na uw aanvraag neemt Vakvriend meestal binnen 1 werkdag contact op om uw woning en wensen door te nemen.',
        'wc_faq_extra'         => $is_local ? 'Werkt Vakvriend ook in ' . $city . '?:::Ja, Vakvriend helpt woningeigenaren in ' . $city . ' en ' . $region_label . ' met advies, offerte en installatie.' : '',
    );

    if ($type === 'qvantum') {
        $meta['wc_hero_titel'] = 'Qvantum warmtepomp offerte aanvragen';
        $meta['wc_hero_subtitel'] = 'Ontdek of een Qvantum warmtepomp met thermische batterij past bij uw woning. QA, QE en QG slaan warmte slim op en leveren warm tapwater vers via een platenwisselaar.';
        $meta['wc_meta_title'] = 'Qvantum warmtepomp offerte | Vakvriend installateur';
        $meta['wc_meta_desc'] = 'Vraag gratis Qvantum warmtepomp advies aan. Vakvriend berekent subsidie, besparing en installatiekosten voor QA, QE en QG systemen.';
        $meta['wc_focus_keyword'] = 'qvantum warmtepomp';
    } elseif ($type === 'compare') {
        $meta['wc_hero_titel'] = 'Warmtepomp offertes vergelijken';
        $meta['wc_hero_subtitel'] = 'Vergelijk warmtepompopties op subsidie, besparing, geluid, installatiekosten en geschiktheid voor uw woning.';
        $meta['wc_meta_title'] = 'Warmtepomp offertes vergelijken | Gratis subsidiecheck';
        $meta['wc_meta_desc'] = 'Vergelijk warmtepomp offertes en krijg gratis advies over subsidie, besparing en het juiste systeem voor uw woning.';
        $meta['wc_focus_keyword'] = 'warmtepomp offertes vergelijken';
    } elseif ($type === 'brand') {
        $meta['wc_hero_titel'] = 'Mijn warmtepomp offerte aanvragen';
        $meta['wc_hero_subtitel'] = 'Krijg helder warmtepompadvies van Vakvriend met subsidiecheck, besparingsberekening en een passende offerte voor uw woning.';
        $meta['wc_meta_title'] = 'Mijn warmtepomp offerte | Gratis subsidiecheck';
        $meta['wc_meta_desc'] = 'Vraag uw gratis warmtepomp offerte aan. Vakvriend berekent subsidie, besparing en installatiekosten voor uw woning.';
        $meta['wc_focus_keyword'] = 'warmtepomp offerte';
    } elseif ($type === 'without_boiler') {
        $meta['wc_hero_titel'] = 'Warmtepomp zonder boiler aanvragen';
        $meta['wc_hero_subtitel'] = 'Ontdek Qvantum warmtepompen met thermische batterij: warm tapwater via een platenwisselaar, zonder groot boilervat met stilstaand water.';
        $meta['wc_meta_title'] = 'Warmtepomp zonder boiler | Qvantum thermische batterij';
        $meta['wc_meta_desc'] = 'Vraag advies aan voor een warmtepomp zonder boilervat. Vakvriend legt Qvantum thermische batterij, subsidie en installatiekosten helder uit.';
        $meta['wc_focus_keyword'] = 'warmtepomp zonder boiler';
        $meta['wc_lokale_h2'] = 'Warmtepomp zonder groot boilervat';
        $meta['wc_lokale_alinea1'] = 'Qvantum gebruikt een thermische batterij voor warmteopslag. Warm tapwater wordt vers gemaakt via een platenwisselaar, waardoor er geen groot vat met stilstaand douchewater nodig is.';
    }

    return $meta;
}

function vk_update_page_meta($post_id, $meta) {
    foreach ($meta as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }

    update_post_meta($post_id, '_yoast_wpseo_title', $meta['wc_meta_title']);
    update_post_meta($post_id, '_yoast_wpseo_metadesc', $meta['wc_meta_desc']);
    update_post_meta($post_id, '_yoast_wpseo_focuskw', $meta['wc_focus_keyword']);
}

function vk_upsert_mapping($host, $post_id) {
    global $wpdb;

    $mapping_table = $wpdb->prefix . 'dms_mappings';
    $values_table = $wpdb->prefix . 'dms_mapping_values';

    $mapping_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$mapping_table} WHERE host = %s LIMIT 1", $host));
    if (!$mapping_id) {
        $wpdb->insert($mapping_table, array(
            'host'          => $host,
            'path'          => '',
            'attachment_id' => 0,
            'custom_html'   => '',
        ), array('%s', '%s', '%d', '%s'));
        $mapping_id = (int) $wpdb->insert_id;
    }

    $value_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$values_table} WHERE mapping_id = %d AND object_type = %s LIMIT 1", $mapping_id, 'post'));
    $row = array(
        'mapping_id'  => (int) $mapping_id,
        'object_id'   => (int) $post_id,
        'object_type' => 'post',
        'primary'     => 1,
    );

    if ($value_id) {
        $wpdb->update($values_table, $row, array('id' => (int) $value_id), array('%d', '%d', '%s', '%d'), array('%d'));
    } else {
        $wpdb->insert($values_table, $row, array('%d', '%d', '%s', '%d'));
    }
}

foreach ($domains as $host => $spec) {
    $post_id = vk_find_or_create_page($spec['title']);
    $content_status = (string) get_post_meta($post_id, 'wc_ai_content_status', true);
    if (strpos($content_status, 'seo-unique') !== 0) {
        vk_update_page_meta($post_id, vk_page_meta($spec));
    }
    vk_upsert_mapping($host, $post_id);
    vk_upsert_mapping('www.' . $host, $post_id);

    echo $host . ' -> page ' . $post_id . ' (' . $spec['title'] . ')' . PHP_EOL;
}

if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}

echo 'Done.' . PHP_EOL;
