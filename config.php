<?php

define ('HOST','localhost');
define ('USER', 'root');
define ('PASS', '');
define ('BASE', 'Techforge_db');

<<<<<<< HEAD
$conexao =  mysqli_connect($hostname, $username, $password, $database);

if (!$conexao) {
   echo "Erro na conexão com o banco de dados: " . mysqli_connect_error();
   die();
=======
$conn = new mysqli(HOST,USER,PASS,BASE);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
>>>>>>> 763bf84fd61a3a3dd9defbaf5f99d96d8d3b2ba9
}



