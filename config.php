<?php

$host = "localhost";
$user = "root";
$password = "";
$dbname = "techforge_db";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("erro de conexão". $conn->connect_error);
}
else { 
    echo"conexão bem sucedida";
}
?>





