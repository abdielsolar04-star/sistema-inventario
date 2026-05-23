<?php
include("seguridad.php");
include("../modelo/conexion.php");

$accion = $_POST['accion'] ?? '';

if ($accion == "crear") {

    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $id_rol = $_POST['id_rol'];

    $sql = "INSERT INTO usuarios (nombre, usuario, password, id_rol, estado)
            VALUES (?, ?, ?, ?, 'Activo')";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $usuario, $password, $id_rol);
    $stmt->execute();

    header("Location: ../vista/usuarios.php");
    exit();
}
?>