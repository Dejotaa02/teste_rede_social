<?php
defined('CONTROL') or die('Acesso Negado!');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?rota=registro');
    exit;
}

require_once __DIR__ . '/../inc/db_database.php';

$nome = $_POST['nome'] ?? null;
$usuario = $_POST['usuario'] ?? null;
$senha = $_POST['senha'] ?? null;

if (empty($nome) || empty($usuario) || empty($senha) || strlen($senha) < 6) {
    $_SESSION['error'] = 'Preencha todos os campos corretamente! Senha deve ter no mínimo 6 caracteres.';
    header('Location: index.php?rota=registro');
    exit;
}

$db = new Database();

$sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
$params = [':usuario' => $usuario];
$result = $db->query($sql, $params);

if (count($result['data']) > 0) {
    $_SESSION['error'] = 'Usuário já cadastrado!';
    header('Location: index.php?rota=registro');
    exit;
}

$params = [
    ':nome' => $nome,
    ':usuario' => $usuario,
    ':senha' => password_hash($senha, PASSWORD_DEFAULT)
];

$sql = "INSERT INTO usuarios (nome, usuario, senha) VALUES (:nome, :usuario, :senha)";
$db->query($sql, $params);

$_SESSION['success'] = 'Cadastro realizado com sucesso!';
header('Location: index.php?rota=login');
exit;
