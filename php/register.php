// apok/php/register.php

<?php
    include_once("registerDB.php");
    include_once("generateHash.php");


    $nome = $_POST['txtNome'];
    $email = $_POST['txtEmail'];
    $emailConfirm = $_POST['txtEmailConfirm'];
    $passwd = $_POST['txtSenha'];
    $passwdConfirm = $_POST['txtSenhaConfirm'];

    if ($email !== $emailConfirm) {
        // Redireciona com um erro específico se os e-mails não baterem
        header('Location:../cadastro.html?error=email_mismatch');
        exit(); 
    }

    if ($passwd !== $passwdConfirm) {
        // Redireciona com um erro específico se as senhas não baterem
        header('Location:../cadastro.html?error=password_mismatch');
        exit();
    }


    $hash = generateHash($passwd);
    $estado = registerPlayer($nome, $email, $hash); 

    if($estado){
        header('Location:../index.html');
    } else{
        header('Location:../cadastro.html?error=db_error'); 
    }
?>