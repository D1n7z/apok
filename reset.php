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
