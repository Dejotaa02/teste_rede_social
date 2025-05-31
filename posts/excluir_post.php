<?php
defined('CONTROL') or die('Acesso Negado!');
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../inc/db_database.php';

$db = new Database();
$usuario = $_SESSION['usuario'] ?? null;

if (!$usuario || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<p style='color:red;'>Requisição inválida.</p>";
    exit;
}

$post_id = $_POST['post_id'] ?? null;

if ($post_id) {
    $sql = "DELETE FROM posts WHERE id = :id AND usuario_id = :usuario_id";
    $db->query($sql, [':id' => $post_id, ':usuario_id' => $usuario['id']]);
}

header("Location: index.php?rota=perfil");
exit;
