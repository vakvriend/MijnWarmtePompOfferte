# Vakvriend Warmtepomp Campagne V2

WordPress theme voor Vakvriend warmtepomp-campagnepagina's met:

- campagnevelden per pagina
- Yoast SEO integratie
- Google Tag Manager instelling
- leadformulier met UTM/GCLID opslag
- Domain Mapping System compatible pagina-opbouw

## Upload

Upload de theme-map als `vakvriend-warmtepomp-campagne-v2`.

## Updates via GitHub releases

Het theme controleert de laatste GitHub release van:

`vakvriend/MijnWarmtePompOfferte`

Maak bij elke release een zip asset met rootmap:

`vakvriend-warmtepomp-campagne-v2/`

WordPress toont daarna een normale theme update zodra de release tag hoger is dan de `Version` in `style.css`.

Voor private repositories:

1. Maak een fine-grained GitHub token met toegang tot deze repository en `Contents: read`.
2. Vul het token in via `Instellingen > Theme updates`.
3. Ga naar `Dashboard > Updates` en klik op opnieuw controleren.

Let op: als de geinstalleerde theme-versie nog geen token-support bevat, moet versie `2.4` eenmalig handmatig worden geupload of de repository tijdelijk public worden gezet. Daarna werken private GitHub updates via het token.

## Tracking

Vul de GTM container in via:

`Instellingen > Campagne tracking`

Beschikbare dataLayer events:

- `lead_form_success`
- `lead_form_submit_attempt`
- `lead_form_step`
- `lead_form_choice`
- `lead_form_error`
- `lead_cta_click`
- `calculator_system_select`
- `phone_click`
- `whatsapp_click`

## AI content per pagina

Gebruik `docs/ai-content-schema.json` als outputcontract voor de AI-generator.
De keys in dit JSON-bestand komen overeen met de campagnevelden in WordPress.
