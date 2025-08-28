<?php
    session_start();

    include_once("playerDB.php");

    $email = $_POST['txtEmail'];
    $passwd = $_POST['txtSenha'];


    $admin_email = getenv('ADMIN_EMAIL');
    $admin_password = getenv('ADMIN_PASSWORD'); 

    if ($email === $admin_email && $passwd === $admin_password) {
        $_SESSION['user_id'] = '0'; 
        $_SESSION['user_name'] = 'Administrador';
        $_SESSION['loggedin'] = true;
        $_SESSION['is_admin'] = true;

        header('Location: admin_dashboard.php'); 
        exit();
    }



    $player = getPlayerByEmail($email);

    if ($player && password_verify($passwd, $player['senha_hash'])) {
        
        $_SESSION['user_id'] = $player['id_player'];
        $_SESSION['user_name'] = $player['nome'];
        $_SESSION['loggedin'] = true; 
        $_SESSION['is_admin'] = false; 
        
        header('Location: dashboard.php');
        exit(); 
    } else {
        header('Location: ../index.html?error=1');
        exit(); 
    }
?>