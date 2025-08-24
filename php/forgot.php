<?php
// apok/php/forgot.php
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

    // Prepara os dados para enviar para o script de envio de e-mail em segundo plano
    $postData = http_build_query([
        'email' => $email,
        'token' => $token
    ]);

    // Comando cURL para chamar o script em segundo plano
    $command = 'curl -X POST -d "' . $postData . '" ' .
               'https://capybyte.site/php/send_email_async.php' .
               ' > /dev/null 2>&1 &';

    // Executa o comando de forma assíncrona
    exec($command);

    // Redireciona o utilizador imediatamente
    header('Location: ../index.html?success=1');
} else {
    error_log("Erro ao processar a solicitacao de recuperacao de senha.");
    header('Location: ../index.html?error=1');
}

?>