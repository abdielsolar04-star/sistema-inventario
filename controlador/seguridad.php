<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| BLOQUEAR CACHE
|--------------------------------------------------------------------------
| Evita regresar con las flechas del navegador
| después de cerrar sesión
|--------------------------------------------------------------------------
*/

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

/*
|--------------------------------------------------------------------------
| VALIDAR LOGIN
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['id_usuario'])) {

    header("Location: ../vista/login.php");
    exit();
}
?>