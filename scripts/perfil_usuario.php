<?php
defined('CONTROL') or die('Acesso Negado!');

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../inc/menu.php';
require_once __DIR__ . '/../inc/db_database.php';

$usuario = $_SESSION['usuario'] ?? null;

if (!$usuario || !isset($usuario['id'])) {
    echo "<p style='color:red;'>Erro: Dados do usuário não disponíveis.</p>";
    exit;
}

// Mostrar mensagem de exclusão ou outra mensagem em sessão, se existir
if (!empty($_SESSION['mensagem'])) {
    echo "<p style='color:green;'>" . htmlspecialchars($_SESSION['mensagem']) . "</p>";
    unset($_SESSION['mensagem']);
}

$db = new Database();

// Inicializa variáveis de mensagem
$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'atualizar') {
        $novo_nome = trim($_POST['nome'] ?? '');
        $novo_email = trim($_POST['usuario'] ?? '');

        if ($novo_nome && $novo_email) {
            // Verificar se o email já está em uso por outro usuário
            $check = $db->query("SELECT id FROM usuarios WHERE usuario = :usuario AND id != :id", [
                ':usuario' => $novo_email,
                ':id' => $usuario['id']
            ]);

            if (!empty($check['data'])) {
                $erro = "Este email já está em uso por outro usuário.";
            } else {
                $sql = "UPDATE usuarios SET nome = :nome, usuario = :usuario WHERE id = :id";
                $params = [':nome' => $novo_nome, ':usuario' => $novo_email, ':id' => $usuario['id']];
                $db->query($sql, $params);
                $_SESSION['usuario']['nome'] = $novo_nome;
                $_SESSION['usuario']['usuario'] = $novo_email;
                $mensagem = "Perfil atualizado.";
            }
        } else {
            $erro = "Nome e email não podem estar vazios.";
        }
    }

    if ($acao === 'alterar_senha') {
        $senha_atual = $_POST['senha_atual'] ?? '';
        $nova = $_POST['nova_senha'] ?? '';
        $confirmar = $_POST['confirmar'] ?? '';

        $sql = "SELECT senha FROM usuarios WHERE id = :id";
        $res = $db->query($sql, [':id' => $usuario['id']]);
        $hash = $res['data'][0]['senha'] ?? '';

        if (password_verify($senha_atual, $hash)) {
            if ($nova === $confirmar && strlen($nova) >= 6) {
                $nova_hash = password_hash($nova, PASSWORD_DEFAULT);
                $db->query("UPDATE usuarios SET senha = :senha WHERE id = :id", [':senha' => $nova_hash, ':id' => $usuario['id']]);
                $mensagem = "Senha alterada com sucesso.";
            } else {
                $erro = "As senhas não coincidem ou são muito curtas.";
            }
        } else {
            $erro = "Senha atual incorreta.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/perfil.css">
    <link rel="shortcut icon" href="../assets/icone.png" type="image/x-icon">
    <title>Meu Perfil</title>
</head>

<body>
    <?php if (!empty($mensagem)) echo "<p style='color:green;'>$mensagem</p>"; ?>
    <?php if (!empty($erro)) echo "<p style='color:red;'>$erro</p>"; ?>

    <div class="perfil-usuario">
        <h2 class="align-text">Perfil</h2>

        <form method="post">
            <input type="hidden" name="acao" value="atualizar">
            <label>Nome:</label><br>
            <input type="text" name="nome" value="<?= htmlspecialchars($_SESSION['usuario']['nome']) ?>" required>
            <label>Email:</label><br>
            <input type="email" name="usuario" value="<?= htmlspecialchars($_SESSION['usuario']['usuario']) ?>" required>
            <button class="btn-alterar" type="submit">Atualizar</button><br>
        </form>

        <form method="post">
            <input type="hidden" name="acao" value="alterar_senha">
            <label>Senha atual:</label><br>
            <input type="password" name="senha_atual" required>
            <label>Nova senha:</label><br>
            <input type="password" name="nova_senha" required>
            <label>Confirmar nova senha:</label><br>
            <input type="password" name="confirmar" required>
            <button class="btn-alterar" type="submit">Alterar Senha</button><br>
        </form>
    </div>
    <div class="meus-posts">
        <h3>Meus Posts</h3>
        <?php require __DIR__ . '/../posts/meus_posts.php'; ?>
    </div>
</body>

</html>