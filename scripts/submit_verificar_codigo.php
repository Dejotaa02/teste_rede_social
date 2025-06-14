<?php
defined('CONTROL') or die('Acesso Negado!');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location: index.php?rota=verificar_codigo');
    exit;
}

$codigo = $_POST['codigo'] ?? null;

if ($codigo != $_SESSION['codigo_recuperacao']) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Código inválido.']);
    exit;
}
echo json_encode(['status' => 'ok', 'mensagem' => 'Senha alterada com sucesso']);
exit;
?>