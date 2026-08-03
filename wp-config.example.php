<?php
/**
 * PAC WordPress configuration template.
 *
 * Copy this file to wp-config.php and provide secrets through environment
 * variables or local values that remain outside Git.
 */

require_once __DIR__ . '/wp-env.php';

$pac_env = get_data_env('WP_ENVIRONMENT_TYPE');
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

define('DB_NAME', get_data_env('DB_NAME') ?: ($pac_is_local ? 'pac' : ''));
define('DB_USER', get_data_env('DB_USER') ?: ($pac_is_local ? 'root' : ''));
define('DB_PASSWORD', get_data_env('DB_PASSWORD'));
define('DB_HOST', get_data_env('DB_HOST') ?: 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

define('AUTH_KEY', get_data_env('AUTH_KEY') ?: 'replace-with-a-unique-value');
define('SECURE_AUTH_KEY', get_data_env('SECURE_AUTH_KEY') ?: 'replace-with-a-unique-value');
define('LOGGED_IN_KEY', get_data_env('LOGGED_IN_KEY') ?: 'replace-with-a-unique-value');
define('NONCE_KEY', get_data_env('NONCE_KEY') ?: 'replace-with-a-unique-value');
define('AUTH_SALT', get_data_env('AUTH_SALT') ?: 'replace-with-a-unique-value');
define('SECURE_AUTH_SALT', get_data_env('SECURE_AUTH_SALT') ?: 'replace-with-a-unique-value');
define('LOGGED_IN_SALT', get_data_env('LOGGED_IN_SALT') ?: 'replace-with-a-unique-value');
define('NONCE_SALT', get_data_env('NONCE_SALT') ?: 'replace-with-a-unique-value');

$table_prefix = get_data_env('DB_TABLE_PREFIX') ?: 'wp_';

if ($pac_is_local) {
    $pac_local_url = get_data_env('WP_HOME');
    if (!$pac_local_url && isset($_SERVER['HTTP_HOST'])) {
        $pac_local_url = 'http://' . $_SERVER['HTTP_HOST'];
    }

    define('WP_HOME', $pac_local_url ?: 'http://pac.local');
    define('WP_SITEURL', get_data_env('WP_SITEURL') ?: WP_HOME);
    define('DISABLE_WP_CRON', true);
    define('AUTOMATIC_UPDATER_DISABLED', true);
} else {
    define('WP_HOME', get_data_env('WP_HOME') ?: 'https://project-africa-conservation.org');
    define('WP_SITEURL', get_data_env('WP_SITEURL') ?: WP_HOME);
}

define('WP_DEBUG', false);
define('WP_CACHE', !$pac_is_local);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';
