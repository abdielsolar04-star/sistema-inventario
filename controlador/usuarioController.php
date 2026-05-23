<?php
include("seguridad.php");
include("../modelo/conexion.php");

if ($_SESSION['rol'] != 'Administrador') {
    die("No tienes permiso");
}

$accion = $_POST['accion'] ?? '';

if ($accion == "crear") {

    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $id_rol = $_POST['id_rol'];

    $sql = "INSERT INTO usuarios(nombre, usuario, password, id_rol, estado)
            VALUES (?, ?, ?, ?, 'Activo')";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $usuario, $password, $id_rol);

    if ($stmt->execute()) {
        header("Location: ../vista/usuarios.php");
        exit();
    } else {
        die("Error al crear usuario: " . $stmt->error);
    }
}

if ($accion == "permisos") {

    $id_usuario = intval($_POST['id_usuario']);
    $permisos = $_POST['permisos'] ?? [];

    $borrar = $conexion->prepare("
        DELETE FROM usuario_permiso 
        WHERE id_usuario = ?
    ");

    $borrar->bind_param("i", $id_usuario);
    $borrar->execute();

    foreach ($permisos as $permiso) {

        $insertar = $conexion->prepare("
            INSERT INTO usuario_permiso(id_usuario, permiso)
            VALUES (?, ?)
        ");

        $insertar->bind_param("is", $id_usuario, $permiso);
        $insertar->execute();
    }

    header("Location: ../vista/usuarios.php");
    exit();
}

header("Location: ../vista/usuarios.php");
exit();
?>