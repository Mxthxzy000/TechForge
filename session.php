<?php
// session.php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,  // ✅ Boolean, não string
        'samesite' => 'Lax',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'  // ✅ Auto-detecta HTTPS
    ]);
    session_start();
}
?>