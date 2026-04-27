<?php
$stad      = wc_meta('wc_stad','uw regio');
$telefoon  = wc_meta('wc_telefoon','075 234 0001');
$whatsapp  = wc_meta('wc_whatsapp','31752340001');
$topbar    = wc_meta('wc_topbar_tekst',"Vrijblijvend warmtepompadvies — Qvantum & Nibe specialist — ISDE-subsidie");
$tel_clean = preg_replace('/\s+/','',$telefoon);
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
    <a href="https://www.vakvriend.nl/" class="vk-topbar-link" target="_blank" rel="noopener">Bekijk wat Vakvriend nog meer doet</a>
    <a href="#formulier" class="vk-topbar-btn">Vrijblijvende woningcheck</a>
  </div>
</div>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
  <div class="nav-container">
    <a href="<?php echo home_url(); ?>" class="nav-logo">
      <img src="https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/6987998276e5008c97c63119_logo-zwart.webp" alt="Vakvriend" width="140" height="40">
    </a>
    <ul class="nav-links">
      <li><a href="#waarom">Voordelen</a></li>
      <li><a href="#vakvriend">Over Vakvriend</a></li>
      <li><a href="#calculator">Besparing</a></li>
      <li><a href="#werkwijze">Werkwijze</a></li>
      <li><a href="#faq">FAQ</a></li>
    </ul>
    <div class="nav-cta">
      <a href="tel:<?=esc_attr($tel_clean)?>" class="nav-tel-link"><?=esc_html($telefoon)?></a>
      <a href="#formulier" class="vk-btn vk-btn-groen nav-offerte-btn">Vrijblijvende woningcheck</a>
    </div>
  </div>
</nav>
