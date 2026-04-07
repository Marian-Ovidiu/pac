# Priorita 7: Release readiness, staging UAT e go-live

Data aggiornamento: 2026-04-07
Stato: parzialmente eseguita in locale, non chiudibile completamente senza staging/browser/accessi

## Step-Back

### Astrazione

Il problema non e piu di sviluppo puro, ma di transizione da "codice apparentemente a posto" a "sistema rilasciabile".

Il principio tecnico corretto qui e `release hardening before go-live`:

- prima si chiudono i residui tecnici minimi
- poi si validano i flussi reali sull'ambiente che conta
- solo alla fine si congela una procedura di deploy/rollback

Questo evita il pattern errato "nuovo codice fino all'ultimo" che tende a mascherare:

1. drift tra repository e artefatti buildati
2. differenze tra smoke test locale e runtime reale
3. bug latenti visibili solo con contenuti, plugin e configurazione staging

### Vincoli logici

- non posso dichiarare la release pronta senza UAT staging reale
- non posso marcare come chiuso il notice ACF se la sorgente piu probabile e il plugin terzo, non il tema
- non posso forzare una policy deploy definitiva senza una decisione esplicita su `public/` e `vendor/`
- non posso validare console, network, mobile e Stripe end-to-end senza browser e senza accessi funzionali

## Pianificazione Strategica

### Sequenza corretta per questa fase

1. Verificare cosa e davvero chiuso in locale.
2. Distinguere residui del tema da residui di plugin/ambiente.
3. Classificare blocker, high e medium per il go-live.
4. Produrre una checklist UAT staging compilabile.
5. Formalizzare un piano di deploy e rollback che non dipenda da conoscenza implicita.

### Edge case e conflitti da considerare

1. `debug.log` contiene errori storici che non equivalgono automaticamente a bug attuali, ma vanno azzerati prima della UAT per evitare falsi positivi.
2. Il notice ACF puo generare `headers already sent` se `WP_DEBUG_DISPLAY` resta attivo in ambiente non di sviluppo.
3. La policy artefatti e ancora ambigua:
   - `public/` oggi risulta materiale di build reale
   - `vendor/` e presente nel repo
   - `.env` e un rischio se resta tracciato
4. Il flusso Stripe e piu solido di prima, ma senza webhook resta un rischio architetturale residuo.

## Implementazione eseguita

### Verifiche locali ripetute

1. Lint PHP del tema:
   - eseguito con esclusione di `vendor/`, `node_modules/` e `resources/cache`
   - esito: `OK`

2. Build frontend:
   - eseguito `npm run build` in [`wp-content/themes/my_structure`](/C:/projects/privati/pac/wp-content/themes/my_structure)
   - esito: `OK`
   - manifest corrente: [`wp-content/themes/my_structure/public/.vite/manifest.json`](/C:/projects/privati/pac/wp-content/themes/my_structure/public/.vite/manifest.json)

3. Bootstrap WordPress:
   - eseguito `php -d display_errors=1 -r "require 'wp-load.php'; echo 'bootstrap-ok';"`
   - esito: `OK` con notice ACF residuo

### Evidenze raccolte

#### Notice residuo ACF

Il bootstrap continua a emettere:

- notice `_load_textdomain_just_in_time` sul dominio `acf`

Riferimenti:

- plugin: [`wp-content/plugins/advanced-custom-fields-pro 2/acf.php`](/C:/projects/privati/pac/wp-content/plugins/advanced-custom-fields-pro%202/acf.php)
- bootstrap tema: [`wp-content/themes/my_structure/app/Core/App.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Core/App.php)

Valutazione:

- non ho trovato finora un trigger tematico netto che giustifichi il notice
- il residuo va trattato come `medium` e con alta probabilita legato al plugin/integrazione esterna, non come blocker del tema

#### Artefatti buildati correnti

File attualmente coerenti con il manifest:

- [`wp-content/themes/my_structure/public/css/main-DV8PrLMj.css`](/C:/projects/privati/pac/wp-content/themes/my_structure/public/css/main-DV8PrLMj.css)
- [`wp-content/themes/my_structure/public/css/style-CVdi7xL5.css`](/C:/projects/privati/pac/wp-content/themes/my_structure/public/css/style-CVdi7xL5.css)
- [`wp-content/themes/my_structure/public/js/main-BqA_vKbH.js`](/C:/projects/privati/pac/wp-content/themes/my_structure/public/js/main-BqA_vKbH.js)
- [`wp-content/themes/my_structure/public/js/homeSlider-BFgSPT3x.js`](/C:/projects/privati/pac/wp-content/themes/my_structure/public/js/homeSlider-BFgSPT3x.js)
- [`wp-content/themes/my_structure/public/js/progettoSlider-CB10HckR.js`](/C:/projects/privati/pac/wp-content/themes/my_structure/public/js/progettoSlider-CB10HckR.js)

#### Worktree

Il worktree non e pulito. Non e stato riallineato automaticamente perche include molte modifiche gia presenti e non sarebbe corretto rimuoverle in modo distruttivo senza una decisione esplicita.

#### debug.log

In [`wp-content/debug.log`](/C:/projects/privati/pac/wp-content/debug.log) risultano:

- notice ACF ripetuti
- fatal storici su rendering Blade relativi a smoke script precedenti

Valutazione:

- non considero questi fatal automaticamente aperti se non riprodotti ora
- prima della UAT staging il log va azzerato o archiviato, altrimenti la regressione non e leggibile

## Stato release readiness

### Blocker

1. UAT staging non eseguita.
   Motivo:
   - non ho accesso a browser/staging nel perimetro di questa esecuzione
   Impatto:
   - non posso validare home, progetti, aziende, galleria, grazie e donazione con contenuti/configurazione reali

2. Regressione browser non eseguita.
   Motivo:
   - mancano browser e ispezione diretta di console/network/mobile
   Impatto:
   - impossibile chiudere definitivamente 404 asset, errori JS, regressioni responsive e focus order reale

3. Decisione deploy non formalizzata.
   Tema aperto:
   - `public/` come artefatto versionato o CI/CD
   - `vendor/` nel repo o ricostruito
   Impatto:
   - il go-live resta ambiguo operativamente

### High

1. Flusso Stripe senza webhook server-to-server.
   Riferimento: [docs/priority-2-payments-hardening.md](/C:/projects/privati/pac/docs/priority-2-payments-hardening.md)
   Impatto:
   - residuo di affidabilita su redirect persi, finalizzazione asincrona e recovery

2. Worktree sporco e non congelato per la release.
   Impatto:
   - rischio di deploy con file non voluti o con stato misto tra sorgente e build

### Medium

1. Notice ACF su textdomain caricato troppo presto.
2. `debug.log` storicizzato con errori precedenti da ripulire prima della UAT.
3. `.gitignore` ancora incoerente e con encoding degradato.
   Riferimento: [`.gitignore`](/C:/projects/privati/pac/.gitignore)

## Checklist UAT staging

Compilare questa tabella su staging reale.

| Flusso | URL | Esito | Screenshot | Console/Network | Debug log | Note |
|---|---|---|---|---|---|---|
| Homepage |  |  |  |  |  |  |
| Archivio progetti |  |  |  |  |  |  |
| Singolo progetto |  |  |  |  |  |  |
| Galleria |  |  |  |  |  |  |
| Aziende |  |  |  |  |  |  |
| Grazie |  |  |  |  |  |  |
| Header e footer |  |  |  |  |  |  |
| Donazione Stripe |  |  |  |  |  |  |
| Mail ringraziamento |  |  |  |  |  |  |
| Aggiornamento/creazione utente |  |  |  |  |  |  |

## Regressione tecnica da eseguire su staging

### Browser

1. Console senza errori JS critici.
2. Network senza 404/500 su asset, AJAX o redirect pagamento.
3. Verifica responsive minima:
   - 360px
   - 768px
   - 1280px

### WordPress

1. `wp-content/debug.log` pulito prima della sessione.
2. Nessun nuovo fatal o warning inatteso dopo i test.
3. Verifica Polylang solo se effettivamente attivo nello staging target.

### SEO/A11y rapida

1. `title` corretto nelle pagine core.
2. `meta description` presente o demandata correttamente al plugin SEO.
3. canonical non duplicato.
4. tastiera funzionante su menu e form donazione.

## Decisione consigliata su webhook Stripe

### Raccomandazione

Introdurlo prima del go-live se il flusso custom Stripe resta l'architettura definitiva.

### Motivo

E il residuo con il miglior rapporto costo/rischio da chiudere prima della produzione.

### Se non viene introdotto ora

Serve accettazione esplicita del rischio con queste mitigazioni:

1. monitoraggio manuale dei pagamenti completati
2. verifica giornaliera di allineamento tra Stripe, email e utenti creati
3. piano di implementazione webhook subito dopo il rilascio

## Policy deploy consigliata

### Raccomandazione

1. `.env` fuori dal repository in modo definitivo
2. `node_modules/` non versionato
3. `resources/cache/` non versionato
4. `public/`:
   - versionato solo se il deploy non puo buildare
   - altrimenti artefatto CI/CD
5. `vendor/`:
   - non versionato se l'hosting puo eseguire `composer install`
   - versionato solo come compromesso infrastrutturale documentato

## Piano deploy

### Pre-deploy

1. Congelare branch/revisione da rilasciare.
2. Rieseguire:
   - lint PHP tema
   - `npm run build`
   - bootstrap WordPress
3. Azzerare o archiviare `debug.log`.
4. Confermare:
   - variabili ambiente
   - chiavi Stripe corrette per ambiente
   - stato plugin attivi

### Deploy

1. Backup DB.
2. Backup files del tema e config ambiente.
3. Deploy codice.
4. Deploy artefatti buildati secondo policy scelta.
5. Eventuale `composer install` se previsto.
6. Cache clear applicativa/server/CDN.

### Smoke test post-deploy

1. Homepage.
2. Archivio progetti.
3. Singolo progetto.
4. Pagina aziende.
5. Donazione di test end-to-end.
6. Mail di ringraziamento.
7. Verifica `debug.log`.

## Rollback plan

1. Ripristino revision precedente del codice.
2. Ripristino artefatti frontend precedenti se versionati separatamente.
3. Ripristino backup DB solo se il deploy ha comportato migrazioni o side effect non recuperabili.
4. Cache clear.
5. Smoke test minimo su homepage e donazione.

## Cosa manca per chiudere davvero la priorita 7

1. Accesso staging reale.
2. Browser per console/network/mobile verification.
3. Esecuzione UAT compilata.
4. Decisione finale su deploy policy.
5. Decisione finale su webhook Stripe.

## Conclusione

La priorita 7 non e chiusa. Localmente il progetto e vicino alla release, ma non e corretto dichiararlo rilasciabile finche restano aperti:

- UAT staging
- regressione browser
- policy deploy finale
- decisione webhook Stripe

Il prossimo passo corretto non e altro refactor UI: e una sessione di validazione staging con checklist compilata e chiusura esplicita dei blocker sopra.
