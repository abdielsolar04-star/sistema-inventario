<?php

$servidor = "sql10.freesqldatabase.com";
$usuario = "sql10827970";
$password = "AQUI_PEGA_LA_CONTRASEÑA_DEL_CORREO";
$base_datos = "sql10827970";
$puerto = 3306;

$conexion = new mysqli($servidor, $usuario, $password, $base_datos, $puerto);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
date_default_timezone_set("America/Mexico_City");

?>