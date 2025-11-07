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
$action = $_POST['action'] ?? '';
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'POST' && $action === 'salvarEndereco') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $rua = $data['rua'] ?? '';
    $numero = $data['numero'] ?? '';
    $complemento = $data['complemento'] ?? '';
    $bairro = $data['bairro'] ?? '';
    $cidade = $data['cidade'] ?? '';
    $estado = $data['estado'] ?? '';
    $cep = $data['cep'] ?? '';
    $tipoEndereco = $data['tipoEndereco'] ?? 'entrega';

    if (!$rua || !$numero || !$bairro || !$cidade || !$estado || !$cep) {
        echo json_encode(['success' => false, 'message' => 'Preencha todos os campos']);
        exit;
    }

    try {
        $stmt = $conn->prepare("INSERT INTO endereco (idUsuario, rua, numero, complemento, bairro, cidade, estado, cep, tipoEndereco) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('issssssss', $idUsuario, $rua, $numero, $complemento, $bairro, $cidade, $estado, $cep, $tipoEndereco);
        $stmt->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Endereço salvo com sucesso!',
            'idEndereco' => $conn->insert_id
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar endereço: ' . $e->getMessage()]);
    }
    exit;
}

if ($requestMethod === 'POST' && $action === 'salvarPagamento') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $tipo = $data['tipo'] ?? '';
    $nomeTitular = $data['nomeTitular'] ?? null;
    $numeroCartao = $data['numeroCartao'] ?? null;
    $validadeCartao = $data['validadeCartao'] ?? null;
    $chavePix = $data['chavePix'] ?? null;

    if (!$tipo) {
        echo json_encode(['success' => false, 'message' => 'Tipo de pagamento inválido']);
        exit;
    }

    try {
        // Mask card number for security
        if ($numeroCartao) {
            $numeroCartao = '**** **** **** ' . substr(preg_replace('/\D/', '', $numeroCartao), -4);
        }

        $stmt = $conn->prepare("INSERT INTO formas_pagamento 
                               (idUsuario, tipoPagamento, nomeTitular, numeroCartao, validadeCartao, chavePix) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isssss', $idUsuario, $tipo, $nomeTitular, $numeroCartao, $validadeCartao, $chavePix);
        $stmt->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Método de pagamento salvo com sucesso!',
            'idFormaPagamento' => $conn->insert_id
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar pagamento: ' . $e->getMessage()]);
    }
    exit;
}

if ($action === 'finalizarPedido') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
        exit;
    }

    $idEndereco = $data['endereco'] ?? 0;
    $freteType = $data['freteType'] ?? '';
    $freteCost = floatval($data['freteCost'] ?? 0);
    $pagamentoId = $data['pagamentoId'] ?? 0;
    $novoPagamento = $data['novoPagamento'] ?? null;
    $montagemId = $data['montagemId'] ?? null;

    if (!$idEndereco || !$freteType) {
        echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
        exit;
    }

    try {
        $conn->begin_transaction();

        $total = 0;
        
        if ($montagemId) {
            $stmt = $conn->prepare("SELECT precoEstimado FROM servico_montagem WHERE idMontagem = ? AND idUsuario = ?");
            $stmt->bind_param('ii', $montagemId, $idUsuario);
            $stmt->execute();
            $resultMontagem = $stmt->get_result()->fetch_assoc();
            $total = $resultMontagem['precoEstimado'] ?? 0;
        } else {
            $stmt = $conn->prepare("SELECT SUM(ic.precoUnitario * ic.quantidade) as total 
                                   FROM carrinho c 
                                   JOIN item_carrinho ic ON c.idCarrinho = ic.idCarrinho 
                                   WHERE c.idUsuario = ? AND c.status = 'ativo'");
            $stmt->bind_param('i', $idUsuario);
            $stmt->execute();
            $resultCarrinho = $stmt->get_result()->fetch_assoc();
            $total = isset($resultCarrinho['total']) ? $resultCarrinho['total'] : 0;
        }

        $total += $freteCost;

        $status = 'pendente';
        
        if ($novoPagamento) {
            $tipoPagamento = $novoPagamento['tipo'];
        } else {
            $stmt2 = $conn->prepare("SELECT tipoPagamento FROM formas_pagamento WHERE idFormaPagamento = ?");
            $stmt2->bind_param('i', $pagamentoId);
            $stmt2->execute();
            $resultPag = $stmt2->get_result()->fetch_assoc();
            $tipoPagamento = $resultPag ? $resultPag['tipoPagamento'] : 'cartao_credito';
        }

        $metodoPagamento = str_replace('_', '', $tipoPagamento);
        
        $stmt = $conn->prepare("INSERT INTO pedido (idUsuario, idEndereco, status, total, metodoPagamento) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('iisds', $idUsuario, $idEndereco, $status, $total, $metodoPagamento);
        $stmt->execute();
        $idPedido = $conn->insert_id;

        if ($montagemId) {
            $stmt = $conn->prepare("UPDATE servico_montagem SET status = 'em montagem' WHERE idMontagem = ?");
            $stmt->bind_param('i', $montagemId);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("SELECT ic.idProduto, ic.quantidade, ic.precoUnitario 
                                   FROM carrinho c 
                                   JOIN item_carrinho ic ON c.idCarrinho = ic.idCarrinho 
                                   WHERE c.idUsuario = ? AND c.status = 'ativo'");
            $stmt->bind_param('i', $idUsuario);
            $stmt->execute();
            $resultItems = $stmt->get_result();

            while ($item = $resultItems->fetch_assoc()) {
                $stmt2 = $conn->prepare("INSERT INTO item_pedido (idPedido, idProduto, quantidade, precoUnitario) 
                                        VALUES (?, ?, ?, ?)");
                $stmt2->bind_param('iiid', $idPedido, $item['idProduto'], $item['quantidade'], $item['precoUnitario']);
                $stmt2->execute();
            }

            $stmt = $conn->prepare("UPDATE carrinho SET status = 'finalizado' 
                                   WHERE idUsuario = ? AND status = 'ativo'");
            $stmt->bind_param('i', $idUsuario);
            $stmt->execute();
        }

        if ($novoPagamento) {
            $tipo = $novoPagamento['tipo'];
            $nomeTitular = $novoPagamento['nomeTitular'] ?? null;
            $numeroCartao = $novoPagamento['numeroCartao'] ?? null;
            $validadeCartao = $novoPagamento['validadeCartao'] ?? null;
            $chavePix = $novoPagamento['chavePix'] ?? null;

            if ($numeroCartao) {
                $numeroCartao = '**** **** **** ' . substr(preg_replace('/\D/', '', $numeroCartao), -4);
            }

            $stmt = $conn->prepare("INSERT INTO formas_pagamento 
                                   (idUsuario, tipoPagamento, nomeTitular, numeroCartao, validadeCartao, chavePix) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('isssss', $idUsuario, $tipo, $nomeTitular, $numeroCartao, $validadeCartao, $chavePix);
            $stmt->execute();
        }

        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Pedido realizado com sucesso!',
            'pedidoId' => $idPedido,
            'total' => $total
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Erro ao processar pedido: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação inválida']);
exit;
?>
