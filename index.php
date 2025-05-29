<?php
define('CONTROL', true);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rota = $_GET['rota'] ?? 'login';

switch ($rota) {
    case 'login':
        require 'scripts/login.php';
        break;
    case 'registro':
        require 'scripts/registro.php';
        break;
    case 'submit_login':
        require 'scripts/submit_login.php';
        break;
    case 'submit_registro':
        require 'scripts/submit_registro.php';
        break;
    case 'logout':
        require 'scripts/logout.php';
        break;
    case 'perfil':
        require 'scripts/perfil_usuario.php';
        break;
    case 'criar_post':
        require 'posts/criar_post.php';
        break;
    case 'ver_posts':
        require 'posts/ver_posts.php';
        break;
    case 'curtir':
        require 'scripts/curtir_post.php';
        break;
    case 'comentar':
        require 'scripts/comentar.php';
        break;
    case 'home':
        require 'scripts/home.php';
        break;
    default:
        require 'scripts/submit_login.php';
        break;
}
