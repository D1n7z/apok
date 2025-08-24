<?php
// check_smtp.php

$host = 'smtp-relay.brevo.com';
$port = 587;
$timeout = 15; // Segundos

echo "<h1>Teste de Conexão SMTP</h1>";
echo "<p>Tentando conectar a <strong>{$host}</strong> na porta <strong>{$port}</strong>...</p>";

// O '@' suprime o warning padrão para que possamos lidar com o erro de forma mais limpa.
$socket = @fsockopen($host, $port, $errno, $errstr, $timeout);

if ($socket) {
    echo "<p style='color:green; font-weight:bold;'>Sucesso! A conexão com o servidor SMTP foi estabelecida.</p>";
    echo "<p>Isso significa que a Railway NÃO está bloqueando a porta {$port}. O problema de timeout provavelmente está nas suas credenciais, no código do PHPMailer ou no próprio serviço da Brevo.</p>";
    // Fecha a conexão
    fclose($socket);
} else {
    echo "<p style='color:red; font-weight:bold;'>Falha na Conexão!</p>";
    echo "<p>Não foi possível conectar ao servidor SMTP. A Railway pode estar bloqueando a conexão ou o servidor de destino está offline.</p>";
    echo "<ul>";
    echo "<li><strong>Código do Erro:</strong> " . htmlspecialchars($errno) . "</li>";
    echo "<li><strong>Mensagem do Erro:</strong> " . htmlspecialchars($errstr) . "</li>";
    echo "</ul>";
}
?>