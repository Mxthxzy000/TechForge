<?php
require '../config.php';
require '../session.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método não permitido']);
    exit();
}

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

echo json_encode(['success' => true]);
?>
