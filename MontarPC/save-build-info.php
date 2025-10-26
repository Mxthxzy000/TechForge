<?php
require '../config.php';
require '../session.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método não permitido']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

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

$_SESSION['pc_build']['nomeSetup'] = $data['nomeSetup'] ?? '';
$_SESSION['pc_build']['observacoes'] = $data['observacoes'] ?? '';

echo json_encode(['success' => true]);
?>
