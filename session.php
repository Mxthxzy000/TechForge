<?php
// session.php - Configuração de sessão
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        // 'secure' => true // Descomente em produção com HTTPS
    ]);
    session_start();
}
?>
