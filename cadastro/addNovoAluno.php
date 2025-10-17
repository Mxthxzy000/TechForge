<?php
require '../session.php';
require '../config.php';
require '../flash.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cadastro.php');
    exit;
}

$nome = trim($_POST["nomeUsuario"] ?? '');
$sobrenome = trim($_POST["sobrenomeUsuario"] ?? '');
$dataNascimento = trim($_POST["nascimentoUsuario"] ?? '');
$celular = trim($_POST["celularUsuario"] ?? '');
$email = trim($_POST["emailUsuario"] ?? '');
$senha = trim($_POST["senhaUsuario1"] ?? '');
$confirmarSenha = trim($_POST["senhaUsuario"] ?? '');

if (empty($nome) || empty($sobrenome) || empty($dataNascimento) || empty($celular) || empty($email) || empty($senha) || empty($confirmarSenha)) {
    set_flash('erro', 'Preencha todos os campos!');
    header('Location: cadastro.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('erro', 'E-mail inválido!');
    header('Location: cadastro.php');
    exit;
}

if ($senha !== $confirmarSenha) {
    set_flash('erro', 'As senhas não coincidem!');
    header('Location: cadastro.php');
    exit;
}

if (strlen($senha) < 6) {
    set_flash('erro', 'A senha deve ter pelo menos 6 caracteres!');
    header('Location: cadastro.php');
    exit;
}

$sqlCheck = "SELECT idUsuario FROM usuario WHERE emailUsuario = ? LIMIT 1";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("s", $email);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows > 0) {
    set_flash('erro', 'Este e-mail já está cadastrado!');
    $stmtCheck->close();
    $conn->close();
    header('Location: cadastro.php');
    exit;
}
$stmtCheck->close();

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuario (nomeUsuario, sobrenomeUsuario, celularUsuario, emailUsuario, senhaUsuario, nascimentoUsuario)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    set_flash('erro', 'Erro no sistema. Tente novamente.');
    $conn->close();
    header('Location: cadastro.php');
    exit;
}

$stmt->bind_param("ssssss", $nome, $sobrenome, $celular, $email, $senha_hash, $dataNascimento);

if ($stmt->execute()) {
    set_flash('sucesso', 'Conta criada com sucesso! Faça o login.');
    $stmt->close();
    $conn->close();
    header('Location: ../Login/login.php');
    exit;
} else {
    set_flash('erro', 'Não foi possível concluir o cadastro. Tente novamente.');
    $stmt->close();
    $conn->close();
    header('Location: cadastro.php');
    exit;
}
?>
