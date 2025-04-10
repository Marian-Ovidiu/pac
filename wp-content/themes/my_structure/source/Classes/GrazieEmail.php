<?php
namespace Classes;

class GrazieEmail
{
    public static function sendThankYouEmail($email, $progettoName, $amount)
    {
        $subject = 'Grazie per la tua donazione!';

        $message = '
        <html>
            <body>
                <h1 style="color: #4CAF50;">Grazie per la tua donazione!</h1>
                <p>Hai donato <strong>' . esc_html($amount) . '€</strong> per il progetto <strong>' . esc_html($progettoName) . '</strong>.</p>
                <p>Il tuo contributo è fondamentale per supportare i nostri progetti. Se hai domande, non esitare a contattarci.</p>
                <br>
                <p>Cordiali saluti,<br>Il nostro team</p>
            </body>
        </html>';

        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: Project Africa Conservation <info@project-africa-conservation.org>',
            'Reply-To: info@project-africa-conservation.org',
        ];

        $sent = wp_mail($email, $subject, $message, $headers);

        if (!$sent) {
            error_log("❌ Errore invio email a {$email}");
        } else {
            error_log("✅ Email inviata a {$email}");
        }

        return $sent;
    }
}
