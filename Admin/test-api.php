<?php
// Teste de conexão e sessão
require '../config.php';
require 'session-check.php';

header('Content-Type: application/json');

$response = [
    'connection' => false,
    'session' => false,
    'admin_id' => null,
    'admin_name' => null,
    'post_data' => $_POST,
    'get_data' => $_GET
];

// Testar conexão
if ($conn) {
    $response['connection'] = true;
}

// Testar sessão
if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true) {
    $response['session'] = true;
    $response['admin_id'] = $_SESSION['idAdm'] ?? null;
    $response['admin_name'] = $_SESSION['nomeAdm'] ?? null;
}

// Testar query simples
try {
    $result = $conn->query("SELECT COUNT(*) as total FROM produtos");
    $response['produtos_count'] = $result->fetch_assoc()['total'];
} catch (Exception $e) {
    $response['query_error'] = $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>