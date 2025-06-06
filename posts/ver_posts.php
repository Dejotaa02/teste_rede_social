<?php
defined('CONTROL') or die('Acesso negado!');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../inc/menu.php';
require_once __DIR__ . '/../inc/db_database.php';

$db = new Database();

// Adiciona subconsultas para contar curtidas e comentários
$sql = "
    SELECT p.*, u.nome,
        (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS curtidas,
        (SELECT COUNT(*) FROM comentarios c WHERE c.post_id = p.id) AS comentarios
    FROM posts p
    JOIN usuarios u ON p.usuario_id = u.id
    ORDER BY p.criado_em DESC
";

$result = $db->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css">
    <title>Explorações Recentes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=mail" />
    <link rel="shortcut icon" href="assets/icone.png" type="image/x-icon">
</head>

<body>
    <?php
    echo "<h2>Explorações Recentes</h2>";

    if ($result['status'] !== 'success' || count($result['data']) === 0) {
        echo "<p>Nenhuma postagem ainda.</p>";
    } else {
        foreach ($result['data'] as $post) {
            echo "<div class='posts_recentes'>";
            echo "<h3>" . htmlspecialchars($post['titulo']) . "</h3>";

            // Aqui adicionamos a exibição da imagem, se existir
            if (!empty($post['imagem'])) {
                echo '<img src="' . htmlspecialchars($post['imagem']) . '" alt="Imagem do post" style="max-width:100%; height:auto; margin:10px 0;">';
            }

            echo "<p>" . nl2br(htmlspecialchars($post['conteudo'])) . "</p>";

            echo "<small>";
            echo "<strong>" . $post['curtidas'] . "</strong> curtidas | ";
            echo "<strong>" . $post['comentarios'] . "</strong> comentários<br>";
            echo "Por " . htmlspecialchars($post['nome']) . " em " . date("d/m/Y H:i", strtotime($post['criado_em']));
            if (!empty($post['editado_em'])) {
                echo " | Editado em " . date("d/m/Y H:i", strtotime($post['editado_em']));
            }
            echo "</small><br>";

            echo "</div>";
        }
    }
    ?>
</body>

</html>