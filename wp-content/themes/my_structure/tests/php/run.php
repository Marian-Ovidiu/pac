<?php

declare(strict_types=1);

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

if (!function_exists('is_email')) {
    function is_email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

$GLOBALS['pac_test_mail_calls'] = [];

if (!function_exists('wp_mail')) {
    function wp_mail($to, $subject, $message, $headers = '')
    {
        $GLOBALS['pac_test_mail_calls'][] = compact('to', 'subject', 'message', 'headers');
        return true;
    }
}

$themeRoot = dirname(__DIR__, 2);
require_once $themeRoot . '/source/Classes/DonationValidator.php';
require_once $themeRoot . '/source/Classes/GrazieEmail.php';

use Classes\DonationValidator;
use Classes\GrazieEmail;

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

$validIntent = static function (): object {
    return (object) [
        'amount' => 2500,
        'currency' => 'eur',
        'status' => 'succeeded',
        'metadata' => [
            'integration' => 'pac_custom_donation',
            'progetto_id' => '42',
        ],
    ];
};

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
            $intent->metadata['integration'] = $invalidValue;
        } elseif ($case === 'project') {
            $intent->metadata['progetto_id'] = $invalidValue;
        } else {
            $intent->{$case} = $invalidValue;
        }

        $error = DonationValidator::validatePaymentIntent($intent, 2500, 42);
        $assertSame($expectedCode, $error->get_error_code());
    });
}

$validDonor = [
    'name' => 'Ada',
    'surname' => 'Lovelace',
    'email' => 'ada@example.test',
    'phone' => '+39000000000',
];

$test('accepts valid donor data', static function () use ($assertSame, $validDonor): void {
    $assertSame(null, DonationValidator::validateDonorData($validDonor));
});

foreach ([
    'name' => 'invalid_name',
    'surname' => 'invalid_name',
    'email' => 'invalid_email',
    'phone' => 'invalid_phone',
] as $field => $expectedCode) {
    $test("rejects invalid donor {$field}", static function () use ($assertSame, $validDonor, $field, $expectedCode): void {
        $donor = $validDonor;
        $donor[$field] = $field === 'email' ? 'not-an-email' : '';
        $error = DonationValidator::validateDonorData($donor);
        $assertSame($expectedCode, $error->get_error_code());
    });
}

$test('does not send to an invalid email', static function () use ($assertSame): void {
    $GLOBALS['pac_test_mail_calls'] = [];
    $assertSame(false, GrazieEmail::sendThankYouEmail('invalid', 'Progetto', 10));
    $assertSame(0, count($GLOBALS['pac_test_mail_calls']));
});

$test('renders and sends the thank-you email', static function () use ($assertSame, $assertContains): void {
    $GLOBALS['pac_test_mail_calls'] = [];
    $sent = GrazieEmail::sendThankYouEmail('donor@example.test', '<b>Foresta</b> & Futuro', 25.5);

    $assertSame(true, $sent);
    $assertSame(1, count($GLOBALS['pac_test_mail_calls']));
    $mail = $GLOBALS['pac_test_mail_calls'][0];
    $assertSame('Grazie per la tua donazione!', $mail['subject']);
    $assertContains('Foresta &amp; Futuro', $mail['message']);
    $assertContains('25,50 EUR', $mail['message']);
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
