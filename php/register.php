<?php
    include_once("registerDB.php");
    include_once("generateHash.php");
    $nome = $_POST['txtNome'];
    $email = $_POST['txtEmail'];
    $passwd = $_POST['txtSenha'];
    $hash = generateHash($passwd);
    $estado = registerPlayer($nome, $email, $hash);
    if($estado){
        header('Location:../index.html');
    } else{
        header('Location:../index.html?error=1'); 
    }
?>
