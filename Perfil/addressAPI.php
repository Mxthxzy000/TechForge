<?php
require '../config.php';
require '../session.php';

// Garantir que não há saída antes do JSON
ob_start();

header('Content-Type: application/json; charset=utf-8');

// Função para enviar resposta JSON limpa
function sendResponse($data) {
    ob_clean(); // Limpa qualquer saída anterior
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($_SESSION['idUsuario'])) {
    sendResponse(['error' => 'Usuário não autenticado']);
}

$idUsuario = $_SESSION['idUsuario'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'getAddresses') {
    $stmt = $conn->prepare("SELECT * FROM endereco WHERE idUsuario = ? ORDER BY tipoEndereco, idEndereco");
    $stmt->bind_param('i', $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $addresses = [];
    while ($row = $result->fetch_assoc()) {
        $addresses[] = $row;
    }
    
    sendResponse(['addresses' => $addresses]);
}

if ($action === 'addAddress') {
    $cep = trim($_POST['cep'] ?? '');
    $rua = trim($_POST['rua'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $complemento = trim($_POST['complemento'] ?? '');
    $bairro = trim($_POST['bairro'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $estado = trim($_POST['estado'] ?? '');
    $tipoEndereco = trim($_POST['tipoEndereco'] ?? 'entrega');
    
    if (empty($cep) || empty($rua) || empty($bairro) || empty($cidade) || empty($estado)) {
        sendResponse(['error' => 'CEP, rua, bairro, cidade e estado são obrigatórios']);
    }
    
    $stmt = $conn->prepare("INSERT INTO endereco (idUsuario, cep, rua, numero, complemento, bairro, cidade, estado, tipoEndereco) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('issssssss', $idUsuario, $cep, $rua, $numero, $complemento, $bairro, $cidade, $estado, $tipoEndereco);
    
    if ($stmt->execute()) {
        sendResponse(['success' => true, 'message' => 'Endereço adicionado com sucesso!']);
    } else {
        sendResponse(['error' => 'Erro ao adicionar endereço: ' . $stmt->error]);
    }
}

if ($action === 'updateAddress') {
    $idEndereco = intval($_POST['idEndereco'] ?? 0);
    $cep = trim($_POST['cep'] ?? '');
    $rua = trim($_POST['rua'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $complemento = trim($_POST['complemento'] ?? '');
    $bairro = trim($_POST['bairro'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $estado = trim($_POST['estado'] ?? '');
    $tipoEndereco = trim($_POST['tipoEndereco'] ?? 'entrega');
    
    if ($idEndereco <= 0) {
        sendResponse(['error' => 'ID de endereço inválido']);
    }
    
    if (empty($cep) || empty($rua) || empty($bairro) || empty($cidade) || empty($estado)) {
        sendResponse(['error' => 'CEP, rua, bairro, cidade e estado são obrigatórios']);
    }
    
    $stmt = $conn->prepare("UPDATE endereco SET cep = ?, rua = ?, numero = ?, complemento = ?, bairro = ?, cidade = ?, estado = ?, tipoEndereco = ? WHERE idEndereco = ? AND idUsuario = ?");
    $stmt->bind_param('ssssssssii', $cep, $rua, $numero, $complemento, $bairro, $cidade, $estado, $tipoEndereco, $idEndereco, $idUsuario);
    
    if ($stmt->execute()) {
        sendResponse(['success' => true, 'message' => 'Endereço atualizado com sucesso!']);
    } else {
        sendResponse(['error' => 'Erro ao atualizar endereço: ' . $stmt->error]);
    }
}

if ($action === 'deleteAddress') {
    // Log para debug
    error_log("=== DELETE ADDRESS ===");
    error_log("POST: " . print_r($_POST, true));
    error_log("ID Usuario: $idUsuario");
    
    $idEndereco = intval($_POST['idEndereco'] ?? 0);
    
    error_log("ID Endereco: $idEndereco");
    
    if ($idEndereco <= 0) {
        sendResponse([
            'error' => 'ID de endereço inválido',
            'debug' => [
                'idEndereco_recebido' => $_POST['idEndereco'] ?? 'não enviado',
                'idEndereco_processado' => $idEndereco
            ]
        ]);
    }
    
    // Verificar se o endereço existe e pertence ao usuário
    $checkStmt = $conn->prepare("SELECT idEndereco, rua, cidade FROM endereco WHERE idEndereco = ? AND idUsuario = ?");
    if (!$checkStmt) {
        sendResponse(['error' => 'Erro ao preparar consulta: ' . $conn->error]);
    }
    
    $checkStmt->bind_param('ii', $idEndereco, $idUsuario);
    
    if (!$checkStmt->execute()) {
        sendResponse(['error' => 'Erro ao executar consulta: ' . $checkStmt->error]);
    }
    
    $result = $checkStmt->get_result();
    $endereco = $result->fetch_assoc();
    
    error_log("Endereço encontrado: " . ($endereco ? "SIM" : "NÃO"));
    if ($endereco) {
        error_log("Detalhes: " . print_r($endereco, true));
    }
    
    if (!$endereco) {
        sendResponse([
            'error' => 'Endereço não encontrado ou você não tem permissão',
            'debug' => [
                'idEndereco' => $idEndereco,
                'idUsuario' => $idUsuario,
                'encontrado' => false
            ]
        ]);
    }
    
    $checkStmt->close();
    
    // Executar a exclusão
    $deleteStmt = $conn->prepare("DELETE FROM endereco WHERE idEndereco = ? AND idUsuario = ?");
    if (!$deleteStmt) {
        sendResponse(['error' => 'Erro ao preparar DELETE: ' . $conn->error]);
    }
    
    $deleteStmt->bind_param('ii', $idEndereco, $idUsuario);
    
    if (!$deleteStmt->execute()) {
        error_log("Erro ao executar DELETE: " . $deleteStmt->error);
        sendResponse([
            'error' => 'Erro ao executar DELETE',
            'debug' => [
                'mysql_error' => $deleteStmt->error,
                'mysql_errno' => $deleteStmt->errno
            ]
        ]);
    }
    
    $affected = $deleteStmt->affected_rows;
    error_log("Linhas afetadas: $affected");
    
    $deleteStmt->close();
    
    if ($affected > 0) {
        sendResponse([
            'success' => true,
            'message' => 'Endereço removido com sucesso!'
        ]);
    } else {
        sendResponse([
            'error' => 'Nenhuma linha foi afetada',
            'debug' => [
                'affected_rows' => $affected,
                'idEndereco' => $idEndereco,
                'idUsuario' => $idUsuario
            ]
        ]);
    }
}

if ($action === 'searchCEP') {
    $cep = preg_replace('/[^0-9]/', '', $_GET['cep'] ?? '');
    
    if (strlen($cep) !== 8) {
        sendResponse(['error' => 'CEP inválido']);
    }
    
    $url = "https://viacep.com.br/ws/{$cep}/json/";
    $response = @file_get_contents($url);
    
    if ($response === false) {
        sendResponse(['error' => 'Erro ao buscar CEP']);
    }
    
    $data = json_decode($response, true);
    
    if (isset($data['erro'])) {
        sendResponse(['error' => 'CEP não encontrado']);
    }
    
    sendResponse([
        'success' => true,
        'rua' => $data['logradouro'] ?? '',
        'bairro' => $data['bairro'] ?? '',
        'cidade' => $data['localidade'] ?? '',
        'estado' => $data['uf'] ?? ''
    ]);
}

sendResponse(['error' => 'Ação inválida', 'action_received' => $action]);
?>