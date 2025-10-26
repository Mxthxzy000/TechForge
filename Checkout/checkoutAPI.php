<?php
require '../config.php';
require '../session.php';

header('Content-Type: application/json');

if (empty($_SESSION['idUsuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$idUsuario = $_SESSION['idUsuario'];

try {
    switch ($action) {
        case 'createOrder':
            // Get cart items
            $stmt = $conn->prepare("
                SELECT ic.*, p.valorProduto, p.nomeProduto
                FROM item_carrinho ic
                JOIN produtos p ON ic.idProduto = p.idProduto
                WHERE ic.idCarrinho = (SELECT idCarrinho FROM carrinho WHERE idUsuario = ? LIMIT 1)
            ");
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            
            if (empty($items)) {
                echo json_encode(['success' => false, 'message' => 'Carrinho vazio']);
                exit;
            }
            
            // Calculate total
            $total = 0;
            foreach ($items as $item) {
                $total += $item['valorProduto'] * $item['quantidade'];
            }
            
            // Create order
            $stmt = $conn->prepare("
                INSERT INTO pedido (idUsuario, idEndereco, total, metodoPagamento, status, dataPedido)
                VALUES (?, ?, ?, ?, 'pendente', NOW())
            ");
            $stmt->bind_param("iids", 
                $idUsuario,
                $_POST['idEndereco'],
                $total,
                $_POST['metodoPagamento']
            );
            $stmt->execute();
            $idPedido = $conn->insert_id;
            
            // Add order items
            $stmt = $conn->prepare("
                INSERT INTO item_pedido (idPedido, idProduto, quantidade, precoUnitario)
                VALUES (?, ?, ?, ?)
            ");
            
            foreach ($items as $item) {
                $stmt->bind_param("iiid",
                    $idPedido,
                    $item['idProduto'],
                    $item['quantidade'],
                    $item['valorProduto']
                );
                $stmt->execute();
            }
            
            // Clear cart
            $stmt = $conn->prepare("
                DELETE FROM item_carrinho 
                WHERE idCarrinho = (SELECT idCarrinho FROM carrinho WHERE idUsuario = ?)
            ");
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            
            echo json_encode([
                'success' => true, 
                'message' => 'Pedido criado com sucesso!',
                'idPedido' => $idPedido
            ]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Ação inválida']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}
?>
