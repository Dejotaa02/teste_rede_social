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
    $imagem_antiga = $_POST['imagem_antiga'] ?? null;

    // Verificar se houve upload de nova imagem
    $imagem_nova = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $arquivo_tmp = $_FILES['imagem']['tmp_name'];
        $nome_arquivo = basename($_FILES['imagem']['name']);
        $extensao = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($extensao, $extensoes_permitidas)) {
            echo "<p style='color:red;'>Formato de imagem não permitido. Use JPG, PNG ou GIF.</p>";
        } else {
            $diretorio = __DIR__ . '/../uploads/';

            if (!is_dir($diretorio)) {
                mkdir($diretorio, 0755, true);
            }

            $novo_nome = uniqid('img_') . '.' . $extensao;
            $destino = $diretorio . $novo_nome;

            if (move_uploaded_file($arquivo_tmp, $destino)) {
                $imagem_nova = 'uploads/' . $novo_nome;

                // Apagar imagem antiga se existir e for diferente da nova
                if ($imagem_antiga && $imagem_antiga !== $imagem_nova) {
                    $caminho_antigo = __DIR__ . '/../' . $imagem_antiga;
                    if (file_exists($caminho_antigo)) {
                        unlink($caminho_antigo);
                    }
                }
            } else {
                echo "<p style='color:red;'>Falha ao salvar a imagem.</p>";
            }
        }
    }

    if ($titulo && $conteudo) {
        $sql = "UPDATE posts 
                SET titulo = :titulo, conteudo = :conteudo, editado_em = NOW(), imagem = :imagem
                WHERE id = :id AND usuario_id = :usuario_id";
        $params = [
            ':titulo' => $titulo,
            ':conteudo' => $conteudo,
            ':imagem' => $imagem_nova ?? $imagem_antiga,
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
<form method="post" enctype="multipart/form-data">
    Título:<br>
    <input type="text" name="titulo" value="<?= htmlspecialchars($post['titulo']) ?>" required><br><br>
    
    Conteúdo:<br>
    <textarea name="conteudo" rows="5" cols="40" required><?= htmlspecialchars($post['conteudo']) ?></textarea><br><br>

    <?php if (!empty($post['imagem'])): ?>
        <p>Imagem atual:</p>
        <img src="<?= htmlspecialchars($post['imagem']) ?>" alt="Imagem do post" style="max-width:200px; height:auto; margin-bottom:10px;"><br>
        <input type="hidden" name="imagem_antiga" value="<?= htmlspecialchars($post['imagem']) ?>">
    <?php endif; ?>

    Alterar imagem:<br>
    <input type="file" name="imagem" accept="image/*"><br><br>

    <button type="submit">Salvar</button>
    <a href="index.php?rota=perfil"><button type="button">Cancelar</button></a>
</form>
