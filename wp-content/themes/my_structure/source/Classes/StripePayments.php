<?php

namespace Classes;

use Models\Progetto;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use WP_Error;

class StripePayments
{
    private const NONCE_ACTION = 'pac_stripe_donation';
    private const CREATE_ACTION = 'pac_create_payment_intent';
    private const COMPLETE_ACTION = 'pac_complete_donation';
    private const MIN_AMOUNT_CENTS = 100;
    private const OPTION_PROCESSED_PREFIX = 'pac_stripe_processed_';
    private const OPTION_LOCK_PREFIX = 'pac_stripe_processing_';

    public static function registerHooks()
    {
        add_action('wp_ajax_' . self::CREATE_ACTION, [self::class, 'createIntent']);
        add_action('wp_ajax_nopriv_' . self::CREATE_ACTION, [self::class, 'createIntent']);
        add_action('wp_ajax_' . self::COMPLETE_ACTION, [self::class, 'completePayment']);
        add_action('wp_ajax_nopriv_' . self::COMPLETE_ACTION, [self::class, 'completePayment']);
    }

    public static function createIntent()
    {
        self::verifyNonce();
        self::setApiKey();

        $amountCents = self::getIntRequestValue('amount_cents');
        $progettoId = self::getIntRequestValue('progetto_id');
        $progetto = self::getValidatedProject($progettoId);

        if (is_wp_error($progetto)) {
            self::sendError($progetto, 404);
        }

        if ($amountCents < self::MIN_AMOUNT_CENTS) {
            self::sendError('Importo non valido.', 400);
        }

        $description = $progetto
            ? 'Donazione per il progetto: ' . sanitize_text_field($progetto->title)
            : 'Donazione generica';

        try {
            $paymentIntent = PaymentIntent::create([
                'amount'                    => $amountCents,
                'currency'                  => 'eur',
                'automatic_payment_methods' => ['enabled' => true],
                'description'               => $description,
                'metadata'                  => [
                    'integration' => 'pac_custom_donation',
                    'progetto_id' => (string) $progettoId,
                ],
            ]);

            error_log('[StripePayments] PaymentIntent creato: ' . $paymentIntent->id);

            wp_send_json_success([
                'clientSecret'    => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id,
            ]);
        } catch (ApiErrorException $exception) {
            error_log('[StripePayments] Errore createIntent: ' . $exception->getMessage());
            self::sendError('Errore nella creazione del pagamento.', 502);
        }
    }

    public static function completePayment()
    {
        self::verifyNonce();
        self::setApiKey();

        $paymentIntentId = self::getRequestValue('payment_intent_id');
        $expectedAmountCents = self::getIntRequestValue('expected_amount_cents');
        $progettoId = self::getIntRequestValue('progetto_id');
        $progetto = self::getValidatedProject($progettoId);
        $donorData = self::sanitizeDonorData();

        if ($paymentIntentId === '') {
            self::sendError('Payment intent mancante.', 400);
        }

        if ($expectedAmountCents < self::MIN_AMOUNT_CENTS) {
            self::sendError('Importo non valido.', 400);
        }

        if (is_wp_error($progetto)) {
            self::sendError($progetto, 404);
        }

        $validationError = self::validateDonorData($donorData);
        if ($validationError instanceof WP_Error) {
            self::sendError($validationError, 400);
        }

        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
        } catch (ApiErrorException $exception) {
            error_log('[StripePayments] Errore retrieve PaymentIntent: ' . $exception->getMessage());
            self::sendError('Impossibile verificare il pagamento.', 502);
            return;
        }

        $paymentValidationError = self::validatePaymentIntent($paymentIntent, $expectedAmountCents, $progettoId);
        if ($paymentValidationError instanceof WP_Error) {
            self::sendError($paymentValidationError, 409);
        }

        if (self::isProcessed($paymentIntentId)) {
            wp_send_json_success([
                'processed' => true,
                'message'   => 'Donazione gia registrata.',
            ]);
        }

        if (!self::acquireProcessingLock($paymentIntentId)) {
            wp_send_json_success([
                'processed' => true,
                'message'   => 'Donazione in elaborazione o gia registrata.',
            ]);
        }

        try {
            $userId = self::createOrUpdateDonor($donorData, $paymentIntent, $progettoId);
            GrazieEmail::sendThankYouEmail(
                $donorData['email'],
                'Donazione per il progetto: ' . sanitize_text_field($progetto->title),
                $paymentIntent->amount / 100
            );

            self::markProcessed($paymentIntentId);

            wp_send_json_success([
                'processed'       => true,
                'paymentIntentId' => $paymentIntent->id,
                'userId'          => $userId,
            ]);
        } catch (\Throwable $throwable) {
            error_log('[StripePayments] Errore completePayment: ' . $throwable->getMessage());
            self::releaseProcessingLock($paymentIntentId);
            self::sendError('Errore durante la finalizzazione della donazione.', 500);
        }
    }

    private static function createOrUpdateDonor(array $donorData, PaymentIntent $paymentIntent, int $progettoId)
    {
        $email = $donorData['email'];
        $userId = email_exists($email);

        if (!$userId) {
            $userId = wp_insert_user([
                'user_login' => $email,
                'user_pass'  => wp_generate_password(),
                'user_email' => $email,
                'first_name' => $donorData['name'],
                'last_name'  => $donorData['surname'],
                'role'       => 'donator',
            ]);

            if (is_wp_error($userId)) {
                throw new \RuntimeException($userId->get_error_message());
            }
        } else {
            wp_update_user([
                'ID'         => $userId,
                'first_name' => $donorData['name'],
                'last_name'  => $donorData['surname'],
            ]);
        }

        $amountMajor = round(((int) $paymentIntent->amount) / 100, 2);

        update_user_meta($userId, 'telefono', $donorData['phone']);
        update_user_meta($userId, 'codice_fiscale', $donorData['codiceFiscale']);
        update_user_meta($userId, 'importo_donato', $amountMajor);
        update_user_meta($userId, 'title', $progettoId);
        update_user_meta($userId, 'name', $donorData['name']);
        update_user_meta($userId, 'stripe_payment_intent_id', $paymentIntent->id);
        update_user_meta($userId, 'stripe_payment_status', $paymentIntent->status);

        return $userId;
    }

    private static function validatePaymentIntent(PaymentIntent $paymentIntent, int $expectedAmountCents, int $progettoId)
    {
        if (self::getPaymentIntentMetadataValue($paymentIntent, 'integration') !== 'pac_custom_donation') {
            return new WP_Error('invalid_intent_origin', 'Payment intent non riconosciuto.');
        }

        if ((int) $paymentIntent->amount !== $expectedAmountCents) {
            return new WP_Error('invalid_amount', 'Importo del pagamento incoerente.');
        }

        if (($paymentIntent->currency ?? '') !== 'eur') {
            return new WP_Error('invalid_currency', 'Valuta non supportata.');
        }

        if ((int) self::getPaymentIntentMetadataValue($paymentIntent, 'progetto_id') !== $progettoId) {
            return new WP_Error('invalid_project', 'Progetto del pagamento incoerente.');
        }

        if (($paymentIntent->status ?? '') !== 'succeeded') {
            return new WP_Error('payment_not_completed', 'Pagamento non completato.');
        }

        return null;
    }

    private static function getPaymentIntentMetadataValue(PaymentIntent $paymentIntent, $key)
    {
        if (!isset($paymentIntent->metadata)) {
            return '';
        }

        if (is_array($paymentIntent->metadata)) {
            return isset($paymentIntent->metadata[$key]) ? (string) $paymentIntent->metadata[$key] : '';
        }

        return isset($paymentIntent->metadata->{$key}) ? (string) $paymentIntent->metadata->{$key} : '';
    }

    private static function validateDonorData(array $donorData)
    {
        if ($donorData['name'] === '' || $donorData['surname'] === '') {
            return new WP_Error('invalid_name', 'Nome e cognome sono obbligatori.');
        }

        if (!is_email($donorData['email'])) {
            return new WP_Error('invalid_email', 'Indirizzo email non valido.');
        }

        if ($donorData['phone'] === '') {
            return new WP_Error('invalid_phone', 'Telefono obbligatorio.');
        }

        return null;
    }

    private static function sanitizeDonorData()
    {
        return [
            'name'          => sanitize_text_field(self::getRequestValue('name')),
            'surname'       => sanitize_text_field(self::getRequestValue('surname')),
            'phone'         => sanitize_text_field(self::getRequestValue('phone')),
            'email'         => sanitize_email(self::getRequestValue('email')),
            'codiceFiscale' => sanitize_text_field(self::getRequestValue('codice_fiscale')),
        ];
    }

    private static function getValidatedProject(int $progettoId)
    {
        if ($progettoId <= 0) {
            return new WP_Error('missing_project', 'Progetto non valido.');
        }

        $progetto = Progetto::find($progettoId);

        if (!$progetto) {
            return new WP_Error('project_not_found', 'Progetto non trovato.');
        }

        return $progetto;
    }

    private static function setApiKey()
    {
        $secretKey = my_env('SECRET_KEY') ?: my_env('TEST_SECRET_KEY', '');

        if ($secretKey === '') {
            self::sendError('Configurazione Stripe mancante.', 500);
        }

        Stripe::setApiKey($secretKey);
    }

    private static function verifyNonce()
    {
        if (!check_ajax_referer(self::NONCE_ACTION, 'nonce', false)) {
            self::sendError('Richiesta non autorizzata.', 403);
        }
    }

    private static function getRequestValue($key)
    {
        return isset($_POST[$key])
            ? trim((string) wp_unslash($_POST[$key]))
            : '';
    }

    private static function getIntRequestValue($key)
    {
        return (int) self::getRequestValue($key);
    }

    private static function getProcessedOptionKey($paymentIntentId)
    {
        return self::OPTION_PROCESSED_PREFIX . sanitize_key($paymentIntentId);
    }

    private static function getLockOptionKey($paymentIntentId)
    {
        return self::OPTION_LOCK_PREFIX . sanitize_key($paymentIntentId);
    }

    private static function isProcessed($paymentIntentId)
    {
        return (bool) get_option(self::getProcessedOptionKey($paymentIntentId), false);
    }

    private static function acquireProcessingLock($paymentIntentId)
    {
        if (self::isProcessed($paymentIntentId)) {
            return false;
        }

        return add_option(self::getLockOptionKey($paymentIntentId), time(), '', false);
    }

    private static function markProcessed($paymentIntentId)
    {
        update_option(self::getProcessedOptionKey($paymentIntentId), time(), false);
        self::releaseProcessingLock($paymentIntentId);
    }

    private static function releaseProcessingLock($paymentIntentId)
    {
        delete_option(self::getLockOptionKey($paymentIntentId));
    }

    private static function sendError($message, $status = 400)
    {
        $errorMessage = $message instanceof WP_Error
            ? $message->get_error_message()
            : (string) $message;

        wp_send_json_error(['message' => $errorMessage], $status);
    }
}
