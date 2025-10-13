<?php
require '../session.php';
require '../config.php';
require '../flash.php';

// Recebe os valores de forma segura
$nome = isset($_POST["nomeUsuario"]) ? trim($_POST["nomeUsuario"]) : '';
$sobrenome = isset($_POST["sobrenomeUsuario"]) ? trim($_POST["sobrenomeUsuario"]) : '';
$dataNascimento = isset($_POST["nascimentoUsuario"]) ? trim($_POST["nascimentoUsuario"]) : '';
$celular = isset($_POST["celularUsuario"]) ? trim($_POST["celularUsuario"]) : '';
$email = isset($_POST["emailUsuario"]) ? trim($_POST["emailUsuario"]) : '';
$senha = isset($_POST["senhaUsuario1"]) ? trim($_POST["senhaUsuario1"]) : '';
$confirmarSenha = isset($_POST["senhaUsuario"]) ? trim($_POST["senhaUsuario"]) : '';

// Valida campos
if (empty($nome) || empty($sobrenome) || empty($dataNascimento) || empty($celular) || empty($email) || empty($senha) || empty($confirmarSenha)) {
    echo "<script>
        Swal.fire('Erro!', 'Preencha todos os campos!', 'warning').then(() => {
            window.history.back();
        });
    </script>";
    exit;
}

if ($senha !== $confirmarSenha) {
    echo "<script>
        Swal.fire('Erro!', 'As senhas não coincidem!', 'warning').then(() => {
            window.history.back();
        });
    </script>";
    exit;
}

if (strlen($senha) < 6) {
    echo "<script>
        Swal.fire('Erro!', 'A senha deve ter pelo menos 6 caracteres!', 'warning').then(() => {
            window.history.back();
        });
    </script>";
    exit;
}

// Hash da senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Prepara a query
$sql = "INSERT INTO usuario (nomeUsuario, sobrenomeUsuario, celularUsuario, emailUsuario, senhaUsuario, nascimentoUsuario)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $nome, $sobrenome, $celular, $email, $senha_hash, $dataNascimento);

if ($stmt->execute()) {
    set_flash("success","Conta criada! Faça o Login.");
    header('Location: ../Login/login.php'); exit;
} else {
    set_flash(' Erro','Não foi possível concluir o cadastro!');
    header('Location: cadastro.php'); exit;
}
?>
