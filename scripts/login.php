<?php
defined('CONTROL') or die('Acesso Negado!');

$erro = $_SESSION['error'] ?? null;
unset($_SESSION['error']);

//require_once __DIR__ . '/../inc/menu.php';
?>

<h2>Login</h2>

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
