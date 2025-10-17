<?php
// logout.php - PARA TechForge
require 'session.php';
require 'flash.php';

// Só processa se for POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Define flash ANTES de destruir a sessão
    set_flash('sucesso', 'Logout realizado com sucesso!');
    
    // Limpa todas as variáveis de sessão
    $_SESSION = [];

    // Destroi o cookie de sessão
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), 
            '', 
            time() - 42000,
            $params["path"], 
            $params["domain"],
            $params["secure"], 
            $params["httponly"]
        );
    }

    // Destroi a sessão
    session_destroy();

    // Caminho absoluto para TechForge
    header('Location: /TechForge/Login/login.php');
    exit;
}

// Se tentar acessar direto via GET, redireciona
header('Location: /TechForge/Home/index.php');
exit;
?>
