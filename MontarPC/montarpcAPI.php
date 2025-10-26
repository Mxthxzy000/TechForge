<?php
require '../config.php';
require '../session.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['idUsuario'])) {
    echo json_encode(['error' => 'Usuário não autenticado', 'needsLogin' => true]);
    exit;
}

$idUsuario = $_SESSION['idUsuario'];

$build = $_SESSION['pc_build'] ?? null;

if (!$build) {
    echo json_encode(['error' => 'Nenhuma montagem encontrada']);
    exit;
}

$nomeSetup = trim($_POST['nomeSetup'] ?? $build['nomeSetup'] ?? '');
$observacoes = trim($_POST['observacoes'] ?? $build['observacoes'] ?? '');

if (empty($nomeSetup)) {
    echo json_encode(['error' => 'Nome do setup é obrigatório']);
    exit;
}

$cpu = $build['cpu']['name'] ?? '';
$gpu = $build['gpu']['name'] ?? '';
$placaMae = $build['placaMae']['name'] ?? '';
$ram = $build['ram']['name'] ?? '';
$armazenamento = $build['armazenamento']['name'] ?? '';
$fonte = $build['fonte']['name'] ?? '';
$gabinete = $build['gabinete']['name'] ?? '';
$cooler = $build['cooler']['name'] ?? '';

$precoEstimado = 0;
foreach ($build as $key => $component) {
    if (is_array($component) && isset($component['price'])) {
        $precoEstimado += floatval($component['price']);
    }
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
