<?php

declare(strict_types=1);

namespace Pac\Core;

final class ThankYouMailer
{
    public static function send(string $email, string $projectName, float $amount): bool
    {
        if (!is_email($email)) {
            error_log('[PAC Core] Email di ringraziamento non inviata: indirizzo non valido.');
            return false;
        }

        $subject = 'Grazie per la tua donazione!';
        $message = self::render($projectName, $amount);
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: Project Africa Conservation <info@project-africa-conservation.org>',
            'Reply-To: info@project-africa-conservation.org',
        ];
        $sent = wp_mail($email, $subject, $message, $headers);
        error_log($sent
            ? '[PAC Core] Email di ringraziamento accettata dal mailer.'
            : '[PAC Core] Email di ringraziamento rifiutata dal mailer.');

        return $sent;
    }

    public static function render(string $projectName, float $amount): string
    {
        $safeAmount = number_format($amount, 2, ',', '');
        $safeProject = htmlspecialchars(strip_tags($projectName), ENT_QUOTES, 'UTF-8');

        ob_start();
        include dirname(__DIR__) . '/templates/thank-you-email.php';

        return (string) ob_get_clean();
    }
}
