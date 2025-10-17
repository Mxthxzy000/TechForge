<?php
require '../config.php';
require '../session.php';
require '../flash.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// Validação dos campos
$nome = trim($_POST['nome'] ?? '');
$celular = trim($_POST['celular'] ?? '');
$email = trim($_POST['email'] ?? '');
$duvida = trim($_POST['duvida'] ?? '');
$mensagem = trim($_POST['mensagem'] ?? '');

if (empty($nome) || empty($celular) || empty($email) || empty($duvida) || empty($mensagem)) {
    echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios']);
    exit;
}

// Validação de email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email inválido']);
    exit;
}

// Verificar se conexão existe
if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com banco de dados']);
    exit;
}

// Inserir no banco usando prepared statement
$stmt = $conn->prepare("INSERT INTO contatos (nome, celular, email, duvida, mensagem, data_envio) VALUES (?, ?, ?, ?, ?, NOW())");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Erro ao preparar consulta']);
    exit;
}

$stmt->bind_param("sssss", $nome, $celular, $email, $duvida, $mensagem);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Mensagem enviada com sucesso!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao enviar mensagem']);
}

$stmt->close();
$conn->close();
?>
