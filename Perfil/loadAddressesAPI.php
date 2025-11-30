<?php
require '../config.php';
require '../session.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['idUsuario'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

$idUsuario = $_SESSION['idUsuario'];

$stmt = $conn->prepare("SELECT * FROM endereco WHERE idUsuario = ? ORDER BY tipoEndereco, idEndereco");
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

$addresses = [];
while ($row = $result->fetch_assoc()) {
    $addresses[] = $row;
}

echo json_encode(['addresses' => $addresses]);
$stmt->close();
$conn->close();
?>