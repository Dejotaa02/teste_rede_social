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

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=mail" />
    <link rel="shortcut icon" href="assets/icone.png" type="image/x-icon">
</head>

<body>
    <h2>Recentes na comunidade</h2>
    <div class="posts_recentes">
        <?php if (!empty($_SESSION['mensagem'])): ?>
            <div id="msg-sucesso">
                <?= htmlspecialchars($_SESSION['mensagem']) ?>
            </div>
            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>

        <?php if (empty($postagens)): ?>
            <p>Nenhuma postagem disponível.</p>
        <?php else: ?>
            <?php foreach ($postagens as $post) : ?>
                <div id="post-<?= $post['id'] ?>" class="post">
                    <div class="post-header">
                        <strong><?= htmlspecialchars($post['nome']) ?></strong>
                        em <span class="post-date"><?= date('d/m/Y H:i', strtotime($post['criado_em'])) ?></span>
                        <h3 class="post-title"> <?= htmlspecialchars($post['titulo']) ?> </h3>
                    </div>

                    <?php if (!empty($post['imagem'])): ?>
                        <img src="<?= htmlspecialchars($post['imagem']) ?>" alt="Imagem do post">
                    <?php endif; ?>

                    <p class="post-content"><?= nl2br(htmlspecialchars($post['conteudo'])) ?></p>

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

                    <div class="likes-section">
                        <?php if ($usuario): ?>
                            <form class="form-curtir" data-post-id="<?= $post['id'] ?>">
                                <button type="submit"><?= $ja_curtiu ? 'Descurtir' : 'Curtir' ?></button>
                            </form>
                        <?php endif; ?>
                        (<span class="contagem-curtidas"><?= $post['curtidas'] ?></span> curtidas)
                    </div>

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
                                echo "<div class='comentario-item' id='comentario-{$coment['id']}'>";
                                echo "<strong>" . htmlspecialchars($coment['nome']) . "</strong>: ";

                                if (
                                    isset($_SESSION['usuario']) &&
                                    $_SESSION['usuario']['id'] == $coment['usuario_id'] &&
                                    isset($_GET['editar_comentario']) &&
                                    $_GET['editar_comentario'] == $coment['id']
                                ) {
                                    echo "<form action='index.php?rota=editar_comentario' method='post'>
                                            <input type='hidden' name='comentario_id' value='{$coment['id']}'>
                                            <textarea name='novo_comentario' rows='2'>" . htmlspecialchars($coment['comentario']) . "</textarea>
                                            <button type='submit'>Salvar</button>
                                            <a href='index.php?rota=home'>Cancelar</a>
                                          </form>";
                                } else {
                                    echo "<p class='comentario-text'>" . nl2br(htmlspecialchars($coment['comentario'])) . "</p>";

                                    if (isset($_SESSION['usuario']) && $_SESSION['usuario']['id'] == $coment['usuario_id']) {
                                        echo " <a href='index.php?rota=home&editar_comentario={$coment['id']}' class='btn-comment-action'>Editar</a> ";

                                        echo "<form action='index.php?rota=excluir_comentario' method='post' style='display:inline;'>
                                                <input type='hidden' name='comentario_id' value='{$coment['id']}'>
                                                <button type='submit' class='btn-excluir-comentario' onclick=\"return confirm('Excluir este comentário?')\">Excluir</button>
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
                            <textarea name="comentario" rows="2" placeholder="Escreva seu comentário..." required></textarea><br>
                            <button type="submit">Comentar</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
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
</body>

</html>