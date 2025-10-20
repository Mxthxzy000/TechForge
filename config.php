<?php
// config.php
$hostname = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$database = getenv('DB_NAME') ?: 'techforge_db';

// Modo debug local
$isLocal = in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($hostname, $username, $password, $database);
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    if ($isLocal) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Falha na conexão: ' . $e->getMessage()]);
    } else {
        die("Erro de conexão. Contate o administrador.");
    }
    exit;
}
?>
