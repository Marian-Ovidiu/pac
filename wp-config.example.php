<?php
/**
 * PAC WordPress configuration template.
 *
 * Copy this file to wp-config.php and provide secrets through environment
 * variables or local values that remain outside Git.
 */

$pac_env = getenv('WP_ENVIRONMENT_TYPE');
$pac_env = is_string($pac_env) ? strtolower(trim($pac_env)) : '';

if (in_array($pac_env, ['local', 'development'], true)) {
    $pac_is_local = true;
} elseif (in_array($pac_env, ['staging', 'production'], true)) {
    $pac_is_local = false;
} elseif (isset($_SERVER['HTTP_HOST']) && is_string($_SERVER['HTTP_HOST'])) {
    $pac_host = strtolower((string) preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST']));
    $pac_is_local = in_array($pac_host, ['localhost', '127.0.0.1', 'pac.local'], true)
        || str_ends_with($pac_host, '.local');
} else {
    $pac_is_local = false;
}

define('WP_ENVIRONMENT_TYPE', $pac_is_local ? 'local' : ($pac_env ?: 'production'));

define('DB_NAME', getenv('DB_NAME') ?: ($pac_is_local ? 'pac' : ''));
define('DB_USER', getenv('DB_USER') ?: ($pac_is_local ? 'root' : ''));
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

define('AUTH_KEY', getenv('AUTH_KEY') ?: 'replace-with-a-unique-value');
define('SECURE_AUTH_KEY', getenv('SECURE_AUTH_KEY') ?: 'replace-with-a-unique-value');
define('LOGGED_IN_KEY', getenv('LOGGED_IN_KEY') ?: 'replace-with-a-unique-value');
define('NONCE_KEY', getenv('NONCE_KEY') ?: 'replace-with-a-unique-value');
define('AUTH_SALT', getenv('AUTH_SALT') ?: 'replace-with-a-unique-value');
define('SECURE_AUTH_SALT', getenv('SECURE_AUTH_SALT') ?: 'replace-with-a-unique-value');
define('LOGGED_IN_SALT', getenv('LOGGED_IN_SALT') ?: 'replace-with-a-unique-value');
define('NONCE_SALT', getenv('NONCE_SALT') ?: 'replace-with-a-unique-value');

$table_prefix = getenv('DB_TABLE_PREFIX') ?: 'wp_';

if ($pac_is_local) {
    $pac_local_url = getenv('WP_HOME');
    if (!$pac_local_url && isset($_SERVER['HTTP_HOST'])) {
        $pac_local_url = 'http://' . $_SERVER['HTTP_HOST'];
    }

    define('WP_HOME', $pac_local_url ?: 'http://pac.local');
    define('WP_SITEURL', getenv('WP_SITEURL') ?: WP_HOME);
    define('DISABLE_WP_CRON', true);
    define('AUTOMATIC_UPDATER_DISABLED', true);
} else {
    define('WP_HOME', getenv('WP_HOME') ?: 'https://project-africa-conservation.org');
    define('WP_SITEURL', getenv('WP_SITEURL') ?: WP_HOME);
}

define('WP_DEBUG', false);
define('WP_CACHE', !$pac_is_local);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';
