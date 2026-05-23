<?php

include("modelo/conexion.php");

$nombre = "Cris";
$usuario = "cris";
$password = password_hash("1234", PASSWORD_DEFAULT);
$rol = 1;
$estado = "Activo";

$sql = $conexion->prepare("
    INSERT INTO usuarios
    (nombre, usuario, password, rol_id, estado)
    VALUES (?, ?, ?, ?, ?)
");

$sql->bind_param(
    "sssis",
    $nombre,
    $usuario,
    $password,
    $rol,
    $estado
);

if($sql->execute()){
    echo "Administrador creado";
}else{
    echo "Error";
}

?>