<?php

function tienePermiso($conexion, $id_usuario, $permiso) {

    if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'Administrador') {
        return true;
    }

    $sql = "SELECT permiso 
            FROM usuario_permiso 
            WHERE id_usuario = ? AND permiso = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("is", $id_usuario, $permiso);
    $stmt->execute();

    $resultado = $stmt->get_result();

    return $resultado->num_rows > 0;
}
?>