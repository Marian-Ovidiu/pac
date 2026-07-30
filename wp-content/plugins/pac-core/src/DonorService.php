<?php

declare(strict_types=1);

namespace Pac\Core;

final class DonorService
{
    public static function createOrUpdate(array $donorData, object $paymentIntent, int $projectId)
    {
        $email = (string) $donorData['email'];
        $userId = email_exists($email);

        if (!$userId) {
            $userId = wp_insert_user([
                'user_login' => $email,
                'user_pass' => wp_generate_password(),
                'user_email' => $email,
                'first_name' => $donorData['name'],
                'last_name' => $donorData['surname'],
                'role' => 'donator',
            ]);
        } else {
            $updated = wp_update_user([
                'ID' => $userId,
                'first_name' => $donorData['name'],
                'last_name' => $donorData['surname'],
            ]);

            if (is_wp_error($updated)) {
                return $updated;
            }
        }

        if (is_wp_error($userId)) {
            return $userId;
        }

        $amountMajor = round(((int) $paymentIntent->amount) / 100, 2);
        update_user_meta($userId, 'telefono', $donorData['phone']);
        update_user_meta($userId, 'codice_fiscale', $donorData['codiceFiscale'] ?? '');
        update_user_meta($userId, 'importo_donato', $amountMajor);
        update_user_meta($userId, 'title', $projectId);
        update_user_meta($userId, 'name', $donorData['name']);
        update_user_meta($userId, 'stripe_payment_intent_id', $paymentIntent->id);
        update_user_meta($userId, 'stripe_payment_status', $paymentIntent->status);

        return (int) $userId;
    }
}
