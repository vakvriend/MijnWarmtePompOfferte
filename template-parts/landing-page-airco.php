<?php
defined('ABSPATH') || exit;

$ctx = function_exists('wc_landing_context') ? wc_landing_context() : array();
$telefoon = $ctx['telefoon'] ?? '075 234 0001';
$tel_clean = $ctx['tel_clean'] ?? preg_replace('/\s+/', '', $telefoon);
$whatsapp = preg_replace('/\D+/', '', (string) ($ctx['whatsapp'] ?? '31752340001'));
$whatsapp_url = 'https://wa.me/' . $whatsapp . '?text=' . rawurlencode('Ik wil graag een airco-offerte via Mijn Koelplan bespreken.');
$theme_uri = get_template_directory_uri();

$advisor_image = $theme_uri . '/assets/img/vakvriend-vragen-portret-clean.png';
$team_image = $theme_uri . '/assets/img/mijnkoelplan-header-vakvriend.jpg';
$execution_image = $theme_uri . '/assets/img/vakvriend-monteurs-lopen-card.webp';
$work_image = $theme_uri . '/assets/img/kostenindicatie-vakvriend-site.webp';
$detail_image = $theme_uri . '/assets/ads/source-vakvriend/699028f63fa7a5d7ac64c198_f421ef4b6949198afbe95557b9883f3d_DSC00752.webp';
$airco_inside_image = $theme_uri . '/assets/img/airco-binnenunit-advies.jpg';
$airco_outside_image = $theme_uri . '/assets/img/airco-buitenunit-gevel.jpg';
$airco_route_image = $theme_uri . '/assets/img/airco-buitenunit-leidingroute.jpg';

$checks = array(
  'Welke ruimte u wilt koelen of verwarmen',
  'Waar de buitenunit logisch en netjes kan staan',
  'Welke montage en afwerking nodig is',
  'Welke prijsrichting realistisch is',
);

$situations = array(
  array('Slaapkamer', 'Stil koelen tijdens warme nachten.'),
  array('Woonkamer', 'Meer vermogen en goede luchtverdeling.'),
  array('Meerdere kamers', 'Multi-split als één buitenunit slimmer is.'),
  array('Ook verwarmen', 'Bijverwarmen per ruimte in voor- en najaar.'),
);

$packages = array(
  array('Slaapkamer airco', 'vanaf circa €1.800', 'Stille single-split inclusief standaard montage.'),
  array('Woonkamer airco', 'circa €2.200 - €3.500', 'Meer vermogen, goede luchtverdeling en nette route.'),
  array('Multi-split', 'vanaf circa €4.000', 'Meerdere ruimtes met één passende buitenunit.'),
);

$steps = array(
  array('1', 'Aanvraag starten', 'U vult adres en e-mail in. Dat is genoeg om de situatie te openen.'),
  array('2', 'Vakvriend beoordeelt', 'We kijken naar ruimte, vermogen, buitenunit, leidingroute en afwerking.'),
  array('3', 'Voorstel op maat', 'U krijgt een duidelijke prijs met toestel, montage en vervolgstap.'),
  array('4', 'Netjes geplaatst', 'Na akkoord plannen we de installatie met gecertificeerde monteurs.'),
);

$advice_points = array(
  array($airco_inside_image, 'Binnenunit', 'Niet elke plek aan de muur is logisch. We kijken naar luchtstroom, hoogte, meubels, tochtgevoelige plekken en waar u de unit in het dagelijks gebruik het minst storend ziet.'),
  array($airco_outside_image, 'Buitenunit', 'De buitenunit bepaalt veel: geluid, trillingen, bereikbaarheid voor onderhoud, buren, zonbelasting en of de unit netjes geplaatst kan worden zonder rare oplossingen.'),
  array($airco_route_image, 'Leidingroute', 'De route tussen binnen en buiten bepaalt vaak het verschil tussen een strakke montage en zichtbaar gootwerk. Daarom nemen we doorvoer, condensafvoer en elektra direct mee.'),
);

$faqs = array(
  array('Is dit een vergelijkingssite?', 'Nee. Uw aanvraag gaat naar Vakvriend voor deze airco-offerte. Niet naar meerdere partijen.'),
  array('Krijg ik meteen een vaste prijs?', 'U krijgt eerst een realistische prijsrichting en daarna een voorstel op basis van merk, vermogen, leidingroute, buitenunit en afwerking.'),
  array('Welke merken nemen jullie mee?', 'LG, Samsung, Mitsubishi Electric en Daikin. De keuze hangt af van geluid, ruimte, bediening, budget en beschikbaarheid.'),
);
?>

<main class="mkp-human">
  <section class="mkp-human-hero" id="offerte">
    <div class="mkp-human-shell">
      <div class="mkp-human-grid">
        <div class="mkp-human-copy">
          <p class="mkp-human-kicker">Voor wie eerst duidelijkheid wil</p>
          <h1>Airco laten plaatsen zonder gedoe achteraf.</h1>
          <p class="mkp-human-lead">U krijgt eerst helder wat bij uw woning past: merk, vermogen, buitenunit, leidingroute, afwerking en prijsrichting. Pas daarna beslist u of u verder wilt.</p>

          <div class="mkp-human-advisor">
            <figure>
              <img src="<?=esc_url($advisor_image)?>" alt="Vakvriend adviseur" loading="eager">
            </figure>
            <div>
              <strong>Een Vakvriend kijkt persoonlijk mee.</strong>
              <span>Geen doorschuifloket. Geen aanvraag naar vijf bedrijven. Gewoon één nette offerte van Vakvriend.</span>
              <a href="https://vakvriend.nl/" target="_blank" rel="noopener">Bekijk Vakvriend.nl</a>
            </div>
          </div>

          <div class="mkp-human-explain">
            <strong>Wat gebeurt er na uw aanvraag?</strong>
            <ol>
              <li>Vakvriend bekijkt uw adres, ruimte en gewenste toepassing.</li>
              <li>U krijgt uitleg over vermogen, buitenunit, leidingroute en afwerking.</li>
              <li>Daarna ontvangt u pas een concreet voorstel voor toestel en montage.</li>
            </ol>
          </div>

          <div class="mkp-human-checks">
            <?php foreach ($checks as $check): ?>
              <span><?=esc_html($check)?></span>
            <?php endforeach; ?>
          </div>

          <figure class="mkp-human-hero-photo">
            <img src="<?=esc_url($team_image)?>" alt="Vakvriend monteurs bij de bedrijfswagen" loading="eager">
          </figure>
        </div>

        <aside class="mkp-human-form">
          <div class="mkp-human-form-head">
            <span>Gratis en vrijblijvend</span>
            <h2>Start uw airco-offerte</h2>
            <p>U vraagt geen anonieme prijsvergelijking aan. Vakvriend gebruikt uw gegevens alleen om uw airco-aanvraag persoonlijk op te volgen.</p>
          </div>
          <?php echo wc_homezero_scan_widget(false); ?>
          <div class="mkp-human-contact">
            <a href="<?=esc_url($whatsapp_url)?>">WhatsApp</a>
            <a href="tel:<?=esc_attr($tel_clean)?>">Bel direct</a>
          </div>
        </aside>
      </div>
    </div>
  </section>

  <section class="mkp-human-proof">
    <div class="mkp-human-shell mkp-human-proof-grid">
      <article><strong>4,6/5</strong><span>uit klantbeoordelingen</span></article>
      <article><strong>STEK</strong><span>gecertificeerde montage</span></article>
      <article><strong>4 merken</strong><span>LG, Samsung, Mitsubishi, Daikin</span></article>
      <article><strong>1 offerte</strong><span>toestel, montage en afwerking</span></article>
    </div>
  </section>

  <section class="mkp-human-section">
    <div class="mkp-human-shell">
      <div class="mkp-human-section-head">
        <p class="mkp-human-kicker">Richtprijzen inclusief montage</p>
        <h2>Geen lokprijs, maar een pakket waar montage in zit.</h2>
      </div>
      <div class="mkp-human-packages">
        <?php foreach ($packages as [$title, $price, $text]): ?>
          <article>
            <span><?=esc_html($title)?></span>
            <strong><?=esc_html($price)?></strong>
            <p><?=esc_html($text)?></p>
            <a href="#offerte">Vraag voorstel aan</a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="mkp-human-section mkp-human-soft">
    <div class="mkp-human-shell">
      <div class="mkp-human-section-head">
        <p class="mkp-human-kicker">Zo werkt het</p>
        <h2>Van aanvraag naar nette installatie.</h2>
      </div>
      <div class="mkp-human-steps">
        <?php foreach ($steps as [$number, $title, $text]): ?>
          <article>
            <span><?=esc_html($number)?></span>
            <strong><?=esc_html($title)?></strong>
            <p><?=esc_html($text)?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="mkp-human-section">
    <div class="mkp-human-shell mkp-human-photo-grid">
      <figure>
        <img src="<?=esc_url($execution_image)?>" alt="Vakvriend monteurs onderweg naar de klus" loading="lazy">
      </figure>
      <div>
        <p class="mkp-human-kicker">Echte uitvoering</p>
        <h2>De montage bepaalt of u straks tevreden bent.</h2>
        <p>Bij een airco gaat het niet alleen om het merk aan de muur. Buitenunit, geluid, leidinggoot, condensafvoer, elektra en nette afwerking bepalen of de installatie klopt. Daarom nemen we dat direct mee in het voorstel.</p>
      </div>
    </div>
  </section>

  <section class="mkp-human-section mkp-human-soft">
    <div class="mkp-human-shell">
      <div class="mkp-human-section-head">
        <p class="mkp-human-kicker">Populaire aanvragen</p>
        <h2>Waar wilt u vooral duidelijkheid over?</h2>
      </div>
      <div class="mkp-human-situations">
        <?php foreach ($situations as [$title, $text]): ?>
          <a href="#offerte"><strong><?=esc_html($title)?></strong><span><?=esc_html($text)?></span></a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="mkp-human-section mkp-human-advice" id="advies">
    <div class="mkp-human-shell">
      <div class="mkp-human-advice-grid">
        <div class="mkp-human-advice-intro">
          <p class="mkp-human-kicker">Waar letten we op?</p>
          <h2>Een airco kiezen is meer dan een binnenunit aanwijzen.</h2>
          <p>De beste keuze hangt af van de ruimte, de montageplek en hoe u de airco echt gebruikt. Een slaapkamer vraagt om stilte. Een woonkamer vraagt om luchtverdeling. En bij meerdere kamers wordt de buitenunit ineens net zo belangrijk als het toestel binnen.</p>
          <p>Daarom bekijken we eerst de situatie. Pas daarna adviseren we over merk, vermogen, type unit, buitenopstelling en de afwerking die nodig is om het netjes te maken.</p>
          <a class="mkp-human-text-link" href="#offerte">Laat Vakvriend meekijken</a>
        </div>
        <div class="mkp-human-advice-board">
          <figure class="mkp-human-advice-main">
            <img src="<?=esc_url($airco_inside_image)?>" alt="Binnenunit van een airco in een woning" loading="lazy">
            <figcaption>De juiste plek voorkomt tocht, geluid en lelijke omwegen.</figcaption>
          </figure>
          <div class="mkp-human-advice-points">
            <?php foreach ($advice_points as [$image, $title, $text]): ?>
              <article>
                <figure><img src="<?=esc_url($image)?>" alt="<?=esc_attr($title)?>" loading="lazy"></figure>
                <div>
                  <span><?=esc_html($title)?></span>
                  <p><?=esc_html($text)?></p>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="mkp-human-section">
    <div class="mkp-human-shell mkp-human-photo-grid mkp-human-photo-grid-reverse">
      <div>
        <p class="mkp-human-kicker">Geen lokprijs</p>
        <h2>Een eerlijke prijs vraagt eerst een eerlijke check.</h2>
        <p>Een single-split slaapkamer is vaak veel eenvoudiger dan een woonkamer of multi-split. Daarom tonen we liever een realistische offerte dan een prijs die later alsnog oploopt.</p>
        <a class="mkp-human-button" href="#offerte">Vraag mijn prijsvoorstel aan</a>
      </div>
      <figure>
        <img src="<?=esc_url($work_image)?>" alt="Vakvriend monteur aan het werk" loading="lazy">
      </figure>
    </div>
  </section>

  <section class="mkp-human-section mkp-human-faq">
    <div class="mkp-human-shell mkp-human-faq-grid">
      <div>
        <p class="mkp-human-kicker">Veelgestelde vragen</p>
        <h2>Goed om vooraf te weten.</h2>
      </div>
      <div>
        <?php foreach ($faqs as [$question, $answer]): ?>
          <details>
            <summary><?=esc_html($question)?></summary>
            <p><?=esc_html($answer)?></p>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>
