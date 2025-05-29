<?php
defined('CONTROL') or die('Acesso Negado!');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once __DIR__ . '/../inc/db_database.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?rota=login');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "Postagem não encontrada.";
    exit;
}

$db = new Database();
$sqlPost = "SELECT p.*, u.nome FROM postagens p JOIN usuarios u ON p.usuario_id = u.id WHERE p.id = :id";
$post = $db->query($sqlPost, [':id' => $id])['data'][0] ?? null;

$sqlComents = "SELECT c.*, u.nome FROM comentarios c JOIN usuarios u ON c.usuario_id = u.id WHERE c.postagem_id = :id ORDER BY c.data ASC";
$comentarios = $db->query($sqlComents, [':id' => $id])['data'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comentario = trim($_POST['comentario'] ?? '');
    if (!empty($comentario)) {
        $sql = "INSERT INTO comentarios (postagem_id, usuario_id, conteudo, data) VALUES (:postagem_id, :usuario_id, :conteudo, NOW())";
        $params = [
            ':postagem_id' => $id,
            ':usuario_id' => $_SESSION['usuario']['id'],
            ':conteudo' => $comentario
        ];
        $db->query($sql, $params);
        header("Location: index.php?rota=postagens&id=$id");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Postagem</title>
</head>
<body>
    <?php if (!$post): ?>
        <p>Postagem não encontrada.</p>
    <?php else: ?>
        <h2>Postagem de <?= htmlspecialchars($post['nome']) ?> em <?= date('d/m/Y H:i', strtotime($post['data'])) ?></h2>
        <p><?= nl2br(htmlspecialchars($post['conteudo'])) ?></p>

        <h3>Comentários</h3>
        <?php foreach ($comentarios as $coment): ?>
            <div style="border-top:1px solid #ccc; padding:5px;">
                <strong><?= htmlspecialchars($coment['nome']) ?></strong> disse em <?= date('d/m/Y H:i', strtotime($coment['data'])) ?>:<br>
                <?= nl2br(htmlspecialchars($coment['conteudo'])) ?>
            </div>
        <?php endforeach; ?>

        <form method="post">
            <textarea name="comentario" rows="3" cols="40" placeholder="Comente algo..." required></textarea><br>
            <button type="submit">Comentar</button>
        </form>
    <?php endif; ?>
    <p><a href="?rota=home">Voltar</a></p>
</body>
</html>
