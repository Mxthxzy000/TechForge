<?php
require '../config.php';
require '../session.php';
require '../flash.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['emailUsuario'] ?? '');
$senha = $_POST['senhaUsuario'] ?? '';

if (empty($email) || empty($senha)) {
    set_flash('erro', 'Preencha todos os campos.');
    header('Location: login.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('erro', 'E-mail inválido.');
    header('Location: login.php');
    exit;
}

$sql = "SELECT idUsuario, nomeUsuario, sobrenomeUsuario, emailUsuario, senhaUsuario, fotoUsuario 
        FROM usuario 
        WHERE emailUsuario = ? 
        LIMIT 1";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    set_flash('erro', 'Erro no sistema. Tente novamente.');
    header('Location: login.php');
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || !password_verify($senha, $user['senhaUsuario'])) {
    set_flash('erro', 'E-mail ou senha incorretos.');
    $stmt->close();
    $conn->close();
    header('Location: login.php');
    exit;
}

session_regenerate_id(true);

$_SESSION['idUsuario'] = (int)$user['idUsuario'];
$_SESSION['nomeUsuario'] = trim($user['nomeUsuario'] . ' ' . ($user['sobrenomeUsuario'] ?? ''));
$_SESSION['emailUsuario'] = $user['emailUsuario'];
$_SESSION['fotoUsuario'] = $user['fotoUsuario'] ?? '../imagens/default-avatar.png';

set_flash('sucesso', 'Login realizado com sucesso!');

$stmt->close();
$conn->close();

header('Location: ../Home/index.php');
exit;
?>
