<?php
// apok/php/forgot.php

// 1. ADICIONA A VALIDAÇÃO NO LADO DO SERVIDOR
// Verifica se os campos de e-mail e confirmação de e-mail são idênticos.
if (!isset($_POST['txtEmail'], $_POST['txtEmailConfirm']) || $_POST['txtEmail'] !== $_POST['txtEmailConfirm']) {
    // Se não coincidirem, redireciona de volta para o formulário com uma mensagem de erro.
    // O ideal seria usar sessões (flash messages), mas um parâmetro de URL também funciona.
    header('Location: ../identify.html?error=email_mismatch');
    exit(); // É crucial parar a execução do script após um redirecionamento.
}

// Este ficheiro apenas gera o token e chama o script de envio
include_once("generateToken.php");

$retorno = processForgotPassword();

if ($retorno) {
    $email = $retorno[0];
    $token = $retorno[1];

    // Prepara os dados para enviar para o script de envio em segundo plano
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
    exit(); // Adiciona exit() após o redirecionamento
} else {
    // 2. MODIFICA O COMPORTAMENTO EM CASO DE FALHA
    // Se o e-mail não for encontrado, o script retorna `false`.
    // Redirecionamos para a mesma página de sucesso para não informar a um atacante se o e-mail existe.
    error_log("Tentativa de recuperação para o e-mail (possivelmente inexistente): " . $_POST['txtEmail']);
    header('Location: ../index.html?success=1'); // Redireciona para o sucesso em ambos os casos
    exit(); // Adiciona exit() após o redirecionamento
}

?>