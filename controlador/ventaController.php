<?php
include("seguridad.php");
include("../modelo/conexion.php");

$id_usuario = $_SESSION['id_usuario'];
$id_producto = $_POST['id_producto'];
$cantidad = $_POST['cantidad'];

$sqlProducto = "SELECT * FROM productos WHERE id_producto = ?";
$stmt = $conexion->prepare($sqlProducto);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    die("Producto no encontrado");
}

$producto = $resultado->fetch_assoc();

if ($producto['stock'] < $cantidad) {
    die("No hay suficiente stock");
}

$precio = $producto['precio_venta'];
$total = $precio * $cantidad;

$sqlVenta = "INSERT INTO ventas (id_producto, id_usuario, cantidad, total) VALUES (?, ?, ?, ?)";
$stmtVenta = $conexion->prepare($sqlVenta);
$stmtVenta->bind_param("iiid", $id_producto, $id_usuario, $cantidad, $total);
$stmtVenta->execute();

$id_venta = $conexion->insert_id;

$subtotal = $total;

$sqlDetalle = "INSERT INTO detalle_ventas 
(id_venta, id_producto, codigo, descripcion, cantidad, precio, subtotal)
VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmtDetalle = $conexion->prepare($sqlDetalle);
$stmtDetalle->bind_param(
    "iissidd",
    $id_venta,
    $id_producto,
    $producto['codigo'],
    $producto['nombre_producto'],
    $cantidad,
    $precio,
    $subtotal
);
$stmtDetalle->execute();

$nuevoStock = $producto['stock'] - $cantidad;

$sqlStock = "UPDATE productos SET stock = ? WHERE id_producto = ?";
$stmtStock = $conexion->prepare($sqlStock);
$stmtStock->bind_param("ii", $nuevoStock, $id_producto);
$stmtStock->execute();

$sqlMov = "INSERT INTO movimientos 
(id_producto, id_usuario, tipo_movimiento, cantidad, observacion)
VALUES (?, ?, 'Salida', ?, 'Venta en caja')";

$stmtMov = $conexion->prepare($sqlMov);
$stmtMov->bind_param("iii", $id_producto, $id_usuario, $cantidad);
$stmtMov->execute();

header("Location: ../vista/ticket.php?id_venta=" . $id_venta);
exit();
?>