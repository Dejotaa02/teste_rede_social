<?php
defined('CONTROL') or die('Acesso negado!');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../inc/menu.php';
require_once __DIR__ . '/../inc/db_database.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?rota=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $conteudo = trim($_POST['conteudo'] ?? '');

    if ($titulo && $conteudo) {
        $db = new Database();
        $db->query(
            "INSERT INTO posts (usuario_id, titulo, conteudo, criado_em) VALUES (:uid, :titulo, :conteudo, NOW())",
            [':uid' => $_SESSION['usuario']['id'], ':titulo' => $titulo, ':conteudo' => $conteudo]
        );
        echo "<p style='color:green;'>Post criado com sucesso!</p>";
    } else {
        echo "<p style='color:red;'>Preencha todos os campos.</p>";
    }
}
?>

<h2>Novo Post</h2>
<form method="post">
    Título:<br>
    <input type="text" name="titulo" required><br><br>
    Conteúdo:<br>
    <textarea name="conteudo" rows="5" cols="40" required></textarea><br><br>
    <button type="submit">Publicar</button>
</form>
