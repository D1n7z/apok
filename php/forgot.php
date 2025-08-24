<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
include_once("generateToken.php");

$retorno = processForgotPassword();

if ($retorno) {
    $email = $retorno[0];
    $token = $retorno[1];

    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
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
        header('Location: ../index.html?success=1');
            } catch (Exception $e) {
                error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
                header('Location: ../index.html?error=1');
            }
        } else {
            error_log("Erro ao processar a solicitacao de recuperacao de senha.");
            header('Location: ../index.html?error=1');
        }
?>