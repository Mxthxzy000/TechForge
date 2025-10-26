<?php
header('Content-Type: application/json; charset=utf-8');
require '../config.php';
require '../session.php';

if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['error' => 'Erro de conexão com o banco: ' . $conn->connect_error]);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'addToCart') {
    if (empty($_SESSION['idUsuario'])) {
        echo json_encode(['error' => 'Usuário não autenticado', 'needsLogin' => true]);
        exit;
    }
    
    $idUsuario = $_SESSION['idUsuario'];
    $idProduto = intval($_POST['idProduto'] ?? 0);
    $quantidade = intval($_POST['quantidade'] ?? 1);
    
    if ($idProduto <= 0) {
        echo json_encode(['error' => 'Produto inválido']);
        exit;
    }
    
    // Get or create active cart for user
    $stmt = $conn->prepare("SELECT idCarrinho FROM carrinho WHERE idUsuario = ? AND status = 'ativo' LIMIT 1");
    $stmt->bind_param('i', $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $idCarrinho = $row['idCarrinho'];
    } else {
        // Create new cart
        $stmt = $conn->prepare("INSERT INTO carrinho (idUsuario, status) VALUES (?, 'ativo')");
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $idCarrinho = $conn->insert_id;
    }
    
    // Check if product already in cart
    $stmt = $conn->prepare("SELECT idItem, quantidade FROM item_carrinho WHERE idCarrinho = ? AND idProduto = ?");
    $stmt->bind_param('ii', $idCarrinho, $idProduto);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Update quantity
        $novaQuantidade = $row['quantidade'] + $quantidade;
        $stmt = $conn->prepare("UPDATE item_carrinho SET quantidade = ? WHERE idItem = ?");
        $stmt->bind_param('ii', $novaQuantidade, $row['idItem']);
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Quantidade atualizada']);
    } else {
        // Get product price
        $stmt = $conn->prepare("SELECT valorProduto FROM produtos WHERE idProduto = ?");
        $stmt->bind_param('i', $idProduto);
        $stmt->execute();
        $result = $stmt->get_result();
        $produto = $result->fetch_assoc();
        
        // Insert new item
        $stmt = $conn->prepare("INSERT INTO item_carrinho (idCarrinho, idProduto, quantidade, precoUnitario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('iiid', $idCarrinho, $idProduto, $quantidade, $produto['valorProduto']);
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Produto adicionado ao carrinho']);
    }
    exit;
}

if ($action === 'getCart') {
    if (empty($_SESSION['idUsuario'])) {
        echo json_encode(['produtos' => [], 'needsLogin' => true]);
        exit;
    }
    
    $idUsuario = $_SESSION['idUsuario'];
    
    $sql = "SELECT p.idProduto, p.nomeProduto, p.valorProduto as precoProduto, p.imagem as imagemProduto, 
                   p.linhaProduto, p.tipoProduto, ic.quantidade, ic.idItem
            FROM carrinho c
            JOIN item_carrinho ic ON c.idCarrinho = ic.idCarrinho
            JOIN produtos p ON ic.idProduto = p.idProduto
            WHERE c.idUsuario = ? AND c.status = 'ativo'";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $produtos = [];
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
    
    echo json_encode(['produtos' => $produtos]);
    exit;
}

if ($action === 'updateQuantity') {
    if (empty($_SESSION['idUsuario'])) {
        echo json_encode(['error' => 'Usuário não autenticado']);
        exit;
    }
    
    $idProduto = intval($_POST['idProduto'] ?? 0);
    $quantidade = intval($_POST['quantidade'] ?? 1);
    $idUsuario = $_SESSION['idUsuario'];
    
    if ($quantidade < 1) {
        echo json_encode(['error' => 'Quantidade inválida']);
        exit;
    }
    
    $sql = "UPDATE item_carrinho ic
            JOIN carrinho c ON ic.idCarrinho = c.idCarrinho
            SET ic.quantidade = ?
            WHERE c.idUsuario = ? AND ic.idProduto = ? AND c.status = 'ativo'";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iii', $quantidade, $idUsuario, $idProduto);
    $stmt->execute();
    
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'removeItem') {
    if (empty($_SESSION['idUsuario'])) {
        echo json_encode(['error' => 'Usuário não autenticado']);
        exit;
    }
    
    $idProduto = intval($_POST['idProduto'] ?? 0);
    $idUsuario = $_SESSION['idUsuario'];
    
    $sql = "DELETE ic FROM item_carrinho ic
            JOIN carrinho c ON ic.idCarrinho = c.idCarrinho
            WHERE c.idUsuario = ? AND ic.idProduto = ? AND c.status = 'ativo'";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $idUsuario, $idProduto);
    $stmt->execute();
    
    echo json_encode(['success' => true]);
    exit;
}

// Get cart items details by IDs
if ($action === 'getCartItems') {
    $ids = $_GET['ids'] ?? '';
    
    if (empty($ids)) {
        echo json_encode(['produtos' => []]);
        exit;
    }
    
    // Convert comma-separated IDs to array and sanitize
    $idArray = explode(',', $ids);
    $idArray = array_map('intval', $idArray);
    $idArray = array_filter($idArray);
    
    if (empty($idArray)) {
        echo json_encode(['produtos' => []]);
        exit;
    }
    
    $placeholders = implode(',', array_fill(0, count($idArray), '?'));
    
    $stmt = $conn->prepare("SELECT idProduto, nomeProduto, valorProduto as precoProduto, imagem as imagemProduto, linhaProduto, tipoProduto 
                            FROM produtos 
                            WHERE idProduto IN ($placeholders)");
    
    $types = str_repeat('i', count($idArray));
    $stmt->bind_param($types, ...$idArray);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $produtos = [];
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
    
    echo json_encode(['produtos' => $produtos]);
    exit;
}

// Get single product details
if ($action === 'getProduct') {
    $id = intval($_GET['id'] ?? 0);
    
    if ($id <= 0) {
        echo json_encode(['error' => 'ID inválido']);
        exit;
    }
    
    $stmt = $conn->prepare("SELECT idProduto, nomeProduto, valorProduto as precoProduto, imagem as imagemProduto, linhaProduto, tipoProduto 
                            FROM produtos 
                            WHERE idProduto = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(['produto' => $row]);
    } else {
        echo json_encode(['error' => 'Produto não encontrado']);
    }
    exit;
}

echo json_encode(['error' => 'Ação inválida']);
exit;
?>
