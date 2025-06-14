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
$lattes = $_POST['lattes'] ?? null;

if (empty($nome) || empty($usuario) || empty($senha) || strlen($senha) < 6) {
    $_SESSION['error'] = 'Preencha todos os campos corretamente! Senha deve ter no mínimo 6 caracteres.';
    header('Location: index.php?rota=registro');
    exit;
}
if (empty($lattes)){
    $lattes = "comum";
} else {
    $lattes = "especialista";
}

$db = new Database();

// Verificar se o usuário já existe
$sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
$params = [':usuario' => $usuario];
$result = $db->query($sql, $params);

if (count($result['data']) > 0) {
    $_SESSION['error'] = 'Usuário já cadastrado!';
    header('Location: index.php?rota=registro');
    exit;
}

// Inserir novo usuário
$params = [
    ':nome' => $nome,
    ':usuario' => $usuario,
    ':senha' => password_hash($senha, PASSWORD_DEFAULT),
    ':tipo' => $lattes
];

$sql = "INSERT INTO usuarios (nome, usuario, senha, tipo) VALUES (:nome, :usuario, :senha, :tipo)";
$db->query($sql, $params);

// Buscar usuário recém-criado para iniciar a sessão
$sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
$result = $db->query($sql, [':usuario' => $usuario]);

if ($result['status'] === 'success' && count($result['data']) > 0) {
    $user = $result['data'][0];

    $_SESSION['usuario'] = [
        'id' => $user['id'],
        'nome' => $user['nome'],
        'usuario' => $user['usuario'],
        'tipo' => $user['tupo']
    ];

    $_SESSION['success'] = 'Cadastro realizado com sucesso!';
    header('Location: index.php?rota=home');
    exit;
} else {
    $_SESSION['error'] = 'Erro ao criar conta. Tente novamente.';
    header('Location: index.php?rota=registro');
    exit;
}
