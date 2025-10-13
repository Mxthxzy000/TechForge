<?php
require '../config.php';
require '../session.php';
require '../flash.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['emailUsuario'] ?? '');
    $senha = $_POST['senhaUsuario'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($senha)) {
        set_flash('Erro', 'Credenciais inválidas.');
        header('Location: login.php');
        exit;
    }

    // Incluindo as colunas da foto e sobrenome
    $sql = "SELECT idUsuario, nomeUsuario, sobrenomeUsuario, emailUsuario, senhaUsuario, fotoUsuario FROM usuario WHERE emailUsuario = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($senha, $user['senhaUsuario'])) {
        set_flash('Erro', 'E-mail ou senha incorretos.');
        header('Location: login.php');
        exit;
    }

    session_regenerate_id(true);
    $_SESSION['idUsuario'] = (int)$user['idUsuario'];
    // Concatenando nome + sobrenome
    $_SESSION['nomeUsuario'] = trim($user['nomeUsuario'] . ' ' . ($user['sobrenomeUsuario'] ?? ''));
    $_SESSION['emailUsuario'] = $user['emailUsuario'];
    // Foto padrão caso seja null
    $_SESSION['fotoUsuario'] = $user['fotoUsuario'] ?? '../imagens/default-avatar.png';

    set_flash('Sucesso', 'Login realizado com sucesso!');
    header('Location: ../Home/index.php');
    exit;
}

// Fechar somente se a variável $stmt existir
if (isset($stmt)) {
    $stmt->close();
}

$conn->close();
?>
