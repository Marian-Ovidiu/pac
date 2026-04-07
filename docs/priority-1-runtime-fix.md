# Priority 1 Runtime Fix

Data esecuzione: 2026-04-07

## Scope

Ripristino runtime iniziale del tema custom `my_structure` e allineamento minimo a PHP 8.3, secondo la Priorita 1 della roadmap.

## Risultato

- Parse error bloccante nel controller progetto corretto.
- Warning PHP 8 sul magic method del singleton corretto.
- Sanitizzazione minima aggiunta nei punti che leggono direttamente superglobal e JSON raw.
- Lint PHP del tema completato con esito pulito, esclusi `vendor/`, `node_modules/` e `resources/cache`.
- Bootstrap CLI di WordPress riuscito.

## File modificati

### 1. Parse error controller progetto

File: [`wp-content/themes/my_structure/source/Controllers/ProgettoController.php`](../wp-content/themes/my_structure/source/Controllers/ProgettoController.php)

- Corretto `use` statement malformato in [ProgettoController.php:5](../wp-content/themes/my_structure/source/Controllers/ProgettoController.php#L5).
- Effetto: il controller torna parsabile e il tema non blocca piu il bootstrap.

### 2. Compatibilita PHP 8 magic method

File: [`wp-content/themes/my_structure/app/Core/Singleton.php`](../wp-content/themes/my_structure/app/Core/Singleton.php)

- Aggiunto return type `: void` a [Singleton.php:24](../wp-content/themes/my_structure/app/Core/Singleton.php#L24).
- Effetto: allineamento della firma di `__wakeup()` alle aspettative di PHP 8+.

### 3. Sanitizzazione minima input runtime

File: [`wp-content/themes/my_structure/app/Core/Router.php`](../wp-content/themes/my_structure/app/Core/Router.php)

- Sanitizzato `$_SERVER['REQUEST_METHOD']` in [Router.php:51](../wp-content/themes/my_structure/app/Core/Router.php#L51).
- Sanitizzato `$_REQUEST['action']` in [Router.php:68](../wp-content/themes/my_structure/app/Core/Router.php#L68).
- Effetto: riduzione dell’uso diretto di superglobal non normalizzati.

File: [`wp-content/themes/my_structure/source/Classes/StripePayments.php`](../wp-content/themes/my_structure/source/Classes/StripePayments.php)

- Centralizzata la lettura di `php://input` in [StripePayments.php:11](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L11).
- Validato amount minimo in [StripePayments.php:30](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L30) e [StripePayments.php:60](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L60).
- Sanitizzata l’email in [StripePayments.php:55](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L55).
- Sanitizzati i dati usati per la creazione utente e i metadati in [StripePayments.php:118](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L118) - [StripePayments.php:143](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L143).
- Effetto: il file resta architetturalmente fragile, ma non dipende piu da payload raw non controllati per i casi base.

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
   Evidenza: bootstrap CLI ha emesso un notice `_load_textdomain_just_in_time` sul dominio `acf`.
   Stato: non blocca il runtime, ma va rivisto in un pass successivo su bootstrap/plugin init.

2. Flusso pagamenti ancora fragile.
   Evidenze:
   - [StripePayments.php:75](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L75) moltiplica ancora amount per 100 in `completePayment()`
   - [StripePayments.php:74](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L74) crea ancora un nuovo `PaymentIntent`
   Stato: da trattare in Priorita 2, non in questo pass minimo di ripristino runtime.

3. Alcune stringhe del file pagamenti mostrano encoding degradato nei log/messaggi.
   Evidenze: [StripePayments.php:41](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L41), [StripePayments.php:82](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L82), [StripePayments.php:108](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L108), [StripePayments.php:136](../wp-content/themes/my_structure/source/Classes/StripePayments.php#L136).
   Stato: non bloccante, ma da normalizzare nel refactor del flusso pagamenti.

## Smoke Test Manuali

1. Aprire homepage e verificare che il tema renderizzi senza fatal error.
2. Aprire archivio progetti e verificare caricamento lista, asset JS e gateway disponibili.
3. Aprire un singolo progetto valido e verificare slider, CTA donazione e assenza di 404 impropri.
4. Aprire un singolo progetto invalido e verificare rendering 404 corretto.
5. Testare `POST /create-payment-intent` con importo positivo e poi con importo nullo.
6. Testare `POST /complete-donation` con email invalida e con amount nullo, verificando risposta JSON di errore.
7. Controllare `wp-content/debug.log` dopo i test per notice/fatal del tema.

## Next Step

Il prossimo punto coerente con la documentazione resta la Priorita 2: hardening pagamenti, donazioni e routing sensibile.
