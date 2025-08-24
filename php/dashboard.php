<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.html');
        exit();
    }
    $userName = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1> Bem vindo(a), <?php echo htmlspecialchars($userName); ?>!</h1>
    
    <p>O seu ID de utilizador é: <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>

    <a href="logout.php">Terminar Sessão</a>
</body>
</html>