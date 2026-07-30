<?php

declare(strict_types=1);

namespace Pac\Core;

use Dotenv\Dotenv;

final class Config
{
    private static bool $environmentLoaded = false;

    public static function loadEnvironment(string $root): void
    {
        if (self::$environmentLoaded) {
            return;
        }

        self::$environmentLoaded = true;
        $paths = [rtrim($root, '/\\')];

        if (defined('WP_CONTENT_DIR')) {
            // Temporary compatibility path for installations that have not moved their local .env yet.
            $paths[] = WP_CONTENT_DIR . '/themes/my_structure';
        }

        foreach (array_unique($paths) as $path) {
            if (is_file($path . '/.env')) {
                Dotenv::createImmutable($path)->safeLoad();
            }
        }
    }

    public static function environmentType(): string
    {
        if (function_exists('wp_get_environment_type')) {
            return (string) wp_get_environment_type();
        }

        if (defined('WP_ENVIRONMENT_TYPE')) {
            return (string) constant('WP_ENVIRONMENT_TYPE');
        }

        return 'production';
    }

    public static function isProduction(): bool
    {
        return self::environmentType() === 'production';
    }

    public static function secretKey(): string
    {
        return self::value(self::isProduction() ? 'SECRET_KEY' : 'TEST_SECRET_KEY');
    }

    public static function publishableKey(): string
    {
        return self::value(self::isProduction() ? 'PUBLISHABLE_KEY' : 'TEST_PUBLISHABLE_KEY');
    }

    public static function webhookSecret(): string
    {
        return self::value('STRIPE_WEBHOOK_SECRET');
    }

    private static function value(string $key): string
    {
        if (defined($key)) {
            return trim((string) constant($key));
        }

        foreach ([$_ENV, $_SERVER] as $source) {
            if (isset($source[$key]) && is_scalar($source[$key])) {
                return trim((string) $source[$key]);
            }
        }

        $value = getenv($key);

        return is_string($value) ? trim($value) : '';
    }
}
