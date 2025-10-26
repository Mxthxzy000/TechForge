<?php
require '../config.php';
require '../session.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['idUsuario'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

$idUsuario = $_SESSION['idUsuario'];
$action = $_GET['action'] ?? '';

if ($action === 'getOrders') {
    $sql = "SELECT p.*, e.cidade, e.estado 
            FROM pedido p
            LEFT JOIN endereco e ON p.idEndereco = e.idEndereco
            WHERE p.idUsuario = ?
            ORDER BY p.dataPedido DESC
            LIMIT 10";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    
    echo json_encode(['orders' => $orders]);
    exit;
}

if ($action === 'getOrderItems') {
    $idPedido = intval($_GET['idPedido'] ?? 0);
    
    if ($idPedido <= 0) {
        echo json_encode(['error' => 'ID de pedido inválido']);
        exit;
    }
    
    // Verify order belongs to user
    $stmt = $conn->prepare("SELECT idPedido FROM pedido WHERE idPedido = ? AND idUsuario = ?");
    $stmt->bind_param('ii', $idPedido, $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['error' => 'Pedido não encontrado']);
        exit;
    }
    
    $sql = "SELECT ip.*, p.nomeProduto, p.imagem as imagemProduto
            FROM item_pedido ip
            JOIN produtos p ON ip.idProduto = p.idProduto
            WHERE ip.idPedido = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $idPedido);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    
    echo json_encode(['items' => $items]);
    exit;
}

if ($action === 'getBuilds') {
    $sql = "SELECT * FROM servico_montagem WHERE idUsuario = ? ORDER BY dataSolicitacao DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $builds = [];
    while ($row = $result->fetch_assoc()) {
        $builds[] = $row;
    }
    
    echo json_encode(['builds' => $builds]);
    exit;
}

echo json_encode(['error' => 'Ação inválida']);
exit;
?>
