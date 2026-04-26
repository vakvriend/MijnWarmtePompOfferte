<?php
/**
 * Set city-specific landmark hero images for campaign pages.
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

function vk_wiki_hero($url) {
    return str_replace('/330px-', '/1200px-', $url);
}

$images = array(
    'Warmtepomp Zaandam'       => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/9/9b/%E8%8D%B7%E5%85%B0%E8%B5%9E%E4%B8%B9_Holland_Zaandam_China_Xinjiang_Urumqi_Welcome_you_to_tour_t_-_panoramio_%282%29.jpg/330px-%E8%8D%B7%E5%85%B0%E8%B5%9E%E4%B8%B9_Holland_Zaandam_China_Xinjiang_Urumqi_Welcome_you_to_tour_t_-_panoramio_%282%29.jpg'),
    'Warmtepomp Zaanstad'      => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/7/7d/ZaandamAerial01.JPG/330px-ZaandamAerial01.JPG'),
    'Warmtepomp Krommenie'     => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/Krommenie_kerk.jpg/330px-Krommenie_kerk.jpg'),
    'Warmtepomp Alkmaar'       => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Exterieur%2C_zuidwest_gevel_-_Alkmaar_-_20420107_-_RCE.jpg/330px-Exterieur%2C_zuidwest_gevel_-_Alkmaar_-_20420107_-_RCE.jpg'),
    'Warmtepomp Wormerveer'    => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/6/66/Wormerveer_021.JPG/330px-Wormerveer_021.JPG'),
    'Warmtepomp Den Haag'      => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Binnenhof_luchtfoto.jpg/330px-Binnenhof_luchtfoto.jpg'),
    'Warmtepomp Almere'        => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Skyline_Almere.jpg/330px-Skyline_Almere.jpg'),
    'Warmtepomp Hilversum'     => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/2/26/Raadhuis_Hilversum2022.jpg/330px-Raadhuis_Hilversum2022.jpg'),
    'Warmtepomp Haarlem'       => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/Spaarne_met_Teylers_Museum%2C_de_Waag_en_de_Bavo.jpg/330px-Spaarne_met_Teylers_Museum%2C_de_Waag_en_de_Bavo.jpg'),
    'Warmtepomp Amstelveen'    => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/c/cc/Cobra-museum.jpg/330px-Cobra-museum.jpg'),
    'Warmtepomp Purmerend'     => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/5/59/Purmerend-22-jun-2014.jpg/330px-Purmerend-22-jun-2014.jpg'),
    'Warmtepomp Kennemerland'  => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/2/22/Duinen_in_Nationaal_Park_Zuid-Kennemerland.jpg/330px-Duinen_in_Nationaal_Park_Zuid-Kennemerland.jpg'),
    'Warmtepomp Heerhugowaard' => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Heilig_Hart_van_Jezus_kerk_Dessing_De_Noord.JPG/330px-Heilig_Hart_van_Jezus_kerk_Dessing_De_Noord.JPG'),
    'Warmtepomp Landsmeer'     => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/c/c3/Landsmeer%2C_restaurant_de_Pepermolen_en_de_Nederlands_Hervormde_kerk_RM23823_foto2_2014-05-25_09.38.jpg/330px-Landsmeer%2C_restaurant_de_Pepermolen_en_de_Nederlands_Hervormde_kerk_RM23823_foto2_2014-05-25_09.38.jpg'),
    'Warmtepomp Wormerland'    => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/a/a7/Pakhuizen_Wormer.jpg/330px-Pakhuizen_Wormer.jpg'),
    'Warmtepomp Gouda'         => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/c/cf/Gouda_vanuit_de_lucht.jpg/330px-Gouda_vanuit_de_lucht.jpg'),
    'Warmtepomp Delft'         => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/c/c2/Delft_Blick_von_der_Nieuwe_Kerk_auf_die_Oude_Kerk_1.jpg/330px-Delft_Blick_von_der_Nieuwe_Kerk_auf_die_Oude_Kerk_1.jpg'),
    'Warmtepomp Nieuwegein'    => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/e/e4/Gem-Nieuwegein-OpenTopo.jpg/330px-Gem-Nieuwegein-OpenTopo.jpg'),
    'Warmtepomp Zoetermeer'    => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Luchtsmal.JPG/330px-Luchtsmal.JPG'),
    'Warmtepomp Amersfoort'    => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/3/39/Koppelpoort_Amersfoort_Cropped.jpg/330px-Koppelpoort_Amersfoort_Cropped.jpg'),
    'Warmtepomp Dordrecht'     => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/6/64/Dordrecht%2C_Grote_Kerk_foto2_2010-06-27_17.48.JPG/330px-Dordrecht%2C_Grote_Kerk_foto2_2010-06-27_17.48.JPG'),
    'Warmtepomp Hoorn'         => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/8/82/Hoorn%2C_the_square_%22Roode_Steen%22_with_the_Westfries_Museum.jpg/330px-Hoorn%2C_the_square_%22Roode_Steen%22_with_the_Westfries_Museum.jpg'),
    'Warmtepomp Den Helder'    => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/Fort_Kijkduin1.jpg/330px-Fort_Kijkduin1.jpg'),
    'Warmtepomp Apeldoorn'     => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Paleis_Het_Loo_Apeldoorn.jpg/330px-Paleis_Het_Loo_Apeldoorn.jpg'),
    'Warmtepomp Zeist'         => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/7/78/Zeist%2C_slot_Zeist_voorkant_foto2_2007-06-02_13.37.JPG/330px-Zeist%2C_slot_Zeist_voorkant_foto2_2007-06-02_13.37.JPG'),
    'Warmtepomp Achterhoek'    => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/a/a1/Huis_Bergh.JPG/330px-Huis_Bergh.JPG'),
    'Warmtepomp Veluwe'        => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/f/fd/NP-VeluweZoom.jpg/330px-NP-VeluweZoom.jpg'),
    'Warmtepomp Den Bosch'     => vk_wiki_hero('https://upload.wikimedia.org/wikipedia/commons/thumb/8/85/De_grootste_kathedraal_van_Nederland%2C_de_Sint_Janskathedraal_in_%27s-Hertogenbosch.jpg/330px-De_grootste_kathedraal_van_Nederland%2C_de_Sint_Janskathedraal_in_%27s-Hertogenbosch.jpg'),
);

$special_images = array(
    'Warmtepomp Zonder Boiler' => 'https://www.qvantum.com/wp-content/uploads/2024/02/QAQH_2000px-e1737129072551.jpg',
    'Warmtepomp Vergelijken'   => 'https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/698669fbf18164e5304e45ef_DSC00884-2.webp',
    'Mijn Warmtepomp Offerte'  => 'https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/698669fbf18164e5304e45ef_DSC00884-2.webp',
    'Warmtepomp Qvantum'       => 'https://www.qvantum.com/wp-content/uploads/2024/02/QAQH_2000px-e1737129072551.jpg',
);

foreach ($images + $special_images as $title => $url) {
    $page = get_page_by_title($title, OBJECT, 'page');
    if (!$page) {
        echo 'Missing page: ' . $title . PHP_EOL;
        continue;
    }

    update_post_meta($page->ID, 'wc_hero_foto', esc_url_raw($url));
    echo 'Updated hero image: ' . $title . PHP_EOL;
}

if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}

echo 'Done.' . PHP_EOL;
