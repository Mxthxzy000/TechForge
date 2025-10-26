<?php
require '../config.php';
require '../session.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método não permitido']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

$sessionKey = $data['sessionKey'] ?? '';
$productId = $data['productId'] ?? '';
$productName = $data['productName'] ?? '';
$productPrice = $data['productPrice'] ?? 0;

if (!$sessionKey || !$productId) {
    echo json_encode(['error' => 'Dados inválidos']);
    exit();
}

// Initialize pc_build session if not exists
if (!isset($_SESSION['pc_build'])) {
    $_SESSION['pc_build'] = [
        'cpu' => null,
        'gpu' => null,
        'placaMae' => null,
        'ram' => null,
        'armazenamento' => null,
        'fonte' => null,
        'gabinete' => null,
        'cooler' => null,
        'nomeSetup' => '',
        'observacoes' => ''
    ];
}

// Save component to session
$_SESSION['pc_build'][$sessionKey] = [
    'id' => $productId,
    'name' => $productName,
    'price' => floatval($productPrice)
];

echo json_encode(['success' => true]);
?>
