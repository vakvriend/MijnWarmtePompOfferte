<?php
/**
 * Set local city-specific landmark hero images for campaign pages.
 *
 * Run from the WordPress root:
 * php /tmp/update-hero-landmark-images.php
 */

$wp_load = getcwd() . '/wp-load.php';
if (!file_exists($wp_load)) {
    fwrite(STDERR, "Run this script from the WordPress root.\n");
    exit(1);
}

require $wp_load;

$base = '/wp-content/themes/vakvriend-warmtepomp-campagne-v2/assets/img/landmarks/';
$version = '?v=2';
$images = array(
    'Warmtepomp Zonder Boiler' => 'warmtepomp-zonder-boiler.jpg',
    'Warmtepomp Zaanstad'      => 'warmtepomp-zaanstad.jpg',
    'Warmtepomp Krommenie'     => 'warmtepomp-krommenie.jpg',
    'Warmtepomp Zaandam'       => 'warmtepomp-zaandam.jpg',
    'Warmtepomp Alkmaar'       => 'warmtepomp-alkmaar.jpg',
    'Warmtepomp Wormerveer'    => 'warmtepomp-wormerveer.jpg',
    'Warmtepomp Den Haag'      => 'warmtepomp-den-haag.jpg',
    'Warmtepomp Vergelijken'   => 'warmtepomp-vergelijken.webp',
    'Mijn Warmtepomp Offerte'  => 'mijn-warmtepomp-offerte.webp',
    'Warmtepomp Almere'        => 'warmtepomp-almere.jpg',
    'Warmtepomp Hilversum'     => 'warmtepomp-hilversum.jpg',
    'Warmtepomp Haarlem'       => 'warmtepomp-haarlem.jpg',
    'Warmtepomp Qvantum'       => 'warmtepomp-qvantum.jpg',
    'Warmtepomp Amstelveen'    => 'warmtepomp-amstelveen.jpg',
    'Warmtepomp Aalsmeer'      => 'warmtepomp-amstelveen.jpg',
    'Warmtepomp Badhoevedorp'  => 'warmtepomp-haarlem.jpg',
    'Warmtepomp Diemen'        => 'warmtepomp-amstelveen.jpg',
    'Warmtepomp Ouderkerk'     => 'warmtepomp-amstelveen.jpg',
    'Warmtepomp Uithoorn'      => 'warmtepomp-amstelveen.jpg',
    'Warmtepomp Hoofddorp'     => 'warmtepomp-haarlem.jpg',
    'Warmtepomp Nieuw Vennep'  => 'warmtepomp-haarlem.jpg',
    'Warmtepomp Purmerend'     => 'warmtepomp-purmerend.jpg',
    'Warmtepomp Kennemerland'  => 'warmtepomp-kennemerland.jpg',
    'Warmtepomp Zaanstreek'    => 'warmtepomp-zaanstad.jpg',
    'Warmtepomp Heiloo'        => 'warmtepomp-alkmaar.jpg',
    'Warmtepomp Castricum'     => 'warmtepomp-kennemerland.jpg',
    'Warmtepomp Bloemendaal'   => 'warmtepomp-haarlem.jpg',
    'Warmtepomp Zandvoort'     => 'warmtepomp-kennemerland.jpg',
    'Warmtepomp IJmuiden'      => 'warmtepomp-kennemerland.jpg',
    'Warmtepomp Beverwijk'     => 'warmtepomp-kennemerland.jpg',
    'Warmtepomp Heerhugowaard' => 'warmtepomp-heerhugowaard.jpg',
    'Warmtepomp Landsmeer'     => 'warmtepomp-landsmeer.jpg',
    'Warmtepomp Wormerland'    => 'warmtepomp-wormerland.jpg',
    'Warmtepomp Bussum'        => 'warmtepomp-hilversum.jpg',
    'Warmtepomp Naarden'       => 'warmtepomp-hilversum.jpg',
    'Warmtepomp Huizen'        => 'warmtepomp-hilversum.jpg',
    'Warmtepomp Laren'         => 'warmtepomp-hilversum.jpg',
    'Warmtepomp Blaricum'      => 'warmtepomp-hilversum.jpg',
    'Warmtepomp Weesp'         => 'warmtepomp-hilversum.jpg',
    'Warmtepomp Gooi'          => 'warmtepomp-hilversum.jpg',
    'Warmtepomp Lelystad'      => 'warmtepomp-almere.jpg',
    'Warmtepomp Dronten'       => 'warmtepomp-almere.jpg',
    'Warmtepomp Gouda'         => 'warmtepomp-gouda.jpg',
    'Warmtepomp Delft'         => 'warmtepomp-delft.jpg',
    'Warmtepomp Nieuwegein'    => 'warmtepomp-nieuwegein.jpg',
    'Warmtepomp Zoetermeer'    => 'warmtepomp-zoetermeer.jpg',
    'Warmtepomp Amersfoort'    => 'warmtepomp-amersfoort.jpg',
    'Warmtepomp Dordrecht'     => 'warmtepomp-dordrecht.jpg',
    'Warmtepomp Hoorn'         => 'warmtepomp-hoorn.jpg',
    'Warmtepomp Den Helder'    => 'warmtepomp-den-helder.jpg',
    'Warmtepomp Apeldoorn'     => 'warmtepomp-apeldoorn.jpg',
    'Warmtepomp Zeist'         => 'warmtepomp-zeist.jpg',
    'Warmtepomp Achterhoek'    => 'warmtepomp-achterhoek.jpg',
    'Warmtepomp Veluwe'        => 'warmtepomp-veluwe.jpg',
    'Warmtepomp Den Bosch'     => 'warmtepomp-den-bosch.jpg',
);

foreach ($images as $title => $file) {
    $page = get_page_by_title($title, OBJECT, 'page');
    if (!$page) {
        echo 'Missing page: ' . $title . PHP_EOL;
        continue;
    }

    update_post_meta($page->ID, 'wc_hero_foto', esc_url_raw($base . $file . $version));
    echo 'Updated hero image: ' . $title . PHP_EOL;
}

if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}

echo 'Done.' . PHP_EOL;
