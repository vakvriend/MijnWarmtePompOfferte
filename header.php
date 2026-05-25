<?php
$stad      = wc_meta('wc_stad','uw regio');
$telefoon  = wc_meta('wc_telefoon','075 234 0001');
$whatsapp  = wc_meta('wc_whatsapp','31752340001');
$is_airco  = function_exists('wc_is_airco_site') && wc_is_airco_site();
$topbar    = wc_meta('wc_topbar_tekst', $is_airco ? 'Airco offerte aanvragen - LG, Samsung, Mitsubishi en Daikin - installatie door Vakvriend' : "Vrijblijvend warmtepompadvies — Qvantum & Nibe specialist — ISDE-subsidie");
$tel_clean = preg_replace('/\s+/','',$telefoon);
$post      = get_post();
$is_legal  = $post && in_array($post->post_name, array('privacy', 'disclaimer'), true);
$is_preview = function_exists('wc_use_modern_landing') && wc_use_modern_landing();
$anchor_base = ($is_airco || $is_legal) ? home_url('/') : '';
$form_href = ($is_airco || $is_legal) ? home_url('/#offerte') : '#formulier';

if ($is_preview && !$is_legal) {
  $topbar = wc_meta('wc_topbar_tekst', $is_airco ? 'Airco offerte aanvragen - topmerken - montage door Vakvriend' : 'Vrijblijvende woningcheck - merkonafhankelijk advies - ISDE meegenomen');
}

$nav_links = ($is_preview && $is_airco)
  ? array(
      array('Offerte', $anchor_base . '#offerte'),
      array('Kosten', $anchor_base . '#kosten'),
      array('Merken', $anchor_base . '#merken'),
      array('Reviews', $anchor_base . '#reviews'),
    )
  : (($is_preview && !$is_legal)
  ? array(
      array('Woningcheck', '#formulier'),
      array('Kosten', '#kosten'),
      array('Qvantum', '#qvantum'),
      array('Routes', '#routes'),
      array('Reviews', '#reviews'),
      array('FAQ', '#faq'),
    )
  : array(
      array('Voordelen', $anchor_base . '#waarom'),
      array('Over Vakvriend', $anchor_base . '#vakvriend'),
      array('Besparing', $anchor_base . '#calculator'),
      array('Werkwijze', $anchor_base . '#werkwijze'),
      array('FAQ', $anchor_base . '#faq'),
    ));
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- TOPBAR -->
<div class="vk-topbar">
  <span class="vk-topbar-tekst"><?=esc_html($topbar)?></span>
  <div class="vk-topbar-actions">
    <a href="<?=esc_url(wc_theme_setting('topbar_link_url', 'https://www.vakvriend.nl/'))?>" class="vk-topbar-link" target="_blank" rel="noopener"><?=esc_html(wc_theme_setting('topbar_link_text', 'Bekijk wat Vakvriend nog meer doet'))?></a>
  </div>
</div>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
  <div class="nav-container">
    <a href="<?php echo home_url(); ?>" class="nav-logo">
      <img src="https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/6987998276e5008c97c63119_logo-zwart.webp" alt="Vakvriend" width="140" height="40">
    </a>
    <ul class="nav-links">
      <?php foreach ($nav_links as [$label, $href]): ?>
        <li><a href="<?=esc_url($href)?>"><?=esc_html($label)?></a></li>
      <?php endforeach; ?>
    </ul>
    <div class="nav-cta">
      <?php if (!$is_airco): ?>
        <a href="<?php echo esc_url(home_url('/klant-dashboard/')); ?>" class="nav-dashboard-link">Klantdashboard</a>
      <?php endif; ?>
      <a href="tel:<?=esc_attr($tel_clean)?>" class="nav-tel-link"><?=esc_html($telefoon)?></a>
      <a href="<?=esc_url($form_href)?>" class="vk-btn vk-btn-groen nav-offerte-btn"><?=esc_html($is_airco ? 'Vraag offerte aan' : 'Vrijblijvende woningcheck')?></a>
    </div>
  </div>
</nav>
