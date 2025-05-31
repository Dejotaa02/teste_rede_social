<?php
defined('CONTROL') or die('Acesso Negado!');
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../inc/db_database.php';
require_once __DIR__ . '/../inc/menu.php';

$db = new Database();
$usuario = $_SESSION['usuario'] ?? null;

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
        <div id="post-<?= $post['id'] ?>" style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
            <strong><?= htmlspecialchars($post['nome']) ?></strong>
            em <?= date('d/m/Y H:i', strtotime($post['criado_em'])) ?><br>

            <?php if (!empty($post['imagem'])): ?>
                <img src="<?= htmlspecialchars($post['imagem']) ?>" alt="Imagem do post" style="max-width:100%; height:auto; margin:10px 0;">
            <?php endif; ?>

            <p><?= nl2br(htmlspecialchars($post['conteudo'])) ?></p>

            <?php
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
                <form class="form-curtir" data-post-id="<?= $post['id'] ?>" style="display:inline;">
                    <button type="submit"><?= $ja_curtiu ? 'Descurtir' : 'Curtir' ?></button>
                </form>
            <?php endif; ?>
            (<span class="contagem-curtidas"><?= $post['curtidas'] ?></span> curtidas)

            <hr>
            <h4>Comentários:</h4>
            <div class="comentarios" id="comentarios-<?= $post['id'] ?>">
                <?php
                $sql_com = "SELECT c.*, u.nome FROM comentarios c 
                            JOIN usuarios u ON c.usuario_id = u.id 
                            WHERE c.post_id = :post_id 
                            ORDER BY c.criado_em ASC";
                $res_com = $db->query($sql_com, [':post_id' => $post['id']]);

                if ($res_com['status'] === 'success') {
                    foreach ($res_com['data'] as $coment) {
                        echo "<div style='margin-bottom: 5px;' id='comentario-{$coment['id']}'>";
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
                }
                ?>
            </div>

            <?php if ($usuario): ?>
                <form class="form-comentar" data-post-id="<?= $post['id'] ?>">
                    <textarea name="comentario" rows="2" cols="40" required></textarea><br>
                    <button type="submit">Comentar</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
// Ocultar mensagem de sucesso
window.onload = function() {
    const msg = document.getElementById('msg-sucesso');
    if (msg) {
        setTimeout(() => {
            msg.style.transition = 'opacity 0.5s ease';
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 500);
        }, 5000);
    }
};

// Curtir/Descurtir AJAX
document.querySelectorAll('.form-curtir').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const postId = this.dataset.postId;
        const button = this.querySelector('button');
        fetch('index.php?rota=curtir', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'post_id=' + postId
        })
        .then(() => location.reload()); // Ou atualize via JS, se quiser evitar reload
    });
});

// Comentar AJAX
document.querySelectorAll('.form-comentar').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const postId = this.dataset.postId;
        const textarea = this.querySelector('textarea');
        const comentario = textarea.value.trim();
        if (!comentario) return;

        const formData = new FormData();
        formData.append('post_id', postId);
        formData.append('comentario', comentario);

        fetch('index.php?rota=comentar', {
            method: 'POST',
            body: formData
        })
        .then(() => location.reload()); // Ou atualize dinamicamente
    });
});
</script>
