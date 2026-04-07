# pac-payments-hardening

## Quando usarla

Usa questa skill quando lavori su donazioni, Stripe, endpoint custom, validazione e side effect utente/email.

## Input minimi

- [`wp-content/themes/my_structure/source/Classes/StripePayments.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Classes/StripePayments.php)
- [`wp-content/themes/my_structure/source/routes/web.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/routes/web.php)
- [`wp-content/themes/my_structure/app/Core/Router.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Core/Router.php)

## Workflow

1. Disegna il flusso attuale end-to-end.
2. Verifica amount, valuta, conferma pagamento, redirect, email e creazione utente.
3. Riduci o rimuovi endpoint non protetti.
4. Introduci validazione, nonce o REST authentication secondo il caso.
5. Separa la logica in componenti con responsabilita chiare.
6. Definisci scenari di test e fallimenti attesi.

## Checklist

- amount trattato in modo coerente
- nessun side effect su payload invalido
- endpoint sensibili protetti
- niente URL hardcoded inutili

## Output atteso

- flusso donazioni piu sicuro
- documentazione delle assunzioni aperte
