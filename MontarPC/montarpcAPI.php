<?php
require '../config.php';
require '../session.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['idUsuario'])) {
    echo json_encode(['error' => 'Usuário não autenticado', 'needsLogin' => true]);
    exit;
}

$idUsuario = $_SESSION['idUsuario'];

$nomeSetup = trim($_POST['nomeSetup'] ?? '');
$cpu = trim($_POST['cpu'] ?? '');
$gpu = trim($_POST['gpu'] ?? '');
$placaMae = trim($_POST['placaMae'] ?? '');
$ram = trim($_POST['ram'] ?? '');
$armazenamento = trim($_POST['armazenamento'] ?? '');
$fonte = trim($_POST['fonte'] ?? '');
$gabinete = trim($_POST['gabinete'] ?? '');
$cooler = trim($_POST['cooler'] ?? '');
$observacoes = trim($_POST['observacoes'] ?? '');
$precoEstimado = floatval($_POST['precoEstimado'] ?? 0);

if (empty($nomeSetup)) {
    echo json_encode(['error' => 'Nome do setup é obrigatório']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO servico_montagem (idUsuario, nomeSetup, cpu, gpu, placaMae, ram, armazenamento, fonte, gabinete, cooler, observacoes, precoEstimado, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'em análise')");

$stmt->bind_param('issssssssssd', $idUsuario, $nomeSetup, $cpu, $gpu, $placaMae, $ram, $armazenamento, $fonte, $gabinete, $cooler, $observacoes, $precoEstimado);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Montagem salva com sucesso! Você pode visualizá-la no seu perfil.']);
} else {
    echo json_encode(['error' => 'Erro ao salvar montagem']);
}

exit;
?>
