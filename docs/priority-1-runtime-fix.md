# Priority 1 Runtime Fix

Data esecuzione: 2026-04-07

## Scope

Ripristino runtime iniziale del tema custom `my_structure` e allineamento minimo a PHP 8.3, secondo la Priorita 1 della roadmap.

## Risultato

- `ProgettoController` e `Singleton` risultano compatibili con PHP 8.3 e senza errori di sintassi.
- Lint PHP del tema completato con esito pulito, esclusi `vendor/`, `node_modules/` e `resources/cache`.
- Bootstrap CLI di WordPress riuscito.
- Rafforzata la sanitizzazione minima nei punti che leggono superglobal, payload JSON e file JSON locali.
- Normalizzate stringhe con encoding degradato nei file runtime toccati.

## File modificati

### 1. Compatibilita PHP 8 e bootstrap tema

File: [`wp-content/themes/my_structure/app/Core/Singleton.php`](../wp-content/themes/my_structure/app/Core/Singleton.php)

- Confermata firma compatibile di `__wakeup(): void`.
- Effetto: nessun warning PHP 8 sul magic method.

File: [`wp-content/themes/my_structure/source/Controllers/ProgettoController.php`](../wp-content/themes/my_structure/source/Controllers/ProgettoController.php)

- Confermato controller parsabile e senza parse error.
- Effetto: il bootstrap del tema non e bloccato dal controller progetto.

### 2. Sanitizzazione minima input runtime

File: [`wp-content/themes/my_structure/app/Core/Router.php`](../wp-content/themes/my_structure/app/Core/Router.php)

- Mantiene sanitizzazione di `$_SERVER['REQUEST_METHOD']` e `$_REQUEST['action']`.
- Effetto: ridotto uso diretto di superglobal non normalizzati.

File: [`wp-content/themes/my_structure/app/Helpers/acf_helpers.php`](../wp-content/themes/my_structure/app/Helpers/acf_helpers.php)

- Sanitizzato `$_GET['page']` prima del matching ACF.
- Effetto: il rule match delle option page non dipende piu da input raw.

File: [`wp-content/themes/my_structure/source/Classes/StripePayments.php`](../wp-content/themes/my_structure/source/Classes/StripePayments.php)

- Cast esplicito del payload letto da `php://input`.
- Confermata validazione minima di `amount`, `email` e campi usati nella creazione utente.
- Normalizzati alcuni messaggi runtime/log con encoding degradato.
- Effetto: file piu robusto sotto PHP 8.3 senza cambiare il flusso funzionale esistente.

### 3. Robustezza helper e fallback runtime

File: [`wp-content/themes/my_structure/app/Helpers/translation_helpers.php`](../wp-content/themes/my_structure/app/Helpers/translation_helpers.php)

- Corretto il fallback locale.
- Aggiunto controllo sul risultato di `json_decode()`.
- Effetto: evitati warning su foreach/locale non inizializzata.

File: [`wp-content/themes/my_structure/app/Helpers/utility_helpers.php`](../wp-content/themes/my_structure/app/Helpers/utility_helpers.php)

- Aggiunta validazione del manifest Vite prima dell'accesso alle chiavi.
- Sostituito `Dotenv::load()` con `safeLoad()`.
- Normalizzati messaggi log con encoding degradato.
- Effetto: bootstrap meno fragile se `.env` o manifest non sono presenti o sono invalidi.

File: [`wp-content/themes/my_structure/source/Classes/GrazieEmail.php`](../wp-content/themes/my_structure/source/Classes/GrazieEmail.php)

- Normalizzati i messaggi di log con encoding degradato.
- Effetto: log leggibili e senza caratteri corrotti nei percorsi email.

## Verifica eseguita

### Lint

Comando eseguito:

```powershell
Get-ChildItem wp-content/themes/my_structure -Recurse -Filter *.php |
  Where-Object { $_.FullName -notmatch '\\vendor\\' -and $_.FullName -notmatch '\\node_modules\\' } |
  Where-Object { $_.FullName -notmatch '\\resources\\cache\\' } |
  ForEach-Object { php -l $_.FullName }
```

Esito:

- nessun errore di sintassi nei file PHP del tema

### Bootstrap runtime

Comando eseguito:

```powershell
php -d display_errors=1 -r "require 'wp-load.php'; echo 'bootstrap-ok';"
```

Esito:

- bootstrap WordPress riuscito
- output finale: `bootstrap-ok`
- residuo emerso: notice su caricamento anticipato del textdomain `acf`, non bloccante

## Residui aperti

### Non bloccanti ma rilevanti

1. Notice ACF su textdomain caricato troppo presto.
   Evidenza: bootstrap CLI emette `_load_textdomain_just_in_time` sul dominio `acf`.
   Stato: non blocca il runtime, ma va rivisto in un pass successivo su bootstrap/plugin init.

2. Flusso pagamenti ancora fragile.
   Evidenze:
   - [`wp-content/themes/my_structure/source/Classes/StripePayments.php`](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L73) crea ancora un nuovo `PaymentIntent` in `completePayment()`
   - [`wp-content/themes/my_structure/source/Classes/StripePayments.php`](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L74) continua a moltiplicare `amount` per 100 nel completamento
   Stato: da trattare in Priorita 2, non in questo pass minimo di ripristino runtime.

3. Alcuni plugin/installazioni locali restano incoerenti con il repository.
   Evidenza: il clone locale mostra drift tra filesystem e opzioni WordPress.
   Stato: non blocca il lint del tema, ma impatta staging e smoke test end-to-end.

## Smoke Test Manuali

1. Aprire homepage e verificare che il tema renderizzi senza fatal error.
2. Aprire archivio progetti e verificare caricamento lista, asset JS e gateway disponibili.
3. Aprire un singolo progetto valido e verificare slider, CTA donazione e assenza di 404 impropri.
4. Aprire un singolo progetto invalido e verificare rendering 404 corretto.
5. Testare `POST /create-payment-intent` con importo positivo e poi con importo nullo.
6. Testare `POST /complete-donation` con email invalida e con amount nullo, verificando risposta JSON di errore.
7. Verificare che la localizzazione statica continui a funzionare con e senza Polylang attivo.
8. Controllare `wp-content/debug.log` dopo i test per notice/fatal del tema.

## Next Step

Il prossimo punto coerente con la roadmap resta la Priorita 2: hardening pagamenti, donazioni e routing sensibile.
