<?php
include '../config.php';
header('Content-Type: application/json; charset=utf-8');

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

    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $linha = $r['linhaProduto'] ?: 'Genérico';
            if (!isset($data['lines'][$linha])) $data['lines'][$linha] = [];
            $data['lines'][$linha][] = ['tipo' => $r['tipoProduto'], 'count' => $r['qtd']];
        }
    }

    $tagCounts = [];
    $res2 = $conn->query("SELECT tagsProduto FROM produtos");
    if ($res2) {
        while ($row = $res2->fetch_assoc()) {
            $tags = explode(',', $row['tagsProduto']);
            foreach ($tags as $t) {
                $t = trim($t);
                if (!$t) continue;
                $tagCounts[$t] = ($tagCounts[$t] ?? 0) + 1;
            }
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



if ($action === 'search_suggest') {
    $q = $conn->real_escape_string($_GET['q']);
    $res = $conn->query("SELECT nomeProduto FROM produtos WHERE nomeProduto LIKE '%$q%' LIMIT 3");
    echo json_encode($res ? $res->fetch_all(MYSQLI_ASSOC) : []);
    exit;
}
