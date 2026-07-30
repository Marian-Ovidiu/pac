# PAC Staging UAT

## Automated coverage

The manual GitHub Actions workflow `PAC Staging UAT` runs Playwright against the configured staging environment and checks:

- authentication through the custom WordPress login URL
- access to the authenticated WordPress dashboard
- homepage, gallery, companies and thank-you pages
- critical browser console/page errors
- a complete Stripe test-mode donation through the Payment Element
- redirect to the thank-you page

The donation test refuses to continue unless the public Stripe key starts with `pk_test_`.

## Required staging secrets

Configure these GitHub environment secrets under the protected `staging` environment:

- `PAC_STAGING_URL`
- `PAC_STAGING_LOGIN_URL`
- `PAC_STAGING_USER`
- `PAC_STAGING_PASSWORD`
- `PAC_STAGING_PROJECT_URL`
- `PAC_STAGING_DONOR_EMAIL`

The staging account should be a dedicated temporary administrator. The donor address should point to a test inbox or mail catcher.

## Email acceptance gate

Playwright verifies that the payment is completed and the thank-you page is reached. Delivery to the final mailbox remains an external acceptance check because the repository has no authorized inbox API.

After the workflow succeeds, verify in the test inbox or mail catcher:

1. exactly one thank-you message was received
2. subject is `Grazie per la tua donazione!`
3. project and amount are correct
4. no live recipient received the message
5. WP Mail SMTP reports no delivery error

Record the workflow URL and inbox evidence in the release checklist. Do not mark staging UAT complete until both are present.
