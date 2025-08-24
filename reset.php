<?php
// apok/reset.php
include_once("php/connection.php");
include_once("php/generateHash.php");

$token_from_url = $_GET['token'] ?? '';
$message = '';
$show_form = false; // Variável para controlar a exibição do formulário

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($token_from_url)) {
    // Gera hash do token
    $token_hash = hash('sha256', $token_from_url);

    $conn = connectDB();
    try {
        // Verifica hash no banco
        $stmt = $conn->prepare("SELECT id_player FROM tokens WHERE token_hash = :token_hash AND expires_at > NOW()");
        $stmt->bindParam(':token_hash', $token_hash, PDO::PARAM_STR);
        $stmt->execute();
        $token_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($token_data) {
            $show_form = true;
        } else {
            // Token inválido
            $message = "Token inválido ou expirado.";
        }
    } catch (PDOException $e) {
        error_log("Erro ao validar token: " . $e->getMessage());
        $message = "Erro interno.";
    } finally {
        if ($conn) {
            closeDB($conn);
        }
    }
}

// Verifica se uma nova senha foi enviada (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token_from_post = $_POST['token'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';

    if (strlen($newPassword) < 6) {
        $message = "A senha deve ter pelo menos 6 caracteres.";
        $show_form = true; // Mostra o formulário novamente para o usuário corrigir
        $token_from_url = $token_from_post; // Garante que o token continue disponível para o formulário
    } elseif (empty($token_from_post)) {
        $message = "Token inválido ou expirado.";
    } else {
        // Gera hash do token do formulário
        $token_hash_from_post = hash('sha256', $token_from_post);

        $conn = connectDB();
        try {
            // Busca token
            $stmt = $conn->prepare("SELECT id_player FROM tokens WHERE token_hash = :token_hash AND expires_at > NOW()");
            $stmt->bindParam(':token_hash', $token_hash_from_post, PDO::PARAM_STR);
            $stmt->execute();
            $token_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($token_data) {
                // Atualiza senha
                $id_player = $token_data['id_player'];
                $hashed_password = generateHash($newPassword); // Usa BCrypt para a nova senha

                $stmt = $conn->prepare("UPDATE player SET senha_hash = :senha_hash WHERE id_player = :id_player");
                $stmt->bindParam(':senha_hash', $hashed_password, PDO::PARAM_STR);
                $stmt->bindParam(':id_player', $id_player, PDO::PARAM_INT);
                $stmt->execute();

                // Remove token
                $stmt = $conn->prepare("DELETE FROM tokens WHERE token_hash = :token_hash");
                $stmt->bindParam(':token_hash', $token_hash_from_post, PDO::PARAM_STR);
                $stmt->execute();

                $message = "Senha redefinida.";
            } else {
                $message = "Token inválido ou expirado.";
            }
        } catch (PDOException $e) {
            error_log("Erro redefinir senha: " . $e->getMessage());
            $message = "Erro interno.";
        } finally {
            if ($conn) {
                closeDB($conn);
            }
        }
    }
}

// Se não houver token na URL (acesso direto), exibe mensagem de erro
if (empty($token_from_url) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $message = "Token não fornecido.";
}
?>

<html>
    <head>
        <title>Redefinir Senha</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <h1>Redefinir Senha</h1>
        
        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if ($show_form): ?>
            <form action="reset.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token_from_url); ?>">
                <label for="new_password">Nova Senha (mínimo 6 caracteres):</label>
                <input type="password" name="new_password" required minlength="6">
                <button type="submit">Redefinir Senha</button>
            </form>
        <?php endif; ?>
    </body>
</html>