<?php
defined('CONTROL') or die('Acesso Negado!');

$erro = $_SESSION['error'] ?? null;
unset($_SESSION['error']);

//require_once __DIR__ . '/../inc/menu.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    
<h2>Login</h2>

<div>
    <form action="index.php?rota=submit_login" method="post">
        <label>Email:</label><br>
        <input type="email" name="usuario" required><br><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" required><br><br>

        <?php if ($erro) : ?>
            <p style="color:red;"><?= $erro ?></p>
        <?php endif; ?>

        <button type="submit">Entrar</button>
    </form>

    <p>Visitante? clique <a href="?rota=ver_posts">aqui</a>.</p>
</div>
</body>
</html>