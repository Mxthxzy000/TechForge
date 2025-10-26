<?php
require '../config.php';
require '../session.php';

header('Content-Type: application/json');

if (empty($_SESSION['idUsuario'])) {
    echo json_encode(['error' => 'Você precisa estar logado']);
    exit;
}

if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Erro ao fazer upload da foto']);
    exit;
}

$idUsuario = $_SESSION['idUsuario'];
$file = $_FILES['photo'];

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
$uploadDir = '../imagens/usuarios/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'user_' . $idUsuario . '_' . time() . '.' . $extension;
$filepath = $uploadDir . $filename;

// Move uploaded file
if (!move_uploaded_file($file['tmp_name'], $filepath)) {
    echo json_encode(['error' => 'Erro ao salvar foto']);
    exit;
}

// Update database
$photoPath = '../imagens/usuarios/' . $filename;
$sql = "UPDATE usuario SET fotoUsuario = ? WHERE idUsuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $photoPath, $idUsuario);

if ($stmt->execute()) {
    // Update session
    $_SESSION['fotoUsuario'] = $photoPath;
    
    echo json_encode([
        'success' => true,
        'message' => 'Foto atualizada com sucesso!',
        'photoPath' => $photoPath
    ]);
} else {
    // Delete uploaded file if database update fails
    unlink($filepath);
    echo json_encode(['error' => 'Erro ao atualizar foto no banco de dados']);
}

$stmt->close();
$conn->close();
?>
