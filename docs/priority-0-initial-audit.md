# Priority 0 Initial Audit

Data audit: 2026-04-07

## Scope

Audit operativo e di sicurezza iniziale del repository WordPress, allineato alla Priorita 0 della roadmap di modernizzazione.

## Executive Summary

- Core WordPress aggiornato a 6.8.1 in [`wp-includes/version.php`](../wp-includes/version.php).
- Il repository traccia segreti ad alta criticita in [`wp-config.php`](../wp-config.php) e nel file [`wp-content/themes/my_structure/.env`](../wp-content/themes/my_structure/.env).
- E presente un file di login automatico Hostinger tracciato nel root: [`create_autologin_w1kusx7crchwfmu.php`](../create_autologin_w1kusx7crchwfmu.php).
- Il bootstrap runtime del tema e bloccato da errore nel controller progetto, coerente con la roadmap: [`wp-content/themes/my_structure/source/Controllers/ProgettoController.php`](../wp-content/themes/my_structure/source/Controllers/ProgettoController.php).
- Il flusso donazioni usa endpoint custom pubblici su `template_redirect`, con Stripe custom parallelo a WooCommerce Stripe.

## Findings

### Critical

1. Segreti di applicazione e pagamento tracciati nel repository.
   Evidenze:
   - [`wp-config.php`](../wp-config.php) contiene credenziali DB, chiavi e salt WordPress alle righe 22-28 e 55-62.
   - [`wp-content/themes/my_structure/.env`](../wp-content/themes/my_structure/.env) contiene chiavi Stripe live/test e webhook secret alle righe 1-8.
   Impatto:
   - compromissione potenziale di database, sessioni WordPress e account Stripe
   - impossibilita di considerare il repository come sorgente condivisibile o deploy-safe
   Azione:
   - rimuovere i file dal versionamento
   - spostare i segreti su secret manager o env non tracciati
   - pianificare rotazione controllata delle chiavi esposte

2. File di autologin Hostinger tracciato nel repository root.
   Evidenze:
   - [`create_autologin_w1kusx7crchwfmu.php`](../create_autologin_w1kusx7crchwfmu.php) include email admin, username hosting, callback URL e logica di login automatico alle righe 3-13 e 85-155.
   - Il file prova anche ad auto-eliminarsi a runtime alla riga 41.
   Impatto:
   - esposizione di metadata amministrativi e vettore di accesso anomalo
   - file temporaneo di provisioning finito nella codebase
   Azione:
   - togliere subito dal versionamento
   - verificare se il file e stato deployato su ambienti accessibili

3. Endpoint donazioni pubblici e custom senza autenticazione o nonce.
   Evidenze:
   - Route pubbliche in [`wp-content/themes/my_structure/source/routes/web.php`](../wp-content/themes/my_structure/source/routes/web.php) righe 6-7.
   - Matching via `template_redirect` in [`wp-content/themes/my_structure/app/Core/Router.php`](../wp-content/themes/my_structure/app/Core/Router.php) righe 16-20 e 49-59.
   - Input letto da `php://input` e usato direttamente in [`wp-content/themes/my_structure/source/Classes/StripePayments.php`](../wp-content/themes/my_structure/source/Classes/StripePayments.php) righe 14-18, 41-46 e 99-122.
   Impatto:
   - creazione di payment intent e side effect utente/email fuori dai meccanismi di sicurezza WordPress
   - superficie di abuso, spam, doppio submit e data pollution
   Azione:
   - migrare endpoint sensibili a REST API o AJAX WordPress con nonce e validazione server-side

### High

4. Chiave Stripe pubblica live hardcoded nel frontend oltre che in `.env`.
   Evidenze:
   - [`wp-content/themes/my_structure/source/assets/js/donation.js`](../wp-content/themes/my_structure/source/assets/js/donation.js) riga 46.
   - [`wp-content/themes/my_structure/source/assets/js/single-donation.js`](../wp-content/themes/my_structure/source/assets/js/single-donation.js) riga 44.
   Impatto:
   - duplicazione della configurazione
   - rischio di mismatch tra ambienti e deploy non controllabili
   Azione:
   - esporre la publishable key dal backend per ambiente, senza hardcode nel bundle

5. Flusso Stripe custom fragile e incoerente con Stripe Elements.
   Evidenze:
   - `createIntent()` usa amount gia in centesimi, mentre `completePayment()` moltiplica di nuovo l'importo per 100 in [`StripePayments.php`](../wp-content/themes/my_structure/source/Classes/StripePayments.php) righe 15, 24 e 42, 58.
   - `completePayment()` crea un nuovo `PaymentIntent` invece di finalizzare quello confermato dal frontend alle righe 57-64.
   - Sono presenti side effect applicativi dopo il pagamento alle righe 76-88 e 104-122.
   Impatto:
   - rischio di importi errati, ordini incoerenti e doppia logica rispetto a WooCommerce Stripe
   Azione:
   - consolidare architettura pagamenti prima di ogni refactor UI

6. Il runtime locale non e verificabile end-to-end a causa di blocker nel tema.
   Evidenze:
   - [`wp-content/themes/my_structure/source/Controllers/ProgettoController.php`](../wp-content/themes/my_structure/source/Controllers/ProgettoController.php) riga 5 contiene una `use` malformata.
   - Il tema e bootstrapppato via [`wp-content/themes/my_structure/functions.php`](../wp-content/themes/my_structure/functions.php) righe 2-6, quindi il problema impatta il caricamento dell'app custom.
   Impatto:
   - impossibile leggere in modo affidabile tema attivo e plugin attivi via bootstrap locale
   - staging e smoke test bloccati fino al fix Priorita 1
   Azione:
   - correggere i blocker PHP 8/runtime prima di qualunque verifica applicativa completa

### Medium

7. `wp-config.php` e volutamente incluso dal versionamento e contiene anche credenziali storiche commentate.
   Evidenze:
   - `.gitignore` consente esplicitamente [`wp-config.php`](../wp-config.php) alla riga 2.
   - In [`wp-config.php`](../wp-config.php) righe 22-24 sono presenti credenziali commentate di un ambiente diverso.
   Impatto:
   - persistenza di credenziali obsolete ma sensibili nella history
   Azione:
   - sostituire con `wp-config.example.php` o approccio env-driven

8. Policy di versionamento incoerente con gli artefatti realmente tracciati.
   Evidenze:
   - `.gitignore` ignora `vendor/`, `uploads/` e `*.log`, ma il repository ha gia file tracciati in:
     - [`wp-content/themes/my_structure/vendor`](../wp-content/themes/my_structure/vendor)
     - [`wp-content/uploads`](../wp-content/uploads)
     - [`wp-content/themes/my_structure/public`](../wp-content/themes/my_structure/public)
   - Conteggi attuali: `vendor` tema 3190 file, `uploads` 45 file, asset build 4 file, WooCommerce plugin 4958 file tracciati.
   Impatto:
   - repository pesante e policy di deploy poco chiara
   Azione:
   - decidere esplicitamente cosa e sorgente, cosa e build artifact, cosa e mirror del runtime

9. Tema custom dipendente da Composer/Vite ma con bootstrap minimale e forte coupling operativo.
   Evidenze:
   - [`wp-content/themes/my_structure/functions.php`](../wp-content/themes/my_structure/functions.php) righe 2-6 richiede `vendor/autoload.php`.
   - [`wp-content/themes/my_structure/composer.json`](../wp-content/themes/my_structure/composer.json) righe 9-23 usa Illuminate, Stripe SDK e Dotenv.
   - [`wp-content/themes/my_structure/package.json`](../wp-content/themes/my_structure/package.json) righe 2-30 usa Vite, Alpine, Stripe JS, Swiper e Tailwind.
   - [`wp-content/themes/my_structure/vite.config.js`](../wp-content/themes/my_structure/vite.config.js) righe 5-29 builda su `public/`.
   Impatto:
   - bootstrap ripetibile solo se dipendenze e artifact sono allineati
   Azione:
   - documentare un flusso unico di bootstrap/build per staging

## Environment And Bootstrap Map

### WordPress / Config

- Core: WordPress 6.8.1 in [`wp-includes/version.php`](../wp-includes/version.php#L19).
- Home e Site URL locali: [`wp-config.php`](../wp-config.php#L34) e [`wp-config.php`](../wp-config.php#L35).
- DB locale atteso: host `localhost`, database `pac`, user `root` in [`wp-config.php`](../wp-config.php#L26), [`wp-config.php`](../wp-config.php#L27), [`wp-config.php`](../wp-config.php#L28), [`wp-config.php`](../wp-config.php#L36).
- Debug locale attivo con log su file: [`wp-config.php`](../wp-config.php#L86), [`wp-config.php`](../wp-config.php#L88), [`wp-config.php`](../wp-config.php#L89).

### Theme bootstrap

- Tema custom dichiarato in [`wp-content/themes/my_structure/style.css`](../wp-content/themes/my_structure/style.css#L2) e [`wp-content/themes/my_structure/style.css`](../wp-content/themes/my_structure/style.css#L5).
- Bootstrap PHP via Composer autoload in [`wp-content/themes/my_structure/functions.php`](../wp-content/themes/my_structure/functions.php#L2).
- Avvio app custom in [`wp-content/themes/my_structure/functions.php`](../wp-content/themes/my_structure/functions.php#L6).
- Hook applicativi principali in [`wp-content/themes/my_structure/app/Core/App.php`](../wp-content/themes/my_structure/app/Core/App.php#L16) - [`wp-content/themes/my_structure/app/Core/App.php`](../wp-content/themes/my_structure/app/Core/App.php#L31).
- Provider registrato: [`wp-content/themes/my_structure/app/Config/providers.php`](../wp-content/themes/my_structure/app/Config/providers.php#L2) - [`wp-content/themes/my_structure/app/Config/providers.php`](../wp-content/themes/my_structure/app/Config/providers.php#L3).
- Routing custom caricato da [`wp-content/themes/my_structure/app/Core/RouterProvider.php`](../wp-content/themes/my_structure/app/Core/RouterProvider.php#L7) - [`wp-content/themes/my_structure/app/Core/RouterProvider.php`](../wp-content/themes/my_structure/app/Core/RouterProvider.php#L18).

### Config management

- Il tema legge `.env` con Dotenv in [`wp-content/themes/my_structure/app/Helpers/utility_helpers.php`](../wp-content/themes/my_structure/app/Helpers/utility_helpers.php#L31) - [`wp-content/themes/my_structure/app/Helpers/utility_helpers.php`](../wp-content/themes/my_structure/app/Helpers/utility_helpers.php#L40).
- Il tema legge il manifest Vite in [`wp-content/themes/my_structure/app/Helpers/utility_helpers.php`](../wp-content/themes/my_structure/app/Helpers/utility_helpers.php#L4) - [`wp-content/themes/my_structure/app/Helpers/utility_helpers.php`](../wp-content/themes/my_structure/app/Helpers/utility_helpers.php#L22).

### Infra items verified from repo

| Area | Stato dal repository | Note |
| --- | --- | --- |
| Hosting | Hostinger probabile | dedotto da [`create_autologin_w1kusx7crchwfmu.php`](../create_autologin_w1kusx7crchwfmu.php) |
| PHP runtime target | non documentato | roadmap richiede compatibilita PHP 8.3+ |
| Database | locale `pac` / `root` per il clone attuale | [`wp-config.php`](../wp-config.php) |
| Cache plugin | presente | `wp-fastest-cache` installato |
| CDN | non verificabile dal repo | nessuna configurazione esplicita trovata |
| Cron | non verificabile dal repo | nessun override `DISABLE_WP_CRON` in `wp-config.php` |
| SMTP | non verificabile dal repo | nessun plugin SMTP installato nel snapshot |
| Backup | non verificabile dal repo | nessun job o dump tracciato |

## Inventory

### Themes

| Tipo | Nome | Versione | Evidenza |
| --- | --- | --- | --- |
| Custom | htn.marian Theme (`my_structure`) | 1.0 | [`wp-content/themes/my_structure/style.css`](../wp-content/themes/my_structure/style.css#L2), [`wp-content/themes/my_structure/style.css`](../wp-content/themes/my_structure/style.css#L5) |
| Fallback | Twenty Twenty-Two | 1.9 | file `style.css` nel tema core |

### Plugin critici installati

| Plugin | Versione | Ruolo operativo | Evidenza |
| --- | --- | --- | --- |
| Advanced Custom Fields PRO | 6.3.12 | campi custom e opzioni tema | [`wp-content/plugins/advanced-custom-fields-pro 2/acf.php`](../wp-content/plugins/advanced-custom-fields-pro%202/acf.php#L9), [`wp-content/plugins/advanced-custom-fields-pro 2/acf.php`](../wp-content/plugins/advanced-custom-fields-pro%202/acf.php#L12) |
| WooCommerce | 10.0.4 | ecommerce e gateway registry | [`wp-content/plugins/woocommerce/woocommerce.php`](../wp-content/plugins/woocommerce/woocommerce.php#L3), [`wp-content/plugins/woocommerce/woocommerce.php`](../wp-content/plugins/woocommerce/woocommerce.php#L6) |
| WooCommerce Stripe | presenza cartella, bootstrap non verificato | gateway Stripe plugin-side | directory [`wp-content/plugins/woocommerce-gateway-stripe`](../wp-content/plugins/woocommerce-gateway-stripe) senza main file evidente nel repo |
| Polylang | 3.7.3 | multilingual | [`wp-content/plugins/polylang/polylang.php`](../wp-content/plugins/polylang/polylang.php#L10), [`wp-content/plugins/polylang/polylang.php`](../wp-content/plugins/polylang/polylang.php#L13) |
| Rank Math SEO | 1.0.250 | SEO tecnico/plugin metadata | [`wp-content/plugins/seo-by-rank-math/rank-math.php`](../wp-content/plugins/seo-by-rank-math/rank-math.php#L11), [`wp-content/plugins/seo-by-rank-math/rank-math.php`](../wp-content/plugins/seo-by-rank-math/rank-math.php#L12) |
| Contact Form 7 | 6.1 | form plugin | [`wp-content/plugins/contact-form-7/wp-contact-form-7.php`](../wp-content/plugins/contact-form-7/wp-contact-form-7.php#L3), [`wp-content/plugins/contact-form-7/wp-contact-form-7.php`](../wp-content/plugins/contact-form-7/wp-contact-form-7.php#L10) |
| WP Fastest Cache | versione non verificata staticamente | cache page/plugin | directory installata |
| WPForms Lite | versione non verificata staticamente | form plugin aggiuntivo | directory installata |
| Maintenance | versione non verificata staticamente | modalita manutenzione | directory installata |
| Iubenda Cookie Law Solution | versione non verificata staticamente | cookie compliance | directory installata |
| WPS Hide Login | versione non verificata staticamente | hardening accesso login | directory installata |
| WordPress Importer / Cat2Tag Importer | utility migrazione | non mission critical in runtime | directory installate |

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

- Plugin attivi in WordPress.
- Tema attivo letto dal database.
- Opzioni critiche di WooCommerce, Polylang, Rank Math e ACF nel database.
- SMTP, cron reali, backup reali, webhook effettivamente configurati.

Motivo:

- Il bootstrap locale produce errore critico coerente con il blocker in [`wp-content/themes/my_structure/source/Controllers/ProgettoController.php`](../wp-content/themes/my_structure/source/Controllers/ProgettoController.php#L5).
- In assenza di runtime affidabile o dump DB non e corretto marcare come "attivo" cio che nel repo risulta solo "installato".

## Staging Checklist

### Prerequisiti

- Correggere i blocker runtime del tema prima del clone funzionale.
- Preparare un dominio di staging separato da produzione.
- Creare credenziali DB dedicate allo staging.

### Clone applicativo

- Clonare repository su nuovo remote o branch di staging.
- Escludere dal deploy staging i file sensibili tracciati attuali.
- Installare dipendenze PHP del tema oppure usare un artifact deliberato e documentato.
- Installare dipendenze Node del tema e rigenerare `public/` se gli asset build non sono considerati sorgente.

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

- Smoke test homepage, archivio progetti, single progetto, form donazione e checkout.
- Verificare plugin critici: ACF, Polylang, WooCommerce, Stripe, Rank Math.
- Testare i custom route `/create-payment-intent` e `/complete-donation` solo con dati test.
- Verificare creazione log, errori PHP e asset Vite.

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
- [`wp-content/plugins/woocommerce`](../wp-content/plugins/woocommerce)
- eventuali plugin terzi non custom vendorizzati nel repository

## Recommended Next Step

1. Chiudere la Priorita 0 rimuovendo dal versionamento i file sensibili e definendo la policy artifact/runtime.
2. Passare subito alla Priorita 1 per ripristinare il runtime del tema.
3. Eseguire poi un audit runtime di plugin attivi, opzioni WooCommerce/Polylang/Rank Math e staging smoke test.
