<?php
include "../config.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['emailUsuario'] ?? '';
    $senha = $_POST['senhaUsuario'] ?? '';

    $sql = "SELECT * FROM usuario WHERE emailUsuario = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($senha, $user['senhaUsuario'])) {
            $_SESSION['idUsuario'] = $user['idUsuario'];
            $_SESSION['nomeUsuario'] = $user['nomeUsuario'];
            $_SESSION['emailUsuario'] = $user['emailUsuario'];

            header("Location: ../Home/index.php");
            exit;
        }
    }

    header("Location: login.php?erro=1");
    exit;
}
?>
