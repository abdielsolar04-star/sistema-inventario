<?php
include("seguridad.php");
include("../modelo/conexion.php");

$id_producto = $_POST['id_producto'];
$tipo = $_POST['tipo_movimiento'];
$cantidad = $_POST['cantidad'];
$observacion = $_POST['observacion'];
$id_usuario = $_SESSION['id_usuario'];

if ($tipo == "Salida") {
    $consulta = $conexion->prepare("SELECT stock FROM productos WHERE id_producto = ?");
    $consulta->bind_param("i", $id_producto);
    $consulta->execute();
    $resultado = $consulta->get_result();
    $producto = $resultado->fetch_assoc();

    if ($producto['stock'] < $cantidad) {
        die("No hay suficiente stock");
    }

    $update = $conexion->prepare("UPDATE productos SET stock = stock - ? WHERE id_producto = ?");
} else {
    $update = $conexion->prepare("UPDATE productos SET stock = stock + ? WHERE id_producto = ?");
}

$update->bind_param("ii", $cantidad, $id_producto);
$update->execute();

$sql = "INSERT INTO movimientos 
(id_producto, id_usuario, tipo_movimiento, cantidad, observacion)
VALUES (?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("iisis", $id_producto, $id_usuario, $tipo, $cantidad, $observacion);
$stmt->execute();

$ip = $_SERVER['REMOTE_ADDR'];
$so = $_SERVER['HTTP_USER_AGENT'];

$auditoria = "INSERT INTO auditoria
(id_usuario, accion, tabla_afectada, descripcion, ip_usuario, sistema_operativo)
VALUES (?, ?, 'movimientos', ?, ?, ?)";

$descripcion = "$tipo de producto ID: $id_producto cantidad: $cantidad";

$stmtAud = $conexion->prepare($auditoria);
$stmtAud->bind_param("issss", $id_usuario, $tipo, $descripcion, $ip, $so);
$stmtAud->execute();

header("Location: ../vista/movimientos.php");
exit();
?>