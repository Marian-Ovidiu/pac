<?php

namespace Classes;

class GrazieEmail
{
    public static function sendThankYouEmail($email, $progettoName, $amount)
    {
        $subject = 'Grazie per la tua donazione!';
        $message = "
        <html>
        <body>
            <h1 style='color: #4CAF50;'>Grazie per la tua donazione!</h1>
            <p>Hai donato <strong>{$amount}€</strong> per il progetto <strong>{$progettoName}</strong>.</p>
            <p>Il tuo contributo è fondamentale per supportare i nostri progetti. Se hai domande, non esitare a contattarci.</p>
            <br>
            <p>Cordiali saluti,<br>Il nostro team</p>
        </body>
        </html>
    ";

        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: Project Africa Conservation <info@project-africa-conservation.org>',
            'Reply-To: info@project-africa-conservation.org',
            'X-Mailer: WP Mail SMTP/GrazieEmail',
        ];

        if (!wp_mail($email, $subject, $message, $headers)) {
            error_log("Errore durante l'invio dell'email a {$email}");
            return false;
        }

        return true;
    }

}