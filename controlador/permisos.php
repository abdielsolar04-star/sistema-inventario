<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('esAdmin')) {
    function esAdmin() {
        return isset($_SESSION['rol']) && $_SESSION['rol'] == 1;
    }
}

if (!function_exists('tienePermiso')) {
    function tienePermiso($permiso) {
        if (esAdmin()) {
            return true;
        }

        if (!isset($_SESSION['id_usuario'])) {
            return false;
        }

        include(__DIR__ . "/../modelo/conexion.php");

        $id_usuario = $_SESSION['id_usuario'];

        $sql = "SELECT * FROM usuario_permiso WHERE id_usuario=? AND permiso=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("is", $id_usuario, $permiso);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }
}

if (!function_exists('proteger')) {
    function proteger($permiso) {
        if (!tienePermiso($permiso)) {
            echo "No tienes permiso";
            exit();
        }
    }
}
?>