<?php
require '../session.php';

if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true || !isset($_SESSION['idAdm'])) {
    header('Location: ../login/login.php');
    exit;
}
?>
