<?php
defined('CONTROL') or die('Acesso Negado!');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../inc/db_database.php';
require_once __DIR__ . '/../inc/menu.php';

$db = new Database();

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

<?php if (!empty($_SESSION['mensagem'])): ?>
    <div id="msg-sucesso" style="color:green;">
        <?= htmlspecialchars($_SESSION['mensagem']) ?>
    </div>
    <?php unset($_SESSION['mensagem']); ?>
<?php endif; ?>

<?php if (empty($postagens)): ?>
    <p>Nenhuma postagem disponível.</p>
<?php else: ?>
    <?php foreach ($postagens as $post) : ?>
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
            <strong><?= htmlspecialchars($post['nome']) ?></strong>
            em <?= date('d/m/Y H:i', strtotime($post['criado_em'])) ?><br>

            <?php if (!empty($post['imagem'])): ?>
                <img src="<?= htmlspecialchars($post['imagem']) ?>" alt="Imagem do post" style="max-width:100%; height:auto; margin:10px 0;">
            <?php endif; ?>

            <p><?= nl2br(htmlspecialchars($post['conteudo'])) ?></p>

            <?php
            $usuario = $_SESSION['usuario'] ?? null;
            $ja_curtiu = false;

            if ($usuario) {
                $sql_like = "SELECT id FROM likes WHERE post_id = :post_id AND usuario_id = :usuario_id";
                $res_like = $db->query($sql_like, [
                    ':post_id' => $post['id'],
                    ':usuario_id' => $usuario['id']
                ]);

                $ja_curtiu = $res_like['status'] === 'success' && count($res_like['data']) > 0;
            }
            ?>

            <?php if ($usuario): ?>
                <form action="index.php?rota=curtir" method="post" style="display:inline;">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <button type="submit"><?= $ja_curtiu ? 'Descurtir' : 'Curtir' ?></button>
                </form>
            <?php endif; ?>

            (<?= $post['curtidas'] ?> curtidas)
            <hr>
            <h4>Comentários:</h4>
            <?php
            $sql_com = "SELECT c.*, u.nome FROM comentarios c 
                        JOIN usuarios u ON c.usuario_id = u.id 
                        WHERE c.post_id = :post_id 
                        ORDER BY c.criado_em ASC";
            $res_com = $db->query($sql_com, [':post_id' => $post['id']]);

            if ($res_com['status'] === 'success') {
                foreach ($res_com['data'] as $coment) {
                    echo "<div style='margin-bottom: 5px;'>";
                    echo "<strong>" . htmlspecialchars($coment['nome']) . "</strong>: ";

                    if (
                        isset($_SESSION['usuario']) &&
                        $_SESSION['usuario']['id'] == $coment['usuario_id'] &&
                        isset($_GET['editar_comentario']) &&
                        $_GET['editar_comentario'] == $coment['id']
                    ) {
                        echo "<form action='index.php?rota=editar_comentario' method='post' style='display:inline;'>
                                <input type='hidden' name='comentario_id' value='{$coment['id']}'>
                                <textarea name='novo_comentario' rows='2' cols='40'>" . htmlspecialchars($coment['comentario']) . "</textarea>
                                <button type='submit'>Salvar</button>
                                <a href='index.php?rota=home'>Cancelar</a>
                            </form>";
                    } else {
                        echo nl2br(htmlspecialchars($coment['comentario']));

                        if (isset($_SESSION['usuario']) && $_SESSION['usuario']['id'] == $coment['usuario_id']) {
                            echo " <a href='index.php?rota=home&editar_comentario={$coment['id']}'><button>Editar</button></a> ";

                            echo "<form action='index.php?rota=excluir_comentario' method='post' style='display:inline;'>
                                    <input type='hidden' name='comentario_id' value='{$coment['id']}'>
                                    <button type='submit' onclick=\"return confirm('Excluir este comentário?')\">Excluir</button>
                                </form>";
                        }
                    }

                    echo "</div>";
                }
            } else {
                echo "<p style='color:red;'>Erro ao carregar comentários.</p>";
                error_log("Erro ao buscar comentários do post ID {$post['id']}: " . $res_com['message']);
            }
            ?>

            <?php if ($usuario): ?>
                <form action="index.php?rota=comentar" method="post">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <textarea name="comentario" rows="2" cols="40" required></textarea><br>
                    <button type="submit">Comentar</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
window.onload = function() {
    const msgSucesso = document.getElementById('msg-sucesso');
    if (msgSucesso) {
        setTimeout(() => {
            msgSucesso.style.transition = 'opacity 0.5s ease';
            msgSucesso.style.opacity = '0';
            setTimeout(() => msgSucesso.remove(), 500);
        }, 5000);
    }
};
</script>
