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
    // Buscar imagem associada ao post
    $sql_img = "SELECT imagem FROM posts WHERE id = :id AND usuario_id = :usuario_id";
    $res = $db->query($sql_img, [':id' => $post_id, ':usuario_id' => $usuario['id']]);
    
    if ($res['status'] === 'success' && count($res['data']) > 0) {
        $imagem = $res['data'][0]['imagem'] ?? null;

        if ($imagem) {
            // Montar caminho físico completo para exclusão do arquivo
            $caminho_arquivo = __DIR__ . '/../' . $imagem;

            if (file_exists($caminho_arquivo)) {
                unlink($caminho_arquivo); // Exclui o arquivo da imagem
            }
        }

        // Apagar o post no banco
        $sql_del = "DELETE FROM posts WHERE id = :id AND usuario_id = :usuario_id";
        $db->query($sql_del, [':id' => $post_id, ':usuario_id' => $usuario['id']]);
    }
}

header("Location: index.php?rota=perfil");
exit;
?>
