<?php
/**
 * Add unique local SEO copy to campaign pages.
 *
 * Run from the WordPress root:
 * php /tmp/update-unique-seo-content.php
 */

$wp_load = getcwd() . '/wp-load.php';
if (!file_exists($wp_load)) {
    fwrite(STDERR, "Run this script from the WordPress root.\n");
    exit(1);
}

require $wp_load;

$pages = array(
    'Warmtepomp Zaandam' => array('city' => 'Zaandam', 'region' => 'de Zaanstreek', 'area' => 'lintbebouwing, jaren-30-woningen, rijwoningen en nieuwere gezinswoningen rond de Zaan', 'focus' => 'hybride en lucht/water systemen zijn hier vaak de eerste logische stap', 'attention' => 'geluid van de buitenunit, plaatsing bij smalle tuinen en het afgiftesysteem in oudere woningen'),
    'Warmtepomp Zaanstad' => array('city' => 'Zaanstad', 'region' => 'de Zaanstreek', 'area' => 'Zaandam, Koog aan de Zaan, Krommenie en Wormerveer met uiteenlopende woningbouw', 'focus' => 'een wijkgerichte opname voorkomt dat u een te groot of te klein systeem kiest', 'attention' => 'isolatieniveau, radiatoren en de ruimte voor buitenunit of ventilatie-oplossing'),
    'Warmtepomp Krommenie' => array('city' => 'Krommenie', 'region' => 'de Zaanstreek', 'area' => 'gezinswoningen, oudere dorpsbebouwing en woningen met beperkte technische ruimte', 'focus' => 'een hybride warmtepomp of compacte Qvantum-oplossing kan aantrekkelijk zijn', 'attention' => 'beschikbare binnenruimte, dakdoorvoer en de plek waar geluid het minst opvalt'),
    'Warmtepomp Alkmaar' => array('city' => 'Alkmaar', 'region' => 'de regio Alkmaar', 'area' => 'binnenstedelijke woningen, jaren-70-rijwoningen en goed geisoleerde nieuwere wijken', 'focus' => 'lucht/water en hybride systemen zijn vaak snel te beoordelen', 'attention' => 'monumentale details, beperkte buitenruimte en het verschil tussen vloerverwarming en radiatoren'),
    'Warmtepomp Wormerveer' => array('city' => 'Wormerveer', 'region' => 'de Zaanstreek', 'area' => 'woningen langs de Zaan, rijwoningen en huizen waar isolatie per straat sterk verschilt', 'focus' => 'een goede warmteverliesberekening is belangrijk voordat u gasloos gaat', 'attention' => 'vocht, ventilatie, kruipruimte en de route van leidingen naar de buitenunit'),
    'Warmtepomp Den Haag' => array('city' => 'Den Haag', 'region' => 'Haaglanden', 'area' => 'appartementen, bovenwoningen, portiekwoningen en ruime eengezinswoningen', 'focus' => 'de keuze tussen ventilatie, hybride en lucht/water vraagt hier om maatwerk', 'attention' => 'VvE-regels, geluid, balkonruimte en plaatsing aan gevel of dak'),
    'Warmtepomp Almere' => array('city' => 'Almere', 'region' => 'Flevoland', 'area' => 'relatief jonge woningen met vaak goede isolatie en praktische installatieruimte', 'focus' => 'volledig elektrisch is hier vaker haalbaar dan in oudere binnensteden', 'attention' => 'het bestaande afgiftesysteem, tapwaterwensen en de combinatie met zonnepanelen'),
    'Warmtepomp Hilversum' => array('city' => 'Hilversum', 'region' => 'het Gooi', 'area' => 'villa’s, jaren-30-woningen, appartementen en groene woonstraten', 'focus' => 'stilte en nette inpassing zijn hier minstens zo belangrijk als rendement', 'attention' => 'geluidspositie, esthetiek, erfgrenzen en warmteafgifte via radiatoren'),
    'Warmtepomp Haarlem' => array('city' => 'Haarlem', 'region' => 'Kennemerland', 'area' => 'oude stadswoningen, gezinswoningen en appartementen dicht op elkaar', 'focus' => 'hybride, ventilatie en compacte lucht/water oplossingen verdienen een zorgvuldige vergelijking', 'attention' => 'smalle tuinen, dakopstelling, leidingroutes en isolatie van oudere gevels'),
    'Warmtepomp Amstelveen' => array('city' => 'Amstelveen', 'region' => 'de regio Amstelveen', 'area' => 'ruime gezinswoningen, appartementen en goed onderhouden naoorlogse woningen', 'focus' => 'Nibe en Qvantum kunnen hier goed worden vergeleken op comfort en terugverdientijd', 'attention' => 'vloerverwarming, zonnepanelen, tapwatergebruik en stille buitenunitplaatsing'),
    'Warmtepomp Purmerend' => array('city' => 'Purmerend', 'region' => 'Waterland', 'area' => 'rijwoningen, hoekwoningen en nieuwere wijken met redelijk voorspelbaar warmteverbruik', 'focus' => 'hybride of lucht/water kan snel inzicht geven in gasbesparing', 'attention' => 'dakdoorvoer, buitenunit opstelling en het verschil tussen stadsverwarming en gasketel-situaties'),
    'Warmtepomp Kennemerland' => array('city' => 'Kennemerland', 'region' => 'Kennemerland', 'area' => 'Haarlem, Bloemendaal, Heemstede, Velsen en Beverwijk met veel verschillende woningtypen', 'focus' => 'een regionale vergelijking helpt om de juiste oplossing per woningtype te kiezen', 'attention' => 'kustklimaat, beperkte buitenruimte, oudere bouw en geluidsnormen'),
    'Warmtepomp Heerhugowaard' => array('city' => 'Heerhugowaard', 'region' => 'de regio Dijk en Waard', 'area' => 'veel gezinswoningen en nieuwere wijken met praktische technische ruimtes', 'focus' => 'lucht/water warmtepompen zijn vaak interessant, zeker bij goede isolatie', 'attention' => 'buffervat, buitenunit, leidinglengtes en eventuele voorbereiding op volledig elektrisch'),
    'Warmtepomp Landsmeer' => array('city' => 'Landsmeer', 'region' => 'Waterland', 'area' => 'vrijstaande woningen, dijkwoningen en gezinswoningen nabij Amsterdam-Noord', 'focus' => 'maatwerk is belangrijk door uiteenlopende bouwjaren en beschikbare ruimte', 'attention' => 'geluid naar buren, fundering/kruipruimte en de afstand tussen binnen- en buitenunit'),
    'Warmtepomp Wormerland' => array('city' => 'Wormerland', 'region' => 'de Zaanstreek', 'area' => 'dorpswoningen, lintbebouwing en woningen met relatief veel buitenruimte', 'focus' => 'een lucht/water systeem kan interessant zijn wanneer isolatie en radiatoren kloppen', 'attention' => 'plaatsing uit het zicht, leidingwerk en de combinatie met ventilatie'),
    'Warmtepomp Gouda' => array('city' => 'Gouda', 'region' => 'Midden-Holland', 'area' => 'historische woningen, rijwoningen en nieuwere gezinswoningen buiten de binnenstad', 'focus' => 'hybride is vaak een verstandige tussenstap bij oudere woningen', 'attention' => 'vloer- en gevelisolatie, monumentale beperkingen en afgifte via bestaande radiatoren'),
    'Warmtepomp Delft' => array('city' => 'Delft', 'region' => 'Haaglanden', 'area' => 'studentenpanden, stadswoningen, appartementen en gezinswoningen in omliggende wijken', 'focus' => 'compacte systemen en slimme plaatsing maken hier vaak het verschil', 'attention' => 'geluid, beperkte buitenruimte, VvE-afspraken en ventilatiekwaliteit'),
    'Warmtepomp Nieuwegein' => array('city' => 'Nieuwegein', 'region' => 'de regio Utrecht', 'area' => 'veel jaren-70- en jaren-80-woningen met duidelijke isolatiekansen', 'focus' => 'een hybride of lucht/water warmtepomp kan veel gasverbruik wegnemen', 'attention' => 'radiatorcapaciteit, kruipruimte, dakisolatie en voorbereiding op lage temperatuur verwarming'),
    'Warmtepomp Zoetermeer' => array('city' => 'Zoetermeer', 'region' => 'Haaglanden', 'area' => 'ruime woonwijken, rijwoningen en gezinswoningen met regelmatig vergelijkbare plattegronden', 'focus' => 'een snelle woningcheck kan goed bepalen of hybride of all-electric past', 'attention' => 'buitenunitlocatie, geluid naar buren en het huidige gasverbruik'),
    'Warmtepomp Amersfoort' => array('city' => 'Amersfoort', 'region' => 'de regio Amersfoort', 'area' => 'oude binnenstad, Vathorst, gezinswijken en vrijstaande woningen', 'focus' => 'het juiste systeem verschilt sterk per wijk en bouwjaar', 'attention' => 'monumentale woningen, vloerverwarming, dakisolatie en bodemwarmtepomp-kansen'),
    'Warmtepomp Dordrecht' => array('city' => 'Dordrecht', 'region' => 'de Drechtsteden', 'area' => 'stadswoningen, dijkwoningen en gezinswoningen met wisselend isolatieniveau', 'focus' => 'hybride en lucht/water oplossingen moeten hier op woningverlies worden doorgerekend', 'attention' => 'vocht, ventilatie, buitenruimte en bestaande radiatoren'),
    'Warmtepomp Hoorn' => array('city' => 'Hoorn', 'region' => 'West-Friesland', 'area' => 'historische woningen, gezinswijken en nieuwere uitbreidingen', 'focus' => 'Qvantum en Nibe zijn interessant wanneer comfort en tapwater goed worden afgestemd', 'attention' => 'windbelasting, geluid, isolatie en de plek van de buitenunit'),
    'Warmtepomp Den Helder' => array('city' => 'Den Helder', 'region' => 'de Kop van Noord-Holland', 'area' => 'kustwoningen, rijwoningen en huizen waar wind en zout klimaat meetellen', 'focus' => 'een robuuste installatie en goede buitenunitpositie zijn hier extra belangrijk', 'attention' => 'kustklimaat, corrosiebestendige montage en geluid bij open ligging'),
    'Warmtepomp Apeldoorn' => array('city' => 'Apeldoorn', 'region' => 'de Veluwe', 'area' => 'vrijstaande woningen, gezinswoningen en huizen met kansen voor bodemwarmte', 'focus' => 'bodemwarmtepomp en lucht/water verdienen hier vaak allebei aandacht', 'attention' => 'perceelruimte, boringmogelijkheden, isolatie en toekomstig gasloos wonen'),
    'Warmtepomp Zeist' => array('city' => 'Zeist', 'region' => 'de Utrechtse Heuvelrug', 'area' => 'villa’s, jaren-30-woningen en appartementen in groene wijken', 'focus' => 'stilte, comfort en esthetische plaatsing zijn vaak doorslaggevend', 'attention' => 'erfgrenzen, geluid, monumentale uitstraling en lage temperatuur verwarming'),
    'Warmtepomp Achterhoek' => array('city' => 'Achterhoek', 'region' => 'de Achterhoek', 'area' => 'vrijstaande woningen, boerderijen en ruime percelen', 'focus' => 'bodemwarmte of een krachtige lucht/water oplossing kan aantrekkelijk zijn', 'attention' => 'lange leidingroutes, bijgebouwen, isolatieniveau en eventueel krachtstroom'),
    'Warmtepomp Veluwe' => array('city' => 'Veluwe', 'region' => 'de Veluwe', 'area' => 'vrijstaande woningen, bosrijke percelen en gezinswoningen in dorpen', 'focus' => 'rustige inpassing en een goed berekende warmtevraag zijn hier belangrijk', 'attention' => 'geluid, buitenunit uit het zicht, bodemopties en isolatie van grotere woningen'),
    'Warmtepomp Den Bosch' => array('city' => 'Den Bosch', 'region' => 'Noordoost-Brabant', 'area' => 'stadswoningen, gezinswijken en nieuwere uitbreidingen rond de stad', 'focus' => 'hybride, lucht/water en bodemwarmte moeten per woning eerlijk vergeleken worden', 'attention' => 'ruimte, geluid, tapwatergebruik en de stap naar volledig gasloos'),
);

$special = array(
    'Warmtepomp Zonder Boiler' => array(
        'status' => 'seo-unique-v1',
        'wc_stad' => 'Nederland',
        'wc_regio' => 'heel Nederland',
        'wc_meta_title' => 'Warmtepomp zonder boiler | Vers warm water met Qvantum',
        'wc_meta_desc' => 'Zoekt u een warmtepomp zonder groot boilervat? Vakvriend legt uit hoe Qvantum met thermische batterij en platenwisselaar vers warm tapwater maakt.',
        'wc_hero_titel' => 'Warmtepomp zonder boiler aanvragen',
        'wc_hero_subtitel' => 'Qvantum gebruikt geen groot vat met stilstaand douchewater. De thermische batterij levert warmte aan een platenwisselaar, waardoor tapwater vers wordt verwarmd wanneer u de kraan opent.',
        'wc_lokale_h2' => 'Warmtepomp zonder groot boilervat',
        'wc_lokale_intro' => 'Voor veel woningen is ruimte een probleem. Een traditioneel boilervat is groot, zwaar en niet altijd handig te plaatsen. Qvantum kiest daarom voor warmteopslag in een thermische batterij.',
        'wc_lokale_alinea1' => 'Denk aan een warme steen die energie bewaart. De warmtepomp laadt die batterij op en gebruikt de warmte later voor verwarming en tapwater. Het douchewater zelf ligt dus niet opgeslagen.',
        'wc_lokale_alinea2' => 'Wanneer u warm water vraagt, stroomt koud leidingwater langs een platenwisselaar. Die haalt warmte uit de batterij en maakt het water direct warm. Dat is compact en praktisch.',
        'wc_lokale_alinea3' => 'Vakvriend controleert of deze oplossing past bij uw gezin, douchegedrag, meterkast, leidingwerk en beschikbare installatieruimte.',
        'wc_lokale_faq_v1' => 'Is een warmtepomp zonder boiler geschikt voor elk huishouden?',
        'wc_lokale_faq_a1' => 'Niet altijd. Het hangt af van tapwatergebruik, installatieruimte en het gekozen Qvantum-systeem. Vakvriend rekent dit vooraf door.',
        'wc_lokale_faq_v2' => 'Zit het warme water opgeslagen in de thermische batterij?',
        'wc_lokale_faq_a2' => 'Nee. De batterij slaat warmte op, geen douchewater. Tapwater wordt vers verwarmd via een platenwisselaar.',
        'wc_lokale_faq_v3' => 'Kan ik subsidie krijgen op deze oplossing?',
        'wc_lokale_faq_a3' => 'Ja, als het gekozen toestel aan de ISDE-eisen voldoet. Vakvriend berekent het verwachte subsidiebedrag.',
        'wc_extra_tekst' => "Een warmtepomp zonder boiler is vooral interessant wanneer u minder ruimte wilt reserveren voor techniek. Bij veel woningen staat de wasmachine, omvormer, ventilatiebox of cv-ketel al in dezelfde ruimte. Dan is een compact systeem met thermische batterij aantrekkelijk.\n\nVakvriend kijkt niet alleen naar de warmtepomp zelf, maar ook naar comfort. Hoeveel mensen douchen er? Is er een bad? Hoe snel wilt u opnieuw warm water beschikbaar hebben? Die vragen bepalen of een Qvantum-oplossing zonder groot boilervat verstandig is.\n\nOok onderhoud en legionellarisico spelen mee. Omdat het tapwater niet als voorraad in een groot vat staat, werkt het systeem anders dan een klassieke boiler. Wij leggen dit tijdens de opname simpel uit, zodat u precies weet wat u koopt.",
    ),
    'Warmtepomp Vergelijken' => array(
        'status' => 'seo-unique-v1',
        'wc_stad' => 'Nederland',
        'wc_regio' => 'heel Nederland',
        'wc_meta_title' => 'Warmtepomp offertes vergelijken | Qvantum, Nibe en hybride',
        'wc_meta_desc' => 'Vergelijk warmtepomp offertes op subsidie, geluid, rendement, tapwater, installatiekosten en geschiktheid voor uw woning.',
        'wc_hero_titel' => 'Warmtepomp offertes vergelijken',
        'wc_hero_subtitel' => 'Een lage prijs zegt weinig als het systeem niet past. Vakvriend vergelijkt warmtepompen op rendement, geluid, tapwater, subsidie en installatiepraktijk.',
        'wc_lokale_h2' => 'Offertes vergelijken zonder appels met peren',
        'wc_lokale_intro' => 'Warmtepomp offertes lijken vaak op elkaar, maar de verschillen zitten in vermogen, boilervoorziening, geluid, leidingwerk, regeling en subsidiebedrag.',
        'wc_lokale_alinea1' => 'Een offerte voor hybride verwarmen is niet vergelijkbaar met een volledig elektrische lucht/water warmtepomp. Ook Qvantum en Nibe lossen tapwater en regeling op een andere manier op.',
        'wc_lokale_alinea2' => 'Vakvriend maakt de vergelijking praktisch: wat kost het netto na ISDE, hoeveel gas bespaart u, hoeveel ruimte is nodig en hoe groot is de installatie-impact?',
        'wc_lokale_alinea3' => 'Zo voorkomt u dat u kiest voor de goedkoopste offerte, terwijl een iets andere oplossing beter past bij uw woning en comfortwensen.',
        'wc_lokale_faq_v1' => 'Waar moet ik op letten bij warmtepomp offertes?',
        'wc_lokale_faq_a1' => 'Let op vermogen, tapwateroplossing, geluid, garantie, installatiewerk, subsidie en of de woning vooraf goed is beoordeeld.',
        'wc_lokale_faq_v2' => 'Is de goedkoopste offerte meestal de beste?',
        'wc_lokale_faq_a2' => 'Niet per se. Een te klein of verkeerd geplaatst systeem kan later duurder uitpakken door comfortklachten of aanpassingen.',
        'wc_lokale_faq_v3' => 'Kan Vakvriend een bestaande offerte beoordelen?',
        'wc_lokale_faq_a3' => 'Ja. We kunnen uitleggen wat er technisch in staat en welke onderdelen ontbreken of extra aandacht vragen.',
        'wc_extra_tekst' => "Een goede vergelijking begint met dezelfde uitgangspunten. Heeft elke aanbieder gerekend met hetzelfde gasverbruik? Is tapwater meegenomen? Zijn extra groepen, leidingwerk, dakdoorvoer of geluidsvoorzieningen inbegrepen?\n\nBij Qvantum speelt de thermische batterij een rol. Bij Nibe is er juist veel modelkeuze. Bij hybride systemen blijft de cv-ketel meewerken. Dat maakt vergelijken lastig, maar ook belangrijk.\n\nVakvriend vertaalt technische offertes naar begrijpelijke keuzes. U ziet niet alleen de aanschafprijs, maar ook de verwachte besparing, subsidie en praktische gevolgen in huis.",
    ),
    'Mijn Warmtepomp Offerte' => array(
        'status' => 'seo-unique-v1',
        'wc_stad' => 'Nederland',
        'wc_regio' => 'heel Nederland',
        'wc_meta_title' => 'Mijn warmtepomp offerte | Gratis advies van Vakvriend',
        'wc_meta_desc' => 'Vraag uw persoonlijke warmtepomp offerte aan met subsidiecheck, besparingsberekening en advies over Qvantum, Nibe of hybride.',
        'wc_hero_titel' => 'Mijn warmtepomp offerte aanvragen',
        'wc_hero_subtitel' => 'Ontvang een duidelijke offerte die past bij uw woning, verbruik en comfortwensen. Vakvriend rekent subsidie en besparing meteen mee.',
        'wc_lokale_h2' => 'Een offerte die begint bij uw woning',
        'wc_lokale_intro' => 'Een warmtepomp is geen standaardproduct. De juiste keuze hangt af van isolatie, radiatoren, vloerverwarming, tapwater en beschikbare ruimte.',
        'wc_lokale_alinea1' => 'Vakvriend kijkt eerst naar de woning en pas daarna naar het merk of model. Daardoor krijgt u geen verkooppraatje, maar een voorstel dat technisch klopt.',
        'wc_lokale_alinea2' => 'In de offerte ziet u wat de installatie kost, welke ISDE-subsidie mogelijk is en welke besparing realistisch is bij uw verbruik.',
        'wc_lokale_alinea3' => 'U kunt Qvantum, Nibe, hybride en bodemwarmte naast elkaar laten beoordelen. Zo kiest u rustig en met feiten.',
        'wc_lokale_faq_v1' => 'Is een warmtepomp offerte gratis?',
        'wc_lokale_faq_a1' => 'Ja, de eerste beoordeling en subsidiecheck zijn gratis en vrijblijvend.',
        'wc_lokale_faq_v2' => 'Welke gegevens heeft Vakvriend nodig?',
        'wc_lokale_faq_a2' => 'Woningtype, gasverbruik, verwarmingssysteem en uw wensen voor comfort en tapwater zijn het belangrijkst.',
        'wc_lokale_faq_v3' => 'Krijg ik direct een definitieve prijs?',
        'wc_lokale_faq_a3' => 'Na de opname kan Vakvriend de prijs pas echt goed maken. Online geven we alvast richting en subsidie-inschatting.',
        'wc_extra_tekst' => "Een goede warmtepomp offerte voorkomt verrassingen. Daarom nemen we niet alleen de warmtepomp op, maar ook leidingwerk, elektra, regeling, geluid en eventuele aanpassingen aan radiatoren of vloerverwarming.\n\nDe offerte moet ook duidelijk maken wat u netto betaalt. ISDE-subsidie kan veel verschil maken, maar het bedrag hangt af van type en vermogen. Vakvriend rekent dat voor u uit.\n\nZo wordt de offerte geen losse prijs, maar een compleet plan voor minder gasverbruik, beter comfort en een woning die klaar is voor de komende jaren.",
    ),
    'Warmtepomp Qvantum' => array(
        'status' => 'seo-unique-v1',
        'wc_stad' => 'Nederland',
        'wc_regio' => 'heel Nederland',
        'wc_meta_title' => 'Qvantum warmtepomp offerte | QA, QE en QG specialist',
        'wc_meta_desc' => 'Vakvriend adviseert over Qvantum QA, QE en QG warmtepompen met thermische batterij, platenwisselaar en ISDE-subsidie.',
        'wc_hero_titel' => 'Qvantum warmtepomp offerte aanvragen',
        'wc_hero_subtitel' => 'Qvantum gebruikt in QA, QE en QG een thermische batterij. Vakvriend legt uit welk systeem past bij uw woning en tapwatergebruik.',
        'wc_lokale_h2' => 'Qvantum warmtepomp met thermische batterij',
        'wc_lokale_intro' => 'Qvantum is interessant voor woningen waar compactheid, slim stroomgebruik en warm tapwater zonder klassiek boilervat belangrijk zijn.',
        'wc_lokale_alinea1' => 'De QA is de lucht/water oplossing, de QE gebruikt ventilatielucht en de QG is bedoeld voor water/water of bodemtoepassingen. Alle drie werken met thermische opslag.',
        'wc_lokale_alinea2' => 'Warm tapwater wordt vers verwarmd via een platenwisselaar. De warmte komt uit de batterij, niet uit een groot vat met stilstaand douchewater.',
        'wc_lokale_alinea3' => 'Vakvriend beoordeelt per woning of Qvantum beter past dan Nibe of een hybride oplossing. Daarbij kijken we naar ruimte, geluid, comfort en subsidie.',
        'wc_lokale_faq_v1' => 'Welke Qvantum past bij mijn woning?',
        'wc_lokale_faq_a1' => 'Dat hangt af van woningtype, ventilatie, bodemopties, isolatie en tapwatergebruik. Vakvriend vergelijkt QA, QE en QG voor u.',
        'wc_lokale_faq_v2' => 'Hebben alle Qvantum systemen een thermische batterij?',
        'wc_lokale_faq_a2' => 'Ja, QA, QE en QG gebruiken thermische opslag. De uitvoering verschilt per systeem.',
        'wc_lokale_faq_v3' => 'Is Qvantum altijd beter dan Nibe?',
        'wc_lokale_faq_a3' => 'Nee. Qvantum is sterk in compacte warmteopslag, Nibe heeft een breed assortiment. De woning bepaalt wat verstandig is.',
        'wc_extra_tekst' => "Qvantum vraagt om goede uitleg, omdat het anders snel klinkt alsof er toch een boiler verstopt zit. Dat is niet zo. De batterij slaat warmte op; tapwater wordt pas warm gemaakt als u het gebruikt.\n\nVoor appartementen kan de QE interessant zijn, zeker wanneer buitenunitplaatsing lastig is. Voor grondgebonden woningen kan de QA of QG logischer zijn. De juiste keuze hangt af van ventilatie, isolatie en het bestaande verwarmingssysteem.\n\nVakvriend neemt Qvantum mee in een breder advies. Als Nibe of hybride technisch beter past, zeggen we dat ook.",
    ),
);

function vk_find_page_by_title($title) {
    $page = get_page_by_title($title, OBJECT, 'page');
    return $page ? (int) $page->ID : 0;
}

function vk_local_meta($spec) {
    $city = $spec['city'];
    $region = $spec['region'];

    return array(
        'wc_ai_content_status' => 'seo-unique-v1',
        'wc_meta_title' => 'Warmtepomp ' . $city . ' | Gratis subsidiecheck en offerte',
        'wc_meta_desc' => 'Warmtepomp advies in ' . $city . '. Vakvriend beoordeelt uw woning, berekent ISDE-subsidie en vergelijkt Qvantum, Nibe en hybride oplossingen.',
        'wc_hero_kicker' => 'Gratis subsidiecheck en advies in ' . $city,
        'wc_hero_titel' => 'Warmtepomp offerte aanvragen in ' . $city,
        'wc_hero_subtitel' => 'Vakvriend helpt woningeigenaren in ' . $city . ' met warmtepompadvies dat past bij de woning, het verbruik en de beschikbare ruimte. U krijgt helder inzicht in subsidie, besparing en installatiekosten.',
        'wc_lokale_h2' => 'Warmtepomp installateur in ' . $city,
        'wc_lokale_intro' => 'Een warmtepomp in ' . $city . ' vraagt om meer dan een standaard offerte. ' . ucfirst($spec['area']) . ' vragen om een advies waarin comfort, geluid en installatieruimte samen worden bekeken.',
        'wc_lokale_alinea1' => 'Voor ' . $city . ' geldt: ' . $spec['focus'] . '. Vakvriend begint daarom met een praktische woningcheck in plaats van direct een merk of model te verkopen.',
        'wc_lokale_alinea2' => 'Tijdens de opname letten we vooral op ' . $spec['attention'] . '. Dat bepaalt of Qvantum, Nibe, hybride of bodemwarmte de beste route is.',
        'wc_lokale_alinea3' => 'Na de aanvraag ontvangt u een voorstel met verwachte ISDE-subsidie, netto investering en een realistische inschatting van de besparing voor uw woning in ' . $region . '.',
        'wc_lokale_faq_v1' => 'Welke warmtepomp past bij een woning in ' . $city . '?',
        'wc_lokale_faq_a1' => 'Dat hangt af van bouwjaar, isolatie, radiatoren of vloerverwarming, buitenruimte en tapwatergebruik. Vakvriend beoordeelt dit per woning en vergelijkt meerdere systemen.',
        'wc_lokale_faq_v2' => 'Is een hybride warmtepomp in ' . $city . ' een goede tussenstap?',
        'wc_lokale_faq_a2' => 'Vaak wel, vooral bij woningen die nog niet volledig klaar zijn voor lage temperatuur verwarming. Bij betere isolatie kan volledig elektrisch soms direct interessanter zijn.',
        'wc_lokale_faq_v3' => 'Waar let Vakvriend lokaal extra op?',
        'wc_lokale_faq_a3' => 'We kijken naar geluid, plaatsing, leidingroutes, beschikbare techniekruimte en eventuele beperkingen door buren, VvE of woningtype.',
        'wc_faq_extra' => 'Werkt Vakvriend in ' . $city . '?:::Ja, Vakvriend helpt woningeigenaren in ' . $city . ' en ' . $region . ' met warmtepompadvies, offerte, installatie en ISDE-subsidie.',
        'wc_extra_tekst' => 'In ' . $city . ' is de beste warmtepompkeuze sterk afhankelijk van de woning. ' . ucfirst($spec['area']) . ' hebben elk een andere warmtevraag. Daarom kijkt Vakvriend naar de combinatie van isolatie, warmteafgifte en dagelijks comfort.' . "\n\n" .
            'Een belangrijk verschil zit in tapwater en ruimte. Qvantum werkt met een thermische batterij en platenwisselaar, terwijl Nibe veel keuze biedt in lucht/water, bodem en hybride oplossingen. Vakvriend legt uit wat dat concreet betekent voor uw meterkast, technische ruimte, buitenunit en douchecomfort.' . "\n\n" .
            'Ook de subsidie verschilt per toestel en vermogen. In de offerte nemen we het verwachte ISDE-bedrag, de installatiekosten en de besparing apart op. Zo ziet u niet alleen wat het systeem kost, maar ook waarom het juist voor uw woning in ' . $region . ' wel of niet verstandig is.',
    );
}

function vk_apply_meta($post_id, $meta) {
    foreach ($meta as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }
    if (!empty($meta['wc_meta_title'])) {
        update_post_meta($post_id, '_yoast_wpseo_title', $meta['wc_meta_title']);
    }
    if (!empty($meta['wc_meta_desc'])) {
        update_post_meta($post_id, '_yoast_wpseo_metadesc', $meta['wc_meta_desc']);
    }
}

foreach ($pages as $title => $spec) {
    $post_id = vk_find_page_by_title($title);
    if (!$post_id) {
        echo 'Missing page: ' . $title . PHP_EOL;
        continue;
    }

    vk_apply_meta($post_id, vk_local_meta($spec));
    echo 'Updated unique local content: ' . $title . PHP_EOL;
}

foreach ($special as $title => $meta) {
    $post_id = vk_find_page_by_title($title);
    if (!$post_id) {
        echo 'Missing special page: ' . $title . PHP_EOL;
        continue;
    }

    $meta['wc_ai_content_status'] = $meta['status'];
    unset($meta['status']);
    vk_apply_meta($post_id, $meta);
    echo 'Updated unique special content: ' . $title . PHP_EOL;
}

if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}

echo 'Done.' . PHP_EOL;
