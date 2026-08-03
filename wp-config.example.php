<?php
/**
 * PAC WordPress configuration template.
 *
 * Copy this file to wp-config.php and provide secrets through environment
 * variables or local values that remain outside Git.
 */

$pac_env_file = __DIR__ . '/.env';

if (is_file($pac_env_file)) {
    $pac_env_autoload = __DIR__ . '/wp-content/plugins/pac-core/vendor/autoload.php';

    if (!is_file($pac_env_autoload)) {
        throw new RuntimeException(
            'PAC cannot load the root .env file: install the pac-core Composer dependencies first.'
        );
    }

    require_once $pac_env_autoload;

    if (!class_exists(Dotenv\Dotenv::class)) {
        throw new RuntimeException('PAC cannot load the root .env file: phpdotenv is unavailable.');
    }

    Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();
}

$pac_getenv = static function (string $key): string {
    $value = getenv($key);

    if (is_string($value)) {
        return $value;
    }

    foreach ([$_ENV, $_SERVER] as $source) {
        if (isset($source[$key]) && is_scalar($source[$key])) {
            return (string) $source[$key];
        }
    }

    return '';
};

$pac_env = $pac_getenv('WP_ENVIRONMENT_TYPE');
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

define('DB_NAME', $pac_getenv('DB_NAME') ?: ($pac_is_local ? 'pac' : ''));
define('DB_USER', $pac_getenv('DB_USER') ?: ($pac_is_local ? 'root' : ''));
define('DB_PASSWORD', $pac_getenv('DB_PASSWORD'));
define('DB_HOST', $pac_getenv('DB_HOST') ?: 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

define('AUTH_KEY', $pac_getenv('AUTH_KEY') ?: 'replace-with-a-unique-value');
define('SECURE_AUTH_KEY', $pac_getenv('SECURE_AUTH_KEY') ?: 'replace-with-a-unique-value');
define('LOGGED_IN_KEY', $pac_getenv('LOGGED_IN_KEY') ?: 'replace-with-a-unique-value');
define('NONCE_KEY', $pac_getenv('NONCE_KEY') ?: 'replace-with-a-unique-value');
define('AUTH_SALT', $pac_getenv('AUTH_SALT') ?: 'replace-with-a-unique-value');
define('SECURE_AUTH_SALT', $pac_getenv('SECURE_AUTH_SALT') ?: 'replace-with-a-unique-value');
define('LOGGED_IN_SALT', $pac_getenv('LOGGED_IN_SALT') ?: 'replace-with-a-unique-value');
define('NONCE_SALT', $pac_getenv('NONCE_SALT') ?: 'replace-with-a-unique-value');

$table_prefix = $pac_getenv('DB_TABLE_PREFIX') ?: 'wp_';

if ($pac_is_local) {
    $pac_local_url = $pac_getenv('WP_HOME');
    if (!$pac_local_url && isset($_SERVER['HTTP_HOST'])) {
        $pac_local_url = 'http://' . $_SERVER['HTTP_HOST'];
    }

    define('WP_HOME', $pac_local_url ?: 'http://pac.local');
    define('WP_SITEURL', $pac_getenv('WP_SITEURL') ?: WP_HOME);
    define('DISABLE_WP_CRON', true);
    define('AUTOMATIC_UPDATER_DISABLED', true);
} else {
    define('WP_HOME', $pac_getenv('WP_HOME') ?: 'https://project-africa-conservation.org');
    define('WP_SITEURL', $pac_getenv('WP_SITEURL') ?: WP_HOME);
}

define('WP_DEBUG', false);
define('WP_CACHE', !$pac_is_local);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);

unset($pac_env_autoload, $pac_env_file, $pac_getenv);

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';
