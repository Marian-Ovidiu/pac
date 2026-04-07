# Priorita 6: Refactor UI e design system

## Obiettivo

Rendere il tema `my_structure` coerente, manutenibile e accessibile, partendo da home, archivio progetti e singolo progetto, senza introdurre dipendenze frontend aggiuntive.

## Step-Back

Il principio usato e `design system minimo, non framework parallelo`.

- una grammatica visiva condivisa vale piu di tanti componenti specializzati
- i template core devono usare gli stessi token, spaziature, CTA e pattern di superficie
- dove esisteva duplicazione forte, il refactor ha preferito consolidare, non moltiplicare
- i campi ACF sono rimasti il perimetro del contenuto, quindi il redesign non richiede nuovi field group

## Pianificazione Strategica

1. Introdurre token e classi UI riusabili nel layer Tailwind/SCSS.
2. Consolidare partial e componenti orfani o duplicati.
3. Rifare i template ad alto impatto con gli stessi mattoni visuali.
4. Riusare i dati ACF esistenti senza inventare nuovo debito di contenuto.
5. Verificare build, mobile baseline e bootstrap delle pagine core.

## Design System Minimo

### Token e layer condivisi

Aggiornati:

- [`tailwind.config.js`](/C:/projects/privati/pac/wp-content/themes/my_structure/tailwind.config.js)
- [`style.scss`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/assets/scss/style.scss)

Nuovi token introdotti:

- `custom-ink`
- `custom-forest`
- `custom-sand`
- `custom-clay`
- `custom-stone`

Nuove classi riusabili:

- `ui-container`
- `ui-section`
- `ui-card`
- `ui-card-soft`
- `ui-panel`
- `ui-kicker`
- `ui-title`
- `ui-subtitle`
- `ui-richtext`
- `ui-button`
- `ui-button-secondary`
- `ui-button-ghost`
- `ui-pill`
- `ui-stat`
- `ui-input`
- `ui-donation-card`
- `ui-step`
- `ui-image-frame`

Questo layer serve a mantenere coerenza su:

- gerarchia tipografica
- superfici e depth
- CTA
- campi input
- hero e card progetto

## Refactor Implementato

### Shared layout

Aggiornati:

- [`header-menu.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/partials/header-menu.blade.php)
- [`footer-menu.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/partials/footer-menu.blade.php)

Risultato:

- header piu compatto e coerente con il nuovo sistema visivo
- footer trasformato in blocco editoriale/istituzionale piu leggibile
- CTA, pill e navigation styles unificati

### Componenti consolidati

Aggiornati:

- [`slider.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/slider.blade.php)
- [`testo-sottotesto.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/testo-sottotesto.blade.php)
- [`mono-logo.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/mono-logo.blade.php)
- [`missione.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/missione.blade.php)
- [`home-cards.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/home-cards.blade.php)
- [`aziende.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/aziende.blade.php)
- [`section.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/section.blade.php)
- [`donation-form.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/donation-form.blade.php)

Risultato:

- una sola grammatica per hero, blocchi testo, card progetto e sezioni narrative
- componente donazione condiviso tra archivio e singolo progetto
- slider e gallery resi piu coerenti con la brand identity attuale

### Template core rifatti

Aggiornati:

- [`home.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/home.blade.php)
- [`archivio-progetto.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/archivio-progetto.blade.php)
- [`single-progetto.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/single-progetto.blade.php)

Risultato:

- home con hero piu deciso, intro istituzionale, card progetto piu editoriali e promo aziende allineata
- archivio progetti con hero forte e card progetto + donazione in layout coerente
- singolo progetto con hero, sezioni narrative e blocco donazione finale integrato

## Riduzione Duplicazioni

Rimossi partial/componenti orfani:

- [`footer-menu-deutsch.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/partials/footer-menu-deutsch.blade.php)
- [`footer-menu-english.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/partials/footer-menu-english.blade.php)
- [`footer-menu-francais.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/partials/footer-menu-francais.blade.php)
- [`header-menu-deutsch.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/partials/header-menu-deutsch.blade.php)
- [`header-menu-english.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/partials/header-menu-english.blade.php)
- [`header-menu-francais.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/partials/header-menu-francais.blade.php)
- [`language-menu.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/partials/language-menu.blade.php)
- [`duo-logo.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/duo-logo.blade.php)
- [`home-mobile-cards.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/home-mobile-cards.blade.php)
- [`linear-slider.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/linear-slider.blade.php)

Motivazione:

- non erano referenziati dal runtime attuale
- aumentavano il rumore architetturale
- mantenevano in vita una falsa stratificazione multilingual/legacy

## Allineamento ACF

Il redesign ha riusato i campi esistenti:

- slider home
- missione
- progetti home
- promo aziende
- opzioni archivio progetto
- contenuti progetto singolo

Non sono stati aggiunti nuovi field group o nuovi campi ACF. Il refactor lavora solo su:

- gerarchia
- presentazione
- CTA
- disposizione dei blocchi

## Verifiche

Eseguite:

- `npm run build`
- smoke test bootstrap `home`
- smoke test bootstrap `archivio-progetto`
- smoke test bootstrap `single-progetto`

Esito:

- build Vite: `OK`
- homepage: `OK`
- archivio progetti: `OK`
- singolo progetto: `OK`

## Note Per Editor E Content Team

1. I titoli hero e i testi brevi sono molto piu visibili di prima: testi troppo lunghi romperanno il ritmo visivo.
2. Le immagini hero e card devono avere `alt`, `caption` e tagli piu curati, perche il nuovo design le valorizza di piu.
3. Le CTA ACF vengono ora trattate come elementi primari del layout: servono label piu nette e operative.
4. Nei progetti singoli, i blocchi `Problemi` e `Soluzioni` reggono meglio con copy medio-breve e immagini pulite.

## Rischi Residui

- il modello multilingual resta sospeso finche non si chiude la decisione su Polylang
- il flusso donazione lato contenuto/UX puo essere ulteriormente raffinato, ma il refactor attuale non cambia il comportamento applicativo
- alcune pagine secondarie del tema non sono state ridisegnate in questo pass
