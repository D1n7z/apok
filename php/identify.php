<?php
header("Content-Type: application/json");
include_once("connection.php");

$email = $_POST['email'] ?? '';

if (empty($email)) {
    echo json_encode(["exists" => false, "error" => "E-mail não informado."]);
    exit;
}

try {
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT 1 FROM player WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $exists = $stmt->fetchColumn() ? true : false;
    echo json_encode(["exists" => $exists]);
} catch (PDOException $e) {
    echo json_encode(["exists" => false, "error" => $e->getMessage()]);
} finally {
    if ($conn) {
        closeDB($conn);
    }
}
?>