<?php

namespace Classes;

use WP_Error;

final class DonationValidator
{
    public static function validatePaymentIntent(object $paymentIntent, int $expectedAmountCents, int $progettoId)
    {
        if (self::getMetadataValue($paymentIntent, 'integration') !== 'pac_custom_donation') {
            return new WP_Error('invalid_intent_origin', 'Payment intent non riconosciuto.');
        }

        if ((int) ($paymentIntent->amount ?? 0) !== $expectedAmountCents) {
            return new WP_Error('invalid_amount', 'Importo del pagamento incoerente.');
        }

        if (($paymentIntent->currency ?? '') !== 'eur') {
            return new WP_Error('invalid_currency', 'Valuta non supportata.');
        }

        if ((int) self::getMetadataValue($paymentIntent, 'progetto_id') !== $progettoId) {
            return new WP_Error('invalid_project', 'Progetto del pagamento incoerente.');
        }

        if (($paymentIntent->status ?? '') !== 'succeeded') {
            return new WP_Error('payment_not_completed', 'Pagamento non completato.');
        }

        return null;
    }

    public static function validateDonorData(array $donorData)
    {
        if (($donorData['name'] ?? '') === '' || ($donorData['surname'] ?? '') === '') {
            return new WP_Error('invalid_name', 'Nome e cognome sono obbligatori.');
        }

        if (!is_email($donorData['email'] ?? '')) {
            return new WP_Error('invalid_email', 'Indirizzo email non valido.');
        }

        if (($donorData['phone'] ?? '') === '') {
            return new WP_Error('invalid_phone', 'Telefono obbligatorio.');
        }

        return null;
    }

    private static function getMetadataValue(object $paymentIntent, string $key): string
    {
        if (!isset($paymentIntent->metadata)) {
            return '';
        }

        if (is_array($paymentIntent->metadata)) {
            return isset($paymentIntent->metadata[$key]) ? (string) $paymentIntent->metadata[$key] : '';
        }

        return isset($paymentIntent->metadata->{$key}) ? (string) $paymentIntent->metadata->{$key} : '';
    }
}
