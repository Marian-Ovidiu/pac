# Release Readiness Checklist

Data avvio: 2026-04-07
Scope: esecuzione sequenziale della Priorita 7

## Stato

- `done`: completato con evidenza verificata
- `in_progress`: in lavorazione
- `blocked`: non eseguibile nel perimetro attuale
- `pending`: non ancora iniziato

## Checklist Sequenziale

1. `[done]` Formalizzare checklist operativa di release readiness.
   Evidenza: questo file.

2. `[done]` Raccogliere baseline tecnica locale aggiornata.
   Evidenze:
   - [docs/priority-7-release-readiness.md](/C:/projects/privati/pac/docs/priority-7-release-readiness.md)
   - `git status --short`
   - `npm run build`
   - lint PHP tema
   - bootstrap WordPress

3. `[blocked]` Chiudere il notice ACF pre-release o isolarne con certezza la causa correggibile nel tema.
   Stato attuale:
   - il notice `_load_textdomain_just_in_time` su `acf` resta presente
   - la sorgente piu probabile appare lato plugin/integrazione, non chiaramente nel tema
   Evidenze:
   - [`wp-content/plugins/advanced-custom-fields-pro 2/acf.php`](/C:/projects/privati/pac/wp-content/plugins/advanced-custom-fields-pro%202/acf.php)
   - [`wp-content/themes/my_structure/app/Core/App.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Core/App.php)
   Sblocco richiesto:
   - accesso funzionale piu profondo al runtime plugin/staging oppure decisione di accettazione del rischio come `medium`

4. `[blocked]` Pulire il worktree e congelare gli artefatti della release.
   Stato attuale:
   - il worktree e ampiamente sporco
   - esiste ambiguita su cosa vada effettivamente committato tra `public/`, `vendor/` e documentazione
   Evidenze:
   - `git status --short`
   Sblocco richiesto:
   - decisione esplicita sulla policy di deploy/versionamento

5. `[done]` Verificare coerenza artefatti buildati correnti.
   Evidenze:
   - [`wp-content/themes/my_structure/public/.vite/manifest.json`](/C:/projects/privati/pac/wp-content/themes/my_structure/public/.vite/manifest.json)
   - [`wp-content/themes/my_structure/public/css/main-DV8PrLMj.css`](/C:/projects/privati/pac/wp-content/themes/my_structure/public/css/main-DV8PrLMj.css)
   - [`wp-content/themes/my_structure/public/css/style-CVdi7xL5.css`](/C:/projects/privati/pac/wp-content/themes/my_structure/public/css/style-CVdi7xL5.css)
   - [`wp-content/themes/my_structure/public/js/main-BqA_vKbH.js`](/C:/projects/privati/pac/wp-content/themes/my_structure/public/js/main-BqA_vKbH.js)
   - [`wp-content/themes/my_structure/public/js/homeSlider-BFgSPT3x.js`](/C:/projects/privati/pac/wp-content/themes/my_structure/public/js/homeSlider-BFgSPT3x.js)
   - [`wp-content/themes/my_structure/public/js/progettoSlider-CB10HckR.js`](/C:/projects/privati/pac/wp-content/themes/my_structure/public/js/progettoSlider-CB10HckR.js)

6. `[done]` Rieseguire lint PHP del tema.
   Esito:
   - `OK`

7. `[done]` Rieseguire build frontend.
   Esito:
   - `OK`

8. `[done]` Rieseguire bootstrap WordPress.
   Esito:
   - `OK` con notice ACF residuo

9. `[pending]` Azzerare o archiviare `wp-content/debug.log` prima della UAT staging.
   Nota:
   - da fare solo quando si apre la sessione UAT vera, per evitare di perdere evidenze storiche prematuramente

10. `[blocked]` Eseguire UAT staging end-to-end.
    Flussi:
    - home
    - archivio progetti
    - singolo progetto
    - galleria
    - aziende
    - grazie
    - header/footer
    - donazione Stripe
    - mail ringraziamento
    - creazione/aggiornamento utente
    Sblocco richiesto:
    - accesso staging reale
    - browser
    - credenziali/URL applicabili

11. `[blocked]` Eseguire regressione browser su console/network/mobile.
    Sblocco richiesto:
    - browser con devtools

12. `[done]` Formalizzare decisione finale su webhook Stripe.
    Decisione tecnica raccomandata:
    - implementare il webhook Stripe prima del go-live se il flusso custom resta il canale definitivo di donazione
    Motivazione:
    - e il residuo high con il miglior rapporto costo/rischio
    - chiude i casi di redirect perso e finalizzazione asincrona
    Fallback se non approvato ora:
    - accettazione esplicita del rischio
    - monitoraggio manuale post-rilascio
    - pianificazione immediata del webhook nel primo pass successivo

13. `[blocked]` Formalizzare policy finale di deploy.
    Decisioni richieste:
    - `public/` versionato vs artefatto CI/CD
    - `vendor/` versionato vs `composer install`
    - `.env` definitivamente fuori repo

14. `[pending]` Preparare pacchetto go-live finale.
    Include:
    - checklist deploy
    - smoke test post-deploy
    - rollback plan
    - owner e finestra di rilascio

## Prossimo Step Sequenziale

Il prossimo step nell'ordine e il punto 3. Al momento risulta `blocked`, quindi la sequenza impone di:

1. formalizzare il blocco
2. non saltarlo come "chiuso"
3. passare al primo item eseguibile che non falsi lo stato della release

Nel perimetro attuale, il prossimo item utile ancora eseguibile senza fingere validazioni esterne e il punto 14, ma resta dipendente dal punto 13 sulla policy deploy. Quindi la sequenza si ferma con questi blocker reali:

1. chiusura/accettazione del residuo ACF
2. policy finale di deploy
3. accesso staging/browser per UAT e regressione
