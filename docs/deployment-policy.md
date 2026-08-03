# PAC Repository And Deployment Policy

## Repository contents

The repository versions:

- WordPress core and the third-party plugins deployed with the site
- source files for the custom `my_structure` theme
- Vite artifacts under `wp-content/themes/my_structure/public/`
- documentation and local bootstrap helpers that contain no secrets

The repository does not version:

- `wp-config.php` or any real `.env` file
- Hostinger autologin/provisioning files
- `node_modules/`, Composer `vendor/`, Blade/cache files or uploads
- logs, database dumps, archives or local IDE metadata

## Local bootstrap

1. Copy `wp-config.example.php` to `wp-config.php`.
2. Copy `.env.example` to `.env` and configure database credentials outside Git. The config template loads the root `.env` before WordPress connects to MySQL.
3. Use Stripe test credentials only in the local `.env` file.
4. Install the application plugin dependencies:

```sh
cd wp-content/plugins/pac-core
composer install
```

5. In `wp-content/themes/my_structure`, run:

```sh
composer install
npm ci
npm run build
```

6. Activate `pac-core`, then start WordPress with PHP 8.3 and `WP_ENVIRONMENT_TYPE=local`.

Automatic WordPress updates are disabled by the example local configuration so that runtime smoke tests cannot silently modify the worktree.

## Deploy

1. Deploy a reviewed commit, never an uncommitted worktree.
2. Install PAC Core and theme PHP dependencies with:

```sh
cd wp-content/plugins/pac-core
composer install --no-dev --optimize-autoloader
cd ../../themes/my_structure
composer install --no-dev --optimize-autoloader
```

3. Build frontend assets before deployment with `npm ci && npm run build`; commit the resulting `public/` manifest and bundles.
4. Provide `wp-config.php` and a root `.env` outside Git, or equivalent server-level environment variables. Include the database credentials, Stripe keys, `STRIPE_WEBHOOK_SECRET` and WordPress salts. Server-level variables take precedence over `.env` values.
5. Synchronize uploads independently from Git.
6. Configure Stripe `payment_intent.succeeded` delivery to `/wp-json/pac/v1/stripe/webhook` and verify a test-mode delivery before release.

## WordPress and plugin updates

- Perform core and plugin updates intentionally on a dedicated branch.
- Keep core, plugin, theme and security-policy changes in separate commits.
- After each update, run PHP lint, WordPress bootstrap and HTTP smoke tests.
- Do not deploy until the worktree is clean and the exact commit is known.
