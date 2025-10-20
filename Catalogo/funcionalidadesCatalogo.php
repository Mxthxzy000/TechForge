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
    
    // Filtro por linha (Intel/AMD)
    if (!empty($linha)) {
        $condicoes[] = "linhaProduto = '" . $conn->real_escape_string($linha) . "'";
    }
    
    // Filtro por tipo/tag - CORREÇÃO APLICADA
    if (!empty($tag)) {
        // Mapeamento direto - os valores já estão corretos no banco
        $condicoes[] = "tipoProduto = '" . $conn->real_escape_string($tag) . "'";
    }
    
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

// Ação para pegar tags populares
if ($action === 'getPopularTags') {
    $sql = "SELECT tagsProduto FROM produtos WHERE tagsProduto IS NOT NULL AND tagsProduto != ''";
    $res = $conn->query($sql);
    
    $all_tags = [];
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $tags = explode(',', $row['tagsProduto']);
            foreach ($tags as $tag) {
                $clean_tag = trim($tag);
                if (!empty($clean_tag)) {
                    $all_tags[] = $clean_tag;
                }
            }
        }
    }
    
    // Contar frequência e pegar as 10 mais populares
    $tag_count = array_count_values($all_tags);
    arsort($tag_count);
    $popularTags = array_slice($tag_count, 0, 10);
    
    echo json_encode(['tags' => $popularTags]);
    exit;
}

// NOVA AÇÃO PARA FILTRAR POR TAGS (TAGS REAIS DO CAMPO tagsProduto)
if ($action === 'filterByTag') {
    $tag = $_GET['tag'] ?? '';
    
    if (empty($tag)) {
        echo json_encode(['produtos' => []]);
        exit;
    }

    $tag = $conn->real_escape_string($tag);
    
    // Busca produtos onde a tag está presente no campo tagsProduto
    $sql = "SELECT idProduto, nomeProduto, precoProduto, imagemProduto, linhaProduto, tipoProduto 
            FROM produtos 
            WHERE tagsProduto LIKE '%$tag%'";

    $res = $conn->query($sql);

    if (!$res) {
        echo json_encode(['error' => 'Erro ao buscar produtos por tag', 'sql_error' => $conn->error]);
        exit;
    }

    $produtos = [];
    while ($row = $res->fetch_assoc()) {
        $produtos[] = $row;
    }

    echo json_encode(['produtos' => $produtos]);
    exit;
}

// AÇÃO PARA FILTRAR POR MÚLTIPLAS TAGS - VERSÃO CORRIGIDA
// AÇÃO PARA FILTRAR POR MÚLTIPLAS TAGS - AND (AMBAS AS TAGS)
if ($action === 'filterMultipleTags') {
    // Verifica se tags é array ou string única
    $tags = $_GET['tags'] ?? [];
    
    // Se for string única, converte para array
    if (!is_array($tags)) {
        $tags = [$tags];
    }
    
    if (empty($tags)) {
        echo json_encode(['produtos' => []]);
        exit;
    }

    // Para cada tag, adiciona uma condição AND (todas as tags devem estar presentes)
    $sql = "SELECT idProduto, nomeProduto, precoProduto, imagemProduto, linhaProduto, tipoProduto 
            FROM produtos 
            WHERE 1=1";
    
    foreach ($tags as $tag) {
        $clean_tag = $conn->real_escape_string(trim($tag));
        if (!empty($clean_tag)) {
            $sql .= " AND tagsProduto LIKE '%$clean_tag%'";
        }
    }

    $res = $conn->query($sql);

    if (!$res) {
        echo json_encode(['error' => 'Erro ao buscar produtos por múltiplas tags', 'sql_error' => $conn->error]);
        exit;
    }

    $produtos = [];
    while ($row = $res->fetch_assoc()) {
        $produtos[] = $row;
    }

    echo json_encode(['produtos' => $produtos]);
    exit;
}

// Ação para debug - ver todas as tags e quantos produtos cada uma tem
if ($action === 'debugTags') {
    $sql = "SELECT tagsProduto FROM produtos WHERE tagsProduto IS NOT NULL AND tagsProduto != ''";
    $res = $conn->query($sql);
    
    $all_tags = [];
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $tags = explode(',', $row['tagsProduto']);
            foreach ($tags as $tag) {
                $clean_tag = trim($tag);
                if (!empty($clean_tag)) {
                    $all_tags[] = $clean_tag;
                }
            }
        }
    }
    
    // Contar frequência
    $tag_count = array_count_values($all_tags);
    arsort($tag_count);
    
    echo json_encode(['tag_count' => $tag_count]);
    exit;
}

echo json_encode(['error' => 'Ação inválida']);
exit;
?>