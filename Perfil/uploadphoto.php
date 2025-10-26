<?php
require '../config.php';
require '../session.php';

header('Content-Type: application/json');

if (empty($_SESSION['idUsuario'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Nenhuma imagem foi enviada ou ocorreu um erro no upload']);
    exit;
}

$idUsuario = $_SESSION['idUsuario'];
$file = $_FILES['photo'];

// Validate file type
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
$fileType = mime_content_type($file['tmp_name']);

if (!in_array($fileType, $allowedTypes)) {
    echo json_encode(['error' => 'Tipo de arquivo não permitido. Use JPG, PNG, GIF ou WEBP']);
    exit;
}

// Validate file size (max 5MB)
if ($file['size'] > 5 * 1024 * 1024) {
    echo json_encode(['error' => 'Arquivo muito grande. Tamanho máximo: 5MB']);
    exit;
}

// Get file extension
$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (empty($extension)) {
    // Fallback to mime type
    $mimeToExt = [
        'image/jpeg' => 'jpg',
        'image/jpg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp'
    ];
    $extension = $mimeToExt[$fileType] ?? 'jpg';
}

// Create ImagensUsuarios directory if it doesn't exist
$uploadDir = '../ImagensUsuarios/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Delete old photo if exists
$sql = "SELECT fotoUsuario FROM usuario WHERE idUsuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$stmt->close();

if ($userData && !empty($userData['fotoUsuario']) && file_exists($userData['fotoUsuario'])) {
    unlink($userData['fotoUsuario']);
}

// Save new photo
$fileName = $idUsuario . '.' . $extension;
$filePath = $uploadDir . $fileName;

if (!move_uploaded_file($file['tmp_name'], $filePath)) {
    echo json_encode(['error' => 'Erro ao salvar a imagem']);
    exit;
}

// Update database
$sql = "UPDATE usuario SET fotoUsuario = ? WHERE idUsuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $filePath, $idUsuario);

if ($stmt->execute()) {
    // Update session
    $_SESSION['fotoUsuario'] = $filePath;
    
    echo json_encode([
        'success' => true,
        'message' => 'Foto atualizada com sucesso!',
        'photoPath' => $filePath
    ]);
} else {
    echo json_encode(['error' => 'Erro ao atualizar foto no banco de dados']);
}

$stmt->close();
$conn->close();
?>
