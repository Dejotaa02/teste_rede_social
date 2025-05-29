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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conteudo = trim($_POST['conteudo'] ?? '');

    if (!empty($conteudo)) {
        $db = new Database();
        $sql = "INSERT INTO postagens (usuario_id, conteudo, data) VALUES (:usuario_id, :conteudo, NOW())";
        $params = [
            ':usuario_id' => $_SESSION['usuario']['id'],
            ':conteudo' => $conteudo
        ];
        $db->query($sql, $params);
        header('Location: index.php?rota=home');
        exit;
    } else {
        $erro = "Digite algum conteúdo.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Postagem</title>
</head>
<body>
    <h1>Nova Postagem</h1>
    <form method="post">
        <textarea name="conteudo" rows="5" cols="40" placeholder="O que você encontrou na natureza?" required></textarea><br><br>
        <button type="submit">Publicar</button>
    </form>
    <?php if (!empty($erro)) : ?>
        <p style="color:red;"><?= $erro ?></p>
    <?php endif; ?>
    <p><a href="?rota=home">Voltar</a></p>
</body>
</html>
