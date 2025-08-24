<?php
// apok/php/send_email_async.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['token'])) {
    $email = $_POST['email'];
    $token = $_POST['token'];

    $mail = new PHPMailer(true);

    try {
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
        $resetLink = "https://capybyte.site/reset.php?token=" . urlencode($token);
        $mail->Body    = "
            <html><body>
            <p>Olá,</p>
            <p>Recebemos um pedido de redefinição de senha.</p>
            <p>Clique no link abaixo para redefinir sua senha:</p>
            <a href=\"{$resetLink}\">Redefinir Senha</a>
            </body></html>
        ";
        $mail->AltBody = "Para redefinir sua senha, copie e cole este link no seu navegador: " . $resetLink;

        $mail->send();
        error_log("E-mail para {$email} enviado via SMTP 587.");

    } catch (Exception $e) {
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
            $resetLink = "https://capybyte.site/reset.php?token=" . urlencode($token);
            $mail->Body    = "
                <html><body>
                <p>Olá,</p>
                <p>Recebemos um pedido de redefinição de senha.</p>
                <p>Clique no link abaixo para redefinir sua senha:</p>
                <a href=\"{$resetLink}\">Redefinir Senha</a>
                </body></html>
            ";
            $mail->AltBody = "Para redefinir sua senha, copie e cole este link no seu navegador: " . $resetLink;

            $mail->send();
            error_log("E-mail para {$email} enviado via SMTP 465.");

        } catch (Exception $e_fallback) {
            error_log("Falha porta 465: " . $mail->ErrorInfo);
        }
    }
}
?>