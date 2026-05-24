<?php
error_reporting(0);
ini_set('display_errors', 0);

include("../controlador/seguridad.php");
include("../modelo/conexion.php");

$id_usuario = $_SESSION['id_usuario'];

$codigo = trim($_POST['codigo'] ?? '');
$id_producto = $_POST['id_producto'] ?? '';
$cantidad = intval($_POST['cantidad'] ?? 1);

if ($cantidad <= 0) {
    $cantidad = 1;
}

if ($codigo != '') {
    $sql = "SELECT * FROM productos WHERE codigo=? AND estado='activo' LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $codigo);
} else {
    $sql = "SELECT * FROM productos WHERE id_producto=? AND estado='activo' LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_producto);
}

$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    die("Producto no encontrado");
}

$producto = $resultado->fetch_assoc();

if ($producto['stock'] < $cantidad) {
    die("No hay suficiente stock");
}

$id_producto = $producto['id_producto'];
$total = $producto['precio_venta'] * $cantidad;

$sqlVenta = "INSERT INTO ventas(id_producto,id_usuario,cantidad,total,fecha_venta)
             VALUES(?,?,?,?,NOW())";
$stmtVenta = $conexion->prepare($sqlVenta);
$stmtVenta->bind_param("iiid", $id_producto, $id_usuario, $cantidad, $total);
$stmtVenta->execute();

$id_venta = $conexion->insert_id;

$sqlDetalle = "INSERT INTO detalle_ventas(id_venta,id_producto,codigo,descripcion,cantidad,precio,subtotal)
               VALUES(?,?,?,?,?,?,?)";
$stmtDetalle = $conexion->prepare($sqlDetalle);
$stmtDetalle->bind_param(
    "iissidd",
    $id_venta,
    $id_producto,
    $producto['codigo'],
    $producto['nombre_producto'],
    $cantidad,
    $producto['precio_venta'],
    $total
);
$stmtDetalle->execute();

$nuevoStock = $producto['stock'] - $cantidad;

$sqlStock = "UPDATE productos SET stock=? WHERE id_producto=?";
$stmtStock = $conexion->prepare($sqlStock);
$stmtStock->bind_param("ii", $nuevoStock, $id_producto);
$stmtStock->execute();

header("Location: ../vista/ticket.php?id=".$id_venta);
exit();
?>