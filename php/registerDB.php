<?php
    include_once("connection.php");
    function loginPlayer($nome, $email, $passwd){
        // Conecta no Banco
        $conn = connectDB();

        // Prepare o SQL
        $sql = "INSERT INTO player(nome, senha_hash, email) VALUES (?, ?, ?);";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nome, $passwd, $email]);

        // Fecha o Banco
        closeDB($conn);

        return true;
    }
?>
