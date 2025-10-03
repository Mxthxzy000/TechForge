<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "Techforge_db";

$conexao =  mysqli_connect($hostname, $username, $password, $database);

if (!$conexao) {
   echo "Erro na conexão com o banco de dados: " . mysqli_connect_error();
   die();
}

else {
   echo "Conexão bem-sucedida!";
}

