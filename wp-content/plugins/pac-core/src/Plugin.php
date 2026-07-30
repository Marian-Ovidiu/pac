<?php

declare(strict_types=1);

namespace Pac\Core;

final class Plugin
{
    private const CLEANUP_HOOK = 'pac_core_cleanup_pending_donations';

    public static function boot(): void
    {
        StripePayments::registerHooks();
        StripeWebhook::registerHooks();
        add_action(self::CLEANUP_HOOK, [DonationStore::class, 'cleanupExpired']);
        add_action('admin_notices', [self::class, 'configurationNotice']);
    }

    public static function activate(): void
    {
        add_role('donator', 'Donatore', ['read' => true]);

        if (!wp_next_scheduled(self::CLEANUP_HOOK)) {
            wp_schedule_event(time() + HOUR_IN_SECONDS, 'daily', self::CLEANUP_HOOK);
        }
    }

    public static function deactivate(): void
    {
        wp_clear_scheduled_hook(self::CLEANUP_HOOK);
    }

    public static function configurationNotice(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $missing = [];

        if (Config::secretKey() === '') {
            $missing[] = Config::isProduction() ? 'SECRET_KEY' : 'TEST_SECRET_KEY';
        }

        if (Config::webhookSecret() === '') {
            $missing[] = 'STRIPE_WEBHOOK_SECRET';
        }

        if (!$missing) {
            return;
        }

        echo '<div class="notice notice-warning"><p>'
            . esc_html(sprintf('PAC Core: configurazione mancante (%s).', implode(', ', $missing)))
            . '</p></div>';
    }
}
