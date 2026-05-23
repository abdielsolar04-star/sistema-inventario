<?php
session_start();

include("../modelo/conexion.php");

$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

$sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";

$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {

    $row = $resultado->fetch_assoc();

    if (
        password_verify($contrasena, $row['password']) ||
        $contrasena == $row['password']
    ) {

        $_SESSION['id'] = $row['id_usuario'];
        $_SESSION['usuario'] = $row['usuario'];
        $_SESSION['rol'] = $row['rol_id'];

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