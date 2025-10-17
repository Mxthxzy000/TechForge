<?php
// config.php
$hostname = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$database = getenv('DB_NAME') ?: 'techforge_db';

try {
    $conn = new mysqli($hostname, $username, $password, $database);
    
    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error);
        throw new Exception("Erro ao conectar ao banco de dados");
    }
    
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    // Em produção, mostre mensagem genérica
    die("Erro de conexão. Contate o administrador.");
}
?>