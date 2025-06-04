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
    $imagemPath = null;

    // Verifica se foi enviado arquivo de imagem
    if (!empty($_FILES['imagem']['name'])) {
        $arquivo = $_FILES['imagem'];

        // Validar upload sem erros
        if ($arquivo['error'] === UPLOAD_ERR_OK) {
            $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
            $maxTamanho = 2 * 1024 * 1024; // 2MB

            $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

            if (!in_array($extensao, $extensoesPermitidas)) {
                $_SESSION['erro'] = "Formato de imagem não permitido. Use jpg, jpeg, png ou gif.";
                header('Location: index.php?rota=criar_post');
                exit;
            }

            if ($arquivo['size'] > $maxTamanho) {
                $_SESSION['erro'] = "Imagem muito grande. Tamanho máximo: 2MB.";
                header('Location: index.php?rota=criar_post');
                exit;
            }

            // Criar pasta uploads/posts se não existir
            $uploadDir = __DIR__ . '/../uploads/posts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Nome único para evitar sobrescrever
            $nomeArquivo = uniqid('post_') . '.' . $extensao;
            $caminhoCompleto = $uploadDir . $nomeArquivo;

            if (move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
                // Salvar caminho relativo para usar no site
                $imagemPath = 'uploads/posts/' . $nomeArquivo;
            } else {
                $_SESSION['erro'] = "Erro ao salvar imagem.";
                header('Location: index.php?rota=criar_post');
                exit;
            }
        } else {
            $_SESSION['erro'] = "Erro no upload da imagem.";
            header('Location: index.php?rota=criar_post');
            exit;
        }
    }

    if ($titulo && $conteudo) {
        $db = new Database();

        $db->query(
            "INSERT INTO posts (usuario_id, titulo, conteudo, imagem, criado_em) VALUES (:uid, :titulo, :conteudo, :imagem, NOW())",
            [
                ':uid' => $_SESSION['usuario']['id'],
                ':titulo' => $titulo,
                ':conteudo' => $conteudo,
                ':imagem' => $imagemPath
            ]
        );
        $_SESSION['mensagem'] = "Post criado com sucesso!";
        header('Location: index.php?rota=home');
        exit;
    } else {
        $_SESSION['erro'] = "Preencha todos os campos.";
        header('Location: index.php?rota=criar_post');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css">
    <link rel="shortcut icon" href="../assets/icone.png" type="image/x-icon">
    <title>Base de dados</title>
</head>

<body class="post-body">

    <?php if (!empty($_SESSION['erro'])): ?>
        <div id="msg-erro" style="color:red;">
            <?= htmlspecialchars($_SESSION['erro']) ?>
        </div>
        <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>

    <h2 class="align-text">Crie sua postagem</h2>
    <div class="criar-post">
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="titulo" placeholder="Título do Post" required class="input-post">

            <textarea name="conteudo" placeholder="Escreva seu post" required class="input-post"></textarea>

            <div class="file-upload-wrapper">
                <label for="foto" class="custom-file-upload">Anexe uma imagem</label>
                <input type="file" name="imagem" id="imagem" class="input-photo" accept="image/*" capture="environment">
                <p id="file-name"></p>
            </div>

            <h2 class="align-text">Marque no mapa a região em que encontrou essa espécime</h2></br>
            <div id="map-container" style="height: 400px; width: 100%;">
                <div id="map" style="height: 400px; width: 100%;"></div>
            </div></br>
            <button type="submit">Publicar</button>
        </form>
    </div>
    
    <script>
        window.onload = function() {
            const msgErro = document.getElementById('msg-erro');
            if (msgErro) {
                setTimeout(() => {
                    msgErro.style.transition = 'opacity 0.5s ease';
                    msgErro.style.opacity = '0';
                    setTimeout(() => msgErro.remove(), 500);
                }, 5000);
            }
        }
    </script>

    <script src="scripts/js_scripts/post.js"></script>
    <!-- colocar chave da api -->
    <script async src="https://maps.googleapis.com/maps/api/js?key=98&callback=initMap"></script>
</body>
</html>