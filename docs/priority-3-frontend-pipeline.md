# Priorita 3: Pipeline frontend, asset e deploy

## Obiettivo

Unificare il caricamento asset del tema `my_structure` su una sola pipeline Vite, compatibile con WordPress e ripetibile in deploy.

## Step-Back

Il principio applicato e `single source of truth` per gli asset frontend:

- gli asset locali del tema devono essere risolti solo tramite manifest Vite in produzione
- WordPress resta il punto di orchestrazione degli enqueue
- i controller possono dichiarare dipendenze funzionali, ma non devono conoscere path buildati o asset raw in `source/assets`
- gli asset esterni restano espliciti e separati dal bundle locale

Questo evita una doppia pipeline parallela tra file sorgente caricati direttamente e file compilati in `public/`.

## Stato iniziale rilevato

- Vite era gia configurato in [`wp-content/themes/my_structure/vite.config.js`](/C:/projects/privati/pac/wp-content/themes/my_structure/vite.config.js)
- il manifest era presente in [`wp-content/themes/my_structure/public/.vite/manifest.json`](/C:/projects/privati/pac/wp-content/themes/my_structure/public/.vite/manifest.json)
- il tema caricava ancora asset locali direttamente da `source/assets` tramite [`app/Core/Bases/BaseController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Core/Bases/BaseController.php)
- il bootstrap globale usava solo in parte [`app/Helpers/utility_helpers.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Helpers/utility_helpers.php)
- alcuni controller mescolavano asset buildati, asset raw e CDN

## Intervento implementato

### 1. Manifest Vite centralizzato

Le funzioni helper ora risolvono gli entrypoint tramite manifest:

- [`app/Helpers/utility_helpers.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Helpers/utility_helpers.php)

Funzioni chiave:

- `vite_manifest()`
- `vite_entry_key()`
- `vite_entry()`
- `vite_asset()`
- `vite_asset_css()`

Effetto:

- un path logico come `js/main.js` o `scss/style.scss` viene tradotto nel file buildato corretto in `public/`
- gli eventuali CSS associati a un entry JS vengono enqueued automaticamente

### 2. BaseController allineato a Vite

[`app/Core/Bases/BaseController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Core/Bases/BaseController.php) ora:

- mantiene il supporto per URL esterni
- per gli asset locali usa solo il manifest Vite
- evita di pubblicare in pagina riferimenti diretti a `source/assets`
- localizza variabili JS su handle esistenti o su un handle neutro se necessario

### 3. Bootstrap globale tema unificato

[`app/Helpers/theme_helpers.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Helpers/theme_helpers.php) ora:

- enqueua `main.js` dal manifest
- enqueua `style.scss` dal manifest
- aggancia gli eventuali CSS generati da `main.js`
- mantiene la localizzazione `pacPayments` senza introdurre una pipeline alternativa

### 4. Controller pagina ripuliti

Controller aggiornati:

- [`source/Controllers/HomeController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Controllers/HomeController.php)
- [`source/Controllers/PageController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Controllers/PageController.php)
- [`source/Controllers/ProgettoController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Controllers/ProgettoController.php)

Pulizia applicata:

- rimossi enqueue locali diretti verso file raw del tema
- gli entrypoint locali di pagina ora passano da Vite
- le dipendenze esterne esplicite, come Stripe CDN, restano separate
- gli slider locali usano entrypoint dedicati Vite invece di script caricati direttamente

### 5. Vite config allineato agli entrypoint reali

[`wp-content/themes/my_structure/vite.config.js`](/C:/projects/privati/pac/wp-content/themes/my_structure/vite.config.js) ora builda in modo esplicito:

- `main`
- `style`
- `homeSlider`
- `progettoSlider`

## Mappa caricamento asset finale

### Asset globali

- `assets/js/main.js` -> entry JS globale
- `assets/scss/style.scss` -> entry CSS globale

### Asset pagina-specifici

- `assets/js/homeSlider.js` -> homepage slider
- `assets/js/progettoSlider.js` -> slider pagina progetto

### Asset esterni mantenuti tali

- Stripe JS via CDN, per compatibilita con il flusso donazioni attuale

## Esito verifiche

Verifiche eseguite:

- `npm install`
- `npm run build`
- lint PHP completo del tema escludendo `vendor`, `node_modules` e cache runtime
- bootstrap CLI WordPress

Esito:

- build Vite riuscita
- manifest aggiornato correttamente
- nessun riferimento residuo agli asset raw del tema nel flusso di enqueue applicativo
- lint PHP: `OK`
- bootstrap WordPress: `OK` con il noto notice ACF non bloccante

## Policy versionamento e artefatti

### Da non versionare

- `wp-content/themes/my_structure/.env`
- `wp-content/themes/my_structure/node_modules/`
- `wp-content/themes/my_structure/resources/cache/`
- log e file temporanei locali

Motivo:

- contengono segreti, dipendenze ricostruibili o stato runtime locale

### Da versionare solo se il deploy lo richiede davvero

- `wp-content/themes/my_structure/vendor/`
- `wp-content/themes/my_structure/public/`

Policy raccomandata:

- `vendor/`: non versionare se il deploy puo eseguire `composer install`; mantenerlo versionato solo su hosting senza Composer
- `public/`: non versionare se esiste una build CI/CD o una build sul server; mantenerlo versionato solo finche il deploy dipende dal repository come artefatto finale

### Da escludere lato WordPress progetto

- `wp-content/cache/`
- `wp-content/uploads/` se gestito come contenuto runtime e non come fixture di sviluppo

## Build e deploy

Prerequisiti minimi:

- Node.js installato
- dipendenze NPM installate nel tema
- PHP/WordPress gia bootstrapabili lato ambiente

Comandi standard:

```bash
cd wp-content/themes/my_structure
npm install
npm run build
```

Comando sviluppo locale:

```bash
cd wp-content/themes/my_structure
npm run dev
```

Nota:

- il tema oggi e predisposto per il manifest di produzione
- l'uso del dev server Vite richiede un'integrazione esplicita aggiuntiva nel tema se si vuole HMR reale; non e stata introdotta in questo pass per non creare una seconda pipeline

## Decisioni aperte

1. Se `public/` debba restare versionato oppure diventare artefatto CI/CD.
2. Se `vendor/` del tema debba restare nel repository per limiti dell'hosting.
3. Se convenga consolidare anche Stripe JS dentro un boundary piu esplicito lato theme loader, pur restando esterno al bundle.

## Smoke test manuali

1. Homepage: verificare CSS globale, menu, footer e slider homepage.
2. Archivio progetti: verificare che gli script globali e i testi localizzati siano presenti.
3. Singolo progetto: verificare slider progetto, form donazione e caricamento Stripe.
4. Pagina galleria: verificare asset globali e contenuti dinamici.
5. WP admin + frontend pubblico: controllare assenza di 404 asset in console o rete.

## Conclusione

La pipeline del tema e ora unificata: WordPress enqueua asset locali solo tramite manifest Vite, senza fallback impliciti ai file sorgente del tema. Rimane aperta solo la decisione operativa su quali artefatti buildati debbano restare nel repository in funzione del processo di deploy reale.
