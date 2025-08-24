<?php
include_once("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['txtEmail'] ?? '';
    $emailConfirm = $_POST['txtEmailConfirm'] ?? '';

    // Verifica se os campos estão preenchidos e iguais
    if (empty($email) || empty($emailConfirm)) {
        echo json_encode(['success' => false, 'message' => 'Preencha ambos os campos de e-mail.']);
        exit;
    }

    if ($email !== $emailConfirm) {
        echo json_encode(['success' => false, 'message' => 'Os e-mails não coincidem.']);
        exit;
    }

    $conn = connectDB();
    try {
        // Verifica se o e-mail existe no banco de dados
        $stmt = $conn->prepare("SELECT id_player FROM player WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->fetchColumn()) {
            echo json_encode(['success' => true, 'message' => 'E-mail válido e encontrado.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'E-mail não encontrado.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao consultar o banco de dados.']);
    } finally {
        if ($conn) {
            closeDB($conn);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método inválido.']);
    }
?>