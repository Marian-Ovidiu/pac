<?php

declare(strict_types=1);

namespace Pac\Core;

final class DonationStore
{
    private const PENDING_PREFIX = 'pac_stripe_pending_';
    private const PROCESSED_PREFIX = 'pac_stripe_processed_';
    private const LOCK_PREFIX = 'pac_stripe_processing_';
    private const EMAIL_SENT_PREFIX = 'pac_stripe_email_sent_';
    private const PENDING_TTL = 7 * DAY_IN_SECONDS;
    private const LOCK_TTL = 300;

    public static function savePending(string $intentId, array $donation): bool
    {
        $record = [
            'version' => 1,
            'intent_id' => $intentId,
            'expected_amount_cents' => (int) ($donation['expected_amount_cents'] ?? 0),
            'project_id' => (int) ($donation['project_id'] ?? 0),
            'project_name' => (string) ($donation['project_name'] ?? ''),
            'donor' => (array) ($donation['donor'] ?? []),
            'created_at' => time(),
            'expires_at' => time() + self::PENDING_TTL,
        ];
        $key = self::pendingKey($intentId);

        if (update_option($key, $record, false)) {
            return true;
        }

        return get_option($key, null) === $record;
    }

    public static function getPending(string $intentId): ?array
    {
        $key = self::pendingKey($intentId);
        $record = get_option($key, null);

        if (!is_array($record)) {
            return null;
        }

        if ((int) ($record['expires_at'] ?? 0) < time()) {
            delete_option($key);
            return null;
        }

        return $record;
    }

    public static function isProcessed(string $intentId): bool
    {
        return get_option(self::processedKey($intentId), false) !== false;
    }

    public static function acquireLock(string $intentId): bool
    {
        if (self::isProcessed($intentId)) {
            return false;
        }

        $key = self::lockKey($intentId);
        $lockedAt = (int) get_option($key, 0);

        if ($lockedAt > 0 && $lockedAt >= time() - self::LOCK_TTL) {
            return false;
        }

        if ($lockedAt > 0) {
            delete_option($key);
        }

        return add_option($key, time(), '', false);
    }

    public static function releaseLock(string $intentId): void
    {
        delete_option(self::lockKey($intentId));
    }

    public static function markProcessed(string $intentId, string $eventId): bool
    {
        $record = [
            'processed_at' => time(),
            'event_id' => sanitize_key($eventId),
        ];
        $key = self::processedKey($intentId);
        $saved = update_option($key, $record, false) || get_option($key, null) === $record;

        if (!$saved) {
            return false;
        }

        delete_option(self::pendingKey($intentId));
        delete_option(self::emailSentKey($intentId));
        self::releaseLock($intentId);

        return true;
    }

    public static function isEmailSent(string $intentId): bool
    {
        return get_option(self::emailSentKey($intentId), false) !== false;
    }

    public static function markEmailSent(string $intentId): bool
    {
        $key = self::emailSentKey($intentId);
        $record = ['sent_at' => time()];

        return update_option($key, $record, false) || get_option($key, null) === $record;
    }

    public static function cleanupExpired(): void
    {
        global $wpdb;

        if (!isset($wpdb->options)) {
            return;
        }

        $pattern = $wpdb->esc_like(self::PENDING_PREFIX) . '%';
        $rows = $wpdb->get_results($wpdb->prepare(
            "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s LIMIT 500",
            $pattern
        ));

        foreach ((array) $rows as $row) {
            $record = maybe_unserialize($row->option_value ?? null);

            if (is_array($record) && (int) ($record['expires_at'] ?? 0) < time()) {
                delete_option((string) $row->option_name);
            }
        }
    }

    private static function pendingKey(string $intentId): string
    {
        return self::PENDING_PREFIX . sanitize_key($intentId);
    }

    private static function processedKey(string $intentId): string
    {
        return self::PROCESSED_PREFIX . sanitize_key($intentId);
    }

    private static function lockKey(string $intentId): string
    {
        return self::LOCK_PREFIX . sanitize_key($intentId);
    }

    private static function emailSentKey(string $intentId): string
    {
        return self::EMAIL_SENT_PREFIX . sanitize_key($intentId);
    }
}
