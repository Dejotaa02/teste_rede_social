<?php defined('CONTROL') or die('Acesso negado!');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$erro = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTRE-SE</title>
    <link rel="stylesheet" href="css/registro.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=mail" />
    <link rel="shortcut icon" href="assets/icone.png" type="image/x-icon">
</head>

<body>

    <main class="container">
        <form action="?rota=submit_registro" method='post'>
            <h1>Cadastro</h1>
            <div class="input-box">
                <input type="text" name="nome" id="nome" placeholder="Nome">
                <i class="bx bxs-user"></i>
            </div>

            <div class="input-box">
                <input type="email" name="usuario" id="usuario" placeholder="Email">
                <i class="bx bxs-envelope"></i>
            </div>

            <div class="input-box">
                <input type="password" name="senha" id="senha" placeholder="Senha">
                <i class="bx bxs-lock-alt"></i>
            </div>
            <div class="input-box">
                <div class="checkbox-field">
                    <label for="especialista-checkbox">Desejo criar uma conta como especialista</label>
                    <input type="checkbox" id="especialista" name="especialista-checkbox" class="check-especialista">
                </div>
            </div>

            <div class="input-box">
                <input type="url" id="lattes" placeholder="Insira seu currículo Lattes">
                 <i class="bx bxs-clipboard"></i>
            </div>

            </div>
            <?php if (!empty($erro)) : ?>
                <p id="erro"><?= $erro ?></p>
            <?php endif; ?>

            <button type="submit" class="login-btn">Registrar-se</button>
            <div class="voltar-link">
                <p><a href="?rota=login">Voltar</a> </p>
            </div>
        </form>
    </main>
    <!-- 
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
    <?php endif; ?> -->

    <script src="scripts/js_scripts/registro.js"></script>
</body>

</html>