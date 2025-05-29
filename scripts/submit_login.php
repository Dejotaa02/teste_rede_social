<?php
defined('CONTROL') or die('Acesso negado!');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../inc/db_database.php';

$usuario = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';

if (!$usuario || !$senha) {
    $_SESSION['error'] = "Preencha todos os campos!";
    header('Location: index.php?rota=login');
    exit;
}

$db = new Database();
$sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
$result = $db->query($sql, [':usuario' => $usuario]);

if (empty($result['data'])) {
    $_SESSION['error'] = "Usuário não encontrado!";
    header('Location: index.php?rota=login');
    exit;
}

$user = $result['data'][0];
if (!password_verify($senha, $user['senha'])) {
    $_SESSION['error'] = "Senha incorreta!";
    header('Location: index.php?rota=login');
    exit;
}

$_SESSION['usuario'] = [
    'id' => $user['id'],
    'nome' => $user['nome'],
    'usuario' => $user['usuario']
];

header('Location: index.php?rota=home');
exit;
