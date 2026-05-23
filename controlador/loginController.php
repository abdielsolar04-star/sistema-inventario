<?php
session_start();

include("../modelo/conexion.php");

$usuario = $_POST['usuario'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

$sql = "SELECT * FROM usuarios WHERE usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado && $resultado->num_rows > 0) {

    $row = $resultado->fetch_assoc();

    if ($contrasena == $row['password'] || password_verify($contrasena, $row['password'])) {

        $_SESSION['id'] = $row['id_usuario'];
        $_SESSION['id_usuario'] = $row['id_usuario'];
        $_SESSION['usuario'] = $row['usuario'];
        $_SESSION['rol'] = $row['id_rol'];

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