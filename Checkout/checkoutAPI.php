<?php
require '../config.php';
require '../session.php';

header('Content-Type: application/json; charset=utf-8');

// Verificar se usuário está logado
if (empty($_SESSION['idUsuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$idUsuario = $_SESSION['idUsuario'];

// Pegar dados do JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

$action = $data['action'] ?? '';

if ($action === 'finalizarPedido') {
    $idEndereco = intval($data['endereco'] ?? 0);
    $freteType = $data['freteType'] ?? '';
    $freteCost = floatval($data['freteCost'] ?? 0);
    $pagamentoId = isset($data['pagamentoId']) ? intval($data['pagamentoId']) : null;
    $novoPagamento = $data['novoPagamento'] ?? null;

    // Validações
    if (!$idEndereco || !$freteType || $freteCost < 0) {
        echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
        exit;
    }

    try {
        $conn->begin_transaction();

        // 1. Buscar total do carrinho
        $stmt = $conn->prepare("
            SELECT SUM(ic.precoUnitario * ic.quantidade) as total 
            FROM carrinho c 
            JOIN item_carrinho ic ON c.idCarrinho = ic.idCarrinho 
            WHERE c.idUsuario = ? AND c.status = 'ativo'
        ");
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $resultCarrinho = $stmt->get_result()->fetch_assoc();
        $subtotal = floatval($resultCarrinho['total'] ?? 0);

        if ($subtotal <= 0) {
            throw new Exception('Carrinho vazio ou inválido');
        }

        $total = $subtotal + $freteCost;

        // 2. Determinar método de pagamento
        if ($pagamentoId) {
            // Usar pagamento salvo
            $stmt = $conn->prepare("SELECT tipoPagamento FROM formas_pagamento WHERE idFormaPagamento = ? AND idUsuario = ?");
            $stmt->bind_param('ii', $pagamentoId, $idUsuario);
            $stmt->execute();
            $resultPag = $stmt->get_result()->fetch_assoc();
            
            if (!$resultPag) {
                throw new Exception('Método de pagamento não encontrado');
            }
            
            $metodoPagamento = $resultPag['tipoPagamento'];
        } elseif ($novoPagamento) {
            // Salvar novo pagamento
            $metodoPagamento = $novoPagamento['tipo'];
            
            $nomeTitular = $novoPagamento['nomeTitular'] ?? null;
            $numeroCartao = isset($novoPagamento['numeroCartao']) ? 
                            '**** **** **** ' . substr($novoPagamento['numeroCartao'], -4) : null;
            $validadeCartao = $novoPagamento['validadeCartao'] ?? null;
            $bandeiraCartao = $novoPagamento['bandeiraCartao'] ?? null;
            $chavePix = $novoPagamento['chavePix'] ?? null;
            
            $stmt = $conn->prepare("
                INSERT INTO formas_pagamento 
                (idUsuario, tipoPagamento, nomeTitular, numeroCartao, validadeCartao, bandeiraCartao, chavePix) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param('issssss', $idUsuario, $metodoPagamento, $nomeTitular, $numeroCartao, 
                              $validadeCartao, $bandeiraCartao, $chavePix);
            $stmt->execute();
        } else {
            throw new Exception('Método de pagamento não especificado');
        }

        // 3. Criar pedido
        $status = 'pendente';
        $metodoPagamentoSimplificado = str_replace('_', '', $metodoPagamento);
        
        $stmt = $conn->prepare("
            INSERT INTO pedido (idUsuario, idEndereco, status, total, metodoPagamento, dataPedido) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param('iisds', $idUsuario, $idEndereco, $status, $total, $metodoPagamentoSimplificado);
        $stmt->execute();
        $idPedido = $conn->insert_id;

        if (!$idPedido) {
            throw new Exception('Erro ao criar pedido');
        }

        // 4. Copiar itens do carrinho para o pedido
        $stmt = $conn->prepare("
            SELECT ic.idProduto, ic.quantidade, ic.precoUnitario 
            FROM carrinho c 
            JOIN item_carrinho ic ON c.idCarrinho = ic.idCarrinho 
            WHERE c.idUsuario = ? AND c.status = 'ativo'
        ");
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $resultItems = $stmt->get_result();

        while ($item = $resultItems->fetch_assoc()) {
            $stmt2 = $conn->prepare("
                INSERT INTO item_pedido (idPedido, idProduto, quantidade, precoUnitario) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt2->bind_param('iiid', $idPedido, $item['idProduto'], $item['quantidade'], $item['precoUnitario']);
            $stmt2->execute();
            
            // Atualizar estoque
            $stmt3 = $conn->prepare("
                UPDATE produtos 
                SET quantidadeProduto = quantidadeProduto - ?,
                    vendasProduto = vendasProduto + ?
                WHERE idProduto = ?
            ");
            $stmt3->bind_param('iii', $item['quantidade'], $item['quantidade'], $item['idProduto']);
            $stmt3->execute();
        }

        // 5. Marcar carrinho como finalizado
        $stmt = $conn->prepare("UPDATE carrinho SET status = 'finalizado' WHERE idUsuario = ? AND status = 'ativo'");
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();

        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Pedido realizado com sucesso!',
            'pedidoId' => $idPedido,
            'total' => $total
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'success' => false, 
            'message' => 'Erro ao processar pedido: ' . $e->getMessage()
        ]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação inválida']);
exit;
?>