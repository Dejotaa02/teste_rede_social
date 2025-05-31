<?php
defined('CONTROL') or die('Acesso Negado!');
if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../inc/db_database.php';
$db = new Database();

$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) {
    echo json_encode(['status' => 'error', 'message' => 'Não autenticado']);
    exit;
}

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

    // Retorna HTML renderizado do comentário
    $html = "<div style='margin-bottom: 5px;'>";
    $html .= "<strong>" . htmlspecialchars($usuario['nome']) . "</strong>: ";
    $html .= nl2br(htmlspecialchars($comentario));
    $html .= " <a href='index.php?rota=home&editar_comentario=0'><button>Editar</button></a> ";
    $html .= "<form action='index.php?rota=excluir_comentario' method='post' style='display:inline;'>
                <input type='hidden' name='comentario_id' value='0'>
                <button type='submit' onclick=\"return confirm('Excluir este comentário?')\">Excluir</button>
              </form>";
    $html .= "</div>";

    echo json_encode(['status' => 'success', 'html' => $html]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
}
