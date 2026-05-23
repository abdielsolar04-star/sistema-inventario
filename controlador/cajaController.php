<?php
include("seguridad.php");
include("../modelo/conexion.php");

$id_usuario = $_SESSION['id_usuario'];
$productos = json_decode($_POST['productos_json'] ?? '', true);
$total = floatval($_POST['total'] ?? 0);

if (!$productos || count($productos) == 0) {
    die("No hay productos en la venta");
}

$conexion->begin_transaction();

try {

    $stmtVenta = $conexion->prepare("
        INSERT INTO ventas(id_producto, id_usuario, cantidad, total)
        VALUES (NULL, ?, 0, ?)
    ");

    $stmtVenta->bind_param("id", $id_usuario, $total);
    $stmtVenta->execute();

    $id_venta = $conexion->insert_id;

    foreach ($productos as $p) {

        $id_producto = intval($p['id_producto']);
        $cantidad = intval($p['cantidad']);

        $consulta = $conexion->prepare("
            SELECT * FROM productos WHERE id_producto = ?
        ");
        $consulta->bind_param("i", $id_producto);
        $consulta->execute();

        $producto = $consulta->get_result()->fetch_assoc();

        if (!$producto) {
            throw new Exception("Producto no encontrado");
        }

        if ($producto['stock'] < $cantidad) {
            throw new Exception("Stock insuficiente para " . $producto['nombre_producto']);
        }

        $precio = $producto['precio_venta'];
        $subtotal = $precio * $cantidad;

        $detalle = $conexion->prepare("
            INSERT INTO detalle_ventas
            (id_venta, id_producto, codigo, descripcion, cantidad, precio, subtotal)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $detalle->bind_param(
            "iissidd",
            $id_venta,
            $id_producto,
            $producto['codigo'],
            $producto['nombre_producto'],
            $cantidad,
            $precio,
            $subtotal
        );

        $detalle->execute();

        $update = $conexion->prepare("
            UPDATE productos 
            SET stock = stock - ?
            WHERE id_producto = ?
        ");

        $update->bind_param("ii", $cantidad, $id_producto);
        $update->execute();
    }

    $ip = $_SERVER['REMOTE_ADDR'];
    $so = $_SERVER['HTTP_USER_AGENT'];

    $auditoria = $conexion->prepare("
        INSERT INTO auditoria
        (id_usuario, accion, tabla_afectada, descripcion, ip_usuario, sistema_operativo)
        VALUES (?, 'VENTA', 'ventas', ?, ?, ?)
    ");

    $descripcion = "Venta cobrada. Total: $" . $total;

    $auditoria->bind_param("isss", $id_usuario, $descripcion, $ip, $so);
    $auditoria->execute();

    $conexion->commit();

    header("Location: ../vista/ticket.php?id=" . $id_venta);
    exit();

} catch (Exception $e) {

    $conexion->rollback();
    die("Error al cobrar: " . $e->getMessage());
}
?>