<?php
defined('CONTROL') or die('Acesso Negado!');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../inc/db_database.php';
require_once __DIR__ . '/../inc/menu.php';

$db = new Database();

// Usando tabela 'posts' corretamente e campo 'criado_em'
$sql = "
    SELECT p.*, u.nome,
        (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS curtidas
    FROM posts p
    JOIN usuarios u ON p.usuario_id = u.id
    ORDER BY p.criado_em DESC
";
$result = $db->query($sql);
$postagens = $result['status'] === 'success' ? $result['data'] : [];

?>

<h2>Explorações Recentes</h2>

<?php if (empty($postagens)): ?>
    <p>Nenhuma postagem disponível.</p>
<?php else: ?>
    <?php foreach ($postagens as $post) : ?>
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
            <strong><?= htmlspecialchars($post['nome']) ?></strong>
            em <?= date('d/m/Y H:i', strtotime($post['criado_em'])) ?><br>
            <p><?= nl2br(htmlspecialchars($post['conteudo'])) ?></p>

            <form action="index.php?rota=curtir" method="post" style="display:inline;">
                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                <button type="submit">Curtir</button>
            </form>
            (<?= $post['curtidas'] ?> curtidas)

            <h4>Comentários:</h4>
            <?php
            $sql_com = "SELECT c.*, u.nome FROM comentarios c 
                        JOIN usuarios u ON c.usuario_id = u.id 
                        WHERE c.post_id = :post_id 
                        ORDER BY c.criado_em ASC";
            $res_com = $db->query($sql_com, [':post_id' => $post['id']]);

            if ($res_com['status'] === 'success') {
                foreach ($res_com['data'] as $coment) {
                    echo "<p><strong>" . htmlspecialchars($coment['nome']) . "</strong>: " . htmlspecialchars($coment['comentario']) . "</p>";
                }
            } else {
                echo "<p style='color:red;'>Erro ao carregar comentários.</p>";
                error_log("Erro ao buscar comentários do post ID {$post['id']}: " . $res_com['message']);
            }
            ?>

            <?php if (isset($_SESSION['usuario'])) : ?>
                <form action="index.php?rota=comentar" method="post">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <textarea name="comentario" rows="2" cols="40" required></textarea><br>
                    <button type="submit">Comentar</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
