<?php
// apok/php/send_email_async.php

// Inclui o autoloader do Composer para carregar as bibliotecas
require_once __DIR__ . '/../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['token'])) {
    $email = $_POST['email'];
    $token = $_POST['token'];
    
    // Configura a API Key da Brevo
    $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');
    
    $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(
        new GuzzleHttp\Client(),
        $config
    );

    $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();
    $sendSmtpEmail->setTo([new \SendinBlue\Client\Model\SendSmtpEmailTo(['email' => $email])]);
    $sendSmtpEmail->setSender(new \SendinBlue\Client\Model\SendSmtpEmailSender(['name' => 'CapyByte', 'email' => 'noreply@capybyte.site']));
    $sendSmtpEmail->setSubject('Redefinição de Senha');
    
    // Monta o link de redefinição
    $resetLink = "https://capybyte.site/reset.php?token=" . urlencode($token);
    
    // Corpo do e-mail em HTML
    $sendSmtpEmail->setHtmlContent("
        <html><body>
        <p>Olá,</p>
        <p>Recebemos um pedido de redefinição de senha.</p>
        <p>Clique no link abaixo para redefinir sua senha:</p>
        <a href=\"{$resetLink}\">Redefinir Senha</a>
        </body></html>
    ");

    try {
        $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
        error_log("E-mail para {$email} enviado com sucesso via API Brevo.");
    } catch (Exception $e) {
        error_log("Erro ao enviar e-mail para {$email} via API Brevo: " . $e->getMessage());
    }
}
?>