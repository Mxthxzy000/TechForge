<?php

switch ($_REQUEST['acao']) {
    case 'cadastrar':
        $nome = $_POST["nomeUsuario"];
        $sobrenome = $_POST["sobrenomeUsuario"];
        $celular = $_POST["celularUsuario"];
        $email = $_POST["emailUsuario"];
        $senha = $_POST["senhaUsuario"];
        $nascimento = $_POST["nascimentoUsuario"];




        $sql = "INSERT INTO usuario (nome, sobrenome, email, senha, nascimento) VALUES ('{$nome}', '{$sobrenome}','{$celular}', '{$email}', '{$senha}', '{$data_nascimento}')";

        $conn->query($sql);

        if ($res==true) {
            print "<script>alert('Cadastrado com sucesso!');</script>";
            print "<script>location.href='index.php';</script>";
        } else {
            print "<script>alert('Não foi possível cadastrar!');</script>";
            print "<script>location.href='?cadastro.php;</script>";
        }
        break;
    

}