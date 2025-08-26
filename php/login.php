<?php
    session_start();

    include_once("playerDB.php");

    $email = $_POST['txtEmail'];
    $passwd = $_POST['txtSenha'];

    $player = getPlayerByEmail($email);

    if ($player && password_verify($passwd, $player['senha_hash'])) {
        
        $_SESSION['user_id'] = $player['id_player'];
        $_SESSION['user_name'] = $player['nome'];
        $_SESSION['loggedin'] = true; 
        
        header('Location: dashboard.php');
        exit(); 
    } else {
        header('Location: ../index.html?error=1');
        exit(); 
    }
?>