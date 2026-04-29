<?php
/**
 * Create basic privacy and disclaimer pages for all mapped domains.
 *
 * Run from the WordPress root:
 * php wp-content/themes/vakvriend-warmtepomp-campagne-v2/scripts/create-legal-pages.php
 */

$wp_load = getcwd() . '/wp-load.php';
if (!file_exists($wp_load)) {
    fwrite(STDERR, "Run this script from the WordPress root.\n");
    exit(1);
}

require $wp_load;

function vk_upsert_legal_page($slug, $title, $content) {
    $page = get_page_by_path($slug, OBJECT, 'page');

    $post = array(
        'post_title'   => $title,
        'post_name'    => $slug,
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => $content,
    );

    if ($page) {
        $post['ID'] = $page->ID;
        $post_id = wp_update_post($post, true);
    } else {
        $post_id = wp_insert_post($post, true);
    }

    if (is_wp_error($post_id)) {
        throw new RuntimeException($post_id->get_error_message());
    }

    update_post_meta($post_id, '_yoast_wpseo_title', $title . ' | Vakvriend');
    update_post_meta($post_id, '_yoast_wpseo_metadesc', 'Lees de ' . strtolower($title) . ' van Vakvriend voor warmtepomp advies, woningcheck en installatie.');
    update_post_meta($post_id, 'wc_meta_title', $title . ' | Vakvriend');
    update_post_meta($post_id, 'wc_meta_desc', 'Lees de ' . strtolower($title) . ' van Vakvriend voor warmtepomp advies, woningcheck en installatie.');

    return (int) $post_id;
}

$privacy = <<<HTML
<!-- wp:heading -->
<h2>Privacyverklaring</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Vakvriend Installatiebedrijf verwerkt persoonsgegevens zorgvuldig. Wanneer u een woningcheck aanvraagt, gebruiken wij uw gegevens om contact met u op te nemen, uw aanvraag te beoordelen en warmtepompadvies te geven.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Welke gegevens verwerken wij?</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Wij kunnen naam, e-mailadres, telefoonnummer, adresgegevens, woningtype, gasverbruik, systeemvoorkeur en aanvullende opmerkingen verwerken. Deze gegevens worden alleen gebruikt voor advies, opvolging van uw aanvraag, planning en administratie.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Analytics en advertentiemeting</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Deze website gebruikt Google Tag Manager, Google Analytics en Google Ads om bezoek, formulierstappen en conversies te meten. Daarmee verbeteren wij campagnes en de werking van de website. Waar mogelijk gebruiken wij instellingen die privacyvriendelijk zijn.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Bewaartermijn en delen van gegevens</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Wij bewaren gegevens niet langer dan nodig is voor advies, uitvoering, administratie en wettelijke verplichtingen. Gegevens worden niet verkocht. Alleen noodzakelijke dienstverleners, zoals hosting, e-mail, analytics en advertentieplatformen, kunnen gegevens verwerken voor hun technische rol.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Uw rechten</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>U kunt vragen om inzage, correctie of verwijdering van uw persoonsgegevens. Neem daarvoor contact op via info@vakvriend.nl.</p>
<!-- /wp:paragraph -->
HTML;

$disclaimer = <<<HTML
<!-- wp:heading -->
<h2>Disclaimer</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>De informatie op deze website is bedoeld als algemene uitleg over warmtepompen, woningchecks, subsidie en installatie. Vakvriend doet haar best om de informatie actueel en zorgvuldig te houden, maar kan niet garanderen dat elke tekst volledig of foutloos is.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Geen definitief technisch advies zonder opname</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Een warmtepompadvies hangt af van isolatie, warmteverlies, afgiftesysteem, tapwatergebruik, geluid, elektra, ruimte en montagevoorwaarden. Definitief advies en prijzen kunnen pas worden gegeven na beoordeling van de woning en de installatiesituatie.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Subsidie en besparing</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Subsidiebedragen, besparingen en terugverdientijden zijn indicatief. De uiteindelijke ISDE-subsidie hangt af van toestel, meldcode, vermogen en beoordeling door RVO. Besparing hangt af van gebruik, energieprijzen en instellingen.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Aansprakelijkheid</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Vakvriend is niet aansprakelijk voor schade door gebruik van algemene informatie op deze website zonder persoonlijk advies of technische beoordeling. Links naar externe websites vallen buiten onze verantwoordelijkheid.</p>
<!-- /wp:paragraph -->
HTML;

$privacy_id = vk_upsert_legal_page('privacy', 'Privacyverklaring', $privacy);
$disclaimer_id = vk_upsert_legal_page('disclaimer', 'Disclaimer', $disclaimer);

if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}

echo 'Privacy page: ' . $privacy_id . PHP_EOL;
echo 'Disclaimer page: ' . $disclaimer_id . PHP_EOL;
echo 'Done.' . PHP_EOL;
