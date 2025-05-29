<?php
defined('CONTROL') or die('Acesso negado!');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../inc/db_database.php';
$db = new Database();

$usuario = $_SESSION['usuario'] ?? null;
$comentario_id = $_POST['comentario_id'] ?? null;

if (!$usuario || !$comentario_id) {
    header("Location: index.php?rota=home");
    exit;
}

// Verifica se o comentário pertence ao usuário logado
$check = $db->query("SELECT * FROM comentarios WHERE id = :id AND usuario_id = :uid", [
    ':id' => $comentario_id,
    ':uid' => $usuario['id']
]);

if ($check['status'] === 'success' && count($check['data']) === 1) {
    // Apaga o comentário
    $db->query("DELETE FROM comentarios WHERE id = :id", [':id' => $comentario_id]);
}

header("Location: index.php?rota=home");
exit;
