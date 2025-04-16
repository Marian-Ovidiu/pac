<?php
namespace Classes;

use Classes\GrazieEmail;
use Models\Progetto;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripePayments
{
    public static function createIntent()
    {
        Stripe::setApiKey(my_env('SECRET_KEY'));
        $data = json_decode(file_get_contents("php://input"), true);

        // 🔐 Verifica ReCAPTCHA
        $recaptchaToken = $data['recaptchaToken'] ?? null;
        if (! $recaptchaToken) {
            wp_send_json_error(['message' => 'Token reCAPTCHA mancante.']);
            return;
        }

        // Chiamata al servizio di verifica di Google
        $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret'   => my_env('RECAPTCHA_SECRET_KEY'),
                'response' => $recaptchaToken,
            ],
        ]);

        if (is_wp_error($response)) {
            wp_send_json_error(['message' => 'Errore di comunicazione con reCAPTCHA.']);
            return;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (! $body['success'] || $body['score'] < 0.5) {
            wp_send_json_error(['message' => 'Verifica reCAPTCHA fallita.']);
            return;
        }

        $amount       = isset($data['amount']) ? (int) $data['amount'] : 0;
        $progetto_id  = $data['progetto_id'] ?? null;
        $progetto     = Progetto::find($progetto_id);
        $progettoName = $progetto ? "Donazione per il progetto: " . $progetto->title : "Donazione generica";

        try {
            $paymentIntent = PaymentIntent::create([
                'amount'                    => $amount,
                'currency'                  => 'eur',
                'automatic_payment_methods' => ['enabled' => true],
                'description'               => $progettoName,
            ]);

            wp_send_json_success(['clientSecret' => $paymentIntent->client_secret]);
        } catch (\Exception $e) {
            error_log("Stripe error: " . $e->getMessage());
            wp_send_json_error(['message' => 'Errore nella creazione del pagamento']);
        }
    }

    public static function completePayment()
    {
        // 🔐 Verifica ReCAPTCHA
        $recaptchaToken = $data['recaptchaToken'] ?? null;
        if (! $recaptchaToken) {
            wp_send_json_error(['message' => 'Token reCAPTCHA mancante.']);
            return;
        }

        $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret'   => my_env('RECAPTCHA_SECRET_KEY'),
                'response' => $recaptchaToken,
            ],
        ]);

        if (is_wp_error($response)) {
            wp_send_json_error(['message' => 'Errore di comunicazione con reCAPTCHA.']);
            return;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (! $body['success'] || $body['score'] < 0.5) {
            wp_send_json_error(['message' => 'Verifica reCAPTCHA fallita.']);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        $paymentMethodId = $data['paymentMethodId'];
        $amount          = $data['amount'];
        $email           = $data['email'] ?? null;

        if (! $email || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            wp_send_json_error(['message' => 'Indirizzo email non valido.']);
            return;
        }

        Stripe::setApiKey(my_env('SECRET_KEY'));

        try {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount'              => $amount * 100,
                'currency'            => 'eur',
                'payment_method'      => ['card', 'paypal'],
                'confirmation_method' => 'manual',
                'confirm'             => true,
                'return_url'          => 'https://project-africa-conservation.org',
            ]);

            if ($paymentIntent->status === 'succeeded') {
                if (isset($data['email'])) {
                    $progetto_id  = $data['progettoId'] ?? null;
                    $progetto     = Progetto::find($progetto_id);
                    $progettoName = $progetto ? "Donazione per il progetto: " . htmlspecialchars($progetto->title, ENT_QUOTES, 'UTF-8') : "Donazione generica";

                    GrazieEmail::sendThankYouEmail($email, $progettoName, $amount);
                }
                if (! email_exists($email)) {
                    self::createUser($data);
                }

                wp_send_json_success([
                    'success'  => true,
                    'redirect' => 'progetto/',
                    'message'  => 'Ordine completato con successo.',
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
        $email = $data['email'];

        $user_id = email_exists($email);
        if (! $user_id) {
            $user_id = wp_insert_user([
                'user_login' => $email,
                'user_pass'  => wp_generate_password(),
                'user_email' => $email,
                'first_name' => $data['name'],
                'last_name'  => $data['surname'],
                'role'       => 'donator',
            ]);
        }

        // Aggiorna i meta sempre, anche se l'utente già esiste
        update_user_meta($user_id, 'telefono', $data['phone'] ?? '');
        update_user_meta($user_id, 'codice_fiscale', $data['codiceFiscale'] ?? '');
        update_user_meta($user_id, 'importo_donato', $data['amount']);
        update_user_meta($user_id, 'title', $data['progettoId']);
        update_user_meta($user_id, 'name', $data['name']);
    }

}
