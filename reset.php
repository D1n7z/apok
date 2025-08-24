<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';

    // Conecte ao banco de dados
    include_once("php/connection.php");
    $conn = connectDB();

    // Verifique se o token existe e está válido
    $stmt = $conn->prepare("SELECT email FROM reset_tokens WHERE token = :token AND expires_at > NOW()");
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Token válido, prossiga com a redefinição de senha
        // Atualize a senha do usuário e remova/invalide o token
    } else {
        echo "Token inválido ou expirado.";
    }
}
?>

<html>
    <head>
        <title>Redefinir Senha</title>
    </head>
    <body>
        <h1>Redefinir Senha</h1>
        <form action="reset.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($hash_token); ?>">
            <label for="new_password">Nova Senha:</label>
            <input type="password" name="new_password" required>
            <button type="submit">Redefinir Senha</button>
        </form>
    </body>
</html>
