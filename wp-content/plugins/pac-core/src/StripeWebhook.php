<?php

declare(strict_types=1);

namespace Pac\Core;

use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

final class StripeWebhook
{
    public const ROUTE = '/stripe/webhook';

    public static function registerHooks(): void
    {
        add_action('rest_api_init', static function (): void {
            register_rest_route('pac/v1', self::ROUTE, [
                'methods' => 'POST',
                'callback' => [self::class, 'handleRequest'],
                'permission_callback' => '__return_true',
            ]);
        });
    }

    public static function handleRequest(WP_REST_Request $request): WP_REST_Response
    {
        $secret = Config::webhookSecret();

        if ($secret === '') {
            error_log('[PAC Core] Webhook rejected: signing secret missing.');
            return new WP_REST_Response(['received' => false], 503);
        }

        $signature = (string) $request->get_header('stripe-signature');

        if ($signature === '') {
            return new WP_REST_Response(['received' => false], 400);
        }

        try {
            $event = self::parseEvent($request->get_body(), $signature, $secret);
        } catch (UnexpectedValueException | SignatureVerificationException $exception) {
            error_log('[PAC Core] Webhook rejected: invalid payload or signature.');
            return new WP_REST_Response(['received' => false], 400);
        }

        $result = self::processEvent($event);

        if ($result instanceof WP_Error) {
            error_log(sprintf(
                '[PAC Core] Webhook retry event=%s code=%s.',
                sanitize_key((string) ($event->id ?? 'unknown')),
                sanitize_key($result->get_error_code())
            ));

            return new WP_REST_Response([
                'received' => false,
                'code' => $result->get_error_code(),
            ], self::statusForError($result));
        }

        return new WP_REST_Response(['received' => true] + $result, 200);
    }

    public static function parseEvent(string $payload, string $signature, string $secret): object
    {
        return Webhook::constructEvent($payload, $signature, $secret);
    }

    public static function processEvent(object $event)
    {
        $eventId = sanitize_key((string) ($event->id ?? 'unknown'));

        if (($event->type ?? '') !== 'payment_intent.succeeded') {
            error_log('[PAC Core] Webhook ignored event=' . $eventId . '.');
            return ['ignored' => true];
        }

        $paymentIntent = $event->data->object ?? null;

        if (!is_object($paymentIntent)) {
            return new WP_Error('invalid_webhook_object', 'Oggetto PaymentIntent mancante.');
        }

        error_log(sprintf(
            '[PAC Core] Webhook received event=%s intent=%s.',
            $eventId,
            sanitize_key((string) ($paymentIntent->id ?? 'unknown'))
        ));

        return DonationFinalizer::finalize($paymentIntent, $eventId);
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
}
