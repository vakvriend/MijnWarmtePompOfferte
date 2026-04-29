<?php
defined('ABSPATH') || exit;
?>

<main class="vk-legal-page">
  <section class="vk-legal-hero">
    <div class="vk-container">
      <a class="vk-legal-back" href="<?=esc_url(home_url('/'))?>">Terug naar de woningcheck</a>
      <h1><?=esc_html(get_the_title())?></h1>
      <p>Heldere informatie over hoe Vakvriend met aanvragen, gegevens en website-informatie omgaat.</p>
    </div>
  </section>

  <section class="vk-legal-content-section">
    <div class="vk-container">
      <article class="vk-legal-content">
        <?php
        while (have_posts()) {
            the_post();
            the_content();
        }
        ?>
      </article>
    </div>
  </section>
</main>
