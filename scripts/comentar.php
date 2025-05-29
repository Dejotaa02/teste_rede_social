<?php
defined('CONTROL') or die('Acesso Negado!');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../inc/db_database.php';
$db = new Database();

$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) exit;

$post_id = $_POST['post_id'] ?? null;
$comentario = trim($_POST['comentario'] ?? '');

if ($post_id && $comentario) {
    $sql = "INSERT INTO comentarios (post_id, usuario_id, comentario) VALUES (:post_id, :usuario_id, :comentario)";
    $params = [
        ':post_id' => $post_id,
        ':usuario_id' => $usuario['id'],
        ':comentario' => $comentario
    ];
    $db->query($sql, $params);
}

header("Location: index.php?rota=home");
exit;
