<?php
include '../config.php';
session_start();

$usuarioLogado = false;
$nome = '';
$email = '';
$fotoUsuario = '../assets/img/default-profile.png';

if (isset($_SESSION['idUsuario'])) {
    $usuarioLogado = true;
    $nome = $_SESSION['nomeUsuario'];
    $email = $_SESSION['emailUsuario'];
    $id = $_SESSION['idUsuario'];
    $sql = "SELECT fotoUsuario FROM usuario WHERE idUsuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (!empty($user['fotoUsuario'])) {
            $fotoUsuario = 'data:image/jpeg;base64,' . base64_encode($user['fotoUsuario']);
        }
    }
}
?>
