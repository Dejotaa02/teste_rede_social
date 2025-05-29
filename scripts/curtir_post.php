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
if ($post_id) {
    // Verifica se já curtiu
    $sql = "SELECT * FROM likes WHERE post_id = :post_id AND usuario_id = :usuario_id";
    $params = [':post_id' => $post_id, ':usuario_id' => $usuario['id']];
    $result = $db->query($sql, $params)['data'];

    if (count($result) === 0) {
        // Curtir
        $sql = "INSERT INTO likes (post_id, usuario_id) VALUES (:post_id, :usuario_id)";
        $db->query($sql, $params);
    } else {
        // Descurtir
        $sql = "DELETE FROM likes WHERE post_id = :post_id AND usuario_id = :usuario_id";
        $db->query($sql, $params);
    }
}

header("Location: index.php?rota=home");
exit;
