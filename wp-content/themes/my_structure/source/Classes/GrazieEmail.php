<?php

namespace Classes;

class GrazieEmail
{
    public static function sendThankYouEmail($email, $progettoName, $amount)
    {
        if (!is_email($email)) {
            error_log('[GrazieEmail] Indirizzo email non valido.');
            return false;
        }

        $subject = 'Grazie per la tua donazione!';
        $message = self::renderThankYouMessage($progettoName, $amount);

        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: Project Africa Conservation <info@project-africa-conservation.org>',
            'Reply-To: info@project-africa-conservation.org',
        ];

        error_log('[GrazieEmail] Invio email di ringraziamento.');
        $sent = wp_mail($email, $subject, $message, $headers);

        if (!$sent) {
            error_log('[GrazieEmail] Errore invio email di ringraziamento.');
        } else {
            error_log('[GrazieEmail] Email di ringraziamento inviata.');
        }

        return $sent;
    }

    public static function renderThankYouMessage($progettoName, $amount): string
    {
        $safeAmount = number_format((float) $amount, 2, ',', '');
        $safeProgetto = htmlspecialchars(strip_tags((string) $progettoName), ENT_QUOTES, 'UTF-8');

        ob_start();
        include __DIR__ . '/templates/grazie-email-template.php';

        return (string) ob_get_clean();
    }
}
