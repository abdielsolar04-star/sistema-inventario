<?php

$servidor = "sql10.freesqldatabase.com";
$usuario = "sql10828040";
$password = "NrkVnY4tsx";
$base_datos = "sql10828040";
$puerto = 3306;

$conexion = new mysqli(
    $servidor,
    $usuario,
    $password,
    $base_datos,
    $puerto
);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
date_default_timezone_set("America/Mexico_City");

?>