# PAC Release Integration Plan

Data: 2026-06-19

## Obiettivo

Portare il repository dallo stato attuale a una release verificabile e distribuibile, senza mescolare:

- configurazione locale e produzione
- sorgenti e dipendenze generate
- fix applicativi e aggiornamenti infrastrutturali
- pagamenti reali e test

La rotazione delle chiavi e intenzionalmente rinviata al gate finale. Fino ad allora nessun task deve stampare, copiare o usare segreti live in locale.

## Modello target

I prompt sono progettati per un coding agent mini con accesso al repository e al terminale.

Il nome pubblico corrente del modello mini OpenAI orientato al coding e `gpt-5.4-mini`. Se l'interfaccia usata espone `gpt-4.5-mini` come alias, i prompt restano applicabili perche:

- hanno scope ristretto
- indicano file e comandi concreti
- richiedono verifica esplicita
- vietano assunzioni non confermate
- prevedono stop condition chiare

## Regole di esecuzione

1. Eseguire i prompt nell'ordine indicato.
2. Usare una nuova conversazione per ogni prompt.
3. Non eseguire due prompt in parallelo sullo stesso worktree.
4. Prima di ogni prompt:
   - creare o selezionare un branch dedicato
   - verificare `git status --short`
   - conservare le modifiche preesistenti
5. Un prompt e concluso solo quando:
   - i test richiesti passano
   - il diff e stato controllato
   - il report finale elenca file modificati e rischi residui
6. Non fare deploy in produzione durante i prompt 01-09.
7. Non ruotare chiavi prima del prompt 10.

## Sequenza e gate

| Ordine | Work package | Dipende da | Gate di uscita |
|---|---|---|---|
| 01 | Baseline locale sicura | DB locale importato | sito locale riproducibile con PHP 8.3 |
| 02 | Media e URL locali | 01 | pagine core senza asset locali mancanti |
| 03 | Policy repository/deploy | 01 | dipendenze generate fuori dall'indice Git |
| 04 | Aggiornamento dipendenze | 03 | audit senza critical/high applicabili |
| 05 | Estrazione logica applicativa dal tema | 04 | pagamenti e side effect in plugin dedicato |
| 06 | Webhook Stripe | 05 | finalizzazione server-to-server idempotente |
| 07 | Test automatici e CI | 04-06 | lint/build/test ripetibili |
| 08 | SEO, cache e infrastruttura pubblica | 07 | robots/sitemap/cache verificati |
| 09 | Staging e UAT | 08 | zero blocker e high aperti |
| 10 | Segreti, release e go-live | 09 | chiavi ruotate e rollback pronto |

---

## Prompt 01 — Baseline locale sicura e riproducibile

```text
Sei un coding agent responsabile della baseline locale del repository:
/Users/marian/Sites/Personale/pac

Continua fino a completare il task e le verifiche. Se non conosci un file o una configurazione, leggili con gli strumenti: non indovinare.

OBIETTIVO
Rendere l'avvio locale riproducibile con PHP 8.3 e MySQL 8.4, senza cache, cron, email o pagamenti live.

STATO NOTO
- database locale: pac, MySQL root/root
- dump produzione gia importato
- server locale previsto: PHP built-in server
- router locale: router.php
- wp-config.php distingue local e production
- WP Fastest Cache e stato disattivato nel DB locale
- esistono modifiche locali preesistenti: non sovrascriverle

FILE IN SCOPE
- wp-config.php
- router.php
- .gitignore
- un nuovo documento docs/local-development.md
- eventuale file di configurazione locale non sensibile strettamente necessario

TASK
1. Ispeziona lo stato reale dei file prima di modificarli.
2. Assicurati che localhost e 127.0.0.1 con porta siano riconosciuti come local.
3. In local:
   - WP_CACHE deve essere false
   - DISABLE_WP_CRON deve essere true
   - WP_HOME e WP_SITEURL devono derivare dall'host locale o da env
   - non devono partire email reali
   - le chiavi Stripe live non devono essere preferite o usate
4. Verifica che router.php serva file reali direttamente e inoltri i permalink a index.php.
5. Documenta il comando esatto usando il binario PHP 8.3:
   /opt/homebrew/opt/php@8.3/bin/php
6. Non cambiare il comportamento production salvo rendere esplicita la separazione per ambiente.

VINCOLI
- non stampare valori di .env o wp-config.php
- non modificare il database di produzione
- non fare deploy
- non installare MAMP
- non introdurre Docker
- usa patch minime

VERIFICHE OBBLIGATORIE
- php -l wp-config.php
- php -l router.php
- bootstrap WordPress con WP_ENVIRONMENT_TYPE=local
- avvio temporaneo su 127.0.0.1:8080
- HTTP 200 su /, /progetto/sociale-nigeria e login custom
- nessun URL asset che punti a un host locale diverso da quello richiesto

STOP CONDITION
Se servono credenziali o una decisione esterna, fermati senza inventare valori e descrivi il blocco.

OUTPUT FINALE
- risultato
- file modificati
- comandi eseguiti
- verifiche passate/fallite
- rischi residui
```

## Prompt 02 — Sincronizzazione media e normalizzazione URL locale

```text
Lavora nel repository /Users/marian/Sites/Personale/pac.
Completa il task end-to-end. Ispeziona filesystem e database prima di agire; non indovinare.

OBIETTIVO
Allineare il filesystem locale al database di produzione importato e rimuovere i riferimenti locali incoerenti.

PREREQUISITI
- Prompt 01 completato
- sito avviabile con PHP 8.3
- accesso read-only ai file uploads di produzione oppure archivio uploads fornito dall'utente

TASK
1. Confronta gli attachment WordPress con wp-content/uploads.
2. Produci prima una lista dei file mancanti, senza modificare nulla.
3. Se e disponibile un archivio o accesso autorizzato, sincronizza soltanto wp-content/uploads.
4. Installa WP-CLI via Homebrew se non presente.
5. Esegui un dry-run di search-replace:
   produzione -> http://127.0.0.1:8080
   su tutte le tabelle con prefisso, saltando guid.
6. Se il dry-run e coerente, esegui la sostituzione reale solo sul DB locale.
7. Disattiva nel DB locale plugin non utili o pericolosi in sviluppo:
   - cache
   - maintenance
   - SMTP reale
   Documenta ogni modifica.

VINCOLI
- non modificare guid
- non committare uploads
- non cancellare attachment dal DB
- non contattare servizi email o Stripe
- crea un backup DB locale prima del search-replace

VERIFICHE
- nessun 404 sulle immagini usate da home e pagine core
- `wp search-replace --dry-run` senza sostituzioni impreviste
- home e siteurl locali
- wp-content/uploads resta ignorato da Git

STOP CONDITION
Se gli uploads non sono disponibili, non creare placeholder e non scaricare file uno a uno dal sito pubblico: restituisci la lista mancante e il comando di sincronizzazione consigliato.

OUTPUT FINALE
- conteggio attachment e file mancanti
- backup creato
- sostituzioni eseguite
- plugin locali disattivati
- test HTTP
```

## Prompt 03 — Pulizia repository e policy di deploy

```text
Lavora nel repository /Users/marian/Sites/Personale/pac.

OBIETTIVO
Ridurre il repository a sorgenti e artefatti di deploy intenzionali, preservando la possibilita di distribuire il sito su Hostinger.

CONTESTO
- node_modules e vendor del tema risultano storicamente tracciati
- public contiene la build Vite usata dal deploy
- il repository contiene WordPress core e plugin
- la rotazione/rimozione definitiva dei segreti e rinviata al Prompt 10

DECISIONE DI POLICY DA IMPLEMENTARE
- node_modules: non versionato
- resources/cache: non versionato
- wp-content/cache: non versionato
- uploads: non versionato
- public: versionato come artefatto Vite
- vendor del tema: non versionato; ricostruito con `composer install --no-dev --optimize-autoloader`
- plugin di terze parti: non modificarli in questo prompt

TASK
1. Ispeziona `.gitignore` e i file gia tracciati.
2. Aggiorna `.gitignore` in modo leggibile e senza eccezioni contraddittorie.
3. Rimuovi dall'indice Git, senza cancellare dal filesystem locale:
   - wp-content/themes/my_structure/node_modules
   - wp-content/themes/my_structure/vendor
   - wp-content/themes/my_structure/resources/cache
   - wp-content/cache
   - wp-content/uploads
4. Crea docs/deployment-policy.md con:
   - prerequisiti
   - composer install
   - npm ci e npm run build
   - artefatti richiesti
   - strategia plugin/core
5. Non riscrivere la history Git in questo prompt.

VINCOLI
- preserva modifiche utente non correlate
- niente git reset/checkout distruttivi
- non fare commit o push salvo richiesta esplicita
- non rimuovere public
- non toccare segreti o chiavi

VERIFICHE
- git check-ignore sui path previsti
- `git ls-files` non deve piu elencare i cinque path indicati sopra
- non rimuovere per errore i `vendor` interni ai plugin WordPress
- composer install in una verifica pulita
- npm ci e npm run build
- manifest Vite coerente

OUTPUT FINALE
- policy adottata
- numero di file rimossi dall'indice
- dimensione repo/worktree prima e dopo
- comandi deploy documentati
- rischi residui
```

## Prompt 04 — Aggiornamento dipendenze PHP e frontend

```text
Lavora nel repository /Users/marian/Sites/Personale/pac.

OBIETTIVO
Eliminare le vulnerabilita note applicabili mantenendo il comportamento del tema.

PREREQUISITO
Prompt 03 completato e worktree comprensibile.

SCOPE
- wp-content/themes/my_structure/composer.json
- wp-content/themes/my_structure/composer.lock
- wp-content/themes/my_structure/package.json
- wp-content/themes/my_structure/package-lock.json
- sorgenti del tema solo se necessari per compatibilita

TASK
1. Salva baseline di:
   - composer audit
   - npm audit
   - composer outdated --direct
   - npm outdated
2. Aggiorna prima le versioni semver-safe.
3. Risolvi almeno:
   - advisory Carbon
   - axios
   - vite
   - postcss
   - swiper
4. Per Swiper o altri major upgrade, ispeziona gli import e applica solo le modifiche necessarie.
5. Non aggiornare l'intero stack Illuminate a una nuova major in questo prompt; documentalo come migrazione separata.
6. Rimuovi dipendenze frontend non usate solo dopo aver verificato con rg/import analysis.

VINCOLI
- un gruppo di aggiornamenti alla volta
- dopo ogni gruppo esegui build e smoke test
- non modificare plugin WordPress
- non ignorare advisory senza motivazione tecnica

VERIFICHE
- composer validate
- composer audit
- npm audit
- npm run build
- lint PHP completo del tema esclusi vendor/node_modules/cache
- HTTP smoke test pagine core con PHP 8.3

STOP CONDITION
Se un major upgrade rompe il runtime e non e correggibile con patch limitata, ripristina solo quel gruppo di modifiche e documenta un prompt dedicato.

OUTPUT FINALE
- versioni prima/dopo
- advisory chiuse e residue
- file modificati
- test
- eventuali upgrade major rinviati
```

## Prompt 05 — Plugin applicativo PAC e separazione dal tema

```text
Lavora nel repository /Users/marian/Sites/Personale/pac.

OBIETTIVO
Spostare dal tema a un plugin dedicato la logica che deve sopravvivere al cambio tema.

NUOVO COMPONENTE
wp-content/plugins/pac-core/

LOGICA DA VALUTARE E SPOSTARE
- registrazione hook Stripe
- creazione/aggiornamento donatore
- email post-donazione
- eventuale ruolo donator
- endpoint AJAX/REST applicativi

FILE ORIGINE PRINCIPALI
- wp-content/themes/my_structure/source/Classes/StripePayments.php
- wp-content/themes/my_structure/source/Classes/GrazieEmail.php
- wp-content/themes/my_structure/source/routes/web.php
- wp-content/themes/my_structure/app/Helpers/theme_helpers.php

TASK
1. Traccia il flusso attuale prima di modificare.
2. Crea un plugin minimale PSR-4 o con struttura semplice e chiara.
3. Sposta la logica senza cambiare contratti frontend, action name o payload.
4. Mantieni nel tema solo rendering, enqueue e configurazione pubblica necessaria.
5. Evita doppia registrazione degli hook.
6. Aggiungi guardie per dipendenze mancanti e logging privo di dati personali.
7. Documenta attivazione e rollback.

VINCOLI
- nessun redesign
- nessun webhook in questo prompt
- nessuna modifica alle chiavi
- nessun cambiamento schema DB
- preserva compatibilita con dati utenti esistenti

VERIFICHE
- plugin attivabile senza fatal
- tema attivabile con plugin attivo
- nessun hook Stripe duplicato
- create intent testabile con chiavi test
- lint PHP
- smoke test home, archivio e singolo progetto

OUTPUT FINALE
- architettura prima/dopo
- file creati/modificati
- contratti preservati
- test
- rischi residui
```

## Prompt 06 — Webhook Stripe e idempotenza

```text
Lavora nel repository /Users/marian/Sites/Personale/pac.
Questo e un task di pagamento ad alta criticita: verifica ogni assunzione nel codice.

OBIETTIVO
Finalizzare le donazioni tramite webhook Stripe server-to-server, mantenendo il callback browser come acceleratore UX ma non come unica fonte di verita.

PREREQUISITO
Prompt 05 completato; logica pagamenti nel plugin pac-core.

TASK
1. Disegna gli stati della donazione:
   - intent creato
   - pagamento succeeded
   - side effect in elaborazione
   - finalizzato
   - fallito/ritentabile
2. Implementa endpoint webhook WordPress dedicato.
3. Verifica firma con webhook secret da ambiente.
4. Gestisci almeno `payment_intent.succeeded`.
5. Riusa una sola funzione di finalizzazione condivisa tra webhook e callback browser.
6. Garantisci idempotenza concorrente.
7. Non fidarti di amount, progetto o dati donatore ricevuti dal browser dopo il pagamento.
8. Decidi dove salvare i dati necessari prima della conferma e documenta privacy/retention.
9. Restituisci rapidamente 2xx solo dopo una gestione coerente dell'evento.
10. Aggiungi log con event ID e intent ID, senza PII o segreti.

VINCOLI
- solo chiavi Stripe test
- niente eventi reali
- nessun side effect su firma invalida
- nessuna email duplicata
- nessun utente duplicato

TEST OBBLIGATORI
- firma valida/invalida
- evento duplicato
- callback browser prima/dopo webhook
- amount o metadata incoerenti
- email fallita
- lock concorrente
- retry Stripe

OUTPUT FINALE
- macchina a stati
- endpoint e configurazione richiesta
- test eseguiti
- comportamento di retry
- procedura Stripe CLI per test locale
- rischi residui
```

## Prompt 07 — Test automatici e CI

```text
Lavora nel repository /Users/marian/Sites/Personale/pac.

OBIETTIVO
Creare una baseline automatica che impedisca regressioni su runtime, build e pagamenti.

TASK
1. Ispeziona il progetto e scegli il minimo stack di test sostenibile.
2. Aggiungi:
   - lint PHP del codice custom
   - composer validate/audit
   - npm ci/build/audit
   - test unitari per validazione e idempotenza pagamenti
   - smoke test HTTP locale per pagine core
3. Crea script unificati, ad esempio:
   - scripts/check-php.sh
   - scripts/check-frontend.sh
   - scripts/smoke-local.sh
4. Aggiungi workflow GitHub Actions senza segreti live.
5. Usa fixture e fake per Stripe/email; nessuna rete nei test unitari.
6. Documenta esecuzione locale.

VINCOLI
- CI non deve richiedere il dump produzione
- nessuna credenziale reale
- evita framework di test sproporzionati
- non testare codice vendor/plugin terzo

GATE
- tutti i check passano da clone pulito con dipendenze installate
- un errore PHP o build fallita produce exit code non zero
- test pagamenti coprono duplicazione e payload invalido

OUTPUT FINALE
- matrice test
- file CI
- comandi locali
- durata indicativa
- limiti della suite
```

## Prompt 08 — Cache, robots, sitemap e configurazione pubblica

```text
Lavora nel repository /Users/marian/Sites/Personale/pac.

OBIETTIVO
Rendere coerenti cache, indicizzazione e comportamento HTTP tra locale, staging e produzione.

CONTESTO
- WP Fastest Cache deve restare disattivo in local
- il dominio pubblico ha restituito 403 ai test automatici
- robots.txt e sitemap_index.xml hanno restituito 404
- Rank Math e installato

TASK
1. Verifica configurazione repository e opzioni DB applicabili.
2. Definisci una matrice ambiente:
   - local: no cache, no indexing
   - staging: no indexing, cache opzionale
   - production: cache e indexing attivi
3. Correggi robots e sitemap senza duplicare Rank Math.
4. Verifica canonical, home/siteurl e redirect www/non-www.
5. Identifica la sorgente del 403 Hostinger/CDN/WAF e documenta la modifica necessaria.
6. Non indebolire globalmente la sicurezza: consenti crawler legittimi e traffico normale con regole mirate.

VERIFICHE
- status HTTP di home, robots.txt e sitemap
- contenuto robots coerente per ambiente
- sitemap XML valida
- nessun canonical duplicato
- asset statici 200
- cache non genera URL con host errato

STOP CONDITION
Se la correzione richiede pannello Hostinger o CDN, prepara istruzioni esatte e fermati prima di modificare sistemi esterni.

OUTPUT FINALE
- matrice ambiente
- patch locale/repository
- azioni manuali Hostinger
- evidenze HTTP
- rischi residui
```

## Prompt 09 — Staging, UAT e release candidate

```text
Lavora sul repository /Users/marian/Sites/Personale/pac e sullo staging esplicitamente fornito dall'utente.

OBIETTIVO
Produrre una release candidate verificata. Non fare deploy in produzione.

PREREQUISITI
- prompt 01-08 completati
- staging disponibile
- uploads sincronizzati
- chiavi Stripe test configurate
- email catturate da sandbox/mail trap

TASK
1. Crea tag o identificatore della release candidate senza push se non autorizzato.
2. Esegui deploy su staging con la procedura documentata.
3. Azzera o archivia log staging.
4. Esegui UAT:
   - home
   - archivio progetti
   - singolo progetto
   - galleria
   - aziende
   - grazie
   - login/admin
   - donazione completa
   - webhook
   - email
   - creazione/aggiornamento donatore
5. Verifica desktop e mobile.
6. Controlla console, network, PHP log e cron.
7. Classifica ogni finding blocker/high/medium/low.

GATE
- blocker = 0
- high = 0
- nessun 404/500 nei flussi core
- donazione test riconciliata tra Stripe, WordPress ed email
- rollback provato o almeno simulato con comandi verificati

OUTPUT FINALE
- checklist UAT compilata
- evidenze
- bug residui
- decisione GO/NO-GO motivata
- release candidate identificata
```

## Prompt 10 — Rotazione segreti e go-live

```text
Lavora sul repository /Users/marian/Sites/Personale/pac e sugli ambienti esplicitamente autorizzati.
Questo prompt causa modifiche esterne: prima di ogni rotazione elenca cosa cambiera e richiedi conferma se l'autorizzazione non e gia esplicita.

OBIETTIVO
Chiudere l'esposizione dei segreti, preparare il deploy finale e rilasciare con rollback.

PREREQUISITI
- Prompt 09 con esito GO
- backup DB e filesystem produzione
- accesso Hostinger, Stripe, SMTP e repository
- finestra di rilascio concordata

TASK
1. Inventaria senza stampare i segreti:
   - DB
   - WordPress salts
   - Stripe live/test e webhook
   - SMTP
   - autologin/provisioning Hostinger
2. Rimuovi dal tracking:
   - .env
   - wp-config.php reale, sostituendolo con template sicuro se compatibile col deploy
   - file autologin Hostinger
3. Ruota le credenziali in ordine controllato.
4. Aggiorna secret store/hosting.
5. Valuta e pianifica la pulizia history Git; non fare force-push senza conferma esplicita.
6. Esegui deploy della release candidate approvata.
7. Esegui smoke test post-deploy.
8. Monitora log, webhook e pagamenti.
9. Se un gate fallisce, esegui rollback.

GATE POST-DEPLOY
- home e pagine core 200
- robots e sitemap 200
- asset 200
- admin accessibile
- pagamento test controllato o verifica webhook autorizzata
- nessun fatal PHP

OUTPUT FINALE
- segreti ruotati per categoria, mai in chiaro
- commit/tag deployato
- risultati smoke test
- rollback eseguito o non necessario
- issue residue
```

## Definition of Done complessiva

Il progetto e pronto quando:

- il locale e riproducibile con PHP 8.3
- filesystem, DB e media sono coerenti
- il repository non traccia dipendenze o runtime generato
- gli audit non hanno vulnerabilita critical/high applicabili
- la logica pagamenti vive fuori dal tema
- il webhook e idempotente e testato
- CI e smoke test sono verdi
- robots, sitemap e cache sono corretti
- staging UAT ha zero blocker/high
- i segreti sono ruotati
- deploy e rollback sono documentati e verificati
