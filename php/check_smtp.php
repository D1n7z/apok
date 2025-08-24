<?php
// apok/php/check_smtp.php

$host = 'smtp-relay.brevo.com';
$port = 587;
$timeout = 15; // Aumentei um pouco para ter certeza

echo "<h1>Teste de Conexão SMTP para Brevo</h1>";
echo "<p>Tentando conectar em <strong>{$host}</strong> na porta <strong>{$port}</strong>...</p>";
echo "<p>Aguarde até {$timeout} segundos...</p>";
flush(); // Garante que a mensagem acima seja enviada ao navegador

// O '@' suprime o warning para tratarmos o erro de forma limpa
$socket = @fsockopen($host, $port, $errno, $errstr, $timeout);

if ($socket) {
    echo "<h2 style='color:green;'>Sucesso! Conexão estabelecida.</h2>";
    echo "<p>Isso confirma que a Railway <strong>NÃO</strong> está bloqueando a porta {$port}.</p>";
    echo "<p>O problema de timeout deve ser outro, como uma configuração incorreta no PHPMailer ou uma lentidão específica da sua conta Brevo. Verifique se o seu domínio de envio ('noreply@capybyte.site') está corretamente configurado e verificado na Brevo.</p>";
    fclose($socket);
} else {
    echo "<h2 style='color:red;'>Falha na Conexão!</h2>";
    echo "<p>Não foi possível conectar ao servidor da Brevo.</p>";
    echo "<p>Isso indica fortemente que a plataforma Railway pode estar bloqueando conexões de saída na porta 587.</p>";
    echo "<strong>Detalhes do Erro:</strong>";
    echo "<ul>";
    echo "<li>Código: " . htmlspecialchars($errno) . "</li>";
    echo "<li>Mensagem: " . htmlspecialchars($errstr) . "</li>";
    echo "</ul>";
    echo "<p>O próximo passo seria contatar o suporte da Railway ou verificar a documentação deles sobre políticas de firewall para tráfego de saída.</p>";
}
?>