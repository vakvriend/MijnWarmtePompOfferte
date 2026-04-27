<?php
/**
 * Fill campaign page meta from WordPress page titles.
 *
 * Run from the WordPress root:
 * php /tmp/update-campaign-page-meta.php
 */

$wp_load = getcwd() . '/wp-load.php';
if (!file_exists($wp_load)) {
    fwrite(STDERR, "Run this script from the WordPress root.\n");
    exit(1);
}
require $wp_load;

$pages = get_posts(array(
    'post_type'      => 'page',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'ID',
    'order'          => 'ASC',
));

$regions = array(
    'Zaandam'    => 'de Zaanstreek',
    'Zaanstad'   => 'de Zaanstreek',
    'Krommenie'  => 'de Zaanstreek',
    'Wormerveer' => 'de Zaanstreek',
    'Alkmaar'    => 'de regio Alkmaar',
    'Den Haag'   => 'de regio Den Haag',
    'Almere'     => 'de regio Almere',
    'Hilversum'  => 'het Gooi',
    'Haarlem'    => 'Kennemerland',
);

function vk_campaign_city_from_title($title) {
    $title = trim(wp_strip_all_tags((string) $title));
    if (preg_match('/^Warmtepomp\\s+(.+)$/i', $title, $m)) {
        $city = trim($m[1]);
        if (!in_array(strtolower($city), array('vergelijken', 'qvantum'), true)) {
            return $city;
        }
    }

    return 'Nederland';
}

function vk_campaign_update($post_id, $data) {
    foreach ($data as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }
}

foreach ($pages as $page) {
    if (!preg_match('/warmtepomp/i', $page->post_title)) {
        continue;
    }

    $city = vk_campaign_city_from_title($page->post_title);
    $is_local = $city !== 'Nederland';
    $region = $is_local ? ($GLOBALS['regions'][$city] ?? $city . ' en omgeving') : 'heel Nederland';
    $city_label = $is_local ? $city : 'Nederland';
    $in_city = $is_local ? ' in ' . $city : '';
    $region_label = $is_local ? $region : 'heel Nederland';

    $base = array(
        'wc_stad'              => $city_label,
        'wc_regio'             => $region,
        'wc_telefoon'          => '075 234 0001',
        'wc_whatsapp'          => '31752340001',
        'wc_email'             => 'info@vakvriend.nl',
        'wc_ai_content_status' => 'generated-v2',
        'wc_hero_kicker'       => $is_local ? 'Gratis subsidiecheck en advies in ' . $city : 'Gratis subsidiecheck en warmtepomp advies',
        'wc_hero_titel'        => $is_local ? 'Ontdek welke warmtepomp past bij uw woning in ' . $city : 'Ontdek welke warmtepomp bij uw woning past',
        'wc_hero_subtitel'     => 'Vertel ons kort over uw woning en wensen. Vakvriend beoordeelt welk systeem past, wat de ISDE-subsidie doet en welke installatie praktisch haalbaar is.',
        'wc_topbar_tekst'      => ($is_local ? $city . ': ' : '') . 'gratis warmtepomp subsidiecheck - reactie binnen 24 uur',
        'wc_form_titel'        => $is_local ? 'Gratis woningcheck voor ' . $city : 'Gratis warmtepomp woningcheck',
        'wc_form_subtitel'     => 'Beantwoord 4 korte vragen over uw woning, verbruik en wensen. U ontvangt praktisch advies met subsidiecheck.',
        'wc_form_benefits'     => "Gratis woningcheck\nWensen en comfort besproken\nAdvies binnen 24 uur",
        'wc_campaign_proof'    => "Gratis|woningcheck en advies\nISDE|subsidiecheck inbegrepen\n24u|reactie op uw aanvraag",
        'wc_meta_title'        => ($is_local ? 'Warmtepomp ' . $city : 'Warmtepomp offerte') . ' | Gratis subsidiecheck | Vakvriend',
        'wc_meta_desc'         => 'Vraag gratis warmtepomp advies aan' . $in_city . '. Vakvriend berekent subsidie, besparing en installatiekosten voor Qvantum, Nibe en hybride systemen.',
        'wc_focus_keyword'     => $is_local ? 'warmtepomp ' . strtolower($city) : 'warmtepomp offerte',
        'wc_vv_intro'          => 'Vakvriend helpt woningeigenaren' . $in_city . ' met eerlijk warmtepompadvies, vakkundige installatie en begeleiding bij ISDE-subsidie. We kijken eerst naar isolatie, warmteafgifte, verbruik en budget, zodat u een keuze maakt die technisch klopt.',
        'wc_vv_usp1'           => 'Gratis opname en subsidiecheck',
        'wc_vv_usp2'           => 'Qvantum, Nibe en hybride advies',
        'wc_vv_usp3'           => 'Duidelijk advies zonder verrassingen',
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
        'wc_faq_extra'         => ($is_local ? 'Werkt Vakvriend ook in ' . $city . '?:::Ja, Vakvriend helpt woningeigenaren in ' . $city . ' en ' . $region_label . ' met advies, offerte en installatie.' : ''),
    );

    if (stripos($page->post_title, 'Qvantum') !== false) {
        $base['wc_hero_titel'] = 'Qvantum warmtepomp advies aanvragen';
        $base['wc_hero_subtitel'] = 'Ontdek of een Qvantum warmtepomp met thermische batterij past bij uw woning. QA, QE en QG slaan warmte slim op en leveren warm tapwater vers via een platenwisselaar.';
        $base['wc_meta_title'] = 'Qvantum warmtepomp offerte | Vakvriend installateur';
        $base['wc_meta_desc'] = 'Vraag gratis Qvantum warmtepomp advies aan. Vakvriend berekent subsidie, besparing en installatiekosten voor QA, QE en QG systemen.';
        $base['wc_focus_keyword'] = 'qvantum warmtepomp';
    }

    if (stripos($page->post_title, 'Vergelijken') !== false) {
        $base['wc_hero_titel'] = 'Warmtepomp offertes vergelijken';
        $base['wc_hero_subtitel'] = 'Vergelijk warmtepompopties op subsidie, besparing, geluid, installatiekosten en geschiktheid voor uw woning.';
        $base['wc_meta_title'] = 'Warmtepomp offertes vergelijken | Gratis subsidiecheck';
        $base['wc_meta_desc'] = 'Vergelijk warmtepomp offertes en krijg gratis advies over subsidie, besparing en het juiste systeem voor uw woning.';
        $base['wc_focus_keyword'] = 'warmtepomp offertes vergelijken';
    }

    vk_campaign_update($page->ID, $base);
    update_post_meta($page->ID, '_yoast_wpseo_title', $base['wc_meta_title']);
    update_post_meta($page->ID, '_yoast_wpseo_metadesc', $base['wc_meta_desc']);
    update_post_meta($page->ID, '_yoast_wpseo_focuskw', $base['wc_focus_keyword']);
    echo 'Updated page ' . $page->ID . ' - ' . $page->post_title . PHP_EOL;
}

update_option('blogname', 'Gratis warmtepomp offerte | Vakvriend');
update_option('blogdescription', 'Gratis subsidiecheck, besparing en warmtepomp advies.');

delete_site_transient('wc_github_theme_release');
delete_site_transient('update_themes');
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}
