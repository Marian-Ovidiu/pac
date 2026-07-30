<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grazie per la tua donazione</title>
</head>
<body style="margin:0;padding:24px;background:#f5f1e8;color:#122018;font-family:Arial,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:600px;background:#ffffff;border-radius:16px;padding:32px;">
                    <tr>
                        <td>
                            <h1 style="margin:0 0 20px;color:#2f4a2d;font-size:28px;">Grazie per la tua donazione!</h1>
                            <p style="margin:0 0 16px;line-height:1.6;">Il tuo sostegno al progetto <strong><?php echo $safeProgetto; ?></strong> è stato registrato.</p>
                            <p style="margin:0 0 16px;line-height:1.6;">Importo donato: <strong><?php echo $safeAmount; ?> EUR</strong></p>
                            <p style="margin:0;line-height:1.6;">Grazie per essere parte di Project Africa Conservation.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
