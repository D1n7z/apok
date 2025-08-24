<?php
// apok/php/forgot.php

// Valida se os e-mails são iguais
if (!isset($_POST['txtEmail'], $_POST['txtEmailConfirm']) || $_POST['txtEmail'] !== $_POST['txtEmailConfirm']) {
    header('Location: ../identify.html?error=email_mismatch');
    exit();
}

include_once("generateToken.php");

$retorno = processForgotPassword();

if ($retorno) {
    $email = $retorno[0];
    $token = $retorno[1];

    // Envia e-mail em segundo plano
    $postData = http_build_query([
        'email' => $email,
        'token' => $token
    ]);
    $command = 'curl -X POST -d "' . $postData . '" ' .
               'https://capybyte.site/php/send_email_async.php' .
               ' > /dev/null 2>&1 &';
    exec($command);

    header('Location: ../index.html?success=1');
    exit();
} else {
    // Sempre redireciona para sucesso
    error_log("Tentativa de recuperação para o e-mail: " . $_POST['txtEmail']);
    header('Location: ../index.html?success=1');
    exit();
}

?>