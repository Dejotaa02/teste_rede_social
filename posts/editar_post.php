<?php
defined('CONTROL') or die('Acesso Negado!');
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../inc/db_database.php';

$db = new Database();
$usuario = $_SESSION['usuario'] ?? null;

if (!$usuario) {
    echo "<p style='color:red;'>Você precisa estar logado para editar um post.</p>";
    return;
}

$post_id = $_GET['id'] ?? null;
if (!$post_id) {
    echo "<p>ID do post não fornecido.</p>";
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $conteudo = trim($_POST['conteudo']);

    if ($titulo && $conteudo) {
        $sql = "UPDATE posts 
                SET titulo = :titulo, conteudo = :conteudo, editado_em = NOW() 
                WHERE id = :id AND usuario_id = :usuario_id";
        $params = [
            ':titulo' => $titulo,
            ':conteudo' => $conteudo,
            ':id' => $post_id,
            ':usuario_id' => $usuario['id']
        ];
        $db->query($sql, $params);

        header("Location: index.php?rota=perfil");
        exit;
    } else {
        echo "<p style='color:red;'>Preencha todos os campos.</p>";
    }
} else {
    $sql = "SELECT * FROM posts WHERE id = :id AND usuario_id = :usuario_id";
    $res = $db->query($sql, [':id' => $post_id, ':usuario_id' => $usuario['id']]);
    $post = $res['data'][0] ?? null;

    if (!$post) {
        echo "<p style='color:red;'>Post não encontrado ou acesso negado.</p>";
        return;
    }
}
?>

<h2>Editar Post</h2>
<form method="post">
    Título:<br>
    <input type="text" name="titulo" value="<?= htmlspecialchars($post['titulo']) ?>" required><br><br>
    Conteúdo:<br>
    <textarea name="conteudo" rows="5" cols="40" required><?= htmlspecialchars($post['conteudo']) ?></textarea><br><br>
    <button type="submit">Salvar</button>
    <a href="index.php?rota=perfil"><button type="button">Cancelar</button></a>
</form>
