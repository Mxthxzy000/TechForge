<?php
include("../config.php");

$acao = $_POST["acao"] ?? '';

switch ($acao) {
    case 'cadastrar':
        $nome = $_POST["nomeUsuario"];
        $sobrenome = $_POST["sobrenomeUsuario"];
        $nascimento = $_POST["nascimentoUsuario"];
        $celular = $_POST["celularUsuario"];
        $email = $_POST["emailUsuario"];
        $senha = md5($_POST["senhaUsuario"]); 

        $sql = "INSERT INTO usuarios (nomeUsuario, sobrenomeUsuario, nascimentoUsuario, celularUsuario, emailUsuario, senhaUsuario)
                VALUES ('$nome', '$sobrenome', '$nascimento', '$celular', '$email', '$senha')";

        $res = $conn->query($sql);

        if ($res === TRUE) {
            echo "Cadastro realizado com sucesso!";
        } else {
            echo "ERRO: " . $conn->error;
        }

        break;

    default:
        echo "Ação inválida.";
        break;
}
?>
