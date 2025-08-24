<?php
    include_once("connection.php");
    function generateToken(){
        $token = bin2hex(random_bytes(32));
        $hash_token = password_hash($token, PASSWORD_BCRYPT);
        return $hash_token;
    }

    function getID($email){
        $conn = connectDB();

        $sql = "SELECT id_player FROM player WHERE email = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $id = $stmt->fetchColumn();

        closeDB($conn);
        return $id;
    }

    function storeToken($id_player, $hash_token){
        if(!$id_player || !$hash_token) return false;

        $conn = connectDB();

        $expires_at = date('Y-m-d H:i:s', time() + 3600);
        $sql = "INSERT INTO tokens(id_player, expires_at, token_hash) VALUES (?, ?, ?);";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_player, $expires_at, $hash_token]);

        closeDB($conn);
        return true;
    }

    function processForgotPassword(){
        $email = $_POST['txtEmail'];
        $id_player = getID($email);
        $hash_token = generateToken();
        if(storeToken($id_player, $hash_token)){
            return [$email, $hash_token];
        } else{
            header('HTTP/1.1 500 Internal Server Error');
        }
    }
?>
