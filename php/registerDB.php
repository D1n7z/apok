<?php
    include_once("connection.php");
    function registerPlayer($nome, $email, $passwd){ 
        $conn = connectDB();
        try {
            $sql = "INSERT INTO player(nome, senha_hash, email) VALUES (?, ?, ?);";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nome, $passwd, $email]);
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao registrar usuário: " . $e->getMessage());
            return false;
        } finally {
            closeDB($conn);
        }
    }
?>
