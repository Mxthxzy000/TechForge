<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(lifetime_or_options: [
        'lifetime' => 0,
        'path' => '/',
        'httponly' => 'true',
        'samesite' => 'Lax',
        // 'secure' => true
    ]);
    session_start();
}
?>