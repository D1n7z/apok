<?php
// Inicia a sessão para acessar as variáveis de login
session_start();

// Verifica se o usuário NÃO está logado. 
// Adapte 'loggedin' e 'true' para as variáveis de sessão que você usa no seu login.php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    header("location: index.html");
    exit;
}
?>