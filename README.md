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

## Tracking

Vul de GTM container in via:

`Instellingen > Campagne tracking`

Beschikbare dataLayer events:

- `lead_form_success`
- `phone_click`
- `whatsapp_click`
