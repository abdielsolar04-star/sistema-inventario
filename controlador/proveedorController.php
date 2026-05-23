<?php

include("../modelo/conexion.php");

if(isset($_POST['accion'])){

    $nombre = $_POST['nombre_proveedor'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $sql = "
    INSERT INTO proveedores
    (
        nombre_proveedor,
        telefono,
        direccion
    )
    VALUES
    (
        ?,
        ?,
        ?
    )
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param(
        "sss",
        $nombre,
        $telefono,
        $direccion
    );

    $stmt->execute();

    header("Location: ../vista/proveedores.php");
    exit();
}

if(isset($_GET['eliminar'])){

    $id = $_GET['eliminar'];

    $sql = "
    DELETE FROM proveedores
    WHERE id_proveedor=?
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param("i",$id);

    $stmt->execute();

    header("Location: ../vista/proveedores.php");
    exit();
}
?>