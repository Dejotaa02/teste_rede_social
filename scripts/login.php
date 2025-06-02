<?php
defined('CONTROL') or die('Acesso Negado!');

$erro = $_SESSION['error'] ?? null;
unset($_SESSION['error']);

//require_once __DIR__ . '/../inc/menu.php';
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serra do Japi</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=mail" />
    <link rel="shortcut icon" href="../assets/icone.png" type="image/x-icon">
</head>

<body>

    <main class="container">
        <form action="?rota=submit_login" method='post'>
            <h1>Login</h1>
            <div class="input-box">

                <input type="email" name="usuario" id="usuario" placeholder="Email">
                <i class="bx bxs-user"></i>
            </div>
            <div class="input-box">

                <input type="password" name="senha" id="senha" placeholder="Senha">
                <i class="bx bxs-lock-alt"></i>
            </div>

            <div class="remember-password">
               <a href="?rota=ver_posts" class="link">Entrar como visitante</a>
                <!-- Adicionar tela de nova senha -->
                <a href="?rota=redefinir_senha" class="link">Esqueci a minha senha</a>
            </div>

            <?php if (!empty($erro)) : ?>
                <p id="erro"><?= $erro ?></p>
            <?php endif; ?>

            <button type="submit" class="login-btn">Entrar</button>
            <div class="register-link">
                <p><a href="?rota=registro">Crie sua conta</a> </p>
            </div>
        </form>
           

    </main>

    <!-- <form action="index.php?rota=submit_login" method="post">
        <label>Email:</label><br>
        <input type="email" name="usuario" required><br><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" required><br><br>


        <button type="submit">Entrar</button>
    </form>

    <p>Visitante? clique <a href="?rota=ver_posts">aqui</a>.</p> -->

</body>

</html>