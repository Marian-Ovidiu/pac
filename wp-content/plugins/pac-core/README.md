# PAC Core

PAC Core owns the application behavior that must survive a theme change:

- Stripe PaymentIntent creation and browser completion callback
- signed Stripe webhook handling
- durable pending-donation state and concurrent idempotency locks
- donor creation/update
- transactional thank-you email
- the `donator` role

The theme only renders the donation UI, enqueues its assets and reads the public Stripe key through `pac_core_publishable_key()`.

## Installation and activation

```sh
cd wp-content/plugins/pac-core
composer install --no-dev --optimize-autoloader
wp plugin activate pac-core
```

Activating the plugin creates the `donator` role and schedules daily cleanup of expired pending donations. Existing donors and the historical `pac_stripe_processed_*` idempotency markers remain compatible.

## Configuration

Provide values through hosting environment variables, WordPress constants or a root `.env` file copied from `/.env.example`:

- `TEST_SECRET_KEY` and `TEST_PUBLISHABLE_KEY` in local, development and staging
- `SECRET_KEY` and `PUBLISHABLE_KEY` only in production
- `STRIPE_WEBHOOK_SECRET` in every environment that receives webhooks

Non-production requests are rejected unless the secret key starts with `sk_test_`.

Configure Stripe to send `payment_intent.succeeded` to:

```text
https://example.test/wp-json/pac/v1/stripe/webhook
```

The REST route is public by design but rejects requests whose `Stripe-Signature` cannot be verified with the webhook signing secret.

## Donation states and retry behavior

1. `intent_created`: validated project and donor data are saved before the client confirms Stripe.
2. `payment_succeeded`: Stripe reports a succeeded PaymentIntent with matching amount, currency and project metadata.
3. `processing`: the browser callback or webhook owns a five-minute lock; an intermediate marker prevents an accepted email from being resent if final-state persistence must be retried.
4. `finalized`: donor, email and processed marker succeeded; pending PII is deleted.
5. `retryable`: missing pending state, lock contention, mail failure or persistence failure returns a non-2xx webhook response so Stripe retries.

Both browser and webhook call `DonationFinalizer::finalize()`. Processed PaymentIntents return success without repeating side effects.

## Privacy and retention

Pending donor data is stored in a non-autoloaded WordPress option keyed by PaymentIntent ID. It is deleted immediately after successful finalization. Abandoned records expire after seven days and are removed lazily or by the daily cleanup event. Logs contain only event IDs, intent IDs, error codes and exception classes—never donor data or secrets.

## Local webhook test

Use Stripe CLI with test credentials only:

```sh
stripe listen --forward-to http://127.0.0.1:8080/wp-json/pac/v1/stripe/webhook
```

Copy the temporary `whsec_...` value printed by Stripe CLI into the untracked root `.env`, then complete a donation through the local browser using a Stripe test card. A standalone `stripe trigger payment_intent.succeeded` has no matching PAC pending record and should intentionally receive a retryable response.

## Rollback

Deactivate PAC Core only after reverting to a revision where the theme still owns the payment hooks. Deactivating the plugin stops new payment processing but does not delete donors, pending records or processed markers.
