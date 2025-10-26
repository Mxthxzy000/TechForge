<?php
require '../config.php';
require '../session.php';

header('Content-Type: application/json');

if (empty($_SESSION['idUsuario'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

$idUsuario = $_SESSION['idUsuario'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'getPaymentMethods':
        getPaymentMethods($conn, $idUsuario);
        break;
    
    case 'addPaymentMethod':
        addPaymentMethod($conn, $idUsuario);
        break;
    
    case 'updatePaymentMethod':
        updatePaymentMethod($conn, $idUsuario);
        break;
    
    case 'deletePaymentMethod':
        deletePaymentMethod($conn, $idUsuario);
        break;
    
    default:
        echo json_encode(['error' => 'Ação inválida']);
        break;
}

function getPaymentMethods($conn, $idUsuario) {
    $sql = "SELECT idFormaPagamento, tipoPagamento, nomeTitular, numeroCartao, validadeCartao, 
            bandeiraCartao, chavePix, cpfTitular, dataCadastro 
            FROM formas_pagamento 
            WHERE idUsuario = ? 
            ORDER BY dataCadastro DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $paymentMethods = [];
    while ($row = $result->fetch_assoc()) {
        // Mask card number for security
        if (!empty($row['numeroCartao'])) {
            $row['numeroCartao'] = maskCardNumber($row['numeroCartao']);
        }
        $paymentMethods[] = $row;
    }
    
    echo json_encode(['paymentMethods' => $paymentMethods]);
    $stmt->close();
}

function addPaymentMethod($conn, $idUsuario) {
    $tipoPagamento = $_POST['tipoPagamento'] ?? '';
    $nomeTitular = $_POST['nomeTitular'] ?? null;
    $numeroCartao = $_POST['numeroCartao'] ?? null;
    $validadeCartao = $_POST['validadeCartao'] ?? null;
    $bandeiraCartao = $_POST['bandeiraCartao'] ?? null;
    $chavePix = $_POST['chavePix'] ?? null;
    $cpfTitular = $_POST['cpfTitular'] ?? null;
    
    // Validate required fields based on payment type
    if ($tipoPagamento === 'cartao_credito') {
        if (empty($nomeTitular) || empty($numeroCartao) || empty($validadeCartao) || empty($bandeiraCartao)) {
            echo json_encode(['error' => 'Preencha todos os campos obrigatórios do cartão']);
            return;
        }
        // Mask card number (store only last 4 digits)
        $numeroCartao = maskCardNumberForStorage($numeroCartao);
    } elseif ($tipoPagamento === 'pix') {
        if (empty($chavePix)) {
            echo json_encode(['error' => 'Informe a chave PIX']);
            return;
        }
    }
    
    $sql = "INSERT INTO formas_pagamento (idUsuario, tipoPagamento, nomeTitular, numeroCartao, 
            validadeCartao, bandeiraCartao, chavePix, cpfTitular) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $idUsuario, $tipoPagamento, $nomeTitular, $numeroCartao, 
                      $validadeCartao, $bandeiraCartao, $chavePix, $cpfTitular);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Forma de pagamento adicionada com sucesso!'
        ]);
    } else {
        echo json_encode(['error' => 'Erro ao adicionar forma de pagamento']);
    }
    
    $stmt->close();
}

function updatePaymentMethod($conn, $idUsuario) {
    $idFormaPagamento = $_POST['idFormaPagamento'] ?? 0;
    $nomeTitular = $_POST['nomeTitular'] ?? null;
    $validadeCartao = $_POST['validadeCartao'] ?? null;
    $bandeiraCartao = $_POST['bandeiraCartao'] ?? null;
    $chavePix = $_POST['chavePix'] ?? null;
    $cpfTitular = $_POST['cpfTitular'] ?? null;
    
    // Verify ownership
    $checkSql = "SELECT idFormaPagamento FROM formas_pagamento WHERE idFormaPagamento = ? AND idUsuario = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $idFormaPagamento, $idUsuario);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows === 0) {
        echo json_encode(['error' => 'Forma de pagamento não encontrada']);
        $checkStmt->close();
        return;
    }
    $checkStmt->close();
    
    $sql = "UPDATE formas_pagamento 
            SET nomeTitular = ?, validadeCartao = ?, bandeiraCartao = ?, chavePix = ?, cpfTitular = ? 
            WHERE idFormaPagamento = ? AND idUsuario = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssii", $nomeTitular, $validadeCartao, $bandeiraCartao, $chavePix, 
                      $cpfTitular, $idFormaPagamento, $idUsuario);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Forma de pagamento atualizada com sucesso!'
        ]);
    } else {
        echo json_encode(['error' => 'Erro ao atualizar forma de pagamento']);
    }
    
    $stmt->close();
}

function deletePaymentMethod($conn, $idUsuario) {
    $idFormaPagamento = $_POST['idFormaPagamento'] ?? 0;
    
    $sql = "DELETE FROM formas_pagamento WHERE idFormaPagamento = ? AND idUsuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $idFormaPagamento, $idUsuario);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Forma de pagamento removida com sucesso!'
        ]);
    } else {
        echo json_encode(['error' => 'Erro ao remover forma de pagamento']);
    }
    
    $stmt->close();
}

function maskCardNumber($cardNumber) {
    if (strlen($cardNumber) <= 4) {
        return $cardNumber;
    }
    return '**** **** **** ' . substr($cardNumber, -4);
}

function maskCardNumberForStorage($cardNumber) {
    // Remove spaces and non-numeric characters
    $cardNumber = preg_replace('/\D/', '', $cardNumber);
    // Store only last 4 digits
    return substr($cardNumber, -4);
}

$conn->close();
?>
