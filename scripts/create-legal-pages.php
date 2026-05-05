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
<p>Vakvriend Installatiebedrijf gaat zorgvuldig om met persoonsgegevens. Deze privacyverklaring legt uit welke gegevens wij verwerken wanneer u onze website bezoekt, een woningcheck start, contact opneemt of gebruikmaakt van de online warmtepompcheck.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Wie is verantwoordelijk?</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Vakvriend Installatiebedrijf is verantwoordelijk voor de verwerking van persoonsgegevens via deze website en gekoppelde landingspagina's. Voor vragen over privacy kunt u contact opnemen via info@vakvriend.nl.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Welke gegevens verwerken wij?</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Wij kunnen onder meer naam, e-mailadres, telefoonnummer, adresgegevens, postcode, huisnummer, woningtype, energieverbruik, systeemvoorkeur, ventilatiegegevens, foto's, opmerkingen en technische woninginformatie verwerken. Deze gegevens gebruiken wij om uw woningcheck te beoordelen, contact met u op te nemen, advies te geven, vervolgvragen te stellen, planning voor te bereiden en administratie te voeren.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>De woningcheck kan worden aangeboden via een externe widget van HomeZero. Gegevens die u daarin invult kunnen door HomeZero technisch worden verwerkt om de check mogelijk te maken. Vakvriend gebruikt deze gegevens uitsluitend voor warmtepompadvies, opvolging en uitvoering van eventuele werkzaamheden.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Onbevestigde woningchecks</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Als u gegevens invult maar de woningcheck niet afrondt, kunnen wij de ingevulde velden tijdelijk opslaan als onbevestigde woningcheck. Dit helpt ons te begrijpen waar bezoekers afhaken en maakt het mogelijk om, wanneer u contactgegevens heeft ingevuld, de woningcheck zorgvuldig op te volgen of later te hervatten.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Wij proberen hierbij niet meer gegevens op te slaan dan nodig is. Het doel is verbetering van de website, betere opvolging van serieuze woningchecks en het voorkomen dat u opnieuw dezelfde gegevens moet invullen.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Analytics en advertentiemeting</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Deze website gebruikt Google Tag Manager, Google Analytics, Google Ads en Contentsquare om bezoek, formulierstappen, klikgedrag, scrollgedrag, technische fouten, heatmaps en sessie-opnames te meten. Daarmee verbeteren wij campagnes, pagina-inhoud en de werking van de woningcheck.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Wij meten onder andere paginaweergaven, klikgedrag, scroll diepte, apparaat en browser, campagnegegevens, formulierstappen en afhaakmomenten. Waar mogelijk gebruiken wij privacyvriendelijke instellingen en beperken of schermen wij gevoelige invoer af.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Waarom verwerken wij gegevens?</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Wij verwerken gegevens op basis van uw verzoek om advies of contact, ons gerechtvaardigd belang om de website en campagnes te verbeteren, en wettelijke verplichtingen voor administratie. Gegevens worden niet verkocht.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Delen van gegevens</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Alleen noodzakelijke dienstverleners kunnen gegevens verwerken voor hun technische rol. Denk aan hosting, WordPress, de woningcheck-widget, e-mail, sms, analytics, advertentieplatformen en beveiliging. Met deze partijen delen wij alleen wat nodig is voor de werking van de website, opvolging of rapportage.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Bewaartermijnen</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Wij bewaren gegevens niet langer dan nodig is voor advies, opvolging, uitvoering, administratie en wettelijke verplichtingen. Onbevestigde woningchecks en analyticsgegevens worden periodiek beoordeeld en waar mogelijk opgeschoond of geanonimiseerd.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Uw rechten</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>U kunt vragen om inzage, correctie, beperking of verwijdering van uw persoonsgegevens. Ook kunt u bezwaar maken tegen verwerking of vragen om overdracht van gegevens. Neem daarvoor contact op via info@vakvriend.nl. Bent u niet tevreden over de afhandeling, dan kunt u een klacht indienen bij de Autoriteit Persoonsgegevens.</p>
<!-- /wp:paragraph -->
HTML;

$disclaimer = <<<HTML
<!-- wp:heading -->
<h2>Disclaimer</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>De informatie op deze website is bedoeld als algemene uitleg over warmtepompen, woningchecks, subsidie, besparing en installatie. Vakvriend doet haar best om de informatie actueel en zorgvuldig te houden, maar kan niet garanderen dat elke tekst volledig, foutloos of op iedere woning van toepassing is.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Geen definitief technisch advies zonder opname</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Een warmtepompadvies hangt af van isolatie, warmteverlies, afgiftesysteem, tapwatergebruik, ventilatie, geluid, elektra, ruimte, vergunningen en montagevoorwaarden. De online woningcheck geeft richting, maar is geen definitieve technische opname. Definitieve keuzes, prijzen en uitvoering kunnen pas worden vastgesteld na beoordeling van de woning en installatiesituatie.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Subsidie en besparing</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Subsidiebedragen, besparingen en terugverdientijden zijn indicatief. De uiteindelijke ISDE-subsidie hangt af van toestel, meldcode, vermogen, actuele voorwaarden en beoordeling door RVO. Besparing hangt af van gebruik, energieprijzen, instellingen, comfortwens en woninggedrag.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Merken en productinformatie</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Merknamen zoals Qvantum, Nibe, Intergas, Itho Daalderop en Daikin worden gebruikt om mogelijke warmtepompoplossingen te duiden. Vakvriend werkt merkonafhankelijk. Productinformatie, garanties, subsidievoorwaarden en specificaties kunnen wijzigen.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Afbeeldingen en lokale pagina's</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Afbeeldingen, voorbeelden en lokale teksten zijn bedoeld om de dienstverlening herkenbaar uit te leggen. Zij vormen geen bewijs dat iedere getoonde situatie, installatie of uitkomst exact overeenkomt met uw woning.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Aansprakelijkheid</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Vakvriend is niet aansprakelijk voor schade door gebruik van algemene informatie op deze website zonder persoonlijk advies of technische beoordeling. Links naar externe websites, widgets en diensten van derden vallen buiten onze verantwoordelijkheid. Gebruik van deze website betekent dat u deze disclaimer accepteert.</p>
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
