<?php
require '../config.php';
require 'session-check.php';

header('Content-Type: application/json');

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Erro ao fazer upload da imagem']);
    exit;
}

$file = $_FILES['image'];

// Validate file type
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(['error' => 'Tipo de arquivo não permitido. Use JPG, PNG, GIF ou WEBP']);
    exit;
}

// Validate file size (5MB max)
if ($file['size'] > 5 * 1024 * 1024) {
    echo json_encode(['error' => 'Arquivo muito grande. Máximo 5MB']);
    exit;
}

// Create upload directory if it doesn't exist
$uploadDir = '../imagens_produtos/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'produto_' . time() . '_' . uniqid() . '.' . $extension;
$filepath = $uploadDir . $filename;

// Move uploaded file
if (!move_uploaded_file($file['tmp_name'], $filepath)) {
    echo json_encode(['error' => 'Erro ao salvar imagem']);
    exit;
}

// Return relative path for database
$relativePath = '../imagens_produtos/' . $filename;

echo json_encode([
    'success' => true,
    'message' => 'Imagem enviada com sucesso!',
    'imagePath' => $relativePath
]);
?>
