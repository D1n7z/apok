<?php
    include_once("connection.php");

    function getID($email){
        $conn = connectDB();
        $sql = "SELECT id_player FROM player WHERE email = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $id = $stmt->fetchColumn();
        closeDB($conn);
        return $id;
    }

    function storeToken($id_player, $token_hash){ 
        if(!$id_player || !$token_hash) return false;

        $conn = connectDB();
        $expires_at = date('Y-m-d H:i:s', time() + 3600);
        $delete_stmt = $conn->prepare("DELETE FROM tokens WHERE id_player = ?");
        $delete_stmt->execute([$id_player]);

        $sql = "INSERT INTO tokens(id_player, expires_at, token_hash) VALUES (?, ?, ?);";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_player, $expires_at, $token_hash]);

        closeDB($conn);
        return true;
    }

    function processForgotPassword(){
        $email = $_POST['txtEmail'];
        $id_player = getID($email);
        
        if (!$id_player) {
            return false;
        }

        $token = bin2hex(random_bytes(32)); 

        $token_hash = hash('sha256', $token);

        if(storeToken($id_player, $token_hash)){
            return [$email, $token]; 
        } else{
            header('HTTP/1.1 500 Internal Server Error');
            exit();
        }
    }
?>