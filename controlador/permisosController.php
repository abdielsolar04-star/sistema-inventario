<?php
include("seguridad.php");
include("../modelo/conexion.php");

$id_usuario = $_POST['id_usuario'];
$permisos = $_POST['permisos'] ?? [];

$conexion->query("DELETE FROM usuario_permiso WHERE id_usuario = $id_usuario");

foreach ($permisos as $permiso) {
    $sql = "INSERT INTO usuario_permiso (id_usuario, permiso) VALUES (?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("is", $id_usuario, $permiso);
    $stmt->execute();
}

header("Location: ../vista/usuarios.php");
exit();
?>