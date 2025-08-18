<?php
    include_once("playerDB.php");
    include_once("generateHash.php");
    $email = $_POST['txtEmail'];
    $passwd = $_POST['txtSenha'];

    $player = getPlayerByEmail($email);

    if ($player && password_verify($passwd, $player['senha_hash'])) {
        header('Location:dashboard.php?id='.$player['id_player']);
    } else {
        header('Location:../index.html?error=1');
    }
?>
