<?php
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
    header("Location: ../vista/login.php");
    exit();
}

$id_usuario = $_SESSION['id'];
$usuario_sesion = $_SESSION['usuario'];
$rol_sesion = $_SESSION['rol'];
?>