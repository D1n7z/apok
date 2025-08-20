<?php
    $email = $_POST['txtEmail'];

    $token = bin2hex(random_bytes(32));
    $hash_token = password_hash($token, PASSWORD_BCRYPT);
?>
