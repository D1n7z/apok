<?php
header("Content-Type: application/json");

include_once("connection.php");

try{
    $conn = connectDB();
    $email = $_POST['email'];

    if($email) {
        $stmt = $conn->prepare("SELECT 1 FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $response = ["exists" => $stmt->fetchColumn() ? true : false];
    } else{
        $response = ["exists" => false];
    }

    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    if ($conn) {
        closeDB($conn);
    }
}