<?php
defined('CONTROL') or die('Acesso Negado!');
$erro = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
$sucesso = $_SESSION['success'] ?? null;
unset($_SESSION['success']);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="css/redefinirsenha.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=mail" />
    <link rel="shortcut icon" href="assets/icone.png" type="image/x-icon">

</head>

<body>
    <main class="container">
        <form action="?rota=submit_redefinir_senha" method='post' id="form-redefinir">
            <h1>Redefinir Senha</h1>
            <div class="input-box">
                <input type="email" name="usuario" id="usuario" placeholder="Digite seu e-mail" required>
                <i class="bx bxs-user"></i>
            </div>
            <button type="submit" class="login-btn">Enviar Código</button>
            <div class="voltar-link">
                <p><a href="?rota=login">Voltar</a> </p>
            </div>
            <?php if (!empty($erro)) : ?>
                <p id="erro">Erro: <?= htmlspecialchars($erro) ?></p>
            <?php endif; ?>
        </form>
    </main>

   <div id="modal-sucesso" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('modal-sucesso').style.display='none'">&times;</span>
        <p id="modal-mensagem"></p>
    </div>
</div>
    <script src="scripts/js_scripts/redefinir_senha.js"></script>
</body>

</html>