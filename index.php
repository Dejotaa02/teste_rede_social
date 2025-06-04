<?php
define('CONTROL', true);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rota = $_GET['rota'] ?? 'ver_posts';

// Rotas públicas que não exigem login
$rotasPublicas = ['login', 'registro', 'submit_login', 'submit_registro', 'ver_posts', 'logout', 'redefinir_senha'];

// Redirecionar usuário logado tentando acessar login ou registro
if (isset($_SESSION['usuario']) && in_array($rota, $rotasPublicas)) {
    header('Location: index.php?rota=home');
    exit;
}

// Redirecionar usuário não logado tentando acessar rota protegida
if (!isset($_SESSION['usuario']) && !in_array($rota, $rotasPublicas)) {
    header('Location: index.php?rota=login');
    exit;
}

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
    case 'excluir_comentario':
        require 'scripts/excluir_comentario.php';
        break;
    case 'editar_comentario':
        require 'scripts/editar_comentario.php';
        break;
    case 'editar_post':
        require 'posts/editar_post.php';
        break;
    case 'excluir_post':
        require 'posts/excluir_post.php';
        break;
    case 'redefinir_senha':
        require 'scripts/redefinir_senha.php';
        break;
    default:
        require 'scripts/submit_login.php';
        break;
}
