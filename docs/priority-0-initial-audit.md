# Priority 0 Initial Audit

Data audit: 2026-04-07

## Scope

Audit operativo e di sicurezza iniziale del repository WordPress, allineato alla Priorita 0 della roadmap di modernizzazione.

## Executive Summary

- Core WordPress aggiornato a 6.9.4 in [`wp-includes/version.php`](../wp-includes/version.php#L19).
- Il repository traccia segreti ad alta criticita in [`wp-config.php`](../wp-config.php#L22) e [`wp-content/themes/my_structure/.env`](../wp-content/themes/my_structure/.env#L1).
- E presente un file di autologin Hostinger tracciato nel root: [`create_autologin_w1kusx7crchwfmu.php`](../create_autologin_w1kusx7crchwfmu.php#L3).
- Il tema custom `my_structure` e attivo nel database locale, con bootstrap via Composer autoload in [`wp-content/themes/my_structure/functions.php`](../wp-content/themes/my_structure/functions.php#L2).
- Il flusso donazioni usa endpoint custom pubblici su `template_redirect`, con logica Stripe custom parallela ai plugin ecommerce installati.
- Lo stato reale repo/DB non e completamente riproducibile: `wp-mail-smtp` risulta attivo nel DB ma non e presente nel repository, mentre `vendor/` del tema e `uploads/` risultano tracciati.

## Findings

### Critical

1. Segreti di applicazione e pagamento tracciati nel repository.
   Evidenze:
   - [`wp-config.php`](../wp-config.php#L22) contiene credenziali DB correnti e credenziali storiche commentate; [`wp-config.php`](../wp-config.php#L55) contiene salt WordPress versionati.
   - [`wp-content/themes/my_structure/.env`](../wp-content/themes/my_structure/.env#L1) contiene chiavi Stripe live/test e webhook secret.
   - `git ls-files` conferma che entrambi i file sono tracciati.
   Impatto:
   - compromissione potenziale di database, sessioni WordPress e account Stripe
   - repository non condivisibile in sicurezza
   Azione:
   - rimuovere i file dal versionamento
   - spostare i segreti su secret manager o env non tracciati
   - pianificare rotazione controllata delle chiavi esposte

2. File di autologin Hostinger tracciato nel repository root.
   Evidenze:
   - [`create_autologin_w1kusx7crchwfmu.php`](../create_autologin_w1kusx7crchwfmu.php#L3) include metadata amministrativi e di hosting.
   - [`create_autologin_w1kusx7crchwfmu.php`](../create_autologin_w1kusx7crchwfmu.php#L41) prova ad auto-eliminarsi a runtime.
   - [`create_autologin_w1kusx7crchwfmu.php`](../create_autologin_w1kusx7crchwfmu.php#L85) implementa login automatico e callback esterna.
   Impatto:
   - vettore di accesso anomalo e leak di metadata operativi
   - file temporaneo di provisioning finito nella codebase
   Azione:
   - togliere subito dal versionamento
   - verificare se il file e stato deployato su ambienti accessibili

3. Endpoint donazioni pubblici e custom senza nonce o protezione WordPress standard.
   Evidenze:
   - Route pubbliche in [`wp-content/themes/my_structure/source/routes/web.php`](../wp-content/themes/my_structure/source/routes/web.php#L6) e [`wp-content/themes/my_structure/source/routes/web.php`](../wp-content/themes/my_structure/source/routes/web.php#L7).
   - Matching via `template_redirect` in [`wp-content/themes/my_structure/app/Core/Router.php`](../wp-content/themes/my_structure/app/Core/Router.php#L18) e [`wp-content/themes/my_structure/app/Core/Router.php`](../wp-content/themes/my_structure/app/Core/Router.php#L49).
   - Input letto da `php://input` e side effect applicativi in [`wp-content/themes/my_structure/source/Classes/StripePayments.php`](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L21), [`wp-content/themes/my_structure/source/Classes/StripePayments.php`](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L57), [`wp-content/themes/my_structure/source/Classes/StripePayments.php`](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L74).
   Impatto:
   - creazione di payment intent e side effect utente/email fuori dai meccanismi di sicurezza WordPress
   - superficie di abuso, spam, doppio submit e data pollution
   Azione:
   - migrare endpoint sensibili a REST API o AJAX WordPress con nonce e validazione server-side

### High

4. Chiave Stripe publishable live hardcoded nel frontend oltre che in `.env`.
   Evidenze:
   - [`wp-content/themes/my_structure/source/assets/js/donation.js`](../wp-content/themes/my_structure/source/assets/js/donation.js#L46)
   - [`wp-content/themes/my_structure/source/assets/js/single-donation.js`](../wp-content/themes/my_structure/source/assets/js/single-donation.js#L44)
   Impatto:
   - duplicazione della configurazione
   - rischio di mismatch tra ambienti e deploy non controllabili
   Azione:
   - esporre la publishable key dal backend per ambiente, senza hardcode nel bundle

5. Plugin ACF Pro contiene una patch custom di attivazione/licenza nel repository.
   Evidenze:
   - [`wp-content/plugins/advanced-custom-fields-pro 2/acf.php`](../wp-content/plugins/advanced-custom-fields-pro%202/acf.php#L25) intercetta richieste HTTP di attivazione.
   - [`wp-content/plugins/advanced-custom-fields-pro 2/acf.php`](../wp-content/plugins/advanced-custom-fields-pro%202/acf.php#L33) restituisce una licenza simulata.
   Impatto:
   - rischio operativo e di compliance
   - impossibilita di trattare il plugin come upstream integro
   Azione:
   - documentare la deviazione
   - decidere se sostituire con una gestione licenza regolare o isolare il plugin dal repository

6. Stato repo e runtime non completamente riproducibili.
   Evidenze:
   - Il tema richiede Composer autoload in [`wp-content/themes/my_structure/functions.php`](../wp-content/themes/my_structure/functions.php#L2).
   - Il tema legge configurazione da `.env` in [`wp-content/themes/my_structure/app/Helpers/utility_helpers.php`](../wp-content/themes/my_structure/app/Helpers/utility_helpers.php#L31).
   - `.gitignore` esclude [`wp-content/plugins/wp-mail-smtp/`](../.gitignore#L8), ma il DB locale lo riporta tra i plugin attivi.
   - `vendor/` del tema e `uploads/` risultano invece tracciati nel repository.
   Impatto:
   - staging non riproducibile con certezza dal solo repository
   - alto rischio di drift tra repo, DB e filesystem
   Azione:
   - definire una policy esplicita per sorgenti, artifact e runtime mirror
   - riallineare plugin attivi del DB con file realmente presenti

7. Flusso Stripe custom fragile e incoerente.
   Evidenze:
   - `createIntent()` tratta l'importo come centesimi in [`StripePayments.php`](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L23), mentre `completePayment()` lo moltiplica di nuovo per 100 in [`StripePayments.php`](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L75).
   - `completePayment()` crea un nuovo `PaymentIntent` invece di finalizzare quello confermato dal frontend in [`StripePayments.php`](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L74).
   - Dopo il pagamento avvia email e creazione utente in [`StripePayments.php`](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L93) e [`StripePayments.php`](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L116).
   Impatto:
   - rischio di importi errati, ordini incoerenti e doppia logica rispetto a WooCommerce Stripe
   Azione:
   - consolidare architettura pagamenti prima di ogni refactor UI

### Medium

8. `wp-config.php` e volutamente incluso dal versionamento e contiene credenziali storiche commentate.
   Evidenze:
   - `.gitignore` consente esplicitamente [`wp-config.php`](../.gitignore#L2).
   - In [`wp-config.php`](../wp-config.php#L22) restano credenziali commentate di un ambiente diverso.
   Impatto:
   - persistenza di credenziali obsolete ma sensibili nella history
   Azione:
   - sostituire con `wp-config.example.php` o approccio env-driven

9. Policy di versionamento incoerente con gli artefatti realmente tracciati.
   Evidenze:
   - `.gitignore` ignora [`wp-content/themes/my_structure/vendor/`](../.gitignore#L16) e [`wp-content/uploads/`](../.gitignore#L20), ma il repository contiene gia file tracciati in entrambe le cartelle.
   - Gli artifact frontend in `public/` sono parzialmente trattati come sorgente.
   Impatto:
   - repository pesante e policy di deploy poco chiara
   Azione:
   - decidere esplicitamente cosa e sorgente, cosa e build artifact, cosa e mirror del runtime

10. Debug display attivo nel clone locale.
    Evidenze:
    - [`wp-config.php`](../wp-config.php#L86), [`wp-config.php`](../wp-config.php#L88), [`wp-config.php`](../wp-config.php#L89)
    Impatto:
    - esposizione di notice e warning se l'ambiente e raggiungibile
    Azione:
    - in staging usare `WP_DEBUG_DISPLAY=false`

11. Runtime locale avviabile ma con notice/plugin drift.
    Evidenze:
    - `php -l` su [`wp-content/themes/my_structure/source/Controllers/ProgettoController.php`](../wp-content/themes/my_structure/source/Controllers/ProgettoController.php#L1) e [`wp-content/themes/my_structure/app/Core/Singleton.php`](../wp-content/themes/my_structure/app/Core/Singleton.php#L1) non rileva errori sintattici.
    - [`wp-content/debug.log`](../wp-content/debug.log#L1) mostra notice ACF per caricamento traduzioni anticipato.
    Impatto:
    - il blocker sintattico citato nella roadmap non e piu attuale
    - restano warning e incoerenze operative da trattare nelle priorita successive
    Azione:
    - aggiornare la baseline documentale e proseguire con hardening runtime/pagamenti

## Environment And Bootstrap Map

### WordPress / Config

- Core: WordPress 6.9.4 in [`wp-includes/version.php`](../wp-includes/version.php#L19).
- Home e Site URL locali: [`wp-config.php`](../wp-config.php#L34) e [`wp-config.php`](../wp-config.php#L35).
- DB locale atteso: host `localhost`, database `pac`, user `root` in [`wp-config.php`](../wp-config.php#L26), [`wp-config.php`](../wp-config.php#L27), [`wp-config.php`](../wp-config.php#L28), [`wp-config.php`](../wp-config.php#L36).
- Debug locale attivo con log su file e display a video: [`wp-config.php`](../wp-config.php#L86), [`wp-config.php`](../wp-config.php#L88), [`wp-config.php`](../wp-config.php#L89).
- Runtime locale verificato: PHP CLI 8.3.29 e MySQL client 8.0.45.

### Theme bootstrap

- Tema custom dichiarato in [`wp-content/themes/my_structure/style.css`](../wp-content/themes/my_structure/style.css#L2).
- Bootstrap PHP via Composer autoload in [`wp-content/themes/my_structure/functions.php`](../wp-content/themes/my_structure/functions.php#L2).
- Avvio app custom in [`wp-content/themes/my_structure/functions.php`](../wp-content/themes/my_structure/functions.php#L6).
- Hook applicativi principali in [`wp-content/themes/my_structure/app/Core/App.php`](../wp-content/themes/my_structure/app/Core/App.php#L16) - [`wp-content/themes/my_structure/app/Core/App.php`](../wp-content/themes/my_structure/app/Core/App.php#L31).
- Provider registrato: [`wp-content/themes/my_structure/app/Config/providers.php`](../wp-content/themes/my_structure/app/Config/providers.php#L2) - [`wp-content/themes/my_structure/app/Config/providers.php`](../wp-content/themes/my_structure/app/Config/providers.php#L3).
- Routing custom caricato da [`wp-content/themes/my_structure/app/Core/RouterProvider.php`](../wp-content/themes/my_structure/app/Core/RouterProvider.php#L7) - [`wp-content/themes/my_structure/app/Core/RouterProvider.php`](../wp-content/themes/my_structure/app/Core/RouterProvider.php#L18).

### Config management

- Il tema legge `.env` con Dotenv in [`wp-content/themes/my_structure/app/Helpers/utility_helpers.php`](../wp-content/themes/my_structure/app/Helpers/utility_helpers.php#L31) - [`wp-content/themes/my_structure/app/Helpers/utility_helpers.php`](../wp-content/themes/my_structure/app/Helpers/utility_helpers.php#L40).
- Il tema legge il manifest Vite in [`wp-content/themes/my_structure/app/Helpers/utility_helpers.php`](../wp-content/themes/my_structure/app/Helpers/utility_helpers.php#L4) - [`wp-content/themes/my_structure/app/Helpers/utility_helpers.php`](../wp-content/themes/my_structure/app/Helpers/utility_helpers.php#L22).

### Infra items verified from repo and local DB

| Area | Stato verificato | Note |
| --- | --- | --- |
| Hosting | Hostinger probabile | dedotto da [`create_autologin_w1kusx7crchwfmu.php`](../create_autologin_w1kusx7crchwfmu.php#L3) |
| PHP runtime target | 8.3+ richiesto dalla roadmap | PHP locale 8.3.29 |
| Database | clone locale `pac` / `root` | [`wp-config.php`](../wp-config.php#L26) |
| Cache plugin | presente e attivo nel DB | `wp-fastest-cache` |
| SMTP | presente nel DB ma assente nel repo | `wp-mail-smtp` attivo nel DB, cartella plugin mancante |
| CDN | non verificabile dal repo | nessuna configurazione esplicita trovata |
| Cron | non verificabile dal repo | nessun override `DISABLE_WP_CRON` trovato |
| Backup | non verificabile dal repo | nessun job o dump tracciato |

## Inventory

### Themes

| Tipo | Nome | Versione | Stato | Evidenza |
| --- | --- | --- | --- | --- |
| Custom | htn.marian Theme (`my_structure`) | 1.0 | attivo nel DB locale | [`wp-content/themes/my_structure/style.css`](../wp-content/themes/my_structure/style.css#L2) |
| Fallback | Twenty Twenty-Two | non verificata in questo audit | installato | directory tema presente |

### Plugin attivi nel DB locale

| Plugin | Stato DB | Ruolo operativo | Evidenza |
| --- | --- | --- | --- |
| Advanced Custom Fields PRO | attivo | campi custom e opzioni tema | [`wp-content/plugins/advanced-custom-fields-pro 2/acf.php`](../wp-content/plugins/advanced-custom-fields-pro%202/acf.php#L9) |
| Contact Form 7 | attivo | form plugin | [`wp-content/plugins/contact-form-7/wp-contact-form-7.php`](../wp-content/plugins/contact-form-7/wp-contact-form-7.php#L3) |
| Iubenda Cookie Law Solution | attivo | compliance cookie | [`wp-content/plugins/iubenda-cookie-law-solution/iubenda_cookie_solution.php`](../wp-content/plugins/iubenda-cookie-law-solution/iubenda_cookie_solution.php#L3) |
| Maintenance | attivo | maintenance mode | [`wp-content/plugins/maintenance/maintenance.php`](../wp-content/plugins/maintenance/maintenance.php#L3) |
| WP Fastest Cache | attivo | cache page/plugin | [`wp-content/plugins/wp-fastest-cache/wpFastestCache.php`](../wp-content/plugins/wp-fastest-cache/wpFastestCache.php#L3) |
| WP Mail SMTP | attivo nel DB ma plugin assente nel repo | email/SMTP | mismatch repo-DB |
| WPS Hide Login | attivo | hardening accesso login | [`wp-content/plugins/wps-hide-login/wps-hide-login.php`](../wp-content/plugins/wps-hide-login/wps-hide-login.php#L3) |

### Plugin critici installati ma non attivi nel DB locale

| Plugin | Versione rilevata | Note |
| --- | --- | --- |
| WooCommerce | 10.6.2 | installato, non attivo nel DB locale | 
| Polylang | 3.8.2 | installato, non attivo nel DB locale |
| Rank Math SEO | 1.0.267 | installato, non attivo nel DB locale |
| WooCommerce Stripe | bootstrap incompleto nel repo | cartella parziale, nessun main plugin file in root |
| WPForms Lite | bootstrap incompleto nel repo | cartella parziale, nessun main plugin file in root |
| WordPress Importer / Cat2Tag Importer | utility migrazione | non mission critical in runtime |

### Theme dependencies

#### PHP / Composer

- Blade / Illuminate view stack: [`wp-content/themes/my_structure/composer.json`](../wp-content/themes/my_structure/composer.json#L10) - [`wp-content/themes/my_structure/composer.json`](../wp-content/themes/my_structure/composer.json#L18)
- Stripe PHP SDK: [`wp-content/themes/my_structure/composer.json`](../wp-content/themes/my_structure/composer.json#L21)
- Dotenv: [`wp-content/themes/my_structure/composer.json`](../wp-content/themes/my_structure/composer.json#L23)
- Validation: [`wp-content/themes/my_structure/composer.json`](../wp-content/themes/my_structure/composer.json#L19)

#### Frontend / Node

- Alpine: [`wp-content/themes/my_structure/package.json`](../wp-content/themes/my_structure/package.json#L3) - [`wp-content/themes/my_structure/package.json`](../wp-content/themes/my_structure/package.json#L4)
- Axios: [`wp-content/themes/my_structure/package.json`](../wp-content/themes/my_structure/package.json#L5)
- Stripe JS SDK package: [`wp-content/themes/my_structure/package.json`](../wp-content/themes/my_structure/package.json#L7)
- Swiper: [`wp-content/themes/my_structure/package.json`](../wp-content/themes/my_structure/package.json#L8)
- Vite: [`wp-content/themes/my_structure/package.json`](../wp-content/themes/my_structure/package.json#L9)
- Tailwind stack: [`wp-content/themes/my_structure/package.json`](../wp-content/themes/my_structure/package.json#L24) - [`wp-content/themes/my_structure/package.json`](../wp-content/themes/my_structure/package.json#L30)

## What Could Not Be Verified Reliably

- Hosting, CDN, cron e backup reali.
- Configurazioni WooCommerce, Polylang, Rank Math e ACF lato database di produzione.
- Stato reale dei plugin in produzione.
- Se i file sensibili gia tracciati siano stati deployati su ambienti pubblici.

Motivo:

- L'audit e basato sul repository corrente e sul database locale `pac`.
- Il clone locale mostra mismatch tra filesystem e opzioni WordPress, quindi non e corretto inferire lo stato di produzione.

## Staging Checklist

### Prerequisiti

- Preparare un dominio di staging separato da produzione.
- Creare credenziali DB dedicate allo staging.
- Definire la policy per `vendor/`, `public/`, `uploads/` e plugin terzi non custom prima del deploy.

### Clone applicativo

- Clonare repository su branch o remote dedicato allo staging.
- Escludere dal deploy staging i file sensibili attualmente tracciati.
- Installare dipendenze PHP del tema oppure usare un artifact deliberato e documentato.
- Installare dipendenze Node del tema e rigenerare `public/` se gli asset build non sono considerati sorgente.
- Riallineare i plugin attivi del DB con i plugin realmente presenti nel filesystem.

### Clone dati

- Clonare database produzione in staging con search/replace di dominio.
- Clonare `wp-content/uploads` da produzione.
- Verificare permessi filesystem su cache, uploads e log.

### Config sicura

- Usare `WP_HOME` e `WP_SITEURL` di staging.
- Tenere `WP_DEBUG=true`, `WP_DEBUG_LOG=true`, `WP_DEBUG_DISPLAY=false`.
- Valutare `SCRIPT_DEBUG=true` solo se serve debug frontend.
- Usare un `.env` staging separato, non tracciato.
- Usare chiavi Stripe test in staging.
- Disabilitare webhook live o puntarli a endpoint test.
- Disabilitare email reali oppure instradarle a mailbox sink/test.
- Verificare che eventuali redirect custom non puntino al dominio live.

### Safety controls

- Bloccare indicizzazione (`blog_public=0` e/o auth edge).
- Proteggere wp-admin con credenziali dedicate e MFA se disponibile.
- Verificare che `create_autologin_*.php` non sia presente.
- Verificare che non esistano chiavi live in JS bundle o `.env`.
- Disabilitare o limitare cron che inviano email o toccano sistemi terzi.

### Validation

- Smoke test homepage, archivio progetti, single progetto, form donazione e wp-admin.
- Verificare plugin critici attesi per lo staging: ACF, cache, form, WPS Hide Login, e solo se deliberato WooCommerce/Stripe/Polylang/Rank Math.
- Testare i custom route `/create-payment-intent` e `/complete-donation` solo con dati test.
- Verificare creazione log, notice PHP, asset Vite e assenza di output di debug a schermo.

## Files To Remove From Version Control Or Manage Differently

### Da togliere dal versionamento

- [`wp-content/themes/my_structure/.env`](../wp-content/themes/my_structure/.env)
- [`wp-config.php`](../wp-config.php)
- [`create_autologin_w1kusx7crchwfmu.php`](../create_autologin_w1kusx7crchwfmu.php)

### Da sostituire con template/example

- `wp-config.example.php`
- `wp-content/themes/my_structure/.env.example`

### Da gestire con policy esplicita

- [`wp-content/themes/my_structure/vendor`](../wp-content/themes/my_structure/vendor)
- [`wp-content/themes/my_structure/public`](../wp-content/themes/my_structure/public)
- [`wp-content/uploads`](../wp-content/uploads)
- [`wp-content/plugins/advanced-custom-fields-pro 2`](../wp-content/plugins/advanced-custom-fields-pro%202)
- [`wp-content/plugins/woocommerce`](../wp-content/plugins/woocommerce)
- [`wp-content/plugins/woocommerce-gateway-stripe`](../wp-content/plugins/woocommerce-gateway-stripe)
- [`wp-content/plugins/wpforms-lite`](../wp-content/plugins/wpforms-lite)

## Recommended Next Step

1. Chiudere davvero la Priorita 0 rimuovendo dal versionamento i file sensibili e definendo la policy artifact/runtime.
2. Riallineare repository, filesystem e plugin attivi del DB per ottenere uno staging riproducibile.
3. Passare quindi alla Priorita 2 per hardening pagamenti e routing, mantenendo la Priorita 1 limitata ai warning/runtime residui effettivamente ancora aperti.
