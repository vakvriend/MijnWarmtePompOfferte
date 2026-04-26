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
        'status' => 'seo-unique-v2',
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
        'wc_form_titel' => 'Check of uw woning zonder boiler kan',
        'wc_form_subtitel' => 'Ontvang een compacte beoordeling van tapwater, thermische batterij, ruimte en subsidie.',
        'wc_voordelen_titel' => 'Waarom een warmtepomp zonder boilervat?',
        'wc_voordelen_lead' => 'Deze pagina draait specifiek om ruimtebesparing en vers tapwater via Qvantum, niet om een algemene warmtepompvergelijking.',
        'wc_reviews_lead' => 'Aanvragers willen vooral weten of het ontbreken van een groot vat comfort kost. Daarom leggen we tapwater en opslag extra simpel uit.',
        'wc_extra_tekst' => "Een warmtepomp zonder boiler is vooral interessant wanneer u minder ruimte wilt reserveren voor techniek. Bij veel woningen staat de wasmachine, omvormer, ventilatiebox of cv-ketel al in dezelfde ruimte. Dan is een compact systeem met thermische batterij aantrekkelijk.\n\nVakvriend kijkt niet alleen naar de warmtepomp zelf, maar ook naar comfort. Hoeveel mensen douchen er? Is er een bad? Hoe snel wilt u opnieuw warm water beschikbaar hebben? Die vragen bepalen of een Qvantum-oplossing zonder groot boilervat verstandig is.\n\nOok onderhoud en legionellarisico spelen mee. Omdat het tapwater niet als voorraad in een groot vat staat, werkt het systeem anders dan een klassieke boiler. Wij leggen dit tijdens de opname simpel uit, zodat u precies weet wat u koopt.",
    ),
    'Warmtepomp Vergelijken' => array(
        'status' => 'seo-unique-v2',
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
        'wc_form_titel' => 'Laat uw offerte logisch vergelijken',
        'wc_form_subtitel' => 'Upload of beschrijf uw situatie en krijg inzicht in prijs, systeemkeuze, subsidie en verborgen randwerk.',
        'wc_voordelen_titel' => 'Vergelijken op meer dan de totaalprijs',
        'wc_voordelen_lead' => 'Deze pagina helpt bezoekers vooral om appels met appels te vergelijken: vermogen, tapwater, geluid en installatie-impact.',
        'wc_reviews_lead' => 'Mensen vragen hier vaak hulp omdat offertes technisch op elkaar lijken, maar in uitvoering sterk verschillen.',
        'wc_extra_tekst' => "Een goede vergelijking begint met dezelfde uitgangspunten. Heeft elke aanbieder gerekend met hetzelfde gasverbruik? Is tapwater meegenomen? Zijn extra groepen, leidingwerk, dakdoorvoer of geluidsvoorzieningen inbegrepen?\n\nBij Qvantum speelt de thermische batterij een rol. Bij Nibe is er juist veel modelkeuze. Bij hybride systemen blijft de cv-ketel meewerken. Dat maakt vergelijken lastig, maar ook belangrijk.\n\nVakvriend vertaalt technische offertes naar begrijpelijke keuzes. U ziet niet alleen de aanschafprijs, maar ook de verwachte besparing, subsidie en praktische gevolgen in huis.",
    ),
    'Mijn Warmtepomp Offerte' => array(
        'status' => 'seo-unique-v2',
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
        'wc_form_titel' => 'Start uw persoonlijke warmtepomp offerte',
        'wc_form_subtitel' => 'Vul woningtype, systeemwens en verbruik in en ontvang een offertepad dat bij uw huis past.',
        'wc_voordelen_titel' => 'Uw offerte begint bij de woning',
        'wc_voordelen_lead' => 'Deze pagina is breed opgezet voor bezoekers die nog niet weten welk systeem past en eerst richting nodig hebben.',
        'wc_reviews_lead' => 'Aanvragers waarderen vooral dat de offerte niet als losse prijs komt, maar als compleet keuzeplan.',
        'wc_extra_tekst' => "Een goede warmtepomp offerte voorkomt verrassingen. Daarom nemen we niet alleen de warmtepomp op, maar ook leidingwerk, elektra, regeling, geluid en eventuele aanpassingen aan radiatoren of vloerverwarming.\n\nDe offerte moet ook duidelijk maken wat u netto betaalt. ISDE-subsidie kan veel verschil maken, maar het bedrag hangt af van type en vermogen. Vakvriend rekent dat voor u uit.\n\nZo wordt de offerte geen losse prijs, maar een compleet plan voor minder gasverbruik, beter comfort en een woning die klaar is voor de komende jaren.",
    ),
    'Warmtepomp Qvantum' => array(
        'status' => 'seo-unique-v2',
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
        'wc_form_titel' => 'Ontdek welke Qvantum bij u past',
        'wc_form_subtitel' => 'Laat QA, QE en QG beoordelen op ventilatie, bron, tapwater, ruimte en subsidie.',
        'wc_voordelen_titel' => 'Qvantum vraagt om goede uitleg',
        'wc_voordelen_lead' => 'Deze pagina focust op de thermische batterij, platenwisselaar en het verschil tussen QA, QE en QG.',
        'wc_reviews_lead' => 'Bezoekers willen vooral weten wat thermische opslag in normaal Nederlands betekent. Daarom staat uitleg hier voorop.',
        'wc_extra_tekst' => "Qvantum vraagt om goede uitleg, omdat het anders snel klinkt alsof er toch een boiler verstopt zit. Dat is niet zo. De batterij slaat warmte op; tapwater wordt pas warm gemaakt als u het gebruikt.\n\nVoor appartementen kan de QE interessant zijn, zeker wanneer buitenunitplaatsing lastig is. Voor grondgebonden woningen kan de QA of QG logischer zijn. De juiste keuze hangt af van ventilatie, isolatie en het bestaande verwarmingssysteem.\n\nVakvriend neemt Qvantum mee in een breder advies. Als Nibe of hybride technisch beter past, zeggen we dat ook.",
    ),
);

function vk_find_page_by_title($title) {
    $page = get_page_by_title($title, OBJECT, 'page');
    return $page ? (int) $page->ID : 0;
}

function vk_pick($items, $index) {
    return $items[$index % count($items)];
}

function vk_contextual_rows($rows, $city) {
    $out = array();
    foreach (explode("\n", $rows) as $row) {
        $parts = explode('|', $row);
        if (count($parts) >= 3) {
            $parts[2] = rtrim($parts[2], '.') . '. Voor ' . $city . ' wordt dit lokaal meegewogen.';
        }
        $out[] = implode('|', $parts);
    }
    return implode("\n", $out);
}

function vk_contextual_answer($answer, $city) {
    return rtrim($answer, '.') . '. Voor ' . $city . ' toetsen we dit vooraf in de woningcheck.';
}

function vk_local_meta($title, $spec, $index) {
    $city = $spec['city'];
    $region = $spec['region'];
    $area = $spec['area'];
    $focus = $spec['focus'];
    $attention = $spec['attention'];

    $intro_styles = array(
        'In {city} begint goed warmtepompadvies bij het herkennen van de woning. {area} zorgen voor andere keuzes dan een standaard rijtje modellen.',
        '{city} vraagt om een nuchtere opname: eerst warmteverlies, geluid en ruimte controleren, daarna pas kiezen tussen Qvantum, Nibe of hybride.',
        'Wie in {city} een warmtepomp overweegt, heeft weinig aan algemene beloftes. De wijk, het bouwjaar en het afgiftesysteem bepalen de route.',
        'De woningen in {city} lopen technisch te ver uiteen voor copy-paste advies. {area} vragen om een voorstel dat op locatie klopt.',
        'Een warmtepomp in {city} wordt pas interessant als comfort, subsidie en plaatsing tegelijk zijn doorgerekend.',
        'Voor {city} kijken we niet alleen naar minder gasverbruik, maar ook naar stilte, tapwater, leidingroutes en de plek van de installatie.',
        'In {region} kan dezelfde warmtepomp bij de ene woning uitstekend zijn en bij de andere tegenvallen. Daarom begint Vakvriend met de woning, niet met de folder.',
        '{city} verdient een offerte waarin de praktische kanten helder zijn: waar komt de unit, hoe blijft het stil en wat doet het systeem op koude dagen?',
    );
    $hero_styles = array(
        'Geen standaard warmtepompverhaal, maar een advies voor {city} op basis van woningtype, gasverbruik, geluid en de echte installatieruimte.',
        'Vakvriend rekent voor uw woning in {city} uit welke warmtepomp logisch is, wat de ISDE-subsidie doet en welke investering realistisch blijft.',
        'Van hybride tussenstap tot Qvantum of Nibe: u krijgt in {city} een concreet voorstel dat past bij comfort, tapwater en beschikbare ruimte.',
        'Laat uw woning in {city} beoordelen voordat u offertes vergelijkt. Zo voorkomt u een te klein systeem, geluidsgedoe of onnodige kosten.',
        'Warmtepompadvies in {city} met focus op subsidie, besparing, geluidspositie en een installatie die ook in de praktijk prettig werkt.',
        'Ontdek welke warmtepomp bij uw woning in {city} past en ontvang een offerte waarin ruimte, regeling, tapwater en ISDE apart zijn uitgewerkt.',
        'Voor woningen in {city} vergelijkt Vakvriend Qvantum, Nibe, hybride en bodemwarmte zonder verkooppraat. Alleen wat technisch verstandig is.',
        'Een passende warmtepomp in {city} begint met goede vragen over isolatie, radiatoren, douchecomfort, zonnepanelen en buitenunitplaatsing.',
    );
    $angle_styles = array(
        'Voor {city} is het belangrijkste aandachtspunt: {focus}. Dat klinkt simpel, maar het vermogen, het buffervat en de regeling moeten precies bij de woning passen.',
        'In {city} zien we vooral dat {focus}. Daarom krijgt u geen algemene prijs, maar eerst een technische keuzehulp.',
        'De lokale nuance is helder: {focus}. De offerte moet dus uitleggen waarom juist dat systeem past.',
        'Bij veel aanvragen uit {city} komt hetzelfde beslispunt terug: {focus}. Vakvriend rekent dit door voordat er een toestel wordt gekozen.',
        'De beste route voor {city} draait vaak om deze afweging: {focus}. Pas daarna vergelijken we merk, vermogen en subsidie.',
        'Voor {region} nemen we dit uitgangspunt mee: {focus}. Daardoor wordt het advies scherper dan een gemiddelde online rekentool.',
        'In deze omgeving geldt meestal dat {focus}. De kunst is om dat te koppelen aan stilte, comfort en betaalbaarheid.',
        'Voor woningeigenaren in {city} is {focus}. Dat bepaalt of hybride genoeg is of dat all-electric haalbaar wordt.',
    );
    $attention_styles = array(
        'Tijdens de opname letten we nadrukkelijk op {attention}. Juist die details bepalen of de installatie stil, zuinig en netjes geplaatst kan worden.',
        'De technische check draait hier om {attention}. Als dat niet klopt, kan een mooie offerte later alsnog tegenvallen.',
        'We nemen extra tijd voor {attention}, omdat dit in {city} vaak het verschil maakt tussen “werkt” en “werkt comfortabel”.',
        'Vooraf controleren we {attention}. Daarna pas krijgt u een advies over QA, QE, QG, Nibe of hybride.',
        'Een belangrijk deel van het plan is {attention}. Dat zetten we begrijpelijk in de offerte, zodat u weet waar u ja tegen zegt.',
        'Bij de woningcheck worden {attention} praktisch beoordeeld. Geen aannames, maar meetpunten en foto’s van de situatie.',
        'We kijken lokaal vooral naar {attention}. Zo voorkomen we dat de warmtepomp technisch kan, maar in gebruik stoort.',
        'De plaatsing wordt beoordeeld op {attention}; dat is vaak belangrijker dan alleen het hoogste rendement op papier.',
    );
    $closing_styles = array(
        'Na uw aanvraag krijgt u een voorstel met systeemkeuze, netto investering, subsidie-inschatting en de aanpassingen die in uw woning nodig zijn.',
        'U ontvangt geen losse richtprijs, maar een leesbaar plan met subsidie, verwachte besparing en de belangrijkste installatiekeuzes.',
        'De offerte laat zien wat direct kan, wat voorbereiding vraagt en waar u eventueel beter nog niet in moet investeren.',
        'We maken de uitkomst concreet: geschikt systeem, verwachte ISDE, plaatsingsadvies en een nuchtere besparingsverwachting.',
        'Zo ziet u vooraf of een warmtepomp in uw situatie slim is, of dat isolatie, radiatoren of ventilatie eerst aandacht nodig hebben.',
        'Het resultaat is een advies waarmee u kunt beslissen zonder technische mist: kosten, comfort, subsidie en risico’s naast elkaar.',
        'Vakvriend vertaalt de techniek naar keuzes: welke oplossing, waarom die past en welke stappen nodig zijn voor een nette installatie.',
        'Daarmee voorkomt u dat u alleen op aanschafprijs vergelijkt terwijl geluid, tapwater of leidingwerk later de echte kosten bepalen.',
    );
    $extra_openers = array(
        'De warmtepompmarkt is druk, maar uw woning is geen spreadsheet.',
        'Een goede installatie voelt achteraf bijna saai: warm huis, stil systeem, geen verrassingen.',
        'Het verschil tussen een redelijke offerte en een sterke offerte zit vaak in de kleine regels.',
        'Subsidie is mooi, maar comfort blijft leidend.',
        'Een buitenunit, binnenmodule of thermische batterij moet niet alleen passen op papier.',
        'Bij oudere woningen is voorzichtig rekenen beter dan stoer beloven.',
        'Bij nieuwere woningen is juist de verleiding groot om te snel een te groot toestel te kiezen.',
        'Tapwater wordt vaak onderschat totdat het gezin tegelijk wil douchen, koken en verwarmen.',
    );
    $extra_middle = array(
        'Qvantum is interessant wanneer compactheid en warm water via de thermische batterij belangrijk zijn. Nibe is sterk wanneer brede modelkeuze en bewezen configuraties zwaarder wegen.',
        'Hybride kan een slimme tussenstap zijn wanneer radiatoren nog hoge temperaturen vragen. All-electric wordt logischer bij goede isolatie en voldoende warmteafgifte.',
        'Een bodemwarmtepomp vraagt meer voorbereiding, maar kan op ruime percelen juist rust, rendement en levensduur opleveren.',
        'Ventilatie-oplossingen verdienen extra aandacht bij appartementen en woningen waar een buitenunit gevoelig ligt.',
        'Zonnepanelen veranderen de rekensom, maar lossen niet automatisch geluid, tapwater of afgiftevermogen op.',
        'Een lage offerte zonder uitleg over leidingwerk, elektra en regeling is geen voordeel maar een risico.',
        'De ISDE-regeling helpt, maar het gekozen toestel en vermogen bepalen of het bedrag ook echt interessant wordt.',
        'Een warmtepomp die te hard moet werken, bespaart minder en voelt onrustiger. Daarom rekenen we liever vooraf iets scherper.',
    );
    $extra_close = array(
        'Daarom krijgt u van Vakvriend een advies dat u ook aan de keukentafel kunt uitleggen: wat doen we, waarom doen we dat en wat levert het op.',
        'Zo wordt de offerte geen verzameling artikelnummers, maar een plan voor minder gasverbruik en meer zekerheid.',
        'U weet na de opname welke keuze verstandig is, welke keuze kan wachten en welke oplossing we juist afraden.',
        'Dat maakt de aanvraag niet alleen sneller, maar vooral bruikbaarder wanneer u offertes naast elkaar legt.',
        'We leggen de techniek simpel uit, inclusief de beperkingen. Dat is beter dan achteraf moeten bijsturen.',
        'De beste warmtepomp is uiteindelijk niet de duurste of de bekendste, maar de installatie die bij uw huis blijft kloppen.',
        'Met die aanpak wordt subsidie geen lokkertje, maar onderdeel van een volledige rekensom.',
        'Zo houdt u grip op investering, comfort en uitvoering voordat de installatiedatum in de agenda staat.',
    );
    $faq_sets = array(
        array('Past een warmtepomp bij oudere woningen in {city}?', 'Dat kan, maar alleen als isolatie, radiatorvermogen en aanvoertemperatuur goed worden bekeken. Soms is hybride verstandiger dan direct volledig elektrisch.', 'Wanneer is Qvantum interessant in {city}?', 'Qvantum kan aantrekkelijk zijn wanneer een compacte oplossing en vers tapwater via thermische opslag belangrijk zijn.', 'Waar kan een buitenunit het beste staan?', 'Dat bepalen we op basis van geluid, afstand tot buren, leidinglengte en onderhoudsruimte. De stilste plek is niet altijd de makkelijkste plek.'),
        array('Moet mijn woning eerst extra worden geisoleerd?', 'Niet altijd. We bekijken warmteverlies en huidige afgifte. Soms leveren kleine maatregelen meer op dan direct een zwaardere warmtepomp.', 'Kan ik mijn radiatoren blijven gebruiken?', 'Vaak wel, maar het hangt af van formaat en watertemperatuur. Tijdens de check beoordelen we of lage temperatuur verwarming haalbaar is.', 'Hoe betrouwbaar is de subsidie-inschatting?', 'We gebruiken toesteltype, vermogen en actuele ISDE-uitgangspunten. Het definitieve bedrag hangt af van goedkeuring door RVO.'),
        array('Is volledig elektrisch haalbaar in {city}?', 'Bij goed geisoleerde woningen zeker. Bij twijfel rekenen we hybride, all-electric en eventueel bodemwarmte naast elkaar door.', 'Wat als ik weinig ruimte binnen heb?', 'Dan kijken we naar compacte modules, Qvantum met thermische batterij of een ventilatie-oplossing. De technische ruimte wordt altijd meegenomen.', 'Krijg ik een offerte zonder huisbezoek?', 'We kunnen online richting geven, maar voor een betrouwbare prijs is een opname of duidelijke foto-inventarisatie nodig.'),
        array('Hoe voorkom ik geluidsoverlast?', 'Door vermogen, nachtstand, opstelplek en trillingsdemping vooraf mee te nemen. Vooral in dichtbebouwde straten is dat geen detail.', 'Is een bodemwarmtepomp mogelijk in {city}?', 'Dat hangt af van perceel, ondergrond, vergunning en budget. Als bodem niet logisch is, zeggen we dat direct.', 'Welke gegevens moet ik klaarleggen?', 'Gasverbruik, bouwjaar, isolatiemaatregelen, foto’s van cv-ruimte en eventuele vloerverwarming helpen om sneller scherp advies te geven.'),
        array('Wat is slimmer: Nibe of Qvantum?', 'Dat verschilt per woning. Qvantum is sterk in thermische opslag; Nibe biedt veel configuraties. We vergelijken ze op comfort, ruimte en kosten.', 'Kan de warmtepomp samenwerken met zonnepanelen?', 'Ja, maar eigen stroom is niet het enige criterium. De warmtevraag in de winter en tapwaterbehoefte blijven bepalend.', 'Hoe snel weet ik wat mijn woning nodig heeft?', 'Na de aanvraag kunnen we meestal snel aangeven welke richting logisch is en welke punten nog gecontroleerd moeten worden.'),
        array('Is een hybride warmtepomp nog zinvol?', 'Ja, vooral wanneer isolatie of radiatoren nog niet klaar zijn voor lage temperatuur. Het kan een verstandige stap zijn om gasverbruik flink te verlagen.', 'Kan ik later alsnog volledig gasloos worden?', 'Vaak wel. We letten bij het advies op uitbreidbaarheid, zodat u niet opnieuw hoeft te beginnen.', 'Wat maakt de offerte van Vakvriend anders?', 'We zetten subsidie, installatie-impact, geluid en tapwater apart uiteen in plaats van alleen een totaalprijs te geven.'),
        array('Kan dit ook bij een appartement?', 'Soms wel, vooral met ventilatie-oplossingen of wanneer VvE en opstelplek meewerken. We controleren de praktische randvoorwaarden vooraf.', 'Hoe belangrijk is tapwatergebruik?', 'Heel belangrijk. Douchegedrag, bad, gezinssamenstelling en hersteltijd bepalen of een systeem comfortabel blijft.', 'Wordt de ISDE-aanvraag begeleid?', 'Ja. Vakvriend neemt het subsidiebedrag mee in de offerte en helpt bij de aanvraagstukken.'),
        array('Kan een warmtepomp met bestaande vloerverwarming?', 'Dat is vaak gunstig, omdat vloerverwarming met lagere watertemperaturen werkt. We controleren wel groepen, verdeler en regeling.', 'Wat gebeurt er als mijn woning niet geschikt blijkt?', 'Dan krijgt u eerlijk advies over voorbereiding, bijvoorbeeld isolatie, afgifte of ventilatie, voordat u investeert.', 'Waarom niet gewoon de goedkoopste offerte kiezen?', 'Omdat een goedkope installatie met verkeerde plaatsing, te weinig vermogen of ontbrekend elektra-werk later duurder kan zijn.'),
    );
    $benefit_sets = array(
        "🧭|Advies op woningniveau|We beoordelen eerst bouwjaar, isolatie, afgifte en ruimte voordat er een merk op tafel komt.\n🔇|Plaatsing zonder gedoe|Geluid, buren en onderhoudsruimte worden vooraf meegenomen in het plan.\n💶|Subsidie helder verwerkt|U ziet bruto prijs, verwachte ISDE en netto investering apart terug.",
        "🏠|Geen standaardpakket|De keuze tussen hybride, Qvantum, Nibe of bodemwarmte volgt uit uw woning, niet uit een aanbieding.\n🚿|Tapwater eerlijk beoordeeld|Gezin, douchegedrag en beschikbare ruimte tellen mee in de systeemkeuze.\n📋|Offerte met uitleg|Elke grote kostenpost wordt begrijpelijk gemaakt voordat u beslist.",
        "⚙️|Techniekruimte gecheckt|Binnenmodule, leidingen, elektra en eventuele buffer worden vooraf bekeken.\n🌿|Minder gas met beleid|We sturen op besparing zonder comfort of geluid te vergeten.\n📍|Lokale omstandigheden meegewogen|Woningtype, straatbeeld en opstelplek bepalen de uitvoering.",
        "📐|Warmteverlies als basis|Het benodigde vermogen wordt niet gegokt maar onderbouwd met uw situatie.\n🧾|Geen verborgen randwerk|Elektra, leidingroutes en regeling worden meegenomen in de beoordeling.\n🤝|Eerlijk vergelijk|Als een goedkoper systeem beter past, zeggen we dat ook.",
        "🌡️|Comfort in koude weken|We kijken niet alleen naar jaargemiddelden maar naar prestaties wanneer het buiten echt fris is.\n🔁|Voorbereid op later|Hybride kan zo worden gekozen dat een volgende stap naar gasloos logisch blijft.\n🧠|Qvantum en Nibe naast elkaar|U krijgt uitleg over thermische batterij, boileropties en regeling.",
        "🛠️|Installatiepraktijk telt|De offerte gaat ook over bereikbaarheid, montage, condensafvoer en onderhoud.\n⚡|Zonnepanelen slim benut|Eigen stroom wordt meegenomen zonder de wintervraag te overschatten.\n✅|Beslissen met rust|U krijgt een voorstel dat technische keuzes vertaalt naar gewone taal.",
        "🏘️|Geschikt voor drukke wijken|We letten extra op geluid en trillingen bij dicht op elkaar gebouwde woningen.\n🌬️|Ventilatie niet vergeten|Afvoer, WTW en binnenlucht bepalen soms welk systeem logisch is.\n💬|Nazorg inbegrepen|Na installatie blijft Vakvriend aanspreekbaar voor regeling en uitleg.",
        "🌍|Bodemopties waar zinvol|Bij voldoende perceelruimte kijken we ook naar boring en vergunning.\n💧|Warm water zonder aannames|Tapwatercomfort wordt apart besproken, zeker bij Qvantum-systemen.\n📊|Besparing realistisch gemaakt|Geen mooie gemiddelden, maar verwachtingen die passen bij uw verbruik.",
    );
    $review_sets = array(
        "\"De uitleg was helder en niet opdringerig. We wisten eindelijk waarom hybride hier logisch was.\"|Familie uit {city}|Advies en subsidiecheck|5\n\"Netjes meegedacht over de plek van de buitenunit. Dat gaf vertrouwen.\"|Woningeigenaar in {region}|Lucht/water warmtepomp|5",
        "\"Geen verkooppraatje maar een technisch verhaal dat we konden volgen.\"|Bewoner uit {city}|Warmtepomp vergelijking|5\n\"De offerte was duidelijker dan de andere twee die we hadden liggen.\"|Klant in {region}|Offertebeoordeling|5",
        "\"Vooral de uitleg over geluid en leidingwerk was waardevol.\"|Huiseigenaar {city}|Woningcheck|5\n\"Ze keken verder dan alleen de warmtepomp zelf. Precies wat we nodig hadden.\"|Gezin in {region}|Installatievoorstel|5",
        "\"Vakvriend nam ook de minder handige punten van ons huis mee. Dat voelde eerlijk.\"|Bewoner {city}|Hybride advies|5\n\"We kregen eindelijk grip op subsidie, netto prijs en planning.\"|Klant uit {region}|ISDE en offerte|5",
        "\"Prettig dat Qvantum en Nibe gewoon naast elkaar werden uitgelegd.\"|Woningeigenaar in {city}|Merkvergelijking|5\n\"De opname was praktisch en de offerte las verrassend makkelijk.\"|Particulier {region}|Warmtepomp offerte|5",
        "\"Ze dachten goed mee over onze beperkte ruimte binnen.\"|Klant uit {city}|Compact systeemadvies|5\n\"Geen snelle belofte, maar een plan waar we iets aan hadden.\"|Bewoner in {region}|Woninganalyse|5",
        "\"De installatieroute werd vooraf al duidelijk gemaakt. Dat scheelde veel vragen.\"|Huiseigenaar {city}|Voorbereiding installatie|5\n\"Ook de subsidie werd gewoon begrijpelijk uitgelegd.\"|Gezin in {region}|Subsidiecheck|5",
        "\"We wilden gas besparen, maar geen herrie. Daar is serieus naar gekeken.\"|Bewoner uit {city}|Geluid en plaatsing|5\n\"Vakvriend gaf ook aan wat we beter nog niet moesten doen. Dat waardeer ik.\"|Klant {region}|Eerlijk advies|5",
    );
    $faq = vk_pick($faq_sets, $index);
    $extra_faq = $faq[0] . ':::' . vk_contextual_answer($faq[1], $city) . "\n" . $faq[2] . ':::' . vk_contextual_answer($faq[3], $city) . "\n" . $faq[4] . ':::' . vk_contextual_answer($faq[5], $city);
    $local_intro = strtr(vk_pick($intro_styles, $index), array('{city}' => $city, '{region}' => $region, '{area}' => $area));
    $hero_sub = strtr(vk_pick($hero_styles, $index + 2), array('{city}' => $city));
    $alinea1 = strtr(vk_pick($angle_styles, $index + 1), array('{city}' => $city, '{region}' => $region, '{focus}' => $focus));
    $alinea2 = strtr(vk_pick($attention_styles, $index + 3), array('{city}' => $city, '{attention}' => $attention));
    $alinea3 = vk_pick($closing_styles, $index + 5) . ' Voor ' . $city . ' vertalen we dat naar een concreet advies dat past bij ' . $region . '.';
    $extra = strtr(vk_pick($extra_openers, $index), array('{city}' => $city, '{region}' => $region)) . ' ' .
        'Voor ' . $city . ' nemen we daarom de lokale woningmix mee: ' . $area . '. ' . ucfirst($focus) . ', maar alleen wanneer de installatievoorwaarden kloppen.' . "\n\n" .
        vk_pick($extra_middle, $index + 2) . ' In de opname kijken we specifiek naar ' . $attention . ', omdat dat in ' . $region . ' de praktische uitkomst vaak bepaalt.' . "\n\n" .
        vk_pick($extra_close, $index + 4);

    return array(
        'wc_ai_content_status' => 'seo-unique-v2',
        'wc_meta_title' => 'Warmtepomp ' . $city . ' | Advies voor ' . $region,
        'wc_meta_desc' => 'Uniek warmtepompadvies in ' . $city . ': woningcheck, ISDE-berekening en vergelijking van Qvantum, Nibe, hybride of bodemwarmte.',
        'wc_hero_kicker' => 'Gratis subsidiecheck en advies in ' . $city,
        'wc_hero_titel' => 'Warmtepomp offerte aanvragen in ' . $city,
        'wc_hero_subtitel' => $hero_sub,
        'wc_form_titel' => 'Advies voor uw woning in ' . $city,
        'wc_form_subtitel' => 'Beantwoord de vragen en ontvang een check op systeemkeuze, subsidie en plaatsing voor uw situatie in ' . $city . '.',
        'wc_campaign_proof' => "24 uur|eerste reactie op uw aanvraag\nISDE|verwachte subsidie apart berekend\n" . $city . "|advies afgestemd op de lokale woningmix",
        'wc_voordelen_titel' => 'Waarom eerst een woningcheck in ' . $city . '?',
        'wc_voordelen_lead' => 'De juiste warmtepompkeuze hangt in ' . $city . ' af van woningtype, geluid, tapwater en installatieruimte. Daarom werken we niet met standaardpakketten.',
        'wc_voordelen' => vk_contextual_rows(vk_pick($benefit_sets, $index), $city),
        'wc_reviews_titel' => 'Ervaringen met praktisch warmtepompadvies',
        'wc_reviews_lead' => 'Klanten rond ' . $city . ' waarderen vooral dat Vakvriend niet alleen een toestel noemt, maar uitlegt wat de keuze voor hun woning betekent.',
        'wc_reviews' => vk_pick($review_sets, $index),
        'wc_lokale_h2' => 'Warmtepompadvies voor woningen in ' . $city,
        'wc_lokale_intro' => $local_intro,
        'wc_lokale_alinea1' => $alinea1,
        'wc_lokale_alinea2' => $alinea2,
        'wc_lokale_alinea3' => $alinea3,
        'wc_lokale_faq_v1' => strtr($faq[0], array('{city}' => $city)),
        'wc_lokale_faq_a1' => vk_contextual_answer(strtr($faq[1], array('{city}' => $city)), $city),
        'wc_lokale_faq_v2' => strtr($faq[2], array('{city}' => $city)),
        'wc_lokale_faq_a2' => vk_contextual_answer(strtr($faq[3], array('{city}' => $city)), $city),
        'wc_lokale_faq_v3' => strtr($faq[4], array('{city}' => $city)),
        'wc_lokale_faq_a3' => vk_contextual_answer(strtr($faq[5], array('{city}' => $city)), $city),
        'wc_faq_extra' => strtr($extra_faq, array('{city}' => $city)),
        'wc_extra_tekst' => $extra,
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

$index = 0;
foreach ($pages as $title => $spec) {
    $post_id = vk_find_page_by_title($title);
    if (!$post_id) {
        echo 'Missing page: ' . $title . PHP_EOL;
        $index++;
        continue;
    }

    vk_apply_meta($post_id, vk_local_meta($title, $spec, $index));
    echo 'Updated unique local content: ' . $title . PHP_EOL;
    $index++;
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
