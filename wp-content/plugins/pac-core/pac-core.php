<?php
/**
 * Plugin Name: PAC Core
 * Description: Application services for PAC donations, donors and transactional email.
 * Version: 1.0.0
 * Requires at least: 6.5
 * Requires PHP: 8.1
 */

if (!defined('ABSPATH')) {
    exit;
}

$pacCoreAutoload = __DIR__ . '/vendor/autoload.php';

if (!is_file($pacCoreAutoload)) {
    add_action('admin_notices', static function (): void {
        if (!current_user_can('manage_options')) {
            return;
        }

        echo '<div class="notice notice-error"><p>'
            . esc_html__('PAC Core non è operativo: eseguire composer install nella directory del plugin.', 'pac-core')
            . '</p></div>';
    });

    return;
}

require $pacCoreAutoload;

Pac\Core\Config::loadEnvironment(ABSPATH);
Pac\Core\Plugin::boot();

register_activation_hook(__FILE__, [Pac\Core\Plugin::class, 'activate']);
register_deactivation_hook(__FILE__, [Pac\Core\Plugin::class, 'deactivate']);

if (!function_exists('pac_core_publishable_key')) {
    function pac_core_publishable_key(): string
    {
        return Pac\Core\Config::publishableKey();
    }
}
