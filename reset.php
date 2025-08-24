<?php
// apok/reset.php
include_once("php/connection.php");
include_once("php/generateHash.php");

$token_from_url = $_GET['token'] ?? '';
$message = '';

// Verifica se uma nova senha foi enviada
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token_from_post = $_POST['token'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';

    if (empty($token_from_post) || empty($newPassword)) {
        $message = "Por favor, preencha todos os campos.";
    } else {
        $conn = connectDB();

        try {
            // Procura o token_hash no banco de dados
            $stmt = $conn->prepare("SELECT id_player, expires_at FROM tokens WHERE token_hash = :token_hash AND expires_at > NOW()");
            $stmt->bindParam(':token_hash', $token_from_post, PDO::PARAM_STR);
            $stmt->execute();
            $token_data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($token_data) {
                // Token é válido e não expirou, prossegue com a redefinição de senha
                $id_player = $token_data['id_player'];
                $hashed_password = generateHash($newPassword);

                // Atualiza a senha do jogador
                $stmt = $conn->prepare("UPDATE player SET senha_hash = :senha_hash WHERE id_player = :id_player");
                $stmt->bindParam(':senha_hash', $hashed_password, PDO::PARAM_STR);
                $stmt->bindParam(':id_player', $id_player, PDO::PARAM_INT);
                $stmt->execute();

                // Exclui o token utilizado
                $stmt = $conn->prepare("DELETE FROM tokens WHERE token_hash = :token_hash");
                $stmt->bindParam(':token_hash', $token_from_post, PDO::PARAM_STR);
                $stmt->execute();

                $message = "Sua senha foi redefinida com sucesso.";
            } else {
                $message = "Token inválido ou expirado.";
            }
        } catch (PDOException $e) {
            error_log("Erro de redefinição de senha: " . $e->getMessage());
            $message = "Ocorreu um erro ao redefinir a senha.";
        } finally {
            if ($conn) {
                closeDB($conn);
            }
        }
    }
}

if (strlen($newPassword) < 6) {
    $message = "A senha deve ter pelo menos 6 caracteres.";
}
?>

<html>
    <head>
        <title>Redefinir Senha</title>
    </head>
    <body>
        <h1>Redefinir Senha</h1>
        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php else: ?>
            <form action="reset.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token_from_url); ?>">
                <label for="new_password">Nova Senha:</label>
                <input type="password" name="new_password" required>
                <button type="submit">Redefinir Senha</button>
            </form>
        <?php endif; ?>
    </body>
</html>