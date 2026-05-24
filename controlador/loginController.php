<?php
session_start();
include("../modelo/conexion.php");

$usuario = $_POST['usuario'];
$password = $_POST['password'];

$sql = "SELECT usuarios.*, roles.nombre_rol 
        FROM usuarios 
        INNER JOIN roles ON usuarios.id_rol = roles.id_rol
        WHERE usuario = ? AND estado = 'Activo'";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();

    if (password_verify($password, $row['password'])) {

        $_SESSION['id_usuario'] = $row['id_usuario'];
        $_SESSION['nombre'] = $row['nombre'];
        $_SESSION['usuario'] = $row['usuario'];
        $_SESSION['rol'] = $row['nombre_rol'];
        $_SESSION['id_rol'] = $row['id_rol'];

        $ip = $_SERVER['REMOTE_ADDR'];
        $so = $_SERVER['HTTP_USER_AGENT'];

        $auditoria = "INSERT INTO auditoria 
        (id_usuario, accion, tabla_afectada, descripcion, ip_usuario, sistema_operativo)
        VALUES (?, 'LOGIN', 'usuarios', 'Inicio de sesión exitoso', ?, ?)";

        $stmtAud = $conexion->prepare($auditoria);
        $stmtAud->bind_param("iss", $row['id_usuario'], $ip, $so);
        $stmtAud->execute();

        header("Location: ../vista/dashboard.php");
        exit();

    } else {
        header("Location: ../vista/login.php?error=1");
        exit();
    }
} else {
    header("Location: ../vista/login.php?error=1");
    exit();
}
?>