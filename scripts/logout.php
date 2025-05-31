<?php
defined('CONTROL') or die('Acesso Negado!');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_destroy();
header('Location: index.php?rota=ver_posts');
exit;
