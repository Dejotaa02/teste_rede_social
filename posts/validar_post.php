<?php
defined('CONTROL') or die('Acesso Negado!');
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../inc/db_database.php';
$tipo = $_SESSION['tipo'] ?? null;


//Usuário não pode validar;
if($tipo != "especialista"){
    json_encode([]);
    exit;
}

$db = new Database();
$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<p style='color:red;'>Requisição inválida.</p>";
    exit;
}
