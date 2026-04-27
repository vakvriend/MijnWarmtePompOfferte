<?php
$telefoon = wc_meta('wc_telefoon','075 234 0001');
$whatsapp = wc_meta('wc_whatsapp','31752340001');
$email    = wc_meta('wc_email','info@vakvriend.nl');
$tel_clean = preg_replace('/\s+/','',$telefoon);
?>

<!-- CTA SECTIE -->
<section class="vk-section-cta">
  <div class="vk-container">
    <div class="vk-cta-inner">
      <h2>Klaar voor duidelijk warmtepompadvies?</h2>
      <p>Start met een gratis en vrijblijvende woningcheck. Vakvriend beoordeelt woning, comfortwens, systeemkeuze en ISDE-subsidie in één keer.</p>
      <div class="vk-cta-knoppen">
        <a href="#formulier" class="vk-btn vk-btn-wit vk-btn-lg">Start de woningcheck</a>
        <a href="https://wa.me/<?=esc_attr($whatsapp)?>" class="vk-btn vk-btn-wa vk-btn-lg">WhatsApp ons</a>
      </div>
      <p class="vk-cta-note">Of bel direct: <a href="tel:<?=esc_attr($tel_clean)?>"><?=esc_html($telefoon)?></a></p>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="vk-footer">
  <div class="vk-container">
    <div class="vk-footer-grid">
      <div class="vk-footer-brand">
        <img src="https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/697f2cea571f0e140638c77b_Middel%203%402x.webp" alt="Vakvriend" height="48" loading="lazy">
        <p>Gecertificeerd installatiebedrijf voor Qvantum en Nibe warmtepompen, CV-ketels, sanitair en meer.</p>
        <div class="vk-footer-merken">
          <img src="https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e60fe6fe0053da7edd61e_Qvantum-logo.avif" alt="Qvantum" height="16" loading="lazy">
          <img src="https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e60fe021869f07fdf55a8_nibe-logo-png_seeklogo-188975%20(1).avif" alt="Nibe" height="16" loading="lazy">
          <img src="https://cdn.prod.website-files.com/697e2b08c251f37c1879a259/699e60fe13061ed53e151aec_itho-daalderop-logo-png_seeklogo-266199%20(1).avif" alt="Itho Daalderop" height="16" loading="lazy">
        </div>
      </div>
      <div class="vk-footer-col">
        <h4>Vakvriend</h4>
        <ul>
          <li><a href="https://vakvriend.nl" target="_blank">Over Vakvriend</a></li>
          <li><a href="https://vakvriend.nl/services" target="_blank">Alle diensten</a></li>
          <li><a href="https://vakvriend.nl/services/warmtepompen" target="_blank">Warmtepompen</a></li>
          <li><a href="https://vakvriend.nl/about-us" target="_blank">Werkwijze</a></li>
          <li><a href="https://vakvriend.nl/contact" target="_blank">Contact</a></li>
        </ul>
      </div>
      <div class="vk-footer-col">
        <h4>Contact</h4>
        <ul>
          <li>📞 <a href="tel:<?=esc_attr($tel_clean)?>"><?=esc_html($telefoon)?></a></li>
          <li>💬 <a href="https://wa.me/<?=esc_attr($whatsapp)?>">WhatsApp</a></li>
          <li>✉️ <a href="mailto:<?=esc_attr($email)?>"><?=esc_html($email)?></a></li>
          <li>📍 Handelsweg 12K, Wormerveer</li>
        </ul>
        <div class="vk-footer-social">
          <a href="https://wa.me/<?=esc_attr($whatsapp)?>">WhatsApp</a>
          <a href="https://vakvriend.nl" target="_blank">Website</a>
        </div>
      </div>
    </div>
    <div class="vk-footer-bottom">
      <p>© <?=date('Y')?> Vakvriend Installatiebedrijf · <a href="<?=home_url('/privacy')?>">Privacy</a> · <a href="<?=home_url('/disclaimer')?>">Disclaimer</a></p>
      <p style="color:rgba(255,255,255,.3);font-size:12px">Gecertificeerd Qvantum & Nibe installateur · Door heel Nederland</p>
    </div>
  </div>
</footer>

<div class="vk-mobile-sticky" aria-label="Snelle acties">
  <a href="#formulier" class="vk-btn vk-btn-oranje">Woningcheck starten</a>
  <a href="tel:<?=esc_attr($tel_clean)?>" class="vk-btn vk-btn-groen">Bel direct</a>
</div>

<?php wp_footer(); ?>
</body>
</html>
