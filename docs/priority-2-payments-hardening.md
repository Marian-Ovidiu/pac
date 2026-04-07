# Priority 2 Payments Hardening

Data esecuzione: 2026-04-07

## Scope

Hardening del flusso donazioni Stripe del tema custom `my_structure`, con riduzione del rischio su endpoint custom, validazione payload, amount handling e side effect post-pagamento.

## Assunzioni

- Il flusso donazioni custom del tema resta attivo e non viene sostituito da WooCommerce in questo pass.
- WooCommerce e `woocommerce-gateway-stripe` non sono il canale autorevole per queste donazioni custom.
- Il backend puo fidarsi solo dello stato reale del `PaymentIntent` Stripe, non del payload del browser.

## Flusso precedente

1. Il frontend chiamava route custom pubbliche su `template_redirect`.
2. `createIntent` creava un `PaymentIntent`.
3. Il frontend confermava il pagamento con Stripe Elements.
4. `completePayment` creava un secondo `PaymentIntent` lato server e usava il payload client-side per email e creazione utente.

Problemi:

- endpoint sensibili non protetti da nonce
- double charge logic potenziale
- amount incoerente tra centesimi ed euro
- hardcode della publishable key nel JS
- side effect eseguiti senza verificare il `PaymentIntent` gia confermato

## Refactor implementato

### Endpoint

- Rimosse le route pagamento su `template_redirect`.
- Registrati endpoint `admin-ajax` dedicati tramite [`wp-content/themes/my_structure/source/routes/web.php`](../wp-content/themes/my_structure/source/routes/web.php).
- Azioni introdotte:
  - `pac_create_payment_intent`
  - `pac_complete_donation`

### Sicurezza

- Ogni richiesta pagamento richiede nonce WordPress (`pac_stripe_donation`).
- La publishable key Stripe non e piu hardcoded nel JS: viene localizzata lato WordPress.
- La secret key lato server usa env e fallback test, senza URL o chiavi hardcoded.

### Coerenza amount

- Il frontend invia `amount_cents` alla creazione intent.
- Il completamento invia `expected_amount_cents`.
- Il backend recupera il `PaymentIntent` da Stripe e verifica:
  - `status === succeeded`
  - `amount === expected_amount_cents`
  - `currency === eur`
  - `metadata.progetto_id` coerente
  - `metadata.integration === pac_custom_donation`

### Side effect

- Il backend non crea piu un secondo `PaymentIntent`.
- Email e creazione/aggiornamento utente avvengono solo dopo verifica del `PaymentIntent` reale.
- La finalizzazione e idempotente tramite lock/marker su option key per `payment_intent_id`.

### Frontend

- `ApiService` supporta anche `postForm()` per `admin-ajax`.
- `donation.js` usa:
  - nonce
  - `admin-ajax.php`
  - publishable key localizzata
- Il frontend salva uno stato minimo in `sessionStorage` prima della conferma e prova a finalizzare anche dopo redirect Stripe, leggendo `payment_intent` dalla query string.
- `single-donation.js` non contiene piu chiavi live o endpoint legacy: delega al flusso principale.

## File modificati

- [`wp-content/themes/my_structure/source/Classes/StripePayments.php`](../wp-content/themes/my_structure/source/Classes/StripePayments.php)
- [`wp-content/themes/my_structure/source/routes/web.php`](../wp-content/themes/my_structure/source/routes/web.php)
- [`wp-content/themes/my_structure/app/Helpers/theme_helpers.php`](../wp-content/themes/my_structure/app/Helpers/theme_helpers.php)
- [`wp-content/themes/my_structure/source/assets/js/Classes/ApiService.js`](../wp-content/themes/my_structure/source/assets/js/Classes/ApiService.js)
- [`wp-content/themes/my_structure/source/assets/js/donation.js`](../wp-content/themes/my_structure/source/assets/js/donation.js)
- [`wp-content/themes/my_structure/source/assets/js/main.js`](../wp-content/themes/my_structure/source/assets/js/main.js)
- [`wp-content/themes/my_structure/source/assets/js/single-donation.js`](../wp-content/themes/my_structure/source/assets/js/single-donation.js)

## Rischi chiusi

1. Endpoint pagamento non protetti.
2. Publishable key Stripe live hardcoded nel sorgente JS.
3. Creazione di un secondo `PaymentIntent` nel completamento.
4. Side effect applicativi basati solo su dati del browser.
5. Incoerenza amount tra create/complete.
6. Duplicate finalization piu probabile su doppio submit.

## Rischi residui

1. Per affidabilita totale dei metodi con redirect o finalizzazione asincrona, il canale corretto resta un webhook Stripe server-to-server.
2. Il flusso custom continua a convivere nel progetto con WooCommerce/Stripe plugin, quindi la decisione architetturale finale resta aperta.
3. `sessionStorage` copre bene il ritorno browser dopo redirect Stripe, ma non sostituisce un webhook in caso di ritorni persi o browser chiuso.

## Verifica eseguita

### PHP lint

Comando:

```powershell
Get-ChildItem wp-content/themes/my_structure -Recurse -Filter *.php |
  Where-Object { $_.FullName -notmatch '\\vendor\\' -and $_.FullName -notmatch '\\node_modules\\' } |
  Where-Object { $_.FullName -notmatch '\\resources\\cache\\' } |
  ForEach-Object { php -l $_.FullName }
```

Esito:

- nessun errore di sintassi nei file PHP del tema

### Bootstrap

Comando:

```powershell
php -d display_errors=1 -r "require 'wp-load.php'; echo 'bootstrap-ok';"
```

Esito:

- bootstrap WordPress riuscito
- residuo noto: notice ACF non bloccante gia presente

### Sanity check sorgente

- nessuna occorrenza residua di `pk_live_` nei file sorgente attivi del tema
- nessuna occorrenza residua di `/create-payment-intent` o `/complete-donation` nel sorgente attivo

## Scenari di test manuale

1. Donazione card con importo preset e completamento inline.
2. Donazione con importo personalizzato valido.
3. Tentativo con importo nullo o inferiore al minimo.
4. Tentativo con email non valida o telefono mancante.
5. Doppio click sul submit nel passaggio pagamento.
6. Richiesta endpoint senza nonce o con nonce invalido.
7. Redirect Stripe con ritorno alla pagina `grazie` e finalizzazione post-redirect.
8. Retry della chiamata di completamento con stesso `payment_intent_id` per verificare idempotenza.

## Next Step

Se il committente conferma che il flusso custom deve restare separato da WooCommerce, il prossimo hardening consigliato e introdurre un webhook Stripe dedicato per chiudere anche i casi asincroni e i redirect persi.
