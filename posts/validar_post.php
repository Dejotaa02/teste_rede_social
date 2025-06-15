<?php
// defined('CONTROL') or die('Acesso Negado!');


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['sucesso' => false, 'erro' => 'Requisição inválida']);
    exit;
}

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'especialista') {
    echo json_encode(['sucesso' => false, 'erro' => 'Permissão negada']);
    exit;
}

require_once __DIR__ . '/../inc/db_database.php';

$postId  = $_POST['post_id'] ?? null;
$reino   = $_POST['reino'] ?? null;
$familia = $_POST['familia'] ?? null;
$especie  = $_POST['especie'] ?? null;
$genero  = $_POST['genero'] ?? null;
$especialistaId = $_SESSION['usuario']['id'];

if (!$postId || !$reino || !$familia || !$especie || !$genero) {
    echo json_encode(['sucesso' => false, 'erro' => 'Campos obrigatórios ausentes']);
    exit;
}

try{
    $db = new Database();

    $params = [
        ':post_id' => $postId,
        ':reino' => $reino,
        ':familia' => $familia,
        ':especie' => $especie,
        ':genero' => $genero,
        ':especialista_id' => $especialistaId
    ];

    $sql = "INSERT INTO especies (post_id, reino, familia, especie, genero, especialista_id) VALUES (:post_id, :reino, :familia, :especie, :genero, :especialista_id)";

    $db->query($sql, $params);

    echo json_encode(['sucesso' => true, 'mensagem' => 'Validação realizada com sucesso']);
} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => 'Erro ao salvar no banco de dados: ' . $e->getMessage()]);
}
