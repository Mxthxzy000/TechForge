<?php
header('Content-Type: application/json; charset=utf-8');
include '../config.php';

// erro de conexão
if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['error' => 'Erro de conexão com o banco: ' . $conn->connect_error]);
    exit;
}

$action = $_GET['action'] ?? '';

if ($action === 'getAll') {
    $sql = "SELECT idProduto, nomeProduto, precoProduto, imagemProduto, linhaProduto, tipoProduto 
            FROM produtos";
    $res = $conn->query($sql);

    $produtos = [];
    while ($row = $res->fetch_assoc()) {
        $produtos[] = $row;
    }

    echo json_encode(['produtos' => $produtos]);
    exit;
}

if ($action === 'filter') {
    $linha = $_GET['linha'] ?? '';
    $tag = $_GET['tag'] ?? '';
    $precoMin = $_GET['precoMin'] ?? '';
    $precoMax = $_GET['precoMax'] ?? '';

    $condicoes = [];
    if (!empty($linha)) $condicoes[] = "linhaProduto = '" . $conn->real_escape_string($linha) . "'";
    if (!empty($tag))   $condicoes[] = "tipoProduto = '" . $conn->real_escape_string($tag) . "'";
    if (!empty($precoMin)) $condicoes[] = "precoProduto >= " . floatval($precoMin);
    if (!empty($precoMax)) $condicoes[] = "precoProduto <= " . floatval($precoMax);

    $where = count($condicoes) > 0 ? 'WHERE ' . implode(' AND ', $condicoes) : '';

    $sql = "SELECT idProduto, nomeProduto, precoProduto, imagemProduto, linhaProduto, tipoProduto 
            FROM produtos $where";

    $res = $conn->query($sql);

    if (!$res) {
        echo json_encode(['error' => 'Erro ao buscar produtos filtrados', 'sql_error' => $conn->error]);
        exit;
    }

    $produtos = [];
    while ($row = $res->fetch_assoc()) {
        $produtos[] = $row;
    }

    echo json_encode(['produtos' => $produtos]);
    exit;
}

// NOVA FUNÇÃO PARA PESQUISA
if ($action === 'search') {
    $termo = $_GET['termo'] ?? '';
    
    if (empty($termo)) {
        echo json_encode(['produtos' => []]);
        exit;
    }

    $termo = $conn->real_escape_string($termo);
    
    // Busca produtos com nomes similares (máximo 3 para sugestões)
    $sql = "SELECT idProduto, nomeProduto, precoProduto, imagemProduto, linhaProduto, tipoProduto 
            FROM produtos 
            WHERE nomeProduto LIKE '%$termo%' 
            LIMIT 3";

    $res = $conn->query($sql);

    if (!$res) {
        echo json_encode(['error' => 'Erro ao buscar produtos', 'sql_error' => $conn->error]);
        exit;
    }

    $produtos = [];
    while ($row = $res->fetch_assoc()) {
        $produtos[] = $row;
    }

    echo json_encode(['produtos' => $produtos]);
    exit;
}

// NOVA FUNÇÃO PARA PESQUISA COMPLETA
if ($action === 'searchFull') {
    $termo = $_GET['termo'] ?? '';
    
    if (empty($termo)) {
        echo json_encode(['produtos' => []]);
        exit;
    }

    $termo = $conn->real_escape_string($termo);
    
    // Busca todos os produtos com nomes similares
    $sql = "SELECT idProduto, nomeProduto, precoProduto, imagemProduto, linhaProduto, tipoProduto 
            FROM produtos 
            WHERE nomeProduto LIKE '%$termo%'";

    $res = $conn->query($sql);

    if (!$res) {
        echo json_encode(['error' => 'Erro ao buscar produtos', 'sql_error' => $conn->error]);
        exit;
    }

    $produtos = [];
    while ($row = $res->fetch_assoc()) {
        $produtos[] = $row;
    }

    echo json_encode(['produtos' => $produtos]);
    exit;
}

echo json_encode(['error' => 'Ação inválida']);
exit;