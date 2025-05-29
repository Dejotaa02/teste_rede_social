<?php
if (!isset($_SESSION)) session_start();

?>
<nav>
    
    <?php if (isset($_SESSION['usuario'])): ?>
        <a href="index.php?rota=home">Início</a> |
        <a href="index.php?rota=criar_post">Novo Post</a> |
        <a href="index.php?rota=perfil">Meu Perfil</a> |
        <a href="index.php?rota=logout">Sair</a>
    <?php else: ?>
        <a href="index.php?rota=login">Login</a> |
        <a href="index.php?rota=registro">Registrar</a>
    <?php endif; ?>
</nav>
<hr>
