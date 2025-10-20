<?php
ob_start();

include '../config.php';

ob_end_clean();
header('Content-Type: application/json; charset=utf-8');

if (!isset($conn) || $conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro de conexão com banco de dados', 'details' => $conn->connect_error ?? 'Conexão não estabelecida']);
    exit;
}

function calcDiscountPercent($qtd) {
    if ($qtd >= 1000) return 20;
    if ($qtd >= 500) return 10;
    if ($qtd >= 200) return 5;
    if ($qtd >= 100) return 3;
    return 0;
}

$action = $_GET['action'] ?? '';

if ($action === 'getFilters') {
    $data = ['lines' => [], 'topTags' => []];

    $sql = "SELECT linhaProduto, tipoProduto, COUNT(*) AS qtd FROM produtos GROUP BY linhaProduto, tipoProduto";
    $res = $conn->query($sql);

    if (!$res) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao buscar filtros', 'sql_error' => $conn->error]);
        exit;
    }

    while ($r = $res->fetch_assoc()) {
        $linha = $r['linhaProduto'] ?: 'Genérico';
        if (!isset($data['lines'][$linha])) $data['lines'][$linha] = [];
        $data['lines'][$linha][] = ['tipo' => $r['tipoProduto'], 'count' => $r['qtd']];
    }

    $tagCounts = [];
    $res2 = $conn->query("SELECT tagsProduto FROM produtos");
    
    if (!$res2) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao buscar tags', 'sql_error' => $conn->error]);
        exit;
    }
    
    while ($row = $res2->fetch_assoc()) {
        $tags = explode(',', $row['tagsProduto']);
        foreach ($tags as $t) {
            $t = trim($t);
            if (!$t) continue;
            $tagCounts[$t] = ($tagCounts[$t] ?? 0) + 1;
        }
    }

    arsort($tagCounts);
    $i = 0;
    foreach ($tagCounts as $tag => $count) {
        $data['topTags'][] = ['tag' => $tag, 'count' => $count];
        if (++$i >= 20) break;
    }

    echo json_encode($data);
    exit;
}

if ($action === 'filter') {
    $tipos = trim($_GET['type'] ?? '');
    $linhas = trim($_GET['line'] ?? '');
    $tag = trim($_GET['tag'] ?? '');
    $priceMin = floatval($_GET['price_min'] ?? 0);
    $priceMax = floatval($_GET['price_max'] ?? 0);
    $categoria = trim($_GET['categoria'] ?? '');
    $searchQuery = trim($_GET['q'] ?? '');
    
    $sql = "SELECT idProduto, nomeProduto, descricaoProduto, valorProduto, imagem, 
            linhaProduto, tipoProduto, tagsProduto, quantidadeProduto 
            FROM produtos WHERE 1=1";
    
    $params = [];
    $types = '';
    
    if (!empty($tipos)) {
        $tiposArray = explode(',', $tipos);
        $placeholders = implode(',', array_fill(0, count($tiposArray), '?'));
        $sql .= " AND tipoProduto IN ($placeholders)";
        foreach ($tiposArray as $tipo) {
            $params[] = trim($tipo);
            $types .= 's';
        }
    }
    
    if (!empty($linhas)) {
        $linhasArray = explode(',', $linhas);
        $placeholders = implode(',', array_fill(0, count($linhasArray), '?'));
        $sql .= " AND linhaProduto IN ($placeholders)";
        foreach ($linhasArray as $linha) {
            $params[] = trim($linha);
            $types .= 's';
        }
    }
    
    if (!empty($tag)) {
        $sql .= " AND tagsProduto LIKE ?";
        $params[] = "%{$tag}%";
        $types .= 's';
    }
    
    if ($priceMin > 0) {
        $sql .= " AND valorProduto >= ?";
        $params[] = $priceMin;
        $types .= 'd';
    }
    
    if ($priceMax > 0) {
        $sql .= " AND valorProduto <= ?";
        $params[] = $priceMax;
        $types .= 'd';
    }
    
    if ($categoria === 'promocoes') {
        $sql .= " AND quantidadeProduto >= 100";
    } elseif ($categoria === 'gamer') {
        $sql .= " AND (tagsProduto LIKE '%gamer%' OR tagsProduto LIKE '%gaming%')";
    }
    
    if (!empty($searchQuery)) {
        $sql .= " AND (nomeProduto LIKE ? OR descricaoProduto LIKE ?)";
        $searchTerm = "%{$searchQuery}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'ss';
    }
    
    $sql .= " ORDER BY nomeProduto ASC";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao preparar query', 'sql_error' => $conn->error]);
        exit;
    }
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao executar query', 'sql_error' => $stmt->error]);
        $stmt->close();
        exit;
    }
    
    $result = $stmt->get_result();
    
    $produtos = [];
    while ($row = $result->fetch_assoc()) {
        $discountPct = calcDiscountPercent($row['quantidadeProduto']);
        $valorOriginal = floatval($row['valorProduto']);
        $valorComDesconto = $valorOriginal * (1 - $discountPct / 100);
        
        $produtos[] = [
            'id' => $row['idProduto'],
            'nome' => $row['nomeProduto'],
            'descricao' => $row['descricaoProduto'],
            'valor' => $valorOriginal,
            'valor_com_desconto' => number_format($valorComDesconto, 2, '.', ''),
            'discount_pct' => $discountPct,
            'imagem' => $row['imagem'] ?: '../imagens/produto-placeholder.png',
            'linha' => $row['linhaProduto'],
            'tipo' => $row['tipoProduto'],
            'tags' => $row['tagsProduto']
        ];
    }
    
    echo json_encode($produtos);
    $stmt->close();
    exit;
}

if ($action === 'search_suggest') {
    $q = trim($_GET['q'] ?? '');
    
    if (empty($q)) {
        echo json_encode([]);
        exit;
    }
    
    $stmt = $conn->prepare("SELECT nomeProduto FROM produtos WHERE nomeProduto LIKE ? LIMIT 3");
    
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao preparar query de sugestões', 'sql_error' => $conn->error]);
        exit;
    }
    
    $searchTerm = "%{$q}%";
    $stmt->bind_param("s", $searchTerm);
    
    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao buscar sugestões', 'sql_error' => $stmt->error]);
        $stmt->close();
        exit;
    }
    
    $result = $stmt->get_result();
    
    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }
    
    echo json_encode($suggestions);
    $stmt->close();
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Action inválida ou não especificada']);

$conn->close();
?>
