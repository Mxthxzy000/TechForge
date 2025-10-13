<?php
require 'session.php';
require 'flash.php';

// Só processa se for POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Limpa todas as variáveis de sessão
    $_SESSION = [];

    // Destroi o cookie de sessão, se existir
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Destroi a sessão
    session_destroy();

    set_flash('Sucesso', 'Logout realizado com sucesso!');
    header('Location: login.php');
    exit;
}

// Se tentar acessar direto via GET, redireciona
header('Location: ../Home/index.php');
exit;
?>
