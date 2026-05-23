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

function tienePermiso($permiso) {
    if (!isset($_SESSION['rol'])) {
        return false;
    }

    if ($_SESSION['rol'] == 1) {
        return true;
    }

    if ($_SESSION['rol'] == 2) {
        $permitidos = [
            'caja',
            'productos',
            'ventas',
            'movimientos'
        ];

        return in_array($permiso, $permitidos);
    }

    return false;
}

function soloAdmin() {
    if (!esAdmin()) {
        echo "No tienes permiso";
        exit();
    }
}

function soloEmpleado() {
    if (!esEmpleado()) {
        echo "No tienes permiso";
        exit();
    }
}

?>