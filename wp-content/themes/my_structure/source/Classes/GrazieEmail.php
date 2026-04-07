<?php

namespace Classes;

class GrazieEmail
{
    public static function sendThankYouEmail($email, $progettoName, $amount)
    {
        if (!is_email($email)) {
            error_log("[GrazieEmail] Email non valida: {$email}");
            return false;
        }

        $subject = 'Grazie per la tua donazione!';
        $safeAmount = number_format((float) $amount, 2, ',', '');
        $safeProgetto = strip_tags($progettoName);

        ob_start();
        include __DIR__ . '/templates/grazie-email-template.php';
        $message = ob_get_clean();

        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: Project Africa Conservation <info@project-africa-conservation.org>',
            'Reply-To: info@project-africa-conservation.org',
        ];

        error_log("[GrazieEmail] Invio email a {$email} con subject: {$subject}");
        $sent = wp_mail($email, $subject, $message, $headers);

        if (!$sent) {
            error_log("[GrazieEmail] Errore invio email a {$email}");
        } else {
            error_log("[GrazieEmail] Email inviata a {$email}");
        }

        return $sent;
    }
}
