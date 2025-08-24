<?php
// apok/php/send_email_async.php

// Usaremos as classes do PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclui o autoloader do Composer (agora para o PHPMailer)
require_once __DIR__ . '/../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['token'])) {
    $email = $_POST['email'];
    $token = $_POST['token'];

    $mail = new PHPMailer(true);

    try {
        // Configurações do Servidor SMTP
        // $mail->SMTPDebug = 2; // Descomente esta linha para ver logs detalhados do envio
        $mail->isSMTP();
        $mail->Host       = 'smtp-relay.brevo.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = '957944005@smtp-brevo.com'; // O seu login SMTP
        $mail->Password   = '6aENZSGpfns8Jk9I';           // A sua senha SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // Remetente e Destinatário
        $mail->setFrom('noreply@capybyte.site', 'CapyByte');
        $mail->addAddress($email); // Adiciona o destinatário

        // Conteúdo do E-mail
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
        error_log("E-mail para {$email} enviado com sucesso via SMTP Brevo.");
    } catch (Exception $e) {
        // Envia o erro detalhado do PHPMailer para o log do servidor
        error_log("Erro ao enviar e-mail para {$email} via SMTP Brevo: " . $mail->ErrorInfo);
    }
}
?>