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
        'wc_focus_keyword' => 'warmtepomp zonder boiler',
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
        'wc_focus_keyword' => 'warmtepomp offertes vergelijken',
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
        'wc_focus_keyword' => 'warmtepomp offerte',
        'wc_hero_titel' => 'Mijn warmtepomp offerte aanvragen',
        'wc_hero_subtitel' => 'Ontvang een duidelijke offerte die past bij uw woning, verbruik en comfortwensen. Vakvriend rekent subsidie en besparing meteen mee.',
        'wc_lokale_h2' => 'Een offerte die begint bij uw woning',
        'wc_lokale_intro' => 'Een warmtepomp is geen standaardproduct. De juiste keuze hangt af van isolatie, radiatoren, vloerverwarming, tapwater en beschikbare ruimte.',
        'wc_lokale_alinea1' => 'Vakvriend kijkt eerst naar de woning en pas daarna naar het merk of model. Daardoor krijgt u een voorstel dat technisch klopt.',
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
        'wc_focus_keyword' => 'qvantum warmtepomp',
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
            $parts[2] = rtrim($parts[2], '.') . '.';
        }
        $out[] = implode('|', $parts);
    }
    return implode("\n", $out);
}

function vk_add_row_context($rows, $text_index, $context) {
    $out = array();
    $done = false;
    foreach (explode("\n", $rows) as $row) {
        $parts = explode('|', $row);
        if (!$done && isset($parts[$text_index])) {
            $parts[$text_index] = rtrim($parts[$text_index], '.') . '. ' . $context;
            $done = true;
        }
        $out[] = implode('|', $parts);
    }
    return implode("\n", $out);
}

function vk_contextual_answer($answer, $city) {
    return rtrim($answer, '.') . '.';
}

function vk_clean_sentence($text) {
    return rtrim(trim((string) $text), '.') . '.';
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
        'In {region} kan dezelfde warmtepomp bij de ene woning goed passen en bij de andere woning onlogisch zijn. Daarom begint Vakvriend met warmteverlies, afgifte en plaatsing.',
        '{city} verdient een offerte waarin de praktische kanten helder zijn: waar komt de unit, hoe blijft het stil en wat doet het systeem op koude dagen?',
    );
    $hero_styles = array(
        'Geen standaard warmtepompverhaal, maar een advies voor {city} op basis van woningtype, gasverbruik, geluid en de echte installatieruimte.',
        'Vakvriend rekent voor uw woning in {city} uit welke warmtepomp logisch is, wat de ISDE-subsidie doet en welke investering realistisch blijft.',
        'Van hybride tussenstap tot Qvantum of Nibe: u krijgt in {city} een concreet voorstel dat past bij comfort, tapwater en beschikbare ruimte.',
        'Laat uw woning in {city} beoordelen voordat u offertes vergelijkt. Zo voorkomt u een te klein systeem, geluidsgedoe of onnodige kosten.',
        'Warmtepompadvies in {city} met focus op subsidie, besparing, geluidspositie en een installatie die in dagelijks gebruik rustig en betrouwbaar draait.',
        'Ontdek welke warmtepomp bij uw woning in {city} past en ontvang een offerte waarin ruimte, regeling, tapwater en ISDE apart zijn uitgewerkt.',
        'Voor woningen in {city} vergelijkt Vakvriend Qvantum, Nibe, hybride en bodemwarmte op technische geschiktheid.',
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
        'De technische check draait hier om {attention}. Als dat niet klopt, kan een scherpe totaalprijs later alsnog tegenvallen.',
        'We nemen extra tijd voor {attention}, omdat dit in {city} vaak het verschil maakt tussen alleen kunnen installeren en echt comfortabel verwarmen.',
        'Vooraf controleren we {attention}. Daarna pas krijgt u een advies over QA, QE, QG, Nibe of hybride.',
        'Een belangrijk deel van het plan is {attention}. Dat zetten we begrijpelijk in de offerte, zodat u weet waar u ja tegen zegt.',
        'Bij de woningcheck worden {attention} praktisch beoordeeld. Geen aannames, maar meetpunten en foto’s van de situatie.',
        'We kijken lokaal vooral naar {attention}. Zo voorkomen we dat de warmtepomp technisch kan, maar in gebruik stoort.',
        'De plaatsing wordt beoordeeld op {attention}; dat is vaak belangrijker dan alleen het opgegeven rendement.',
    );
    $closing_styles = array(
        'Na uw aanvraag krijgt u een voorstel met systeemkeuze, netto investering, subsidie-inschatting en de aanpassingen die in uw woning nodig zijn.',
        'U ontvangt een leesbaar plan met subsidie, verwachte besparing en de belangrijkste installatiekeuzes.',
        'De offerte laat zien wat direct kan, wat voorbereiding vraagt en waar u eventueel beter nog niet in moet investeren.',
        'We maken de uitkomst concreet: geschikt systeem, verwachte ISDE, plaatsingsadvies en een nuchtere besparingsverwachting.',
        'Zo ziet u vooraf of een warmtepomp in uw situatie slim is, of dat isolatie, radiatoren of ventilatie eerst aandacht nodig hebben.',
        'Het resultaat is een advies waarin kosten, comfort, subsidie en technische aandachtspunten overzichtelijk naast elkaar staan.',
        'Vakvriend vertaalt de techniek naar keuzes: welke oplossing, waarom die past en welke stappen nodig zijn voor een nette installatie.',
        'Daarmee voorkomt u dat u alleen op aanschafprijs vergelijkt terwijl geluid, tapwater of leidingwerk later de echte kosten bepalen.',
    );
    $extra_openers = array(
        'Een warmtepompkeuze begint niet bij het merk, maar bij het warmteverlies, het afgiftesysteem en de beschikbare ruimte.',
        'Een goede installatie voelt achteraf bijna saai: warm huis, stil systeem, geen verrassingen.',
        'Het verschil tussen een redelijke offerte en een sterke offerte zit vaak in de kleine regels.',
        'Subsidie is mooi, maar comfort blijft leidend.',
        'Een buitenunit, binnenmodule of thermische batterij moet ook praktisch goed geplaatst kunnen worden.',
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
        'Daarom krijgt u van Vakvriend een advies met duidelijke keuzes, werkzaamheden en verwachte opbrengst.',
        'Zo wordt de offerte geen verzameling artikelnummers, maar een plan voor minder gasverbruik en meer zekerheid.',
        'U weet na de opname welke keuze verstandig is, welke keuze kan wachten en welke oplossing we juist afraden.',
        'Dat maakt de aanvraag niet alleen sneller, maar vooral bruikbaarder wanneer u offertes naast elkaar legt.',
        'We leggen de techniek simpel uit, inclusief de beperkingen. Dat is beter dan achteraf moeten bijsturen.',
        'De beste warmtepomp is uiteindelijk niet de duurste of de bekendste, maar de installatie die bij uw huis blijft kloppen.',
        'Met die aanpak wordt subsidie onderdeel van de netto investering, niet het enige argument om voor een systeem te kiezen.',
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
        "🛠️|Installatiepraktijk telt|De offerte gaat ook over bereikbaarheid, montage, condensafvoer en onderhoud.\n⚡|Zonnepanelen slim benut|Eigen stroom wordt meegenomen zonder de wintervraag te overschatten.\n✅|Duidelijke keuze|U krijgt een voorstel waarin de technische keuzes helder zijn uitgewerkt.",
        "🏘️|Geschikt voor drukke wijken|We letten extra op geluid en trillingen bij dicht op elkaar gebouwde woningen.\n🌬️|Ventilatie niet vergeten|Afvoer, WTW en binnenlucht bepalen soms welk systeem logisch is.\n💬|Nazorg inbegrepen|Na installatie blijft Vakvriend aanspreekbaar voor regeling en uitleg.",
        "🌍|Bodemopties waar zinvol|Bij voldoende perceelruimte kijken we ook naar boring en vergunning.\n💧|Warm water zonder aannames|Tapwatercomfort wordt apart besproken, zeker bij Qvantum-systemen.\n📊|Besparing realistisch gemaakt|Geen mooie gemiddelden, maar verwachtingen die passen bij uw verbruik.",
    );
    $faq = vk_pick($faq_sets, $index);
    $extra_faq = $faq[0] . ':::' . vk_contextual_answer($faq[1], $city) . "\n" . $faq[2] . ':::' . vk_contextual_answer($faq[3], $city) . "\n" . $faq[4] . ':::' . vk_contextual_answer($faq[5], $city);
    $local_intro = 'In ' . $city . ' beoordelen we eerst de woning en pas daarna het merk of type warmtepomp. Bij ' . $area . ' zijn isolatie, afgiftesysteem en plaatsing bepalend voor een goed advies.';
    $hero_sub = 'Laat uw woning in ' . $city . ' beoordelen op isolatie, warmteafgifte, tapwater en plaatsing. U ontvangt een duidelijke offerte met ISDE-inschatting.';
    $alinea1 = 'De belangrijkste afweging is of hybride, lucht/water, ventilatie of bodemwarmte past bij de bestaande installatie. ' . vk_clean_sentence(ucfirst($focus)) . ' Dat beoordelen we tegen de achtergrond van ' . $area . '.';
    $alinea2 = 'Tijdens de opname controleren we onder meer ' . $attention . '. Die punten bepalen of de installatie stil, onderhoudbaar en zuinig kan worden geplaatst.';
    $alinea3 = 'Na de beoordeling ontvangt u voor uw woning in ' . $city . ' een voorstel met systeemkeuze, verwachte subsidie, montagewerk en eventuele voorbereidingen aan elektra, afgifte of ventilatie.';
    $extra = 'Vakvriend kijkt per woning naar warmteverlies, bestaande radiatoren of vloerverwarming, tapwatergebruik en de beschikbare installatieruimte.' . "\n\n" .
        'Voor ' . $region . ' nemen we ook ' . $attention . ' mee, omdat die punten direct invloed hebben op geluid, rendement en montagekosten.' . "\n\n" .
        'Zo wordt de offerte concreet: welk systeem past, wat moet worden voorbereid en welke investering blijft over na subsidie.';
    $profile = $index % 8;
    $qvantum_titles = array(
        'Qvantum warmtepomp zonder klassiek boilervat',
        'Qvantum bij beperkte ruimte en hoge tapwaterwens',
        'Qvantum als compacte route naar minder gas',
        'Qvantum technisch bekeken per woningtype',
        'Qvantum voor vers tapwater en slimme opslag',
        'Qvantum wanneer plaatsing kritisch is',
        'Qvantum voor woningen waar buitenunit, ventilatie en tapwater samenkomen',
        'Qvantum als alternatief voor een standaard boileropstelling',
    );
    $qvantum_leads = array(
        'In {city} beoordelen we Qvantum vooral op ruimtebeslag, tapwatercomfort en de vraag of de thermische batterij echt voordeel geeft bij {area}. De platenwisselaar maakt warm water pas op het moment dat u het gebruikt; dat is iets anders dan een groot vat met stilstaand tapwater.',
        'Qvantum is niet automatisch de beste keuze, maar kan sterk zijn wanneer {attention} zwaar meewegen. Vakvriend kijkt of QA, QE of QG technisch past, en vergelijkt die uitkomst met Nibe of hybride.',
        'Bij woningen in {region} kan Qvantum interessant worden als compact bouwen, regeling en tapwater belangrijker zijn dan een klassieke boileropstelling. De thermische batterij bewaart warmte, de platenwisselaar verwarmt leidingwater vers.',
        'Voor {city} kijken we bij Qvantum eerst naar warmteverlies en afgiftevermogen. Daarna pas naar het model: QA voor lucht/water, QE voor ventilatie, QG wanneer een bronoplossing logisch is.',
        'Qvantum vraagt om duidelijke uitleg, omdat het systeem anders snel verkeerd wordt begrepen. De batterij slaat geen douchewater op, maar warmte. Dat maakt de beoordeling van leidinglengtes, comfort en installatieruimte extra belangrijk.',
        'Wanneer buitenruimte, geluid of technische ruimte gevoelig ligt, kan Qvantum een serieuze optie zijn. We controleren dan vooral {attention}, omdat daar de praktische winst of beperking zit.',
        'Vakvriend bekijkt Qvantum in {city} naast andere routes. Soms is de thermische batterij precies de reden om het systeem te kiezen; soms is een eenvoudige hybride of Nibe-opstelling verstandiger.',
        'Qvantum past vooral wanneer de woning vraagt om een compacte warmwateroplossing zonder groot boilervat. Voor {city} koppelen we dat aan het echte gebruik: douchen, verwarmen, ventileren en beschikbare ruimte.',
    );
    $qvantum_type_sets = array(
        "💨|QA lucht/water|Past vooral wanneer buitenunitplaatsing mogelijk is en het afgiftesysteem voldoende vermogen levert. In {city} letten we hierbij op {attention}.\n🌬️|QE ventilatie|Interessant bij woningen waar afvoerlucht bruikbaar is en een buitenunit onhandig ligt. Ventilatiecapaciteit en tapwaterprofiel moeten dan eerst kloppen.\n🌍|QG bronoplossing|Alleen zinvol als bronontwerp, perceel en investering passen. Bij twijfel vergelijken we QG met lucht/water en hybride.",
        "🏠|QA voor bestaande woningen|De QA kan geschikt zijn bij {area}, mits geluid, leidingroute en waterzijdige inregeling goed worden ontworpen.\n🌬️|QE zonder buitenunit|De QE gebruikt ventilatielucht. Dat is vooral relevant waar gevel, dak of tuin weinig ruimte geven voor een buitenunit.\n🧊|QG voor stabiel rendement|De QG hoort bij bronwarmte. Die route vraagt voorbereiding, maar kan rustiger draaien wanneer de randvoorwaarden goed zijn.",
        "🔋|Thermische opslag|Bij QA, QE en QG draait de keuze niet alleen om vermogen, maar ook om hoe de batterij warmte opslaat voor verwarming en tapwater.\n🚿|Tapwater vers gemaakt|De platenwisselaar verwarmt leidingwater bij vraag. Daarom kijken we naar gezin, douchegedrag en leidinglengtes.\n📐|Modelkeuze na opname|Pas na warmteverlies, ventilatie en ruimte bepalen we of QA, QE of QG logisch is.",
        "💨|QA met buitenunit|Goed te beoordelen wanneer de opstelplek vrij is en geluid beheersbaar blijft. {attention} krijgen hierbij extra aandacht.\n🌬️|QE met ventilatiebasis|Deze variant vraagt geen buitenunit, maar is afhankelijk van de bestaande ventilatie en het gewenste comfort.\n🌍|QG met bodembron|Een bronoplossing vraagt meer investering. We adviseren die alleen als het woningprofiel dat rechtvaardigt.",
        "🏘️|QA in woonstraten|Bij dicht op elkaar gebouwde woningen draait QA om stille plaatsing, demping en voldoende onderhoudsruimte.\n🌬️|QE bij compacte techniekruimte|De QE kan ruimte besparen, maar ventilatie en tapwater moeten realistisch worden doorgerekend.\n🌍|QG voor lange termijn|QG is vooral interessant wanneer het perceel en de investering passen bij jarenlang laagtemperatuurverwarmen.",
        "🔧|QA als praktische basis|De QA is vaak de eerste technische vergelijking bij lucht/water. We kijken naar vermogen, leidingroute en cv-waterzijdige afstelling.\n🌬️|QE als speciale route|De QE is geen standaardoplossing voor elke woning. Afvoerlucht, kanalen en comfortvraag bepalen of het werkt.\n🌍|QG als maatwerk|QG hoort bij bronwarmte en vraagt een serieuze berekening van bron, vergunning en afgiftesysteem.",
        "💨|QA bij ruimte buiten|Wanneer tuin, dak of zijgevel bruikbaar zijn, vergelijken we QA op geluid, rendement en montage-impact.\n🌬️|QE bij ventilatiekansen|Bij appartementen of compacte woningen kan ventilatiewarmte interessant zijn, zolang luchtdebiet en tapwater niet worden overschat.\n🌍|QG bij voldoende voorbereiding|QG komt pas in beeld wanneer boring, bron en lage temperatuur verwarming samen kloppen.",
        "🔋|Batterij als warmtebuffer|Qvantum gebruikt de batterij om warmte vast te houden, niet om tapwater op voorraad te zetten.\n🚿|Platenwisselaar voor douchewater|Warm tapwater wordt vers gemaakt. Daarom is de combinatie van debiet, comfort en leidingwerk belangrijk.\n⚙️|QA, QE of QG kiezen|De woning bepaalt de variant: buitenlucht, ventilatielucht of bronwarmte.",
    );
    $qvantum_usp_sets = array(
        "Warmte, geen tapwater op voorraad|De batterij bewaart energie; douchewater stroomt vers langs de platenwisselaar.\nCompact wanneer ruimte telt|Dat kan helpen als een groot boilervat lastig te plaatsen is.\nMerkonafhankelijk bekeken|Qvantum gaat naast Nibe, hybride en bodemwarmte in dezelfde rekensom.\nComfort apart beoordeeld|Tapwater, verwarming en regeling worden niet op een hoop gegooid.",
        "Thermische batterij|Warmteopslag kan gunstig zijn bij wisselende warmtevraag en beperkte ruimte.\nGeen klassiek boilervat|Tapwater wordt niet langdurig stilstaand opgeslagen.\nDrie technische routes|QA, QE en QG vragen elk om andere randvoorwaarden.\nEerlijk alternatief|Als Qvantum niet past, adviseren we een andere oplossing.",
        "Vers warm water|De platenwisselaar maakt tapwater op aanvraag.\nMinder ruimteclaim|Voor sommige woningen is dat praktischer dan een groot vat.\nScherpe systeemkeuze|Ventilatie, buitenunit of bron wordt niet door elkaar gehaald.\nControle op comfort|Douchegedrag en hersteltijd tellen mee.",
        "Opslag in warmte|Qvantum bewaart warmte in de batterij en gebruikt die voor verwarming en tapwater.\nGeen voorraad douchewater|Dat verschil leggen we bewust simpel uit.\nGeschikt na berekening|Warmteverlies en afgiftesysteem blijven leidend.\nNiet merkgebonden|Vakvriend kiest niet automatisch voor één fabrikant.",
        "Slim bij beperkte techniekruimte|De batterij kan ruimte besparen, maar alleen als leidingwerk en comfort passen.\nTapwater via wisselaar|Koud leidingwater wordt direct verwarmd wanneer u warm water vraagt.\nQA, QE en QG gescheiden beoordeeld|Elke variant heeft eigen eisen.\nSubsidie meegenomen|Het toestel en vermogen bepalen de ISDE-inschatting.",
        "Compacte warmwaterroute|Interessant wanneer een boilervat onhandig is.\nThermische opslag uitgelegd|Geen vaag verhaal, maar helder: warmte erin, warmte eruit.\nPraktische opname nodig|Ruimte, geluid en tapwater bepalen de keuze.\nVergelijk met Nibe|Soms wint eenvoud, soms wint opslag.",
        "Warmtepomp en tapwater samen bekeken|Qvantum vraagt om controle van verwarming, debiet en comfort.\nGeen stilstaand warmwaterreservoir|Dat is een belangrijk verschil met klassieke boileropstellingen.\nKeuze uit lucht, ventilatie of bron|QA, QE en QG lossen verschillende situaties op.\nAdvies zonder merkdruk|De woning blijft leidend.",
        "Batterij als kern|Alle Qvantum-routes draaien om warmteopslag.\nPlatenwisselaar als warmwatermaker|Tapwater wordt bij vraag verwarmd, niet bewaard.\nRuimte en leidinglengte tellen|De compacte belofte moet in huis ook uitvoerbaar zijn.\nAlternatieven blijven open|Hybride, Nibe of bodemwarmte blijven in beeld.",
    );
    $nibe_type_sets = array(
        "💨|Lucht/water met brede modelkeuze|Nibe is sterk wanneer vermogen, geluidsopstelling en binnenmodule precies moeten worden gekozen voor {area}.\n🌍|Bodem/water met stabiele bron|Bij voldoende voorbereiding kan bronwarmte interessant zijn, maar boring en afgiftesysteem moeten kloppen.\n⚡|Hybride tussenstap|Zinvol wanneer radiatoren of isolatie nog niet klaar zijn voor volledig elektrisch.",
        "💨|Buitenunit met binnenmodule|Een Nibe lucht/water systeem vraagt aandacht voor {attention}; daar valt vaak de kwaliteit van de installatie mee of tegen.\n🌍|Bronwarmte voor lange termijn|Bodem/water is technisch sterk, maar niet automatisch financieel de beste route.\n🔥|Hybride bij bestaande ketel|Een hybride opstelling kan gas besparen zonder direct alles om te bouwen.",
        "💨|Lucht/water bij goede afgifte|Nibe past goed wanneer radiatoren of vloerverwarming voldoende lage temperatuur kunnen leveren.\n🌍|Bodem/water bij ruimte en budget|De bron maakt het systeem rustig, maar vraagt ontwerp, vergunning en aanleg.\n⚡|Hybride als veilige stap|Bij twijfel over isolatie kan hybride comfort behouden en toch gas verminderen.",
        "🔧|Configuren op vermogen|Nibe heeft veel combinaties. Dat is waardevol als warmteverlies en tapwater precies moeten worden afgestemd.\n🌍|Bronoplossing zorgvuldig rekenen|Een boring moet niet alleen technisch kunnen, maar ook financieel logisch zijn.\n🔥|Hybride niet afschrijven|Voor oudere woningen kan hybride juist de verstandigste eerste stap zijn.",
        "💨|Nibe lucht/water|Sterk wanneer stille plaatsing, servicebaarheid en regeling belangrijk zijn.\n🌍|Nibe bodem/water|Geschikt voor woningen waar bron, afgifte en langetermijnrendement samen kloppen.\n⚡|Nibe hybride|Interessant als de woning stapsgewijs richting minder gas gaat.",
        "🏠|Bestaande bouw|Nibe kan goed werken in bestaande woningen als afgiftevermogen en aanvoertemperatuur vooraf zijn gecontroleerd.\n🌍|Ruime percelen|Bij bodemwarmte kijken we naar bronafstand, bereikbaarheid en vergunning.\n🔥|Cv-ketel behouden|Hybride houdt pieklast bij de ketel en laat de warmtepomp het grootste deel doen.",
        "💨|All-electric of hybride|Met Nibe kunnen beide routes, maar de woning bepaalt welke logisch is.\n🌍|Bron als premium optie|Bodem/water nemen we mee wanneer rust en rendement de investering waard zijn.\n⚡|Stap voor stap|Hybride kan later uitbreidbaar worden gekozen.",
        "🔇|Stille werking ontwerpen|Bij Nibe draait het niet alleen om merknaam, maar om juiste buitenunitpositie en regeling.\n🌍|Boring alleen met onderbouwing|Bronwarmte krijgt pas prioriteit als perceel en afgiftesysteem sterk genoeg zijn.\n🔥|Hybride bij hoge warmtevraag|Als de woning nog hogere temperaturen vraagt, voorkomt hybride comfortverlies.",
    );
    $warmtepomp_type_sets = array(
        "💨|Snel te beoordelen|Lucht/water warmtepomp|Voor {city} is dit vaak de eerste vergelijking: buitenunit, binnenmodule en cv-water. De haalbaarheid hangt vooral af van {attention}.|Geen boring nodig;Geluid vooraf ontwerpen;Past bij hybride of all-electric;Afgiftesysteem controleren;ISDE vaak mogelijk|Qvantum QA of Nibe lucht/water\n🌬️|Compacte route|Ventilatie warmtepomp|Gebruikt warmte uit afvoerlucht. Vooral relevant wanneer buitenruimte beperkt is of ventilatie al centraal geregeld is.|Geen buitenunit;Ventilatiecapaciteit bepalend;Tapwater apart rekenen;Niet geschikt voor elk huis;Interessant bij appartementen|Qvantum QE\n🌍|Lange termijn|Bodemwarmtepomp|Bronwarmte kan rustig en zuinig draaien, maar vraagt perceel, boring en lage temperatuur afgifte.|Stabiel rendement;Hogere investering;Vergunning/boring nodig;Goed bij passende percelen;Onderhoudsarm ontworpen|Nibe bodem/water of Qvantum QG",
        "💨|Praktisch bij bestaande bouw|Lucht/water|Haalt warmte uit buitenlucht en levert die aan radiatoren of vloerverwarming. Voor {area} draait het om vermogen, geluid en leidingroute.|Breed toepasbaar;Buitenunit nodig;Goed berekenen;Hybride mogelijk;Netto prijs met ISDE|Nibe of Qvantum QA\n🌬️|Zonder buitenunit|Ventilatie|Kan interessant zijn wanneer afvoerlucht beschikbaar is en plaatsing buiten lastig wordt.|Compact;Afhankelijk van ventilatie;Comfort checken;Beperkt vermogen;Tapwater kritisch bekijken|Qvantum QE\n🌍|Brongebonden|Bodemwarmte|Technisch sterk bij voldoende voorbereiding, vooral wanneer lange levensduur zwaarder weegt dan lage instapprijs.|Bronontwerp nodig;Weinig buitenunitgeluid;Hoge voorbereiding;Past bij lage temperatuur;Niet altijd rendabel|Nibe bodem/water of Qvantum QG",
        "💨|Meest flexibel|Lucht/water|Geschikt als hybride of all-electric route. In {city} beoordelen we vooral of de gekozen opstelplek stil en onderhoudbaar blijft.|Veel systeemkeuze;Geen bronboring;Geluid meenemen;Radiatoren checken;Goede regeling vereist|Nibe lucht/water, Qvantum QA\n🌬️|Specifieke toepassing|Ventilatie|Niet de grootste warmtepomp, wel interessant als ventilatielucht continu beschikbaar is.|Geen tuinunit;Ventilatie bepalend;Beperkt toepassingsgebied;Ruimtebesparend;Opname nodig|Qvantum QE\n🌍|Hoog comfort|Bodem/water|Een bron kan comfort en rendement opleveren, maar de voorbereiding is zwaarder.|Rustig systeem;Bron en vergunning;Geschikt perceel nodig;Hogere investering;Sterk bij nieuwere afgifte|Nibe bodem/water, Qvantum QG",
        "💨|Eerste rekenspoor|Lucht/water|We starten vaak met lucht/water omdat investering, subsidie en plaatsing snel concreet worden. {focus}.|Snel inzicht;Buitenunit ontwerpen;Hybride mogelijk;All-electric mogelijk;Afgiftevermogen toetsen|Qvantum QA of Nibe\n🌬️|Als buiten lastig is|Ventilatie|Bij beperkte buitenruimte of VvE-gevoelige situaties kan ventilatie interessanter zijn dan een buitenunit.|Geen buitenunit;Ventilatie-eisen;Lager vermogen;Tapwater controleren;Niet universeel|Qvantum QE\n🌍|Wanneer de woning het waard is|Bodemwarmte|Voor woningen met goede randvoorwaarden kan bodemwarmte comfort geven met weinig zichtbaar installatiewerk buiten.|Bronboring;Stabiele temperatuur;Lage temperatuur nodig;Meer voorbereiding;Langetermijnkeuze|Nibe bodem/water of Qvantum QG",
        "💨|Buitenlucht als bron|Lucht/water|Een lucht/water warmtepomp is logisch wanneer buitenunitplaatsing en afgiftesysteem goed samenkomen.|Breed inzetbaar;Geluid kritisch;Onderhoudsruimte nodig;ISDE meenemen;Waterzijdig inregelen|Nibe, Qvantum QA\n🌬️|Afvoerlucht benutten|Ventilatie|Deze route kijkt naar warmte die anders via ventilatie verdwijnt. Dat vraagt een andere beoordeling dan lucht/water.|Geen buitenunit;Kanaalwerk belangrijk;Tapwater apart;Compact;Voor specifieke woningen|Qvantum QE\n🌍|Bodem als stabiele bron|Water/water|Bodemwarmte verdient aandacht bij grotere comfortvraag of lange exploitatietijd.|Stabiele bron;Boring nodig;Hoge startkosten;Stil in gebruik;Sterk bij goede isolatie|Nibe bodem/water, Qvantum QG",
        "💨|Hybride of gasloos|Lucht/water|Lucht/water kan naast de cv-ketel starten of volledig elektrisch worden ontworpen. De woning in {city} bepaalt de stapgrootte.|Flexibele route;Buitenunitpositie;Radiatoren controleren;Tapwaterkeuze nodig;Subsidie afhankelijk van toestel|Nibe of Qvantum QA\n🌬️|Ventilatiewarmte|Ventilatie warmtepomp|Interessant bij continu afvoerlucht en beperkte buitenruimte, maar niet bedoeld als standaardoplossing voor elke woning.|Ruimtebesparend;Ventilatie nodig;Vermogen begrensd;Goed bij appartementen;Comfort rekenen|Qvantum QE\n🌍|Bronwarmtepomp|Bodem/water|Een bodemwarmtepomp vraagt meer werk vooraf, maar kan technisch de mooiste route zijn als perceel en budget passen.|Geen buitenunitgeluid;Boring/vergunning;Hoge efficientie;Lange termijn;Niet overal logisch|Nibe bodem/water of Qvantum QG",
        "💨|Veel gekozen|Lucht/water|Voor {region} is lucht/water vaak de nuchtere basisvergelijking. Vooral {attention} bepalen de uitvoering.|Geen bodembron;Geluid ontwerpen;Ruime modelkeuze;Hybride mogelijk;Afgifte checken|Nibe lucht/water, Qvantum QA\n🌬️|Bij ventilatiekansen|Ventilatie|Gebruikt bestaande afvoerlucht. De opbrengst hangt af van ventilatie en warmtevraag.|Geen buitenunit;Afvoerlucht nodig;Compact;Niet voor hoge warmtevraag;Tapwater meenemen|Qvantum QE\n🌍|Bij ruime voorbereiding|Bodemwarmte|Alleen interessant als bron, vergunning en lage temperatuur afgifte realistisch zijn.|Stabiele bron;Meer voorbereiding;Rustig comfort;Hogere investering;Sterk bij passende woning|Nibe bodem/water of Qvantum QG",
        "💨|Snelste route naar offerte|Lucht/water|Deze techniek geeft vaak het snelst duidelijkheid over prijs, subsidie en plaatsing. De details zitten in geluid en afgiftevermogen.|Breed toepasbaar;Buitenunit vereist;ISDE mogelijk;Waterzijdige balans;Hybride of all-electric|Qvantum QA, Nibe\n🌬️|Als buitenunit gevoelig ligt|Ventilatie|Een ventilatievariant kan netter zijn wanneer buitenopstelling lastig wordt, mits de luchtstromen voldoende zijn.|Geen buitenunit;Afhankelijk van ventilatie;Compact;Controle op comfort;Specifieke toepassing|Qvantum QE\n🌍|Voor de lange adem|Bodem/water|Bodemwarmte is geen snelle budgetkeuze, maar kan technisch sterk zijn bij voldoende ruimte en voorbereiding.|Bronboring;Stil systeem;Hoge efficientie;Vergunning nodig;Goede afgifte vereist|Nibe bodem/water, Qvantum QG",
    );
    $vv_prop_sets = array(
        "🏗️|Opname rond {area}|We kijken naar bouwjaar, warmteafgifte en installatieruimte voordat er een merk wordt gekozen.\n🔇|Geluid als ontwerpkeuze|{attention} worden vooraf vertaald naar opstelplek, demping en onderhoudsruimte.\n📋|Merkonafhankelijk voorstel|Qvantum, Nibe, hybride en bodemwarmte blijven opties tot de berekening klaar is.\n💶|ISDE zonder mist|Bruto investering, subsidie en voorbereidend werk staan apart in het advies.",
        "📐|Eerst rekenen|Warmteverlies en afgiftesysteem bepalen of hybride, all-electric of ventilatie logisch is.\n🚿|Tapwater serieus nemen|Douchegedrag, leidinglengte en hersteltijd worden los beoordeeld van ruimteverwarming.\n🔧|Installatiepraktijk meewegen|Elektra, condensafvoer, leidingroute en servicebaarheid horen in de offerte.\n🤝|Geen merkdruk|Als Nibe beter past dan Qvantum of andersom, leggen we dat uit.",
        "🏘️|Woningtype als startpunt|{area} vragen om andere keuzes dan een standaard online advies.\n⚙️|Regeling en afgifte|We controleren of radiatoren, vloerverwarming en regeling lage temperatuur aankunnen.\n🔇|Rustig in gebruik|Geluid, trillingen en nachtbedrijf worden vooraf besproken.\n🧾|Heldere netto prijs|U ziet wat toestel, montage, subsidie en randwerk doen met de investering.",
        "🌡️|Comfort bij koud weer|We kijken niet alleen naar gemiddelden, maar ook naar piekmomenten.\n📍|Plaatsing op locatie|{attention} bepalen vaak waar het systeem echt kan staan.\n🔋|Opslag of eenvoud|Qvantum, Nibe en hybride worden beoordeeld op techniek en uitvoering.\n✅|Besluit met onderbouwing|U krijgt een keuze die u technisch kunt volgen.",
        "🛠️|Montage vooraf doordacht|Binnenmodule, buitenunit, leidingwerk en elektra worden in samenhang bekeken.\n🌬️|Ventilatie telt mee|Bij woningen waar ventilatie belangrijk is, nemen we QE of andere oplossingen apart mee.\n💧|Warm water apart berekenen|Tapwatercomfort krijgt een eigen controle, zeker bij compacte systemen.\n📊|Realistische besparing|We rekenen met uw gebruik, niet met een ideaal gemiddelde.",
        "🏠|Geen standaardpakket|De woning in {city} bepaalt de route: hybride, Qvantum, Nibe of bronwarmte.\n🔍|Aandacht voor details|{attention} komen expliciet terug in het voorstel.\n📞|Uitleg voor akkoord|We nemen de technische keuzes door voordat u beslist.\n💶|Subsidie als onderdeel|ISDE is belangrijk, maar niet het enige argument.",
        "🧭|Route naar minder gas|We kiezen een stap die past bij isolatie, radiatoren en budget.\n🔇|Buren en erfgrens|Geluidspositie wordt niet achteraf opgelost, maar vooraf ontworpen.\n⚡|Elektra en regeling|Meterkast, groepen en regeling worden meteen meegenomen.\n🤝|Objectieve vergelijking|Qvantum, Nibe en hybride blijven eerlijk naast elkaar staan.",
        "📋|Offerte die uitlegt|Geen lijst met artikelnummers, maar uitleg over systeem, plaatsing en subsidie.\n🚿|Comfort boven marketing|Warm water, stilte en verwarming bepalen de kwaliteit in dagelijks gebruik.\n🌍|Bodem alleen waar logisch|Een boring komt pas in beeld als perceel, budget en afgifte passen.\n🛠️|Nazorg inbegrepen|Na installatie blijft regeling en gebruik onderdeel van het werk.",
    );
    $workflow_sets = array(
        "01|Woningprofiel vastleggen|We starten met {area}, gasverbruik, isolatie en foto's van technische ruimte of buitenplek.\n02|Technische route kiezen|Daarna vergelijken we Qvantum, Nibe, hybride en bodemwarmte op {focus}.\n03|Uitvoering concreet maken|Leidingroute, elektra, geluid en ISDE worden duidelijk uitgewerkt.\n04|Installeren en inregelen|Na montage stellen we het systeem in op comfort, tapwater en verbruik.",
        "01|Warmtevraag bepalen|Bouwjaar, isolatie en afgiftesysteem geven eerst de bandbreedte voor het vermogen.\n02|Plaatsing beoordelen|{attention} bepalen of lucht/water, ventilatie of bronwarmte praktisch wordt.\n03|Offerte opbouwen|Toestel, montage, subsidie en randwerk staan los benoemd.\n04|Opleveren met uitleg|U weet hoe de regeling werkt en wanneer bijsturen nodig is.",
        "01|Foto-inventarisatie of opname|We verzamelen cv-ruimte, meterkast, buitenplek en bestaande afgifte.\n02|Merkonafhankelijk vergelijken|Qvantum en Nibe worden naast hybride of bodemwarmte gelegd.\n03|Comfortcheck doen|Tapwater, geluid en koude dagen krijgen aparte aandacht.\n04|Planbaar installeren|Pas daarna plannen we montage, inregeling en subsidiepapieren.",
        "01|Niet beginnen bij het merk|Eerst bekijken we wat de woning technisch vraagt.\n02|Knelpunten benoemen|{attention} worden niet verstopt in kleine lettertjes.\n03|Netto investering tonen|ISDE, elektra, leidingwerk en toestel worden helder uitgesplitst.\n04|Controle na oplevering|We controleren instelling en gebruik, zodat het systeem rustig draait.",
        "01|Aanvraag vertalen naar techniek|We maken van uw gegevens een eerste warmtepomproute.\n02|Afgifte en tapwater controleren|Radiatoren, vloerverwarming en douchecomfort bepalen de juiste opstelling.\n03|Plaatsing ontwerpen|Geluid, leidinglengte en onderhoudsruimte krijgen een vaste plek in het plan.\n04|Subsidie en installatie afronden|Na akkoord begeleiden we uitvoering en ISDE-stukken.",
        "01|Situatie scherp krijgen|We bekijken {area} en vragen door op verbruik, comfortklachten en toekomstplannen.\n02|Systeemkeuze beperken|Niet tien opties, maar de twee of drie routes die technisch zinvol zijn.\n03|Risico's vooraf benoemen|Elektra, geluid, vergunning of ventilatie komen vroeg op tafel.\n04|Werk netjes afronden|Installatie, uitleg en nazorg horen bij dezelfde opdracht.",
        "01|Check op haalbaarheid|We bepalen of hybride, all-electric of ventilatie de juiste eerste stap is.\n02|Vermogen en opstelling koppelen|Het toestel wordt gekozen samen met de plek waar het moet functioneren.\n03|Offerte leesbaar maken|U ziet waarom een keuze past en waar de kosten vandaan komen.\n04|Inregelen op gebruik|Na installatie kijken we naar comfort, stooklijn en warmwatergedrag.",
        "01|Woning niet overslaan|De aanvraag start met bouwkundige en installatietechnische gegevens.\n02|Lokale aandachtspunten meenemen|{attention} worden vertaald naar concrete montagekeuzes.\n03|Alternatieven eerlijk wegen|Qvantum, Nibe, hybride of bodemwarmte krijgen elk hun plek in de vergelijking.\n04|Oplevering zonder raadsel|U krijgt uitleg over bediening, onderhoud en subsidieafronding.",
    );
    $boring_leads = array(
        'Bodemwarmte is technisch sterk, maar niet automatisch de beste keuze. Bij {city} kijken we eerst naar perceelruimte, bereikbaarheid, ondergrond en lage temperatuur afgifte.',
        'Een boring adviseren we pas als de woning en het terrein dat rechtvaardigen. Voor {area} kan lucht/water soms veel logischer zijn dan bronwarmte.',
        'Bronwarmte vraagt meer voorbereiding dan een buitenunit. In {region} beoordelen we daarom vergunning, bronontwerp, investering en de verwachte gebruiksduur.',
        'Bodem/water komt pas op tafel wanneer comfort, rendement en lange termijn zwaarder wegen dan de hogere startinvestering.',
        'Niet elke woning verdient een boring. We leggen uit wanneer bodemwarmte technisch voordeel heeft en wanneer hybride of lucht/water verstandiger is.',
        'Bij bronwarmte moeten boring, afgiftesysteem en regeling samen kloppen. Anders wordt een dure installatie niet automatisch een betere installatie.',
        'Voor grotere woningen of ruime percelen kan bodemwarmte interessant zijn. De beoordeling begint bij ondergrond, vergunning en het gewenste comfortniveau.',
        'Een bodemwarmtepomp is maatwerk. Vakvriend zet bron, boring, afgifte en subsidie apart in de afweging.',
    );
    $boring_cards = array(
        'We zetten bron, boring, vergunning en afgiftesysteem apart naast lucht/water en hybride. Zo wordt duidelijk of bodemwarmte waarde toevoegt.',
        'Als een boring te duur of te omslachtig is voor {city}, adviseren we een eenvoudiger route. Dat is vaak beter dan technisch forceren.',
        'De kaart is pas positief wanneer bereikbaarheid, ondergrond, bronafstand en lage temperatuur verwarming samen haalbaar zijn.',
        'Vakvriend rekent bodemwarmte niet mooier dan het is: sterk rendement, maar alleen bij passende woning en lange gebruikshorizon.',
        'Bij twijfel maken we eerst een vergelijking met lucht/water. Soms levert die route sneller resultaat op met minder bouwkundige impact.',
        'Boring en vergunning horen niet als losse verrassing achteraf in de offerte. Ze worden vanaf het begin meegenomen.',
        'Voor {region} controleren we of bronwarmte praktisch uitvoerbaar is en of de investering past bij het verwachte verbruik.',
        'De bodemroute blijft open, maar alleen met technische onderbouwing. Anders kiezen we liever een eenvoudiger systeem dat betrouwbaar presteert.',
    );
    $tokens = array('{city}' => $city, '{region}' => $region, '{area}' => $area, '{focus}' => $focus, '{attention}' => $attention);
    $row_context = 'Aandachtspunt bij deze woningtypen: ' . $attention . '.';
    $qvantum_types = vk_add_row_context(strtr(vk_pick($qvantum_type_sets, $profile), $tokens), 2, $row_context);
    $qvantum_usps = vk_add_row_context(strtr(vk_pick($qvantum_usp_sets, $profile), $tokens), 1, 'Bij ' . $area . ' controleren we dit nadrukkelijk.');
    $nibe_types = vk_add_row_context(strtr(vk_pick($nibe_type_sets, $profile), $tokens), 2, $row_context);
    $warmtepomp_types = vk_add_row_context(strtr(vk_pick($warmtepomp_type_sets, $profile), $tokens), 3, 'Daarbij telt voor ' . $city . ' vooral ' . $attention . '.');
    $vv_props = vk_add_row_context(strtr(vk_pick($vv_prop_sets, $profile), $tokens), 2, 'De lokale opname focust op ' . $attention . '.');
    $werkwijze = vk_add_row_context(strtr(vk_pick($workflow_sets, $profile), $tokens), 2, 'Dat sluit aan op ' . $area . '.');
    $boringen_lead = strtr(vk_pick($boring_leads, $profile), $tokens) . ' De doorslag zit hier vooral in ' . $attention . '.';
    $boringen_kaart_tekst = strtr(vk_pick($boring_cards, $profile), $tokens) . ' We toetsen dat aan ' . $area . '.';
    $voordelen = vk_add_row_context(vk_contextual_rows(vk_pick($benefit_sets, $index), $city), 2, 'In ' . $region . ' is vooral ' . $attention . ' bepalend.');
    $qvantum_lead = 'Qvantum is vooral interessant wanneer een compacte warmwateroplossing gewenst is of wanneer een groot boilervat lastig te plaatsen is. Bij ' . $area . ' controleren we warmteverlies, tapwatergebruik, ventilatie en ' . $attention . '.';
    $qvantum_uitleg = 'Qvantum slaat warmte op in een thermische batterij. Het tapwater zelf wordt niet in een boilervat bewaard, maar bij warmwatervraag via een platenwisselaar verwarmd. In ' . $city . ' kijken we daarom apart naar douchegebruik, leidinglengtes en beschikbare installatieruimte.';
    $nibe_lead = 'Nibe heeft veel mogelijkheden voor lucht/water, bodem/water en hybride opstellingen. Voor ' . $city . ' beoordelen we vooral vermogen, afgiftesysteem, tapwater, geluid en plaatsing.';
    $types_lead = 'De juiste warmtepomp hangt af van warmteverlies, isolatie, bestaande radiatoren of vloerverwarming en de beschikbare ruimte. Voor ' . $city . ' nemen we daarnaast ' . $attention . ' mee.';
    $vv_intro_sober = 'Vakvriend adviseert merkonafhankelijk. Voor aanvragen uit ' . $city . ' vergelijken we Qvantum, Nibe, hybride en bodemwarmte op basis van de woning, het verbruik, het afgiftesysteem en de praktische montage.';
    $voordelen_lead_sober = 'Een goede woningcheck voorkomt dat een warmtepomp te groot, te klein of verkeerd geplaatst wordt. Bij ' . $area . ' zijn vooral ' . $attention . ' belangrijk.';
    $werkwijze_lead_sober = 'We starten met de woninggegevens, controleren de technische ruimte en bepalen daarna welke warmtepomproute past. Voor ' . $city . ' werken we dit uit in een offerte met systeemkeuze, montagewerk en subsidie-inschatting.';
    $boringen_lead_sober = 'Een bodemwarmtepomp is alleen zinvol wanneer perceel, bron, afgiftesysteem en investering goed bij elkaar passen. Voor ' . $city . ' beoordelen we dit apart van lucht/water en hybride.';

    return array(
        'wc_ai_content_status' => 'seo-unique-v2',
        'wc_meta_title' => 'Warmtepomp ' . $city . ' | Advies voor ' . $region,
        'wc_meta_desc' => 'Uniek warmtepompadvies in ' . $city . ': woningcheck, ISDE-berekening en vergelijking van Qvantum, Nibe, hybride of bodemwarmte.',
        'wc_focus_keyword' => 'warmtepomp ' . strtolower($city),
        'wc_hero_kicker' => 'Gratis subsidiecheck en advies in ' . $city,
        'wc_hero_titel' => 'Warmtepomp offerte aanvragen in ' . $city,
        'wc_hero_subtitel' => $hero_sub,
        'wc_form_titel' => 'Advies voor uw woning in ' . $city,
        'wc_form_subtitel' => 'Beantwoord de vragen en ontvang een check op systeemkeuze, subsidie en plaatsing voor uw situatie in ' . $city . '.',
        'wc_campaign_proof' => "24 uur|eerste reactie op uw aanvraag\nISDE|verwachte subsidie apart berekend\n" . $city . "|advies op basis van woninggegevens",
        'wc_qvantum_eyebrow' => 'Qvantum warmtepomp',
        'wc_qvantum_titel' => 'Qvantum met thermische batterij',
        'wc_qvantum_lead' => $qvantum_lead,
        'wc_qvantum_types' => $qvantum_types,
        'wc_qvantum_uitleg_titel' => 'Warm water via platenwisselaar',
        'wc_qvantum_uitleg_tekst' => $qvantum_uitleg,
        'wc_qvantum_usps' => $qvantum_usps,
        'wc_qvantum_cta' => 'Qvantum check voor ' . $city . ' aanvragen',
        'wc_nibe_eyebrow' => 'Nibe warmtepomp',
        'wc_nibe_titel' => 'Nibe als betrouwbare vergelijking',
        'wc_nibe_lead' => $nibe_lead,
        'wc_nibe_types' => $nibe_types,
        'wc_nibe_cta' => 'Nibe advies voor ' . $city . ' aanvragen',
        'wc_types_eyebrow' => 'Systeemkeuze',
        'wc_types_titel' => 'Welke warmtepomp past bij uw woning?',
        'wc_types_lead' => $types_lead,
        'wc_warmtepomp_types' => $warmtepomp_types,
        'wc_vv_eyebrow' => 'Vakvriend advies',
        'wc_vv_titel' => 'Merkonafhankelijk warmtepompadvies',
        'wc_vv_intro' => $vv_intro_sober,
        'wc_vv_usp1' => vk_pick(array('Merkonafhankelijk advies voor ' . $city, 'Qvantum en Nibe eerlijk vergeleken', 'Woningcheck voor systeemkeuze', 'Technisch onderbouwd advies'), $profile),
        'wc_vv_usp2' => '200+ installaties als technische basis',
        'wc_vv_usp3' => '4,6 / 5 sterren beoordeling',
        'wc_vv_usp4' => 'Actief in ' . $region . ' en omgeving',
        'wc_vv_props' => $vv_props,
        'wc_voordelen_titel' => 'Waarom eerst een woningcheck?',
        'wc_voordelen_lead' => $voordelen_lead_sober,
        'wc_voordelen' => $voordelen,
        'wc_werkwijze_eyebrow' => 'Aanpak',
        'wc_werkwijze_titel' => 'Van woningcheck naar offerte',
        'wc_werkwijze_lead' => $werkwijze_lead_sober,
        'wc_werkwijze' => $werkwijze,
        'wc_boringen_eyebrow' => 'Bodemwarmte in ' . $region,
        'wc_boringen_titel' => 'Grondboring voor een warmtepomp in ' . $city,
        'wc_boringen_lead' => $boringen_lead_sober,
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
            'qvantum' => 'Op deze pagina leggen we Qvantum concreet uit: warmte wordt opgeslagen in de thermische batterij, terwijl tapwater pas via de platenwisselaar wordt verwarmd op het moment dat u warm water vraagt.',
            'nibe' => 'Nibe blijft hier de vergelijkingsbasis voor bezoekers die willen weten wanneer een systeem met boiler, buffervat of hybride opbouw verstandiger is.',
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
        'wc_qvantum_titel' => 'Qvantum technisch bekeken: ' . $topic['label'],
        'wc_qvantum_lead' => $topic['qvantum'],
        'wc_qvantum_uitleg_titel' => 'Thermische batterij en platenwisselaar',
        'wc_qvantum_uitleg_tekst' => $topic['qvantum'] . ' Belangrijk is het verschil tussen warmte opslaan en tapwater opslaan: de batterij bewaart warmte, de platenwisselaar verwarmt leidingwater pas bij warmwatervraag.',
        'wc_nibe_eyebrow' => 'Vergelijking · Nibe',
        'wc_nibe_titel' => 'Nibe als nuchtere vergelijking voor ' . $topic['label'],
        'wc_nibe_lead' => $topic['nibe'],
        'wc_types_eyebrow' => 'Keuzehulp · ' . $topic['label'],
        'wc_types_titel' => 'Welke warmtepomp hoort bij ' . $topic['label'] . '?',
        'wc_types_lead' => 'Deze pagina focust op ' . $topic['angle'] . '. Daarom bekijken we lucht/water, ventilatie, hybride en bodemwarmte vanuit dat specifieke vertrekpunt.',
        'wc_vv_eyebrow' => 'Vakvriend advies',
        'wc_vv_titel' => 'Waarom Vakvriend bij ' . $topic['label'] . '?',
        'wc_vv_intro' => 'Vakvriend vertaalt ' . $topic['label'] . ' naar een praktisch plan met merkonafhankelijk systeemadvies, installatiewerk, ISDE-subsidie en heldere uitleg. U ziet waarom een systeem past, welke randvoorwaarden gelden en welke alternatieven logisch zijn.',
        'wc_werkwijze_eyebrow' => 'Aanpak · ' . $topic['label'],
        'wc_werkwijze_titel' => 'Van vraag naar bruikbare keuze',
        'wc_werkwijze_lead' => 'We starten bij ' . $topic['angle'] . ', controleren daarna woninggegevens en maken pas dan een merkonafhankelijk voorstel met vermogen, plaatsing en subsidie.',
        'wc_boringen_eyebrow' => 'Bodemwarmte afwegen',
        'wc_boringen_titel' => 'Boring alleen wanneer het logisch is',
        'wc_boringen_lead' => $topic['boring'],
        'wc_boringen_kaart_tekst' => $topic['boring'] . ' Vakvriend zet bron, boring en vergunning apart in de offerte, zodat duidelijk is waar de investering uit bestaat.',
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
    if (!empty($meta['wc_focus_keyword'])) {
        update_post_meta($post_id, '_yoast_wpseo_focuskw', $meta['wc_focus_keyword']);
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
