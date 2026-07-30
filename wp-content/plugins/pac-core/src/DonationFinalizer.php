<?php

declare(strict_types=1);

namespace Pac\Core;

use Throwable;
use WP_Error;

final class DonationFinalizer
{
    public static function finalize(object $paymentIntent, string $eventId = 'browser')
    {
        $intentId = sanitize_key((string) ($paymentIntent->id ?? ''));

        if ($intentId === '') {
            return new WP_Error('missing_payment_intent', 'Payment intent mancante.');
        }

        if (DonationStore::isProcessed($intentId)) {
            return self::result($intentId, true);
        }

        $pending = DonationStore::getPending($intentId);

        if (!$pending) {
            return new WP_Error('pending_donation_missing', 'Dati della donazione non disponibili; operazione ritentabile.');
        }

        $paymentError = DonationValidator::validatePaymentIntent(
            $paymentIntent,
            (int) $pending['expected_amount_cents'],
            (int) $pending['project_id']
        );

        if ($paymentError instanceof WP_Error) {
            return $paymentError;
        }

        $donorData = (array) ($pending['donor'] ?? []);
        $donorError = DonationValidator::validateDonorData($donorData);

        if ($donorError instanceof WP_Error) {
            return $donorError;
        }

        if (!DonationStore::acquireLock($intentId)) {
            if (DonationStore::isProcessed($intentId)) {
                return self::result($intentId, true);
            }

            return new WP_Error('donation_processing', 'Donazione già in elaborazione; operazione ritentabile.');
        }

        try {
            if (DonationStore::isProcessed($intentId)) {
                DonationStore::releaseLock($intentId);
                return self::result($intentId, true);
            }

            $userId = DonorService::createOrUpdate(
                $donorData,
                $paymentIntent,
                (int) $pending['project_id']
            );

            if ($userId instanceof WP_Error) {
                DonationStore::releaseLock($intentId);
                return $userId;
            }

            if (!DonationStore::isEmailSent($intentId)) {
                $mailSent = ThankYouMailer::send(
                    (string) $donorData['email'],
                    (string) $pending['project_name'],
                    ((int) $paymentIntent->amount) / 100
                );

                if (!$mailSent) {
                    DonationStore::releaseLock($intentId);
                    return new WP_Error('thank_you_email_failed', 'Invio email fallito; operazione ritentabile.');
                }

                if (!DonationStore::markEmailSent($intentId)) {
                    DonationStore::releaseLock($intentId);
                    return new WP_Error('email_state_failed', 'Salvataggio stato email fallito; operazione ritentabile.');
                }
            }

            if (!DonationStore::markProcessed($intentId, $eventId)) {
                DonationStore::releaseLock($intentId);
                return new WP_Error('donation_state_failed', 'Salvataggio stato donazione fallito; operazione ritentabile.');
            }

            error_log(sprintf(
                '[PAC Core] Donazione finalizzata intent=%s event=%s.',
                $intentId,
                sanitize_key($eventId)
            ));

            return [
                'processed' => true,
                'duplicate' => false,
                'paymentIntentId' => $intentId,
                'userId' => (int) $userId,
            ];
        } catch (Throwable $throwable) {
            DonationStore::releaseLock($intentId);
            error_log(sprintf(
                '[PAC Core] Finalizzazione fallita intent=%s exception=%s.',
                $intentId,
                get_class($throwable)
            ));

            return new WP_Error('donation_finalization_failed', 'Finalizzazione fallita; operazione ritentabile.');
        }
    }

    private static function result(string $intentId, bool $duplicate): array
    {
        return [
            'processed' => true,
            'duplicate' => $duplicate,
            'paymentIntentId' => $intentId,
        ];
    }
}
