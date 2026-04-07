# PAC Execution Prompts

## Uso

Questi prompt sono pensati per essere passati a un coding agent sul repository corrente. Ogni prompt include contesto, obiettivo, vincoli e output attesi.

## Prompt 1: Stabilizzazione e security baseline

```text
Lavora sul repository WordPress in C:\projects\privati\pac.

Obiettivo:
- eseguire la priorita 0 della roadmap di modernizzazione
- produrre un audit operativo e di sicurezza iniziale

Contesto noto:
- core WordPress aggiornato in wp-includes/version.php
- tema custom principale in wp-content/themes/my_structure
- il repository contiene wp-content/themes/my_structure/.env tracciato
- il progetto usa Stripe, WooCommerce, ACF Pro, Polylang e Rank Math

Task richiesti:
1. Mappa ambiente, bootstrap e dipendenze operative.
2. Verifica file sensibili tracciati e rischi di segreti esposti.
3. Produci un inventario plugin/tema/dipendenze critiche.
4. Definisci una checklist per predisporre uno staging sicuro.
5. Se trovi chiavi o credenziali esposte, non stamparle integralmente: segnala il rischio e il file.

Vincoli:
- non fare modifiche distruttive
- non ruotare chiavi o cambiare configurazioni esterne senza richiesta esplicita
- usa riferimenti file precisi

Output atteso:
- report con findings ordinati per severita
- checklist staging pronta all'uso
- elenco file da togliere dal versionamento o da gestire diversamente
```

## Prompt 2: Compatibilita PHP 8.3 e fix runtime

```text
Lavora sul repository WordPress in C:\projects\privati\pac.

Obiettivo:
- eseguire la priorita 1 della roadmap
- portare il tema custom my_structure a uno stato compatibile con PHP 8.3 senza blocker runtime

Contesto noto:
- parse error in wp-content/themes/my_structure/source/Controllers/ProgettoController.php
- warning PHP 8 su wp-content/themes/my_structure/app/Core/Singleton.php
- bootstrap tema in wp-content/themes/my_structure/functions.php
- rendering basato su Blade + controller custom

Task richiesti:
1. Correggi tutti gli errori di sintassi e warning PHP 8 del tema custom.
2. Esegui lint completo del tema escludendo vendor e node_modules.
3. Normalizza eventuali problemi di encoding visibili.
4. Aggiungi o rafforza validazione/sanitizzazione minima dove ci sono input diretti.
5. Documenta smoke test manuali per le pagine principali.

Vincoli:
- mantieni comportamento funzionale attuale salvo bug fix necessari
- non introdurre refactor architetturali grandi se non servono al ripristino runtime
- usa apply_patch per modifiche manuali

Output atteso:
- codice corretto
- elenco file modificati con ragione del fix
- esito lint finale
- rischi residui o aree non verificate
```

## Prompt 3: Hardening pagamenti e routing

```text
Lavora sul repository WordPress in C:\projects\privati\pac.

Obiettivo:
- eseguire la priorita 2 della roadmap
- mettere in sicurezza e rendere affidabile il flusso donazioni/Stripe

Contesto noto:
- logica principale in wp-content/themes/my_structure/source/Classes/StripePayments.php
- route custom in wp-content/themes/my_structure/source/routes/web.php
- router custom in wp-content/themes/my_structure/app/Core/Router.php
- il sito usa anche WooCommerce e woocommerce-gateway-stripe

Task richiesti:
1. Analizza il flusso attuale create intent / complete payment.
2. Correggi incoerenze su amount, validazione input, gestione errori e side effect.
3. Riduci il rischio di endpoint custom non protetti.
4. Se opportuno, migra le azioni sensibili verso REST API WordPress o AJAX con nonce.
5. Separa responsabilita tra pagamento, persistenza donatore, email e logging.
6. Definisci scenari di test manuale e casi limite.

Vincoli:
- non rompere il flusso WooCommerce se il codice ne dipende
- evita hardcode di URL e segreti
- spiega chiaramente le assunzioni sul modello di business

Output atteso:
- refactor implementato o piano tecnico preciso se serve una decisione del committente
- elenco rischi chiusi e rischi residui
- check di sicurezza sui nuovi endpoint
```

## Prompt 4: Pipeline frontend e build/deploy

```text
Lavora sul repository WordPress in C:\projects\privati\pac.

Obiettivo:
- eseguire la priorita 3 della roadmap
- allineare il tema custom a una pipeline frontend moderna e coerente

Contesto noto:
- Vite configurato in wp-content/themes/my_structure/vite.config.js
- manifest in wp-content/themes/my_structure/public/.vite/manifest.json
- BaseController carica ancora asset da source/assets
- utility helper contiene una funzione vite_asset non ancora centralizzata nel flusso

Task richiesti:
1. Analizza l'attuale caricamento asset JS/CSS.
2. Rifattorizza il tema per usare il manifest Vite in produzione.
3. Definisci una policy chiara per versionare o escludere vendor, node_modules, public, cache e .env.
4. Pulisci file wrapper o placeholder inutili nel tema se bloccano la chiarezza architetturale.
5. Documenta i comandi di build e i prerequisiti.

Vincoli:
- non introdurre una seconda pipeline parallela
- mantieni compatibilita con WordPress
- preferisci una soluzione semplice e ripetibile

Output atteso:
- pipeline unificata
- documentazione di build/deploy
- note su eventuali decisioni ancora aperte
```

## Prompt 5: Audit plugin, ACF e multilanguage

```text
Lavora sul repository WordPress in C:\projects\privati\pac.

Obiettivo:
- eseguire la priorita 4 della roadmap
- ridurre complessita plugin e chiarire il modello contenuti

Contesto noto:
- plugin principali: ACF Pro, Polylang, Rank Math, WooCommerce, Stripe, Contact Form 7, WPForms Lite, cache e plugin di manutenzione/import
- il tema custom usa molti campi ACF e partial duplicate per lingua

Task richiesti:
1. Inventaria plugin attivi e ruolo funzionale.
2. Evidenzia duplicazioni o conflitti.
3. Mappa field group ACF e template/controller che li consumano.
4. Verifica il setup Polylang e impatti SEO/multilingual.
5. Proponi una riduzione ragionata del parco plugin.

Vincoli:
- non disattivare plugin senza motivazione e verifica impatto
- usa riferimenti file e, se disponibili, opzioni WordPress rilevanti

Output atteso:
- matrice plugin/use case
- matrice ACF campo/template
- piano di semplificazione con precedenze
```

## Prompt 6: SEO tecnico e accessibilita

```text
Lavora sul repository WordPress in C:\projects\privati\pac.

Obiettivo:
- eseguire la priorita 5 della roadmap
- portare il tema custom a uno standard moderno di SEO tecnico e accessibilita

Contesto noto:
- layout base in wp-content/themes/my_structure/resources/views/layouts/mainLayout.blade.php
- metadati e canonical oggi sono hardcoded
- lang e fisso
- vari template Blade usano immagini, slider e form custom

Task richiesti:
1. Esegui audit del layout base, metadati, canonical e schema.
2. Esegui audit di heading, landmark, keyboard navigation e menu.
3. Verifica immagini, alt, form, slider e componenti dinamici.
4. Implementa i fix ad alto impatto e basso rischio.
5. Elenca i fix che richiedono redesign o decisioni contenutistiche.

Vincoli:
- evita collisioni con Rank Math o plugin SEO attivi
- non introdurre markup non necessario
- usa criteri WCAG e best practice WordPress

Output atteso:
- fix implementati
- backlog a11y/SEO rimanente
- elenco pagine/template ancora da rivedere
```

## Prompt 7: Refactor UI e design system

```text
Lavora sul repository WordPress in C:\projects\privati\pac.

Obiettivo:
- eseguire la priorita 6 della roadmap
- rifare la UI del tema custom in modo coerente, manutenibile e accessibile

Contesto noto:
- il tema usa Blade, Tailwind, Vite e ACF
- esistono partial duplicate per lingua in resources/views/partials
- i template core sono home, archivio-progetto, single-progetto, galleria, aziende

Task richiesti:
1. Definisci un design system minimo e riusabile.
2. Riduci duplicazioni nei partial multilingua.
3. Rifai i template ad alto impatto partendo da home, archivio e singolo progetto.
4. Allinea i campi ACF al nuovo design senza aumentare il debito tecnico.
5. Verifica mobile, performance e accessibilita finale.

Vincoli:
- preserva la brand identity del progetto se e riconoscibile nei contenuti esistenti
- non creare componenti inutilmente astratti
- evita dipendenze frontend superflue

Output atteso:
- UI aggiornata
- componenti riusabili
- note per editor/content team su eventuali cambi contenutistici
```
