<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

/**
 * Ambiente: local vs produzione
 *
 * 1) Variabile d'ambiente `WP_ENVIRONMENT_TYPE` (local|development|staging|production) ha la priorità.
 *    Utile per WP-CLI/cron senza HTTP_HOST: in locale es. `set WP_ENVIRONMENT_TYPE=local` (PowerShell)
 *    prima di `wp`; in produzione imposta `production` nel pannello hosting o nello script cron.
 * 2) In assenza, dal web si usa `HTTP_HOST`: pac.local, localhost, 127.0.0.1, *.local → locale.
 * 3) Senza host né env (raro): si assume produzione.
 *
 * In produzione: getenv('DB_NAME') ecc. se impostate sul server; altrimenti si usano i fallback sotto (meglio spostare i segreti in env sul hosting).
 */
$pac_env = getenv('WP_ENVIRONMENT_TYPE');
$pac_env = is_string($pac_env) ? strtolower(trim($pac_env)) : '';

if ($pac_env === 'local' || $pac_env === 'development') {
    $pac_is_local = true;
} elseif ($pac_env === 'staging' || $pac_env === 'production') {
    $pac_is_local = false;
} elseif (isset($_SERVER['HTTP_HOST']) && is_string($_SERVER['HTTP_HOST'])) {
    $pac_host = strtolower($_SERVER['HTTP_HOST']);
    $pac_is_local = in_array($pac_host, ['localhost', '127.0.0.1', 'pac.local'], true)
        || (strlen($pac_host) >= 6 && substr($pac_host, -6) === '.local');
} else {
    $pac_is_local = false;
}

if (! defined('WP_ENVIRONMENT_TYPE')) {
    define('WP_ENVIRONMENT_TYPE', $pac_is_local ? 'local' : 'production');
}

// ** Database settings - You can get this info from your web host ** //
if ($pac_is_local) {
    define('DB_NAME', 'pac');
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'root');
    define('DB_HOST', 'localhost');
    define('WP_HOME', 'http://pac.local');
    define('WP_SITEURL', 'http://pac.local');
} else {
    // Produzione: preferisci DB_* e WP_HOME in variabili d'ambiente sul hosting; i fallback sono i valori attuali del sito.
    define('DB_NAME', getenv('DB_NAME') ?: 'u597020236_yj8DQ');
    define('DB_USER', getenv('DB_USER') ?: 'u597020236_783vB');
    define('DB_PASSWORD', getenv('DB_PASSWORD') ?: 'projectAfricaC2024');
    define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
    define('WP_HOME', getenv('WP_HOME') ?: 'https://project-africa-conservation.org');
    define('WP_SITEURL', getenv('WP_SITEURL') ?: 'https://project-africa-conservation.org');
}

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '^+1>> JYl0P%pd:5}YX_-./EaLo1yTup:YdU-50vG+0I}>rV3/uv^@ouAR&Q-_XR');
define('SECURE_AUTH_KEY', 'P(Tb*0NLin!F15]LGlZj0U ?f%T5<`d<wvfKD|~W:5fOFsZZ23AIQi}S@6j]m3~[');
define('LOGGED_IN_KEY', '%1 oug%4 ?:M:Y?3jxC$h.Bs5p7 `ZSN[XmD3nc}[`Ot%W0A{iv4e,fko33vy&g{');
define('NONCE_KEY', '6=nFjgW96,8(o-v<w76aC{Zvi%~~n.Bf-S?wkA;soJ29qEC;bmFb:IvrA{Df)znj');
define('AUTH_SALT', 'kD=0T_ulcN/}h4dq|QH~H#LOX4Y9f:DD_ep8V{N,L$JsHZhkpP=:@*i1KH(A.}MY');
define('SECURE_AUTH_SALT', '`%7HE=lr2FM1}=dRk47Nr]/ T3U6v3a~Tf*.Zd|g=T~p$JcrK&XXXd9/(B6GZMLo');
define('LOGGED_IN_SALT', 'Z$umu@:7sA4?,m/6-FagsmA%yi~<qF~#u>3CC2E7}qh>(fB2%O_f7p4X;tH9]&Z@');
define('NONCE_SALT', '%Q)Btd)&[=1}m+3]%T2vYxs[TTgYXyqM:t!sLjI$yB/W<6OTmh*,P+{@V@A!=/AE');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define('WP_DEBUG', false);
define('WP_CACHE', true);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);

/* Add any custom values between this line and the "stop editing" line. */

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (! defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
