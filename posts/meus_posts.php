<?php
defined('CONTROL') or die('Acesso Negado!');
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../inc/db_database.php';

$db = new Database();
$usuario = $_SESSION['usuario'] ?? null;

if (!$usuario) {
    echo "<p style='color:red;'>Acesso não autorizado.</p>";
    return;
}

$sql = "
    SELECT p.*, 
        (SELECT COUNT(*) FROM likes WHERE post_id = p.id) AS curtidas,
        (SELECT COUNT(*) FROM comentarios WHERE post_id = p.id) AS comentarios
    FROM posts p
    WHERE p.usuario_id = :usuario_id
    ORDER BY p.criado_em DESC
";
$result = $db->query($sql, [':usuario_id' => $usuario['id']]);
$postagens = $result['status'] === 'success' ? $result['data'] : [];

if (empty($postagens)) {
    echo "<p>Você ainda não publicou nada.</p>";
} else {
    foreach ($postagens as $post) {
        echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:10px;'>";
        echo "<h3>" . htmlspecialchars($post['titulo']) . "</h3>";

        // Exibe a imagem do post, se existir
        if (!empty($post['imagem'])) {
            echo '<img src="' . htmlspecialchars($post['imagem']) . '" alt="Imagem do post" style="max-width:100%; height:auto; margin:10px 0;">';
        }

        echo "<p>" . nl2br(htmlspecialchars($post['conteudo'])) . "</p>";
        echo "<small>Postado em " . date("d/m/Y H:i", strtotime($post['criado_em'])) . " | ";
        if (!empty($post['editado_em'])) {
            echo "Editado em " . date("d/m/Y H:i", strtotime($post['editado_em'])) . " | ";
        }
        echo $post['curtidas'] . " curtidas | ";
        echo $post['comentarios'] . " comentários";
        echo "</small><br><br>";

        echo "<a href='index.php?rota=editar_post&id={$post['id']}'><button>Editar</button></a> ";
        echo "<form action='index.php?rota=excluir_post' method='post' style='display:inline;'>
                <input type='hidden' name='post_id' value='{$post['id']}'>
                <button type='submit' onclick=\"return confirm('Tem certeza que deseja excluir este post?')\">Excluir</button>
              </form>";
        echo "</div>";
    }
}
?>
