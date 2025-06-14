<?php
defined('CONTROL') or die('Acesso Negado!');
require_once __DIR__ . '/../inc/db_database.php';
$erro = $_SESSION['error'] ?? null;
unset($_SESSION['error']);

//Cabeçalho para que o PHP aceite requisições no formato JSON.
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location: index.php?rota=redefinir_senha');
    exit;
}

$usuario = $_POST['usuario'] ?? null;

if (empty($usuario)) {
    // var_dump($usuario);
    // die('1');
    // $_SESSION['error'] = 'Preencha o campo corretamente!';
    // header('location: index.php?rota=redefinir_senha');
    //Retorna uma mensagem de erro, para o JavaScript tratar no front
    echo json_encode(['status' => 'erro', 'mensagem' => 'O e-mail é obrigatório!']);
    exit;
}

// $db = new Database();
// $params = [':usuario' => $usuario];
// $sql = "SELECT * FROM usuarios WHERE usuario = :usuario OR email = :usuario";
// $result = $db->query($sql, $params);

$db = new Database();
$sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
$result = $db->query($sql, [':usuario' => $usuario]);
/*var_dump($usuario);
    var_dump($result);
    var_dump($_SESSION['error']);
    die('222');*/

if (empty($result['data'])) {
    // $_SESSION['error'] = 'Usuário não encontrado!';
    // header('location: index.php?rota=redefinir_senha');
    //Retorna uma mensagem de erro para o JavaScript tratar no front
    echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não cadastrado!']);
    exit;
}

$codigo = rand(100000, 999999);
$_SESSION['codigo_recuperacao'] = $codigo;
$_SESSION['usuario_recuperacao'] = $usuario;

$apiKey = ''; // Coloque sua chave de API

$headers = [
    'Authorization: Bearer ' . $apiKey,
    'Content-Type: application/json'
];

$data = [
    "from" => "onboarding@resend.dev", // precisa ser um domínio verificado no Resend
    "to" => $usuario,
    "subject" => "Código de recuperação de senha",
    "html" => "<p>Olá! Este é seu código de recuperação de senha: " . $codigo . "</p>"
];

$ch = curl_init('https://api.resend.com/emails');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if ($httpCode === 200 || $httpCode === 202) {
    // $_SESSION['success'] = 'E-mail enviado com sucesso!';
    // header('location: index.php?rota=verificar_codigo');
    echo json_encode(['status' => 'ok', 'mensagem' => 'Código enviado com sucesso para o e-mail informado']);
    // header('location: index.php?rota=verificar_codigo');
    exit;
} else {
    // echo "Erro ao enviar: $httpCode\n";
    // echo "Resposta: $response";
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao enviar e-mail']);
    exit;
}