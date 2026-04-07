# PAC Modernization Roadmap

## Scope

Questa roadmap copre il risanamento del progetto WordPress/PHP custom presente in questo repository, con focus su:

- compatibilita PHP 8.3+
- stabilita runtime del tema custom
- hardening pagamenti e sicurezza
- rifacimento pipeline frontend
- SEO tecnico e accessibilita
- refactor UI e semplificazione architetturale

## Baseline rilevata

- Core WordPress aggiornato: [`wp-includes/version.php`](/C:/projects/privati/pac/wp-includes/version.php)
- Tema custom principale: [`wp-content/themes/my_structure`](/C:/projects/privati/pac/wp-content/themes/my_structure)
- Bootstrap tema minimale: [`wp-content/themes/my_structure/functions.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/functions.php)
- Parse error bloccante: [`wp-content/themes/my_structure/source/Controllers/ProgettoController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Controllers/ProgettoController.php)
- Warning PHP 8 su magic method: [`wp-content/themes/my_structure/app/Core/Singleton.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Core/Singleton.php)
- Layout SEO/a11y hardcoded: [`wp-content/themes/my_structure/resources/views/layouts/mainLayout.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/layouts/mainLayout.blade.php)
- Flusso pagamenti fragile: [`wp-content/themes/my_structure/source/Classes/StripePayments.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Classes/StripePayments.php)
- Router custom sensibile: [`wp-content/themes/my_structure/app/Core/Router.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Core/Router.php)
- Asset loading incoerente con Vite: [`wp-content/themes/my_structure/app/Core/Bases/BaseController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Core/Bases/BaseController.php), [`wp-content/themes/my_structure/app/Helpers/utility_helpers.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Helpers/utility_helpers.php), [`wp-content/themes/my_structure/vite.config.js`](/C:/projects/privati/pac/wp-content/themes/my_structure/vite.config.js)
- Segreti/versionamento da auditare: [`.gitignore`](/C:/projects/privati/pac/.gitignore), [`wp-content/themes/my_structure/.env`](/C:/projects/privati/pac/wp-content/themes/my_structure/.env)

## Priorita 0: Stabilizzazione e sicurezza iniziale

### Obiettivo

Mettere il progetto in una condizione sicura per lavorare senza peggiorare il rischio operativo.

### Task

1. Mappare ambiente e deployment attuale.
   Deliverable: tabella con hosting, PHP, DB, dominio, cache, CDN, cron, SMTP, backup.
   Dipendenze: accesso staging/produzione e wp-admin.
   Done when: esiste un documento unico con la baseline infrastrutturale.

2. Verificare esposizione di segreti e asset sensibili nel repository.
   Controlli:
   - `.env` tracciato
   - eventuali chiavi Stripe
   - credenziali SMTP/API
   - dump o log sensibili
   Deliverable: lista segreti da ruotare e file da escludere dal versionamento.
   Done when: chiavi ruotate o piano di rotazione approvato.

3. Preparare staging riproducibile.
   Controlli:
   - clone DB e uploads
   - `WP_DEBUG`, `WP_DEBUG_LOG`, `SCRIPT_DEBUG`
   - disattivazione email reali e webhook reali
   Deliverable: ambiente di test allineato.
   Done when: si puo navigare il sito end-to-end senza toccare produzione.

4. Fotografare plugin attivi, tema attivo e opzioni critiche.
   Deliverable: inventario plugin + stato attivo/disattivo + dipendenze ACF/Polylang/WooCommerce.
   Done when: l'impatto di un aggiornamento e stimabile.

## Priorita 1: Compatibilita PHP 8.3 e ripristino runtime

### Obiettivo

Eliminare errori bloccanti e warning strutturali del tema custom.

### Task

1. Correggere la parse error in [`source/Controllers/ProgettoController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Controllers/ProgettoController.php).
2. Correggere il warning PHP 8 su [`app/Core/Singleton.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Core/Singleton.php).
3. Eseguire lint completo del tema custom escludendo `vendor/` e `node_modules/`.
4. Normalizzare encoding e stringhe corrotte.
5. Aggiungere validazione e sanitizzazione minima su input/server globals.
6. Introdurre una checklist di smoke test sulle pagine principali.

## Priorita 2: Hardening pagamenti, donazioni e routing sensibile

### Obiettivo

Rendere affidabile e sicuro il flusso donazioni.

### Task

1. Audit completo del flusso Stripe in [`source/Classes/StripePayments.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Classes/StripePayments.php).
2. Spostare gli endpoint sensibili su REST API WordPress o AJAX con nonce.
3. Separare responsabilita tra pagamento, persistenza, email e logging.
4. Validare dati utente e dati progetto.
5. Decidere se usare WooCommerce davvero o un flusso custom puro.
6. Implementare test manuali e di integrazione sui casi chiave.

## Priorita 3: Pipeline frontend, asset e deploy

### Obiettivo

Allineare build moderna e runtime del tema.

### Task

1. Definire una strategia asset unica.
2. Rifattorizzare [`app/Core/Bases/BaseController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Core/Bases/BaseController.php) per usare il manifest Vite in produzione.
3. Decidere la politica di versionamento per `vendor/`, `node_modules/`, `public/`, cache e `.env`.
4. Pulire file wrapper o placeholder inutili nel tema.
5. Documentare i comandi standard di build e bootstrap.

## Priorita 4: Audit plugin, contenuti e dipendenze funzionali

### Obiettivo

Ridurre complessita e dipendenze inutili prima del refactor pesante.

### Task

1. Estrarre lista plugin attivi/inattivi e loro ruolo.
2. Verificare la cartella `advanced-custom-fields-pro 2`.
3. Eliminare duplicazioni funzionali.
4. Mappare i field group ACF e i template che li consumano.
5. Verificare Polylang, menu, slug e `hreflang`.

## Priorita 5: SEO tecnico e accessibilita

### Obiettivo

Portare il tema a standard moderni di semantica, discoverability e conformita minima.

### Task

1. Rifare il layout base in [`resources/views/layouts/mainLayout.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/layouts/mainLayout.blade.php).
2. Audit heading, landmark e navigazione tastiera.
3. Audit immagini e media.
4. Audit form e componenti dinamici.
5. Sistemare sitemap, canonical, schema e metadata dinamici.

## Priorita 6: Refactor UI e semplificazione del tema

### Obiettivo

Rifare il frontend senza ereditare la complessita attuale.

### Task

1. Definire design system.
2. Ridurre partial duplicate per lingua.
3. Rifare i template ad alto impatto.
4. Allineare ACF al nuovo design.
5. Fare performance pass finale.

## Priorita 7: Release readiness, staging UAT e go-live

### Obiettivo

Chiudere i residui operativi e validare il progetto in condizioni reali prima del rilascio.

### Task

1. Chiudere i residui tecnici minimi pre-release.
   Controlli:
   - notice ACF su textdomain caricato troppo presto
   - eventuali warning/debug residui
   - pulizia worktree e allineamento artefatti buildati
   Done when: bootstrap e build non generano rumore inatteso oltre ai residui accettati.

2. Definire la policy finale di repository e deploy.
   Controlli:
   - `public/` versionato o artefatto CI/CD
   - `vendor/` versionato o installato in deploy
   - `.env` fuori dal repo
   - checklist di build/deploy ripetibile
   Done when: esiste una sola procedura di rilascio approvata.

3. Eseguire UAT end-to-end su staging.
   Flussi minimi:
   - home
   - archivio progetti
   - singolo progetto
   - galleria
   - aziende
   - grazie
   - donazione completa
   - mail post-donazione
   Done when: ogni flusso ha esito registrato e screenshot/log utili.

4. Eseguire regressione tecnica trasversale.
   Controlli:
   - console browser
   - network 404/500
   - debug.log
   - mobile baseline
   - multilanguage se attivo
   Done when: i bug critici e high sono a zero.

5. Decidere e implementare i fix finali di affidabilita pagamento.
   Focus:
   - webhook Stripe server-to-server
   - idempotenza finale
   - gestione redirect persi
   Done when: il rischio operativo del flusso donazioni e accettabile per produzione.

6. Preparare pacchetto di go-live.
   Deliverable:
   - checklist release
   - rollback plan
   - smoke test post-deploy
   - owner e tempi
   Done when: il deploy puo essere eseguito da un altro tecnico senza conoscenza implicita.

## Sequenza consigliata

1. Priorita 0
2. Priorita 1
3. Priorita 2
4. Priorita 3
5. Priorita 4
6. Priorita 5
7. Priorita 6
8. Priorita 7

## Criteri di uscita progetto

- tema compatibile con PHP 8.3 senza blocker
- flusso donazioni testato e documentato
- pipeline build/deploy ripetibile
- plugin e dipendenze ridotti e giustificati
- layout base conforme a SEO tecnico e accessibilita
- UI rifatta con componenti riusabili e contenuti gestibili
- staging UAT superata con checklist firmata
- piano di deploy e rollback pronto
