<?php

define ('HOST','localhost');
define ('USER', 'root');
define ('PASS', '');
define ('BASE', 'Techforge_db');

$conn = new mysqli(HOST,USER,PASS,BASE);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}



?>