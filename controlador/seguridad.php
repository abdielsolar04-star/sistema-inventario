<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['id']) || !isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
    header("Location: ../vista/login.php");
    exit();
}

$id_usuario = $_SESSION['id'];
$usuario_sesion = $_SESSION['usuario'];
$rol_sesion = $_SESSION['rol'];
?>