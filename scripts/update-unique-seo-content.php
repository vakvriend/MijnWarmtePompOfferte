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
    'Warmtepomp Aalsmeer' => array('city' => 'Aalsmeer', 'region' => 'de regio Amstelveen', 'area' => 'vrijstaande woningen, gezinswoningen en huizen met regelmatig voldoende buitenruimte', 'focus' => 'lucht/water en hybride systemen zijn vaak snel te beoordelen', 'attention' => 'buitenunitpositie, geluidsafstand tot buren, tapwatergebruik en de meterkastcapaciteit'),
    'Warmtepomp Badhoevedorp' => array('city' => 'Badhoevedorp', 'region' => 'Haarlemmermeer', 'area' => 'gezinswoningen, appartementen en naoorlogse woningen dicht bij Amsterdam', 'focus' => 'hybride of lucht/water moet zorgvuldig worden afgestemd op geluid en ruimte', 'attention' => 'erfgrenzen, dak- of tuinopstelling, leidingroute en bestaande radiatoren'),
    'Warmtepomp Diemen' => array('city' => 'Diemen', 'region' => 'de regio Amsterdam', 'area' => 'appartementen, rijwoningen en nieuwere stadswoningen met beperkte buitenruimte', 'focus' => 'compacte oplossingen en ventilatievarianten verdienen extra aandacht', 'attention' => 'VvE-afspraken, balkon- of dakopstelling, ventilatie en geluid naar buren'),
    'Warmtepomp Ouderkerk' => array('city' => 'Ouderkerk', 'region' => 'de regio Amstelland', 'area' => 'dijkwoningen, gezinswoningen en vrijstaande woningen met uiteenlopende bouwjaren', 'focus' => 'maatwerk is belangrijk door verschil in isolatie en perceelruimte', 'attention' => 'vloerisolatie, kruipruimte, leidingroutes, buitenunitpositie en bodemwarmte-kansen'),
    'Warmtepomp Uithoorn' => array('city' => 'Uithoorn', 'region' => 'de regio Amstelland', 'area' => 'rijwoningen, gezinswoningen en woningen met vaak praktische installatieruimte', 'focus' => 'hybride en lucht/water kunnen goed naast all-electric worden gelegd', 'attention' => 'radiatorcapaciteit, zonnepanelen, tapwaterprofiel en plaatsing van de buitenunit'),
    'Warmtepomp Hoofddorp' => array('city' => 'Hoofddorp', 'region' => 'Haarlemmermeer', 'area' => 'ruime gezinswoningen, nieuwere wijken en woningen met vaak goede isolatiekansen', 'focus' => 'all-electric is regelmatig haalbaar, maar hybride blijft interessant bij bestaande radiatoren', 'attention' => 'warmteverlies, vloerverwarming, tapwaterbehoefte en geluidspositie van de buitenunit'),
    'Warmtepomp Nieuw Vennep' => array('city' => 'Nieuw Vennep', 'region' => 'Haarlemmermeer', 'area' => 'gezinswoningen, nieuwere woonwijken en woningen met praktische buitenruimte', 'focus' => 'lucht/water en hybride systemen zijn vaak logisch te vergelijken', 'attention' => 'buitenunitlocatie, leidinglengte, afgiftesysteem en eventuele voorbereiding op all-electric'),
    'Warmtepomp Purmerend' => array('city' => 'Purmerend', 'region' => 'Waterland', 'area' => 'rijwoningen, hoekwoningen en nieuwere wijken met redelijk voorspelbaar warmteverbruik', 'focus' => 'hybride of lucht/water kan snel inzicht geven in gasbesparing', 'attention' => 'dakdoorvoer, buitenunit opstelling en het verschil tussen stadsverwarming en gasketel-situaties'),
    'Warmtepomp Kennemerland' => array('city' => 'Kennemerland', 'region' => 'Kennemerland', 'area' => 'Haarlem, Bloemendaal, Heemstede, Velsen en Beverwijk met veel verschillende woningtypen', 'focus' => 'een regionale vergelijking helpt om de juiste oplossing per woningtype te kiezen', 'attention' => 'kustklimaat, beperkte buitenruimte, oudere bouw en geluidsnormen'),
    'Warmtepomp Zaanstreek' => array('city' => 'Zaanstreek', 'region' => 'de Zaanstreek', 'area' => 'Zaandam, Wormerveer, Krommenie en omliggende dorpen met veel verschillende bouwjaren', 'focus' => 'een regionale opname voorkomt dat hybride, Qvantum of Nibe te algemeen wordt gekozen', 'attention' => 'isolatieverschillen, vocht en ventilatie, leidingroutes en buitenunitgeluid'),
    'Warmtepomp Heiloo' => array('city' => 'Heiloo', 'region' => 'de regio Alkmaar', 'area' => 'gezinswoningen, vrijstaande woningen en rustige woonstraten met vaak ruimte rond de woning', 'focus' => 'lucht/water en bodemwarmte kunnen beide interessant zijn bij voldoende voorbereiding', 'attention' => 'perceelruimte, geluidspositie, vloerverwarming en isolatieniveau'),
    'Warmtepomp Castricum' => array('city' => 'Castricum', 'region' => 'Kennemerland', 'area' => 'gezinswoningen, oudere dorpsbebouwing en woningen richting de kust', 'focus' => 'kustklimaat en geluid maken een goede systeemkeuze extra belangrijk', 'attention' => 'corrosiebestendige montage, windbelasting, buitenunitpositie en warmteverlies'),
    'Warmtepomp Bloemendaal' => array('city' => 'Bloemendaal', 'region' => 'Kennemerland', 'area' => 'villa’s, jaren-30-woningen en ruime woningen met hoge comfortwensen', 'focus' => 'stilte, afwerking en betrouwbare dimensionering wegen hier zwaar', 'attention' => 'geluidspositie, esthetiek, lage temperatuur verwarming en eventuele bodemopties'),
    'Warmtepomp Zandvoort' => array('city' => 'Zandvoort', 'region' => 'Kennemerland', 'area' => 'kustwoningen, appartementen en compacte woningen met wind en zoutbelasting', 'focus' => 'een robuuste lucht/water of hybride oplossing vraagt extra aandacht voor buitenopstelling', 'attention' => 'kustklimaat, corrosie, geluid, balkon- of dakopstelling en ventilatie'),
    'Warmtepomp IJmuiden' => array('city' => 'IJmuiden', 'region' => 'Kennemerland', 'area' => 'rijwoningen, appartementen en kustwoningen met uiteenlopende isolatie', 'focus' => 'hybride en lucht/water moeten goed op warmteverlies en buitenunitpositie worden beoordeeld', 'attention' => 'windbelasting, zout klimaat, geluid en bestaande radiatoren'),
    'Warmtepomp Beverwijk' => array('city' => 'Beverwijk', 'region' => 'Kennemerland', 'area' => 'rijwoningen, gezinswoningen en oudere woningen met duidelijke isolatiekansen', 'focus' => 'hybride of lucht/water kan interessant zijn als radiatoren en isolatie kloppen', 'attention' => 'radiatorcapaciteit, dakisolatie, buitenunitplek en tapwaterbehoefte'),
    'Warmtepomp Heerhugowaard' => array('city' => 'Heerhugowaard', 'region' => 'de regio Dijk en Waard', 'area' => 'veel gezinswoningen en nieuwere wijken met praktische technische ruimtes', 'focus' => 'lucht/water warmtepompen zijn vaak interessant, zeker bij goede isolatie', 'attention' => 'buffervat, buitenunit, leidinglengtes en eventuele voorbereiding op volledig elektrisch'),
    'Warmtepomp Landsmeer' => array('city' => 'Landsmeer', 'region' => 'Waterland', 'area' => 'vrijstaande woningen, dijkwoningen en gezinswoningen nabij Amsterdam-Noord', 'focus' => 'maatwerk is belangrijk door uiteenlopende bouwjaren en beschikbare ruimte', 'attention' => 'geluid naar buren, fundering/kruipruimte en de afstand tussen binnen- en buitenunit'),
    'Warmtepomp Wormerland' => array('city' => 'Wormerland', 'region' => 'de Zaanstreek', 'area' => 'dorpswoningen, lintbebouwing en woningen met relatief veel buitenruimte', 'focus' => 'een lucht/water systeem kan interessant zijn wanneer isolatie en radiatoren kloppen', 'attention' => 'plaatsing uit het zicht, leidingwerk en de combinatie met ventilatie'),
    'Warmtepomp Bussum' => array('city' => 'Bussum', 'region' => 'het Gooi', 'area' => 'jaren-30-woningen, appartementen en gezinswoningen met wisselend isolatieniveau', 'focus' => 'stilte en comfort wegen zwaar bij de keuze tussen hybride, Qvantum en Nibe', 'attention' => 'geluidspositie, radiatorcapaciteit, erfgrenzen en nette leidingroutes'),
    'Warmtepomp Naarden' => array('city' => 'Naarden', 'region' => 'het Gooi', 'area' => 'historische woningen, villa’s en gezinswoningen met uiteenlopende bouwkundige eisen', 'focus' => 'maatwerk is nodig door monumentale details en comfortwensen', 'attention' => 'isolatie, geluidspositie, esthetische plaatsing en lage temperatuur verwarming'),
    'Warmtepomp Huizen' => array('city' => 'Huizen', 'region' => 'het Gooi', 'area' => 'gezinswoningen, vrijstaande woningen en woningen met regelmatig praktische buitenruimte', 'focus' => 'lucht/water en hybride kunnen goed worden vergeleken op investering en comfort', 'attention' => 'buitenunitplek, geluid, vloerverwarming en tapwaterprofiel'),
    'Warmtepomp Laren' => array('city' => 'Laren', 'region' => 'het Gooi', 'area' => 'vrijstaande woningen, villa’s en karakteristieke woningen met hoge comforteisen', 'focus' => 'stille plaatsing en zorgvuldige dimensionering zijn belangrijker dan een standaardpakket', 'attention' => 'erfgrenzen, esthetiek, bodemwarmte-kansen en lage temperatuur afgifte'),
    'Warmtepomp Blaricum' => array('city' => 'Blaricum', 'region' => 'het Gooi', 'area' => 'vrijstaande woningen, villa’s en ruime percelen met uiteenlopende installatieruimtes', 'focus' => 'bodemwarmte, lucht/water en hybride verdienen een eerlijke technische vergelijking', 'attention' => 'perceelruimte, geluid, leidingroutes, afgiftesysteem en tapwatercomfort'),
    'Warmtepomp Weesp' => array('city' => 'Weesp', 'region' => 'het Gooi', 'area' => 'stadswoningen, appartementen en gezinswoningen met wisselende isolatie', 'focus' => 'compacte oplossingen en hybride systemen kunnen hier praktisch zijn', 'attention' => 'beperkte buitenruimte, VvE-afspraken, ventilatie en bestaande radiatoren'),
    'Warmtepomp Gooi' => array('city' => 'Gooi', 'region' => 'het Gooi', 'area' => 'Bussum, Naarden, Laren, Blaricum, Huizen en Hilversum met veel verschillende woningtypen', 'focus' => 'stilte, comfort en merkonafhankelijk advies zijn in deze regio belangrijk', 'attention' => 'geluidspositie, afgiftesysteem, esthetische plaatsing en bodemwarmte-kansen'),
    'Warmtepomp Lelystad' => array('city' => 'Lelystad', 'region' => 'Flevoland', 'area' => 'gezinswoningen, nieuwere wijken en woningen met vaak praktische installatieruimte', 'focus' => 'all-electric is regelmatig haalbaar, maar hybride blijft zinvol bij bestaande radiatoren', 'attention' => 'warmteverlies, zonnepanelen, buitenunitplek en tapwatergebruik'),
    'Warmtepomp Dronten' => array('city' => 'Dronten', 'region' => 'Flevoland', 'area' => 'gezinswoningen, vrijstaande woningen en woningen met relatief veel ruimte', 'focus' => 'lucht/water en bodemwarmte kunnen beide interessant zijn bij goede voorbereiding', 'attention' => 'perceelruimte, leidinglengte, vloerverwarming en meterkastcapaciteit'),
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
        'wc_lokale_alinea1' => 'De warmtepomp slaat warmte op in de thermische batterij en gebruikt die later voor verwarming en tapwater. Het douchewater zelf wordt niet als voorraad opgeslagen.',
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
        "⚙️|Techniekruimte gecheckt|Binnenmodule, leidingen, elektra en eventuele buffer worden vooraf bekeken.\n🌿|Minder gas met beleid|We sturen op besparing zonder comfort of geluid te vergeten.\n📍|Opstelplek vooraf beoordeeld|Woningtype, geluidspositie en onderhoudsruimte bepalen de uitvoering.",
        "📐|Warmteverlies als basis|Het benodigde vermogen wordt niet gegokt maar onderbouwd met uw situatie.\n🧾|Geen verborgen randwerk|Elektra, leidingroutes en regeling worden meegenomen in de beoordeling.\n🤝|Eerlijk vergelijk|Als een goedkoper systeem beter past, zeggen we dat ook.",
        "🌡️|Comfort in koude weken|We kijken niet alleen naar jaargemiddelden maar naar prestaties wanneer het buiten echt fris is.\n🔁|Voorbereid op later|Hybride kan zo worden gekozen dat een volgende stap naar gasloos logisch blijft.\n🧠|Qvantum en Nibe naast elkaar|U krijgt uitleg over thermische batterij, boileropties en regeling.",
        "🛠️|Installatiepraktijk telt|De offerte gaat ook over bereikbaarheid, montage, condensafvoer en onderhoud.\n⚡|Zonnepanelen slim benut|Eigen stroom wordt meegenomen zonder de wintervraag te overschatten.\n✅|Beslissen met rust|U krijgt een voorstel dat technische keuzes vertaalt naar gewone taal.",
        "🏘️|Geschikt voor drukke wijken|We letten extra op geluid en trillingen bij dicht op elkaar gebouwde woningen.\n🌬️|Ventilatie niet vergeten|Afvoer, WTW en binnenlucht bepalen soms welk systeem logisch is.\n💬|Nazorg inbegrepen|Na installatie blijft Vakvriend aanspreekbaar voor regeling en uitleg.",
        "🌍|Bodemopties waar zinvol|Bij voldoende perceelruimte kijken we ook naar boring en vergunning.\n💧|Warm water zonder aannames|Tapwatercomfort wordt apart besproken, zeker bij Qvantum-systemen.\n📊|Besparing realistisch gemaakt|Geen mooie gemiddelden, maar verwachtingen die passen bij uw verbruik.",
    );
    $faq = vk_pick($faq_sets, $index);
    $extra_faq = $faq[0] . ':::' . vk_contextual_answer($faq[1], $city) . "\n" . $faq[2] . ':::' . vk_contextual_answer($faq[3], $city) . "\n" . $faq[4] . ':::' . vk_contextual_answer($faq[5], $city);
    $local_intro = strtr(vk_pick($intro_styles, $index), array('{city}' => $city, '{region}' => $region, '{area}' => $area));
    $hero_sub = strtr(vk_pick($hero_styles, $index + 2), array('{city}' => $city));
    $alinea1 = strtr(vk_pick($angle_styles, $index + 1), array('{city}' => $city, '{region}' => $region, '{focus}' => $focus));
    $alinea2 = strtr(vk_pick($attention_styles, $index + 3), array('{city}' => $city, '{attention}' => $attention));
    $alinea3 = vk_pick($closing_styles, $index + 5) . ' Voor ' . $city . ' vertalen we dat naar een concreet advies dat past bij ' . $region . '.';
    $extra = strtr(vk_pick($extra_openers, $index), array('{city}' => $city, '{region}' => $region)) . ' ' .
        'Voor ' . $city . ' nemen we daarom de technische uitgangspunten mee: ' . $area . '. ' . ucfirst($focus) . ', maar alleen wanneer de installatievoorwaarden kloppen.' . "\n\n" .
        vk_pick($extra_middle, $index + 2) . ' In de opname kijken we specifiek naar ' . $attention . ', omdat dat in ' . $region . ' de praktische uitkomst vaak bepaalt.' . "\n\n" .
        vk_pick($extra_close, $index + 4);
    $qvantum_types = "💨|Qvantum QA — lucht/water warmtepomp|De QA haalt warmte uit de buitenlucht en geeft die af aan het cv-water. Het systeem werkt met R290 koudemiddel, een QH-hydromodule en een thermische batterij voor verwarming en tapwater. In de opname controleren we vermogen, geluidspositie en leidingroute.\n" .
        "🌬️|Qvantum QE — ventilatiewarmtepomp|De QE gebruikt warmte uit afvoerventilatielucht en heeft geen buitenunit nodig. Daardoor is dit systeem interessant bij appartementen of woningen waar buitenunitplaatsing lastig is. Met de QS-unit kan de oplossing ook als WTW-configuratie worden bekeken.\n" .
        "🌍|Qvantum QG — water/water warmtepomp|De QG is bedoeld voor bodem- of brontoepassingen. Dit vraagt een boring of bron en een goed ontwerp van het afgiftesysteem. Voor uw woning in " . $city . " beoordelen we vooraf of zo'n bodemoplossing technisch en financieel logisch is.";
    $qvantum_usps = "Thermische batterij|Alle Qvantum-systemen werken met thermische opslag. De warmtepomp bewaart warmte, zodat het systeem slimmer kan omgaan met verbruik en stroommomenten.\n" .
        "Warm tapwater via platenwisselaar|Het tapwater zit niet opgeslagen in een groot boilervat. Koud leidingwater stroomt langs een platenwisselaar en wordt direct verwarmd met warmte uit de thermische batterij.\n" .
        "QA, QE en QG|Qvantum heeft een lucht/water-, ventilatie- en water/water-oplossing. De juiste keuze hangt af van woningtype, ventilatie, ruimte en warmteverlies.\n" .
        "Merkonafhankelijk bekeken|Vakvriend vergelijkt Qvantum altijd met andere passende oplossingen. Als Nibe, hybride of bodemwarmte beter past, hoort u dat gewoon.";
    $nibe_types = "💨|Nibe lucht/water warmtepomp|Nibe lucht/water systemen combineren een buitenunit met een binnenmodule en zijn geschikt voor hybride of all-electric toepassingen. Belangrijk zijn de juiste vermogensselectie, geluidspositie en het afgiftesysteem in de woning.\n" .
        "🌍|Nibe bodem/water warmtepomp|Een Nibe bodemwarmtepomp gebruikt een stabiele bron in de bodem. Dat geeft hoog rendement en rustige werking, maar vraagt wel boring, vergunning, bronontwerp en voldoende voorbereiding.\n" .
        "⚡|Nibe hybride oplossing|Een hybride Nibe-opstelling werkt samen met een cv-ketel. Voor " . $city . " kan dat verstandig zijn als radiatoren, isolatie of tapwater nog niet klaar zijn voor volledig elektrisch verwarmen.";
    $warmtepomp_types = "💨|Meest toegepast|Lucht/water warmtepomp|Haalt warmte uit buitenlucht en geeft die via water af aan radiatoren of vloerverwarming. Geschikt voor hybride en volledig elektrische installaties, mits vermogen en geluid goed worden ontworpen voor de woning in " . $city . ".|Geen bodemboring nodig;Geschikt voor bestaande woningen;Let op buitenunitpositie;ISDE-subsidie mogelijk;Goede warmteverliesberekening nodig|Qvantum QA of Nibe lucht/water\n" .
        "🌬️|Zonder buitenunit mogelijk|Ventilatie warmtepomp|Gebruikt warmte uit afvoerventilatielucht. Vooral interessant bij goed geventileerde woningen, appartementen of situaties waar een buitenunit niet wenselijk is.|Geen buitenunit;Ventilatie moet geschikt zijn;Compacte installatie;Tapwater apart beoordelen;Niet elke woning is geschikt|Qvantum QE met mogelijke QS-combinatie\n" .
        "🌍|Hoog rendement|Bodemwarmtepomp|Gebruikt warmte uit de bodem via een gesloten bron of boring. Dit is technisch sterk, maar vraagt meer voorbereiding en een hogere investering.|Stabiele bron;Hoog rendement;Boring en vergunning nodig;Interessant voor lange termijn;Past vooral bij geschikte percelen|Nibe bodem/water of Qvantum QG";
    $vv_props = "🏗️|Technische opname in " . $city . "|Vakvriend kijkt naar bouwjaar, isolatie, afgiftesysteem, tapwater en installatieruimte voordat er een merk wordt gekozen.\n" .
        "🔇|Geluid en buren vooraf geregeld|In " . $region . " beoordelen we buitenunitpositie, trillingsdemping, erfgrens en onderhoudsruimte voordat u akkoord geeft.\n" .
        "📋|Merkonafhankelijk advies|Qvantum, Nibe en andere passende warmtepompoplossingen worden naast elkaar gelegd. De woning in " . $city . " bepaalt de richting, niet de marge op een toestel.\n" .
        "💶|ISDE in de lokale rekensom|U ziet bruto prijs, subsidie-inschatting, installatiewerk en voorbereiding apart terug, afgestemd op uw situatie in " . $city . ".";
    $werkwijze = "01|Woning in " . $city . " beoordelen|We starten met bouwjaar, gasverbruik, isolatie, afgiftesysteem en foto's van de technische ruimte. Daarbij nemen we " . $attention . " direct mee.\n" .
        "02|Qvantum, Nibe of hybride kiezen|Daarna vergelijken we de routes die voor " . $city . " logisch zijn: compacte Qvantum, brede Nibe-oplossing, hybride tussenstap of bodemwarmte.\n" .
        "03|Plaatsing en subsidie uitwerken|U krijgt een voorstel met opstelplek, leidingroute, elektra, verwachte ISDE en eventuele boring of vergunning in " . $region . ".\n" .
        "04|Installeren en uitleggen|Na akkoord plannen we de installatie, regelen we de subsidiepapieren en stellen we het systeem zo in dat comfort en verbruik bij uw woning passen.";
    $boringen_lead = 'Een bodemwarmtepomp is niet overal de beste keuze. Voor ' . $city . ' kijken we eerst naar perceelruimte, ondergrond, bereikbaarheid en vergunning voordat we een boring adviseren.';
    $boringen_kaart_tekst = 'Vakvriend beoordeelt per aanvraag in ' . $region . ' of een bronboring technisch en financieel zinvol is. Als lucht/water of hybride slimmer is voor ' . $city . ', zeggen we dat gewoon.';

    return array(
        'wc_ai_content_status' => 'seo-unique-v2',
        'wc_meta_title' => 'Warmtepomp ' . $city . ' | Advies voor ' . $region,
        'wc_meta_desc' => 'Uniek warmtepompadvies in ' . $city . ': woningcheck, ISDE-berekening en vergelijking van Qvantum, Nibe, hybride of bodemwarmte.',
        'wc_hero_kicker' => 'Gratis subsidiecheck en advies in ' . $city,
        'wc_hero_titel' => 'Warmtepomp offerte aanvragen in ' . $city,
        'wc_hero_subtitel' => $hero_sub,
        'wc_form_titel' => 'Advies voor uw woning in ' . $city,
        'wc_form_subtitel' => 'Beantwoord de vragen en ontvang een check op systeemkeuze, subsidie en plaatsing voor uw situatie in ' . $city . '.',
        'wc_campaign_proof' => "24 uur|eerste reactie op uw aanvraag\nISDE|verwachte subsidie apart berekend\n" . $city . "|advies op basis van woninggegevens",
        'wc_qvantum_eyebrow' => 'Qvantum advies voor ' . $city,
        'wc_qvantum_titel' => 'Qvantum warmtepomp: QA, QE en QG helder uitgelegd',
        'wc_qvantum_lead' => 'Qvantum heeft drie hoofdoplossingen: QA lucht/water, QE ventilatie en QG water/water. Alle systemen werken met thermische opslag. Vakvriend bekijkt voor uw woning in ' . $city . ' of Qvantum technisch logisch is, of dat Nibe, hybride of bodemwarmte beter past.',
        'wc_qvantum_types' => $qvantum_types,
        'wc_qvantum_uitleg_titel' => 'Warm water zonder groot boilervat',
        'wc_qvantum_uitleg_tekst' => 'Qvantum slaat warmte op in een thermische batterij. Tapwater wordt niet als voorraad in een groot boilervat bewaard. Wanneer u warm water vraagt, stroomt koud leidingwater langs een platenwisselaar. Die haalt warmte uit de batterij en verwarmt het water direct en vers. Voor uw woning in ' . $city . ' beoordelen we doucheprofiel, leidinglengtes en beschikbare technische ruimte.',
        'wc_qvantum_usps' => $qvantum_usps,
        'wc_qvantum_cta' => 'Qvantum check voor ' . $city . ' aanvragen',
        'wc_nibe_eyebrow' => 'Nibe configuratie voor ' . $city,
        'wc_nibe_titel' => 'Nibe warmtepomp kiezen voor uw woning',
        'wc_nibe_lead' => 'Nibe is een bewezen warmtepompmerk met lucht/water-, bodem/water- en hybride oplossingen. Vakvriend kijkt voor uw woning in ' . $city . ' vooral naar warmteverlies, afgiftesysteem, tapwater, geluidspositie en de stap naar hybride of volledig elektrisch.',
        'wc_nibe_types' => $nibe_types,
        'wc_nibe_cta' => 'Nibe advies voor ' . $city . ' aanvragen',
        'wc_types_eyebrow' => 'Systeemkeuze in ' . $region,
        'wc_types_titel' => 'Welke warmtepomp past bij uw woning?',
        'wc_types_lead' => 'De juiste techniek hangt af van warmteverlies, isolatie, afgiftesysteem, tapwater en beschikbare ruimte. Voor uw woning in ' . $city . ' leggen we lucht/water, ventilatie, hybride en bodemwarmte merkonafhankelijk naast elkaar.',
        'wc_warmtepomp_types' => $warmtepomp_types,
        'wc_vv_eyebrow' => 'Vakvriend in ' . $region,
        'wc_vv_titel' => 'Waarom Vakvriend voor uw woning in ' . $city . '?',
        'wc_vv_intro' => 'Vakvriend adviseert merkonafhankelijk en installeert warmtepompen voor woningen in ' . $city . ' en ' . $region . '. We kijken niet alleen naar Qvantum of Nibe, maar naar woningtype, geluid, tapwater, subsidie en de praktische plaatsing in en om uw huis.',
        'wc_vv_usp1' => 'Qvantum & Nibe advies voor ' . $city,
        'wc_vv_usp2' => '200+ installaties als technische basis',
        'wc_vv_usp3' => '4,6 / 5 sterren beoordeling',
        'wc_vv_usp4' => 'Actief in ' . $region . ' en omgeving',
        'wc_vv_props' => $vv_props,
        'wc_voordelen_titel' => 'Waarom eerst een woningcheck in ' . $city . '?',
        'wc_voordelen_lead' => 'De juiste warmtepompkeuze hangt in ' . $city . ' af van woningtype, geluid, tapwater en installatieruimte. Daarom werken we niet met standaardpakketten.',
        'wc_voordelen' => vk_contextual_rows(vk_pick($benefit_sets, $index), $city),
        'wc_werkwijze_eyebrow' => 'Aanpak voor ' . $city,
        'wc_werkwijze_titel' => 'Van aanvraag in ' . $city . ' naar een passend warmtepompplan',
        'wc_werkwijze_lead' => 'Vakvriend maakt de route concreet: eerst woningcheck, dan merkonafhankelijk systeemadvies, daarna subsidie, plaatsing en installatie. Zo weet u vooraf wat er in uw woning in ' . $city . ' moet gebeuren.',
        'wc_werkwijze' => $werkwijze,
        'wc_boringen_eyebrow' => 'Bodemwarmte in ' . $region,
        'wc_boringen_titel' => 'Grondboring voor een warmtepomp in ' . $city,
        'wc_boringen_lead' => $boringen_lead,
        'wc_boringen_cta' => 'Boring in ' . $city . ' laten beoordelen',
        'wc_boringen_kaart_titel' => 'Boring alleen als het past bij ' . $city,
        'wc_boringen_kaart_tekst' => $boringen_kaart_tekst,
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
    );
}

function vk_special_support_meta($title) {
    $topics = array(
        'Warmtepomp Zonder Boiler' => array(
            'label' => 'zonder boilervat',
            'angle' => 'ruimtebesparing, vers tapwater en uitleg over de thermische batterij',
            'qvantum' => 'Op deze pagina leggen we Qvantum bewust extra simpel uit: warmte wordt opgeslagen in de thermische batterij, terwijl douchewater pas via de platenwisselaar wordt verwarmd op het moment dat u de kraan opent.',
            'nibe' => 'Nibe blijft hier de vergelijkingsbasis voor bezoekers die willen weten wanneer een klassiekere configuratie met boiler of buffervat verstandiger is.',
            'boring' => 'Een boring hoort niet automatisch bij een warmtepomp zonder boiler. We bekijken eerst of een compacte Qvantum-oplossing logischer is dan bodemwarmte.',
        ),
        'Warmtepomp Vergelijken' => array(
            'label' => 'offertes vergelijken',
            'angle' => 'prijsverschillen, ontbrekend installatiewerk en appels-met-appels vergelijken',
            'qvantum' => 'Bij Qvantum letten we in vergelijkingen vooral op thermische batterij, platenwisselaar, tapwatercomfort en de vraag of QA, QE of QG eerlijk naast een ander merk is gezet.',
            'nibe' => 'Bij Nibe kijken we naar modelkeuze, bewezen techniek, geluidswaarden, boilerconfiguratie en of het voorgestelde vermogen past bij de woning.',
            'boring' => 'Een bodemofferte lijkt duurder, maar kan logisch zijn als boring, vergunning, bron en lange levensduur goed zijn meegenomen.',
        ),
        'Mijn Warmtepomp Offerte' => array(
            'label' => 'persoonlijke offerte',
            'angle' => 'woninggegevens, verbruik, subsidie en een offerte die technisch klopt',
            'qvantum' => 'Voor een persoonlijke offerte bekijken we of Qvantum past bij de beschikbare ruimte, tapwaterwens, ventilatie en het idee van warmteopslag zonder groot boilervat.',
            'nibe' => 'Nibe nemen we mee wanneer betrouwbaarheid, brede configuratiekeuze en een rustige route naar hybride of all-electric belangrijk zijn.',
            'boring' => 'Bodemwarmte komt alleen in de offerte wanneer perceel, budget, vergunning en warmteverlies dat serieus rechtvaardigen.',
        ),
        'Warmtepomp Qvantum' => array(
            'label' => 'Qvantum warmtepomp',
            'angle' => 'QA, QE, QG, thermische batterij en vers tapwater via platenwisselaar',
            'qvantum' => 'Alle Qvantum-systemen gebruiken thermische opslag. De QA gebruikt buitenlucht, de QE ventilatielucht en de QG een bodem- of waterbron; het tapwater wordt vers verwarmd via een platenwisselaar.',
            'nibe' => 'Nibe staat hier vooral als eerlijke tegenhanger: soms is een Nibe-configuratie logischer wanneer ruimte, tapwaterprofiel of afgiftesysteem daarom vraagt.',
            'boring' => 'Bij Qvantum QG beoordelen we boring en bron apart, omdat thermische opslag alleen waarde heeft als de bodemoplossing zelf klopt.',
        ),
    );
    $topic = isset($topics[$title]) ? $topics[$title] : $topics['Mijn Warmtepomp Offerte'];

    return array(
        'wc_qvantum_eyebrow' => 'Verdieping · ' . $topic['label'],
        'wc_qvantum_titel' => 'Qvantum bekeken vanuit ' . $topic['label'],
        'wc_qvantum_lead' => $topic['qvantum'],
        'wc_qvantum_uitleg_titel' => 'Thermische batterij zonder misverstand',
        'wc_qvantum_uitleg_tekst' => $topic['qvantum'] . ' Het kinderlijke beeld blijft hetzelfde: de batterij is de warme steen, de platenwisselaar maakt pas warm water wanneer iemand het nodig heeft.',
        'wc_nibe_eyebrow' => 'Vergelijking · Nibe',
        'wc_nibe_titel' => 'Nibe als nuchtere vergelijking voor ' . $topic['label'],
        'wc_nibe_lead' => $topic['nibe'],
        'wc_types_eyebrow' => 'Keuzehulp · ' . $topic['label'],
        'wc_types_titel' => 'Welke warmtepomp hoort bij ' . $topic['label'] . '?',
        'wc_types_lead' => 'Deze pagina focust op ' . $topic['angle'] . '. Daarom bekijken we lucht/water, ventilatie, hybride en bodemwarmte vanuit dat specifieke vertrekpunt.',
        'wc_vv_eyebrow' => 'Vakvriend advies',
        'wc_vv_titel' => 'Waarom Vakvriend bij ' . $topic['label'] . '?',
        'wc_vv_intro' => 'Vakvriend vertaalt ' . $topic['label'] . ' naar een praktisch plan met merkonafhankelijk systeemadvies, installatiewerk, ISDE-subsidie en heldere uitleg. Geen losse modelpraat, maar advies dat u naast andere offertes kunt leggen.',
        'wc_werkwijze_eyebrow' => 'Aanpak · ' . $topic['label'],
        'wc_werkwijze_titel' => 'Van vraag naar bruikbare keuze',
        'wc_werkwijze_lead' => 'We starten bij ' . $topic['angle'] . ', controleren daarna woninggegevens en maken pas dan een merkonafhankelijk voorstel met vermogen, plaatsing en subsidie.',
        'wc_boringen_eyebrow' => 'Bodemwarmte afwegen',
        'wc_boringen_titel' => 'Boring alleen wanneer het logisch is',
        'wc_boringen_lead' => $topic['boring'],
        'wc_boringen_kaart_tekst' => $topic['boring'] . ' Vakvriend zet dit apart in de offerte, zodat bodemwarmte geen dure bijzin wordt.',
    );
}

function vk_apply_meta($post_id, $meta) {
    foreach (array('wc_extra_tekst', 'wc_reviews_titel', 'wc_reviews_lead', 'wc_reviews') as $key) {
        delete_post_meta($post_id, $key);
    }

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
    $meta = array_merge(vk_special_support_meta($title), $meta);
    vk_apply_meta($post_id, $meta);
    echo 'Updated unique special content: ' . $title . PHP_EOL;
}

if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}

echo 'Done.' . PHP_EOL;
