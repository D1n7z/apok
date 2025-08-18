<?php
    include_once("connection.php");
    function loginPlayer($email, $passwd){
        // Conecta no Banco
        $conn = connectDB();

        // Prepare o SQL
        $sql = "SELECT * FROM player WHERE email = ? AND senha_hash = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email, $passwd]);

        // Obtém os resultados
        $player = $stmt->fetch(PDO::FETCH_ASSOC);
        $player_id = $player ? $player['id_player'] : null;

        // Fecha o Banco
        closeDB($conn);

        return $player_id;
    }

    function getPlayerByEmail($email){
        // Conecta no Banco
        $conn = connectDB();

        // Prepare o SQL
        $sql = "SELECT * FROM player WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);

        // Obtém os resultados
        $player = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fecha o Banco
        closeDB($conn);

        return $player;
    }
?>
