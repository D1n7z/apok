<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

require_once __DIR__ . '/../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['token'])) {
    $email = $_POST['email'];
    $token = $_POST['token'];
    $resetLink = "https://capybyte.site/reset.php?token=" . urlencode($token);
    $emailSent = false;


    $emailBody = "
        <html><body>
        <p>Olá,</p>
        <p>Recebemos um pedido de redefinição de senha.</p>
        <p>Clique no link abaixo para redefinir sua senha:</p>
        <a href=\"{$resetLink}\">Redefinir Senha</a>
        </body></html>
    ";
    $altBody = "Para redefinir sua senha, copie e cole este link no seu navegador: " . $resetLink;

    // Tenta enviar via PHPMailer (SMTP)
    try {
        $mail = new PHPMailer(true);
        // Tenta enviar pela porta 587
        $mail->isSMTP();
        $mail->Host       = 'smtp-relay.brevo.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = '957944005@smtp-brevo.com';
        $mail->Password   = '6aENZSGpfns8Jk9I';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('noreply@capybyte.site', 'CapyByte');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Redefinição de Senha';
        $mail->Body    = $emailBody;
        $mail->AltBody = $altBody;

        $mail->send();
        error_log("E-mail para {$email} enviado via SMTP 587.");
        $emailSent = true;

    } catch (PHPMailerException $e) {
        error_log("Falha porta 587: " . $mail->ErrorInfo);
        error_log("Tentando porta 465...");

        // Tenta enviar pela porta 465
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp-relay.brevo.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = '957944005@smtp-brevo.com';
            $mail->Password   = '6aENZSGpfns8Jk9I';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('noreply@capybyte.site', 'CapyByte');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Redefinição de Senha';
            $mail->Body    = $emailBody;
            $mail->AltBody = $altBody;

            $mail->send();
            error_log("E-mail para {$email} enviado via SMTP 465.");
            $emailSent = true;

        } catch (PHPMailerException $e_fallback) {
            error_log("Falha porta 465: " . $mail->ErrorInfo);
        
        }
    }

    // Se o e-mail não foi enviado via SMTP, tenta via API
    if (!$emailSent) {
        error_log("SMTP falhou. Tentando enviar via API da Brevo...");

        $apiKey = getenv('BREVO_API_KEY');
        if (!$apiKey) {
            error_log("Chave da API da Brevo (BREVO_API_KEY) não configurada nas variáveis de ambiente.");
            exit();
        }

        $config = Brevo\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);
        $apiInstance = new Brevo\Client\Api\TransactionalEmailsApi(new GuzzleHttp\Client(), $config);
        $sendSmtpEmail = new \Brevo\Client\Model\SendSmtpEmail([
            'subject' => 'Redefinição de Senha',
            'sender' => ['name' => 'CapyByte', 'email' => 'noreply@capybyte.site'],
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