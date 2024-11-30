<?php

namespace Classes;

use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripePayments
{
    public static function createIntent()
    {
        var_dump('ciao1');
        Stripe::setApiKey(my_env('SECRET_KEY'));
        var_dump('ciao2');
        $data = json_decode(file_get_contents("php://input"), true);
        var_dump($data); die();

        $amount = isset($data['amount']) ? $data['amount'] : 0;
        $paymentIntent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'eur',
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        header('Content-Type: application/json');
        echo json_encode(['clientSecret' => $paymentIntent->client_secret]);
        exit;
    }

    public static function completePayment()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $paymentMethodId = $data['paymentMethodId'];
        $amount = $data['amount'];
        \Stripe\Stripe::setApiKey(my_env('SECRET_KEY'));

        try {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => 'eur',
                'payment_method' => ['card', 'paypal'],
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => 'http://pac.localhost/progetto/'
            ]);

            if ($paymentIntent->status === 'succeeded') {
                if (isset($data['email']) && !email_exists($data['email'])) {
                    self::createUser($data);
                }
                wp_send_json_success([
                    'success' => true,
                    'redirect' => 'progetto/',
                    'message' => 'Ordine completato con successo.'
                ]);
            } else {
                wp_send_json_error(['message' => 'Il pagamento non è riuscito.']);
            }
        } catch (\Stripe\Exception\ApiErrorException $e) {
            wp_send_json_error(['message' => 'Errore di pagamento: ' . $e->getMessage()]);
        }
    }

    public static function createUser($data)
    {
        $password = wp_generate_password();
        $name = $data['name'];
        $surname = $data['surname'];
        $phone = $data['phone'] ?? null;
        $email = $data['email'];
        $codiceFiscale = $data['codiceFiscale'];
        /*$paymentIntentId = $data['paymentIntentId'];*/
        $progettoId = $data['progettoId'];
        $amount = $data['amount'];

        $user_id = wp_insert_user([
            'user_login' => $email,
            'user_pass' => $password,
            'user_email' => $email,
            'first_name' => $name,
            'last_name' => $surname,
            'role' => 'donator'
        ]);

        update_user_meta($user_id, 'telefono', $phone);
        update_user_meta($user_id, 'codice_fiscale', $codiceFiscale);
        update_user_meta($user_id, 'importo_donato', $amount);
        /*update_user_meta($user_id, 'paymentIntentId', $paymentIntentId);*/
        update_user_meta($user_id, 'progettoId', $progettoId);
    }
}