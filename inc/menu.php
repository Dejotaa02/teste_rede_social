<?php
if (!isset($_SESSION)) session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/menu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=mail" />
    <link rel="shortcut icon" href="assets/icone.png" type="image/x-icon">
</head>

<body>
    <header class="site-header">
        <nav class="main-menu">
            <div class="menu-toggle" id="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <ul class="menu-list" id="menu-list">
            <?php if (isset($_SESSION['usuario'])): ?>
                    <span>Olá, <strong><?= htmlspecialchars($_SESSION['usuario']['nome']) ?> </span>
                    <a href="index.php?rota=home" class="link-item">Início</a>
                    <a href="index.php?rota=criar_post" class="link-item">Novo Post</a>
                    <a href="index.php?rota=perfil" class="link-item">Meu Perfil</a>
                    <a href="index.php?rota=logout" class="link-item">Sair</a>
                <?php else: ?>
                    <a href="index.php?rota=login" class="link-item">Login</a>
                    <a href="index.php?rota=registro" class="link-item">Registrar</a>
                <?php endif; ?>
                </ul>
        </nav>
    </header>
    <script>
        const toggleBtn = document.getElementById('menu-toggle');
        const menuList = document.getElementById('menu-list');

        toggleBtn.addEventListener('click', () => {
            menuList.classList.toggle('show');
        });
    </script>

</body>
</html>