<?php
error_reporting(0);
ini_set('display_errors', 0);

include("../controlador/seguridad.php");
include("../modelo/conexion.php");

$id_usuario = $_SESSION['id_usuario'];
$productos = json_decode($_POST['productos_json'] ?? '[]', true);

if (!$productos || count($productos) == 0) {
    die("No hay productos en la venta");
}

$totalVenta = 0;

foreach ($productos as $p) {
    $totalVenta += floatval($p['subtotal']);
}

$sqlVenta = "INSERT INTO ventas(id_producto,id_usuario,cantidad,total,fecha_venta)
             VALUES(NULL, ?, 0, ?, NOW())";

$stmtVenta = $conexion->prepare($sqlVenta);
$stmtVenta->bind_param("id", $id_usuario, $totalVenta);
$stmtVenta->execute();

$id_venta = $conexion->insert_id;

foreach ($productos as $p) {

    $id_producto = intval($p['id_producto']);
    $cantidad = intval($p['cantidad']);

    $sqlProducto = "SELECT * FROM productos WHERE id_producto=?";
    $stmtProducto = $conexion->prepare($sqlProducto);
    $stmtProducto->bind_param("i", $id_producto);
    $stmtProducto->execute();
    $producto = $stmtProducto->get_result()->fetch_assoc();

    if (!$producto) {
        continue;
    }

    if ($producto['stock'] < $cantidad) {
        die("No hay suficiente stock de ".$producto['nombre_producto']);
    }

    $precio = $producto['precio_venta'];
    $subtotal = $precio * $cantidad;

    $sqlDetalle = "INSERT INTO detalle_ventas
    (id_venta,id_producto,codigo,descripcion,cantidad,precio,subtotal)
    VALUES(?,?,?,?,?,?,?)";

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

    $sqlStock = "UPDATE productos SET stock=? WHERE id_producto=?";
    $stmtStock = $conexion->prepare($sqlStock);
    $stmtStock->bind_param("ii", $nuevoStock, $id_producto);
    $stmtStock->execute();

    $sqlMov = "INSERT INTO movimientos
    (id_producto,id_usuario,tipo_movimiento,cantidad,fecha_movimiento,observacion)
    VALUES(?,?,?,?,NOW(),'Venta desde caja')";

    $tipo = "Salida";
    $stmtMov = $conexion->prepare($sqlMov);
    $stmtMov->bind_param("iisi", $id_producto, $id_usuario, $tipo, $cantidad);
    $stmtMov->execute();
}

header("Location: ../vista/ticket.php?id=".$id_venta);
exit();
?>