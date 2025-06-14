<?php
defined('CONTROL') or die('Acesso Negado!');
$erro = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
$codigo = $_SESSION['codigo_recuperacao'] ?? null;

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <title>Verificar Código</title>
</head>

<body>

    <main class="container">
        <form action="?rota=submit_verificar_codigo" method='post' id="form-redefinir">
            <h1>Insira seu código de recuperação</h1>
            <div class="input-box">
                <input type="text" name="codigo" id="codigo" placeholder="Código de recuperação">
            </div>
            <button type="submit" class="login-btn">Verificar</button>
            <div class="voltar-link">
                <p><a href="?rota=redefinir_senha">Voltar</a> </p>
            </div>
        </form>
        <?php if (!empty($erro)) : ?>
            <p id="erro"><?= $erro ?></p>
        <?php endif; ?>
    </main>

    <div id="modal-sucesso" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('modal-sucesso').style.display='none'">&times;</span>
            <p id="modal-mensagem"></p>
        </div>
    </div>
    
    <script src="scripts/js_scripts/validar_codigo.js"></script>
</body>

</html>