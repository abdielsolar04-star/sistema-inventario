<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function esAdmin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] == 1;
}

function esEmpleado() {
    return isset($_SESSION['rol']) && ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2);
}

function soloAdmin() {
    if (!esAdmin()) {
        header("Location: ../vista/dashboard.php");
        exit();
    }
}

function soloEmpleado() {
    if (!esEmpleado()) {
        header("Location: ../vista/dashboard.php");
        exit();
    }
}
?>