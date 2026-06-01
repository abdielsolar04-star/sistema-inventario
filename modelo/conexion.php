<?php

$host = "sql10.freesqldatabase.com";
$usuario = "sql10828040";
$password = "AQUI_VA_TU_PASSWORD_REAL";
$bd = "sql10828040";
$puerto = 3306;

$conexion = new mysqli($host, $usuario, $password, $bd, $puerto);

if ($conexion->connect_error) {
    die("Error de conexión BD: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");

?>