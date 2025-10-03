<?php

switch ($_REQUEST['acao']) {
    case 'cadastrar':
        $nome = $_POST["nomeUsuario"];
        $sobrenome = $_POST["sobrenomeUsuario"];
        $nascimento = $_POST["nascimentoUsuario"];
        $celular = $_POST["celularUsuario"];
        $email = $_POST["emailUsuario"];
        $senha = $_POST["senhaUsuario"];


        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $nascimentoSQL = DateTime::createFromFormat('d/m/Y', $nascimento)->format('Y-m-d');

        $sql = "INSERT INTO usuario (nome, sobrenome, data_nascimento, email, senha) VALUES ('{$nome}', '{$sobrenome}', '{$data_nascimento}','{$celular}', '{$email}', '{$senha}')";

        $res = $conn->query($sql);

        if ($res==true) {
            print "<script>alert('Cadastrado com sucesso!');</script>";
            print "<script>location.href='?page=listar';</script>";
        } else {
            print "<script>alert('Não foi possível cadastrar!');</script>";
            print "<script>location.href='?page=listar';</script>";
        }
        break;
    
    case 'editar':
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = md5($_POST['senha']);

        $sql = "UPDATE usuarios SET 
                nome='{$nome}',
                email='{$email}',
                senha='{$senha}'
                WHERE id=".$_REQUEST['id'];

        $res = $conn->query($sql);

        if ($res==true) {
            print "<script>alert('Editado com sucesso!');</script>";
            print "<script>location.href='?page=listar';</script>";
        } else {
            print "<script>alert('Não foi possível editar!');</script>";
            print "<script>location.href='?page=listar';</script>";
        }
        break;

    case 'excluir':
        $sql = "DELETE FROM usuarios WHERE id=".$_REQUEST['id'];

        $res = $conn->query($sql);

        if ($res==true) {
            print "<script>alert('Excluído com sucesso!');</script>";
            print "<script>location.href='?page=listar';</script>";
        } else {
            print "<script>alert('Não foi possível excluir!');</script>";
            print "<script>location.href='?page=listar';</script>";
        }
        break;
}