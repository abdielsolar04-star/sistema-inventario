<?php
session_start();

include("../modelo/conexion.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../vista/login.php");
    exit();
}

$usuario = $_POST["usuario"] ?? "";
$contrasena = $_POST["contrasena"] ?? "";

if ($usuario == "" || $contrasena == "") {
    header("Location: ../vista/login.php?error=1");
    exit();
}

$sql = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ? LIMIT 1");
$sql->bind_param("s", $usuario);
$sql->execute();

$resultado = $sql->get_result();

if ($resultado->num_rows == 0) {
    header("Location: ../vista/login.php?error=1");
    exit();
}

$row = $resultado->fetch_assoc();

if (password_verify($contrasena, $row["password"]) || $contrasena == $row["password"]) {

    $_SESSION["id"] = $row["id_usuario"];
    $_SESSION["usuario"] = $row["usuario"];
    $_SESSION["rol"] = $row["rol_id"];

    header("Location: ../vista/dashboard.php");
    exit();

} else {
    header("Location: ../vista/login.php?error=1");
    exit();
}
?>