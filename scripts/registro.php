<?php defined('CONTROL') or die('Acesso negado!');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$erro = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>

<h2>Registrar</h2>
<form method="post" action="?rota=submit_registro">
    Nome: <br>
    <input type="text" name="nome" required><br><br>
    Email (usuário): <br>
    <input type="email" name="usuario" required><br><br>
    Senha (mínimo 6 caracteres): <br>
    <input type="password" name="senha" minlength="6" required><br><br>
    <button type="submit">Registrar</button>
</form>
<?php if ($erro): ?>
    <p style="color: red;"><?= $erro ?></p>
<?php endif; ?>
