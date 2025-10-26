<?php
require '../config.php';
require '../session.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['idUsuario'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
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
    
    echo json_encode(['addresses' => $addresses]);
    exit;
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
        echo json_encode(['error' => 'CEP, rua, bairro, cidade e estado são obrigatórios']);
        exit;
    }
    
    $stmt = $conn->prepare("INSERT INTO endereco (idUsuario, cep, rua, numero, complemento, bairro, cidade, estado, tipoEndereco) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('issssssss', $idUsuario, $cep, $rua, $numero, $complemento, $bairro, $cidade, $estado, $tipoEndereco);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Endereço adicionado com sucesso!']);
    } else {
        echo json_encode(['error' => 'Erro ao adicionar endereço']);
    }
    exit;
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
        echo json_encode(['error' => 'ID de endereço inválido']);
        exit;
    }
    
    if (empty($cep) || empty($rua) || empty($bairro) || empty($cidade) || empty($estado)) {
        echo json_encode(['error' => 'CEP, rua, bairro, cidade e estado são obrigatórios']);
        exit;
    }
    
    $stmt = $conn->prepare("UPDATE endereco SET cep = ?, rua = ?, numero = ?, complemento = ?, bairro = ?, cidade = ?, estado = ?, tipoEndereco = ? WHERE idEndereco = ? AND idUsuario = ?");
    $stmt->bind_param('ssssssssii', $cep, $rua, $numero, $complemento, $bairro, $cidade, $estado, $tipoEndereco, $idEndereco, $idUsuario);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Endereço atualizado com sucesso!']);
    } else {
        echo json_encode(['error' => 'Erro ao atualizar endereço']);
    }
    exit;
}

if ($action === 'deleteAddress') {
    $idEndereco = intval($_POST['idEndereco'] ?? 0);
    
    if ($idEndereco <= 0) {
        echo json_encode(['error' => 'ID de endereço inválido']);
        exit;
    }
    
    $stmt = $conn->prepare("DELETE FROM endereco WHERE idEndereco = ? AND idUsuario = ?");
    $stmt->bind_param('ii', $idEndereco, $idUsuario);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Endereço removido com sucesso!']);
    } else {
        echo json_encode(['error' => 'Erro ao remover endereço']);
    }
    exit;
}

if ($action === 'searchCEP') {
    $cep = preg_replace('/[^0-9]/', '', $_GET['cep'] ?? '');
    
    if (strlen($cep) !== 8) {
        echo json_encode(['error' => 'CEP inválido']);
        exit;
    }
    
    $url = "https://viacep.com.br/ws/{$cep}/json/";
    $response = @file_get_contents($url);
    
    if ($response === false) {
        echo json_encode(['error' => 'Erro ao buscar CEP']);
        exit;
    }
    
    $data = json_decode($response, true);
    
    if (isset($data['erro'])) {
        echo json_encode(['error' => 'CEP não encontrado']);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'rua' => $data['logradouro'] ?? '',
        'bairro' => $data['bairro'] ?? '',
        'cidade' => $data['localidade'] ?? '',
        'estado' => $data['uf'] ?? ''
    ]);
    exit;
}

echo json_encode(['error' => 'Ação inválida']);
exit;
?>
