<?php
    session_start();

    
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
        header('Location: ../index.html');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Bem-vindo ao Painel de Administração, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <p>Aqui você pode gerenciar as funcionalidades do site.</p>
    <a href="logout.php">Terminar Sessão</a>
</body>
</html>