<?php
// apok/php/send_email_async.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Verifique se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['token'])) {
    $email = $_POST['email'];
    $token = $_POST['token'];

    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host       = 'smtp-relay.brevo.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = '957944003@smtp-brevo.com'; 
        $mail->Password   = 'nUcwqAbyPpt27MhQ';    
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet = PHPMailer::CHARSET_UTF8;

        // Remetente e destinatário
        $mail->setFrom('noreply@capybyte.site', 'CapyByte');
        $mail->addAddress($email);

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Redefinição de Senha';
        $mail->Body    = "
            <p>Olá,</p>
            <p>Recebemos um pedido de redefinição de senha.</p>
            <p>Clique no link abaixo para redefinir sua senha:</p>
            <a href=\"https://apok.up.railway.app/reset.php?token={$token}\">Redefinir Senha</a>
        ";

        $mail->send();
    } catch (Exception $e) {
        // Envia o erro para o log do servidor, já que não haverá um redirecionamento
        error_log("Erro assíncrono ao enviar e-mail para {$email}: {$mail->ErrorInfo}");
    }
}
?>