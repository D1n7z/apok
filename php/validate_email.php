<?php
header("Content-Type: application/json");

include_once("connection.php");

try{
    $conn = connectDB();
    $email = $_POST['email'] ?? null;
    error_log("Email recebido: " . $email);

    if($email) {

        if ($admin_email && $email === $admin_email) {
        echo json_encode(["exists" => true]);
        exit();
    }

        $stmt = $conn->prepare("SELECT 1 FROM player WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $response = ["exists" => $stmt->fetchColumn() ? true : false];
    } else{
        $response = ["exists" => false];
    }

    echo json_encode($response);
} catch (PDOException $e) {
    error_log("Erro no validate_email.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Não foi possível verificar o e-mail."]);
} finally {
    if ($conn) {
        closeDB($conn);
    }
}