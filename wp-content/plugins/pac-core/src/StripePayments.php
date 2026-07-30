<?php

declare(strict_types=1);

namespace Pac\Core;

use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Throwable;
use WP_Error;

final class StripePayments
{
    private const NONCE_ACTION = 'pac_stripe_donation';
    private const CREATE_ACTION = 'pac_create_payment_intent';
    private const COMPLETE_ACTION = 'pac_complete_donation';
    private const MIN_AMOUNT_CENTS = 100;

    public static function registerHooks(): void
    {
        add_action('wp_ajax_' . self::CREATE_ACTION, [self::class, 'createIntent']);
        add_action('wp_ajax_nopriv_' . self::CREATE_ACTION, [self::class, 'createIntent']);
        add_action('wp_ajax_' . self::COMPLETE_ACTION, [self::class, 'completePayment']);
        add_action('wp_ajax_nopriv_' . self::COMPLETE_ACTION, [self::class, 'completePayment']);
    }

    public static function createIntent(): void
    {
        self::verifyNonce();
        self::configureStripe();

        $amountCents = self::intRequestValue('amount_cents');
        $projectId = self::intRequestValue('progetto_id');
        $project = ProjectRepository::findPublished($projectId);
        $donorData = self::sanitizeDonorData();
        $donorError = DonationValidator::validateDonorData($donorData);

        if ($project instanceof WP_Error) {
            self::sendError($project, 404);
        }

        if ($donorError instanceof WP_Error) {
            self::sendError($donorError, 400);
        }

        if ($amountCents < self::MIN_AMOUNT_CENTS) {
            self::sendError('Importo non valido.', 400);
        }

        $projectName = sanitize_text_field((string) get_the_title($project));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amountCents,
                'currency' => 'eur',
                'automatic_payment_methods' => ['enabled' => true],
                'description' => 'Donazione per il progetto: ' . $projectName,
                'metadata' => [
                    'integration' => 'pac_custom_donation',
                    'progetto_id' => (string) $projectId,
                ],
            ]);

            $stored = DonationStore::savePending((string) $paymentIntent->id, [
                'expected_amount_cents' => $amountCents,
                'project_id' => $projectId,
                'project_name' => $projectName,
                'donor' => $donorData,
            ]);

            if (!$stored) {
                try {
                    $paymentIntent->cancel();
                } catch (Throwable $ignored) {
                    // The intent identifier is logged below; no secret or donor data is emitted.
                }

                error_log('[PAC Core] Pending donation storage failed intent=' . sanitize_key((string) $paymentIntent->id) . '.');
                self::sendError('Impossibile preparare la donazione.', 500);
            }

            error_log('[PAC Core] PaymentIntent creato intent=' . sanitize_key((string) $paymentIntent->id) . '.');
            wp_send_json_success([
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id,
            ]);
        } catch (ApiErrorException $exception) {
            error_log('[PAC Core] Stripe createIntent failed exception=' . get_class($exception) . '.');
            self::sendError('Errore nella creazione del pagamento.', 502);
        }
    }

    public static function completePayment(): void
    {
        self::verifyNonce();
        self::configureStripe();
        $paymentIntentId = sanitize_key(self::requestValue('payment_intent_id'));

        if ($paymentIntentId === '') {
            self::sendError('Payment intent mancante.', 400);
        }

        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
        } catch (ApiErrorException $exception) {
            error_log('[PAC Core] Stripe retrieve failed intent=' . $paymentIntentId . ' exception=' . get_class($exception) . '.');
            self::sendError('Impossibile verificare il pagamento.', 502);
            return;
        }

        $result = DonationFinalizer::finalize($paymentIntent, 'browser');

        if ($result instanceof WP_Error) {
            self::sendError($result, self::statusForError($result));
        }

        wp_send_json_success($result);
    }

    private static function configureStripe(): void
    {
        $secretKey = Config::secretKey();

        if ($secretKey === '' || (!Config::isProduction() && !str_starts_with($secretKey, 'sk_test_'))) {
            self::sendError('Configurazione Stripe test mancante o non sicura.', 500);
        }

        Stripe::setApiKey($secretKey);
    }

    private static function verifyNonce(): void
    {
        if (!check_ajax_referer(self::NONCE_ACTION, 'nonce', false)) {
            self::sendError('Richiesta non autorizzata.', 403);
        }
    }

    private static function sanitizeDonorData(): array
    {
        return [
            'name' => sanitize_text_field(self::requestValue('name')),
            'surname' => sanitize_text_field(self::requestValue('surname')),
            'phone' => sanitize_text_field(self::requestValue('phone')),
            'email' => sanitize_email(self::requestValue('email')),
            'codiceFiscale' => sanitize_text_field(self::requestValue('codice_fiscale')),
        ];
    }

    private static function requestValue(string $key): string
    {
        return isset($_POST[$key]) ? trim((string) wp_unslash($_POST[$key])) : '';
    }

    private static function intRequestValue(string $key): int
    {
        return (int) self::requestValue($key);
    }

    private static function statusForError(WP_Error $error): int
    {
        return in_array($error->get_error_code(), [
            'pending_donation_missing',
            'donation_processing',
            'thank_you_email_failed',
            'email_state_failed',
            'donation_state_failed',
            'donation_finalization_failed',
        ], true) ? 503 : 409;
    }

    private static function sendError($message, int $status = 400): void
    {
        $errorMessage = $message instanceof WP_Error ? $message->get_error_message() : (string) $message;
        wp_send_json_error(['message' => $errorMessage], $status);
    }
}
