<?php
// apok/php/send_email_async.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use Brevo\Client;
use GuzzleHttp\Client as GuzzleClient;

require_once __DIR__ . '/../vendor/autoload.php';

set_time_limit(120);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['token'])) {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        error_log("Tentativa de envio para um e-mail inválido: " . $_POST['email']);
        exit();
    }
    
    $token = $_POST['token'];

    $platformName = 'CapyByte'; 
    $senderName = 'CapyByte';
    $senderEmail = 'noreply@capybyte.site';
    $resetLink = "https://capybyte.site/reset.php?token=" . urlencode($token);
    $subject = "Redefina sua senha da " . $platformName;
    $emailSent = false;
 
    $smtpUser = getenv('BREVO_SMTP_USER');
    $smtpPass = getenv('BREVO_SMTP_PASS');


    // --- CONSTRUÇÃO DO E-MAIL ---
    $emailBody = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Redefinição de Senha</title>
        <style>
            body { margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4; }
            .container { width: 100%; max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            .header { background-color: #007bff; color: #ffffff; padding: 20px; text-align: center; border-top-left-radius: 8px; border-top-right-radius: 8px; }
            .content { padding: 30px; color: #333333; line-height: 1.6; }
            .button-container { text-align: center; margin: 20px 0; }
            .button { background-color: #007bff; color: #ffffff !important; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; }
            .footer { padding: 20px; text-align: center; font-size: 12px; color: #777777; }
            .footer a { color: #007bff; text-decoration: none; }
        </style>
    </head>
    <body>
        <table class="container" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="header"><h1>Redefinição de Senha</h1></td>
            </tr>
            <tr>
                <td class="content">
                    <p>Olá,</p> <p>Recebemos um pedido para redefinir a senha associada a este endereço de e-mail. Se foi você quem solicitou, clique no botão abaixo para escolher uma nova senha.</p>
                    <div class="button-container">
                        <a href="' . htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8') . '" class="button">Criar Nova Senha</a>
                    </div>
                    <p>Este link expirará em <strong>1 hora</strong>. Se você não fez esta solicitação, por favor, ignore este e-mail.</p>
                    <p>Atenciosamente,<br>A Equipe <strong>' . $platformName . '</strong></p> </td>
            </tr>
            <tr>
                <td class="footer">
                    <p>Se o botão não funcionar, copie e cole o link abaixo no seu navegador:<br>
                    <a href="' . htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8') . '</a></p>
                    <p>&copy; ' . date("Y") . ' ' . $platformName . '. Todos os direitos reservados.</p> </td>
            </tr>
        </table>
    </body>
    </html>';

    $altBody = "
        Olá,\n\n
        Recebemos uma solicitação para redefinir a senha da sua conta em {$platformName}.\n\n
        Para criar uma nova senha, copie e cole o seguinte link no seu navegador:\n
        {$resetLink}\n\n
        Este link é válido por 60 minutos.\n\n
        Se você não solicitou esta alteração, pode ignorar este e-mail com segurança. Sua senha não será alterada.\n\n
        Atenciosamente,\n
        Equipe {$platformName}
    ";

    // Tenta enviar via PHPMailer (SMTP)
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp-relay.brevo.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser; 
        $mail->Password   = $smtpPass; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';
        $mail->Timeout    = 10;

        $mail->setFrom($senderEmail, $senderName);
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = $subject; 
        $mail->Body    = $emailBody;
        $mail->AltBody = $altBody;

        $mail->send();
        error_log("E-mail para {$email} enviado via SMTP 587.");
        $emailSent = true;

    } catch (PHPMailerException $e) {
        error_log("Falha porta 587: " . $e->getMessage());
        error_log("Tentando porta 465...");

        // Tenta porta 465 como fallback
        try {
            $mail = new PHPMailer(true); // Reinicia o objeto
            $mail->isSMTP();
            $mail->Host       = 'smtp-relay.brevo.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtpUser; 
            $mail->Password   = $smtpPass; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';
            $mail->Timeout    = 10;

            $mail->setFrom($senderEmail, $senderName);
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $subject; 
            $mail->Body    = $emailBody;
            $mail->AltBody = $altBody;

            $mail->send();
            error_log("E-mail para {$email} enviado via SMTP 465.");
            $emailSent = true;

        } catch (PHPMailerException $e_fallback) {
            error_log("Falha porta 465: " . $e_fallback->getMessage());
        }
    }

    // Se o SMTP falhou, tenta via API da Brevo
    if (!$emailSent) {
        error_log("SMTP falhou. Tentando enviar via API da Brevo...");

        $apiKey = getenv('BREVO_API_KEY');
        if (!$apiKey) {
            error_log("Chave da API da Brevo (BREVO_API_KEY) não configurada.");
            exit();
        }

        $config = Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);
        $apiInstance = new Client\Api\TransactionalEmailsApi(new GuzzleClient(), $config);
        $sendSmtpEmail = new \Brevo\Client\Model\SendSmtpEmail([
            'subject' => $subject, 
            'sender' => ['name' => $senderName, 'email' => $senderEmail],
            'to' => [['email' => $email]],
            'htmlContent' => $emailBody,
            'textContent' => $altBody
        ]);

        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            error_log("E-mail para {$email} enviado com sucesso via API da Brevo. Message ID: " . $result->getMessageId());
        } catch (Exception $e) {
            error_log("Falha ao enviar e-mail via API da Brevo: " . $e->getMessage());
        }
    }
}
?>