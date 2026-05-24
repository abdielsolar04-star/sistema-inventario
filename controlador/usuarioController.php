<?php
include("seguridad.php");
include("../modelo/conexion.php");

if ($_SESSION['rol'] != 'Administrador') {
    die("No tienes permiso");
}

$accion = $_POST['accion'] ?? '';

if ($accion == "crear") {

    $nombre = trim($_POST['nombre']);
    $usuario = trim($_POST['usuario']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $id_rol = intval($_POST['id_rol']);

    $verificar = $conexion->prepare("
        SELECT id_usuario 
        FROM usuarios 
        WHERE usuario = ?
    ");

    $verificar->bind_param("s", $usuario);
    $verificar->execute();
    $resultado = $verificar->get_result();

    if ($resultado->num_rows > 0) {
        die("Ese usuario ya existe");
    }

    $sql = "
        INSERT INTO usuarios(nombre, usuario, password, id_rol, estado)
        VALUES (?, ?, ?, ?, 'Activo')
    ";

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

if (isset($_GET['eliminar'])) {

    $id_usuario = intval($_GET['eliminar']);

    if ($id_usuario == $_SESSION['id_usuario']) {
        die("No puedes eliminar tu propio usuario");
    }

    $conexion->begin_transaction();

    try {

        $borrarPermisos = $conexion->prepare("
            DELETE FROM usuario_permiso 
            WHERE id_usuario = ?
        ");

        $borrarPermisos->bind_param("i", $id_usuario);
        $borrarPermisos->execute();

        $borrarUsuario = $conexion->prepare("
            DELETE FROM usuarios 
            WHERE id_usuario = ?
        ");

        $borrarUsuario->bind_param("i", $id_usuario);
        $borrarUsuario->execute();

        $conexion->commit();

        header("Location: ../vista/usuarios.php");
        exit();

    } catch (Exception $e) {

        $conexion->rollback();
        die("Error al eliminar usuario");
    }
}

header("Location: ../vista/usuarios.php");
exit();
?>