<?php

declare(strict_types=1);

if (!defined('DAY_IN_SECONDS')) {
    define('DAY_IN_SECONDS', 86400);
}

if (!defined('HOUR_IN_SECONDS')) {
    define('HOUR_IN_SECONDS', 3600);
}

$pluginRoot = dirname(__DIR__);
$repositoryRoot = dirname($pluginRoot, 3);

if (!defined('ABSPATH')) {
    define('ABSPATH', $repositoryRoot . '/');
}

if (!defined('WP_CONTENT_DIR')) {
    define('WP_CONTENT_DIR', $repositoryRoot . '/wp-content');
}

if (!class_exists('WP_Error')) {
    class WP_Error
    {
        private string $code;
        private string $message;

        public function __construct(string $code = '', string $message = '')
        {
            $this->code = $code;
            $this->message = $message;
        }

        public function get_error_code(): string
        {
            return $this->code;
        }

        public function get_error_message(): string
        {
            return $this->message;
        }
    }
}

function is_wp_error($value): bool
{
    return $value instanceof WP_Error;
}

function add_action($hook, $callback, $priority = 10, $acceptedArgs = 1): bool
{
    $GLOBALS['pac_test_hooks'][$hook][] = $callback;
    return true;
}

function register_activation_hook($file, $callback): void
{
    $GLOBALS['pac_test_activation_hook'] = $callback;
}

function register_deactivation_hook($file, $callback): void
{
    $GLOBALS['pac_test_deactivation_hook'] = $callback;
}

function add_role($role, $label, $capabilities)
{
    $GLOBALS['pac_test_roles'][$role] = compact('label', 'capabilities');
    return (object) ['name' => $role];
}

function wp_next_scheduled($hook)
{
    return $GLOBALS['pac_test_schedules'][$hook] ?? false;
}

function wp_schedule_event($timestamp, $recurrence, $hook): bool
{
    $GLOBALS['pac_test_schedules'][$hook] = $timestamp;
    return true;
}

function wp_clear_scheduled_hook($hook): int
{
    unset($GLOBALS['pac_test_schedules'][$hook]);
    return 1;
}

function is_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function sanitize_key($value): string
{
    return preg_replace('/[^a-z0-9_\-]/', '', strtolower((string) $value));
}

function sanitize_text_field($value): string
{
    return trim(strip_tags((string) $value));
}

function get_option($key, $default = false)
{
    return array_key_exists($key, $GLOBALS['pac_test_options'])
        ? $GLOBALS['pac_test_options'][$key]
        : $default;
}

function update_option($key, $value, $autoload = null): bool
{
    if (!empty($GLOBALS['pac_test_fail_update'][$key])) {
        $GLOBALS['pac_test_fail_update'][$key]--;
        return false;
    }

    if (array_key_exists($key, $GLOBALS['pac_test_options']) && $GLOBALS['pac_test_options'][$key] === $value) {
        return false;
    }

    $GLOBALS['pac_test_options'][$key] = $value;
    return true;
}

function add_option($key, $value, $deprecated = '', $autoload = null): bool
{
    if (array_key_exists($key, $GLOBALS['pac_test_options'])) {
        return false;
    }

    $GLOBALS['pac_test_options'][$key] = $value;
    return true;
}

function delete_option($key): bool
{
    $exists = array_key_exists($key, $GLOBALS['pac_test_options']);
    unset($GLOBALS['pac_test_options'][$key]);
    return $exists;
}

function get_post($id): ?object
{
    if ((int) $id !== 42) {
        return null;
    }

    return (object) [
        'ID' => 42,
        'post_type' => 'progetto',
        'post_status' => 'publish',
        'post_title' => 'Foresta & Futuro',
    ];
}

function get_the_title($post): string
{
    return (string) ($post->post_title ?? '');
}

function email_exists($email)
{
    foreach ($GLOBALS['pac_test_users'] as $id => $user) {
        if ($user['user_email'] === $email) {
            return $id;
        }
    }

    return false;
}

function wp_insert_user($data)
{
    $id = $GLOBALS['pac_test_next_user_id']++;
    $GLOBALS['pac_test_users'][$id] = $data;
    return $id;
}

function wp_update_user($data)
{
    $id = (int) $data['ID'];

    if (!isset($GLOBALS['pac_test_users'][$id])) {
        return new WP_Error('missing_user', 'Utente mancante.');
    }

    $GLOBALS['pac_test_users'][$id] = array_merge($GLOBALS['pac_test_users'][$id], $data);
    return $id;
}

function wp_generate_password(): string
{
    return 'test-only-password';
}

function update_user_meta($userId, $key, $value): bool
{
    $GLOBALS['pac_test_user_meta'][(int) $userId][$key] = $value;
    return true;
}

function wp_mail($to, $subject, $message, $headers = ''): bool
{
    $GLOBALS['pac_test_mail_calls'][] = compact('to', 'subject', 'message', 'headers');

    if ($GLOBALS['pac_test_mail_results']) {
        return (bool) array_shift($GLOBALS['pac_test_mail_results']);
    }

    return true;
}

$autoload = $pluginRoot . '/vendor/autoload.php';

if (!is_file($autoload)) {
    fwrite(STDERR, "PAC Core dependencies missing. Run composer install in {$pluginRoot}.\n");
    exit(1);
}

require $pluginRoot . '/pac-core.php';

use Pac\Core\DonationFinalizer;
use Pac\Core\DonationStore;
use Pac\Core\DonationValidator;
use Pac\Core\StripeWebhook;
use Pac\Core\ThankYouMailer;

$tests = [];
$test = static function (string $name, callable $callback) use (&$tests): void {
    $tests[] = [$name, $callback];
};

$assertSame = static function ($expected, $actual, string $message = ''): void {
    if ($expected !== $actual) {
        throw new RuntimeException($message ?: sprintf(
            'Expected %s, got %s',
            var_export($expected, true),
            var_export($actual, true)
        ));
    }
};

$assertContains = static function (string $needle, string $haystack): void {
    if (!str_contains($haystack, $needle)) {
        throw new RuntimeException(sprintf('Expected output to contain "%s".', $needle));
    }
};

$reset = static function (): void {
    $GLOBALS['pac_test_options'] = [];
    $GLOBALS['pac_test_users'] = [];
    $GLOBALS['pac_test_user_meta'] = [];
    $GLOBALS['pac_test_next_user_id'] = 1;
    $GLOBALS['pac_test_mail_calls'] = [];
    $GLOBALS['pac_test_mail_results'] = [];
    $GLOBALS['pac_test_fail_update'] = [];
};

$validDonor = static function (): array {
    return [
        'name' => 'Ada',
        'surname' => 'Lovelace',
        'email' => 'ada@example.test',
        'phone' => '+39000000000',
        'codiceFiscale' => '',
    ];
};

$validIntent = static function (string $id = 'pi_test_42'): object {
    return (object) [
        'id' => $id,
        'amount' => 2500,
        'currency' => 'eur',
        'status' => 'succeeded',
        'metadata' => (object) [
            'integration' => 'pac_custom_donation',
            'progetto_id' => '42',
        ],
    ];
};

$savePending = static function (string $id = 'pi_test_42') use ($validDonor): void {
    $saved = DonationStore::savePending($id, [
        'expected_amount_cents' => 2500,
        'project_id' => 42,
        'project_name' => 'Foresta & Futuro',
        'donor' => $validDonor(),
    ]);

    if (!$saved) {
        throw new RuntimeException('Unable to save pending fixture.');
    }
};

$event = static function (object $intent, string $eventId = 'evt_test_42'): object {
    return (object) [
        'id' => $eventId,
        'type' => 'payment_intent.succeeded',
        'data' => (object) ['object' => $intent],
    ];
};

$eventPayload = static function (object $intent, string $eventId = 'evt_signature'): string {
    return (string) json_encode([
        'id' => $eventId,
        'object' => 'event',
        'type' => 'payment_intent.succeeded',
        'data' => ['object' => $intent],
    ], JSON_THROW_ON_ERROR);
};

$signatureHeader = static function (string $payload, string $secret): string {
    $timestamp = time();
    $signature = hash_hmac('sha256', $timestamp . '.' . $payload, $secret);

    return sprintf('t=%d,v1=%s', $timestamp, $signature);
};

$test('plugin bootstrap registers payment and webhook hooks once', static function () use ($assertSame): void {
    $assertSame(true, function_exists('pac_core_publishable_key'));
    $assertSame(1, count($GLOBALS['pac_test_hooks']['wp_ajax_pac_create_payment_intent'] ?? []));
    $assertSame(1, count($GLOBALS['pac_test_hooks']['wp_ajax_pac_complete_donation'] ?? []));
    $assertSame(1, count($GLOBALS['pac_test_hooks']['rest_api_init'] ?? []));
    $assertSame(true, is_callable($GLOBALS['pac_test_activation_hook'] ?? null));
    call_user_func($GLOBALS['pac_test_activation_hook']);
    $assertSame(true, isset($GLOBALS['pac_test_roles']['donator']));
    $assertSame(true, isset($GLOBALS['pac_test_schedules']['pac_core_cleanup_pending_donations']));
});

$test('accepts a valid succeeded PaymentIntent', static function () use ($assertSame, $validIntent): void {
    $assertSame(null, DonationValidator::validatePaymentIntent($validIntent(), 2500, 42));
});

foreach ([
    'integration' => ['invalid_intent_origin', 'other'],
    'amount' => ['invalid_amount', 999],
    'currency' => ['invalid_currency', 'usd'],
    'project' => ['invalid_project', '99'],
    'status' => ['payment_not_completed', 'processing'],
] as $case => [$expectedCode, $invalidValue]) {
    $test("rejects invalid payment {$case}", static function () use ($assertSame, $validIntent, $case, $expectedCode, $invalidValue): void {
        $intent = $validIntent();

        if ($case === 'integration') {
            $intent->metadata->integration = $invalidValue;
        } elseif ($case === 'project') {
            $intent->metadata->progetto_id = $invalidValue;
        } else {
            $intent->{$case} = $invalidValue;
        }

        $error = DonationValidator::validatePaymentIntent($intent, 2500, 42);
        $assertSame($expectedCode, $error->get_error_code());
    });
}

$test('validates a real Stripe webhook signature', static function () use ($assertSame, $validIntent, $eventPayload, $signatureHeader): void {
    $secret = 'whsec_test_fixture';
    $payload = $eventPayload($validIntent());
    $signature = $signatureHeader($payload, $secret);
    $parsed = StripeWebhook::parseEvent($payload, $signature, $secret);
    $assertSame('evt_signature', $parsed->id);
});

$test('rejects an invalid Stripe signature without side effects', static function () use ($assertSame, $reset, $validIntent, $eventPayload, $signatureHeader): void {
    $reset();
    $payload = $eventPayload($validIntent());
    $signature = $signatureHeader($payload, 'whsec_other');
    $rejected = false;

    try {
        StripeWebhook::parseEvent($payload, $signature, 'whsec_expected');
    } catch (Throwable $throwable) {
        $rejected = true;
    }

    $assertSame(true, $rejected);
    $assertSame(0, count($GLOBALS['pac_test_users']));
    $assertSame(0, count($GLOBALS['pac_test_mail_calls']));
});

$test('browser callback before webhook finalizes only once', static function () use ($assertSame, $reset, $savePending, $validIntent, $event): void {
    $reset();
    $savePending();
    $intent = $validIntent();
    $browser = DonationFinalizer::finalize($intent, 'browser');
    $webhook = StripeWebhook::processEvent($event($intent));
    $assertSame(false, $browser['duplicate']);
    $assertSame(true, $webhook['duplicate']);
    $assertSame(1, count($GLOBALS['pac_test_users']));
    $assertSame(1, count($GLOBALS['pac_test_mail_calls']));
});

$test('webhook before browser callback finalizes only once', static function () use ($assertSame, $reset, $savePending, $validIntent, $event): void {
    $reset();
    $savePending();
    $intent = $validIntent();
    $webhook = StripeWebhook::processEvent($event($intent, 'evt_first'));
    $browser = DonationFinalizer::finalize($intent, 'browser');
    $assertSame(false, $webhook['duplicate']);
    $assertSame(true, $browser['duplicate']);
    $assertSame(1, count($GLOBALS['pac_test_users']));
    $assertSame(1, count($GLOBALS['pac_test_mail_calls']));
});

$test('duplicate webhook event has no duplicate side effects', static function () use ($assertSame, $reset, $savePending, $validIntent, $event): void {
    $reset();
    $savePending();
    $intent = $validIntent();
    StripeWebhook::processEvent($event($intent, 'evt_duplicate'));
    $duplicate = StripeWebhook::processEvent($event($intent, 'evt_duplicate'));
    $assertSame(true, $duplicate['duplicate']);
    $assertSame(1, count($GLOBALS['pac_test_users']));
    $assertSame(1, count($GLOBALS['pac_test_mail_calls']));
});

$test('server-side pending amount cannot be overridden after payment', static function () use ($assertSame, $reset, $savePending, $validIntent): void {
    $reset();
    $savePending();
    $intent = $validIntent();
    $intent->amount = 500;
    $result = DonationFinalizer::finalize($intent, 'evt_amount');
    $assertSame('invalid_amount', $result->get_error_code());
    $assertSame(0, count($GLOBALS['pac_test_users']));
    $assertSame(0, count($GLOBALS['pac_test_mail_calls']));
});

$test('metadata mismatch has no side effects', static function () use ($assertSame, $reset, $savePending, $validIntent): void {
    $reset();
    $savePending();
    $intent = $validIntent();
    $intent->metadata->progetto_id = '99';
    $result = DonationFinalizer::finalize($intent, 'evt_project');
    $assertSame('invalid_project', $result->get_error_code());
    $assertSame(0, count($GLOBALS['pac_test_users']));
    $assertSame(0, count($GLOBALS['pac_test_mail_calls']));
});

$test('email failure remains retryable without duplicating the donor', static function () use ($assertSame, $reset, $savePending, $validIntent): void {
    $reset();
    $savePending();
    $GLOBALS['pac_test_mail_results'] = [false, true];
    $intent = $validIntent();
    $failed = DonationFinalizer::finalize($intent, 'evt_mail');
    $retried = DonationFinalizer::finalize($intent, 'evt_mail_retry');
    $assertSame('thank_you_email_failed', $failed->get_error_code());
    $assertSame(false, $retried['duplicate']);
    $assertSame(1, count($GLOBALS['pac_test_users']));
    $assertSame(2, count($GLOBALS['pac_test_mail_calls']));
});

$test('state persistence retry does not resend an accepted email', static function () use ($assertSame, $reset, $savePending, $validIntent): void {
    $reset();
    $savePending();
    $GLOBALS['pac_test_fail_update']['pac_stripe_processed_pi_test_42'] = 1;
    $intent = $validIntent();
    $failed = DonationFinalizer::finalize($intent, 'evt_state');
    $retried = DonationFinalizer::finalize($intent, 'evt_state_retry');
    $assertSame('donation_state_failed', $failed->get_error_code());
    $assertSame(false, $retried['duplicate']);
    $assertSame(1, count($GLOBALS['pac_test_mail_calls']));
});

$test('concurrent lock returns a retryable error without side effects', static function () use ($assertSame, $reset, $savePending, $validIntent): void {
    $reset();
    $savePending();
    $GLOBALS['pac_test_options']['pac_stripe_processing_pi_test_42'] = time();
    $result = DonationFinalizer::finalize($validIntent(), 'evt_locked');
    $assertSame('donation_processing', $result->get_error_code());
    $assertSame(0, count($GLOBALS['pac_test_users']));
    $assertSame(0, count($GLOBALS['pac_test_mail_calls']));
});

$test('stale processing lock is recovered on retry', static function () use ($assertSame, $reset, $savePending, $validIntent): void {
    $reset();
    $savePending();
    $GLOBALS['pac_test_options']['pac_stripe_processing_pi_test_42'] = time() - 600;
    $result = DonationFinalizer::finalize($validIntent(), 'evt_stale_lock');
    $assertSame(false, $result['duplicate']);
    $assertSame(1, count($GLOBALS['pac_test_mail_calls']));
});

$test('missing pending record asks Stripe to retry', static function () use ($assertSame, $reset, $validIntent): void {
    $reset();
    $result = DonationFinalizer::finalize($validIntent(), 'evt_missing');
    $assertSame('pending_donation_missing', $result->get_error_code());
});

$test('renders escaped thank-you email content', static function () use ($assertContains): void {
    $message = ThankYouMailer::render('<b>Foresta</b> & Futuro', 25.5);
    $assertContains('Foresta &amp; Futuro', $message);
    $assertContains('25,50 EUR', $message);
});

$failures = 0;

foreach ($tests as [$name, $callback]) {
    try {
        $callback();
        echo "PASS {$name}\n";
    } catch (Throwable $throwable) {
        $failures++;
        fwrite(STDERR, "FAIL {$name}: {$throwable->getMessage()}\n");
    }
}

echo sprintf("%d tests, %d failures\n", count($tests), $failures);
exit($failures === 0 ? 0 : 1);
