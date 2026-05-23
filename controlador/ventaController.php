<?php

include("seguridad.php");
include("../modelo/conexion.php");

$id_usuario = $_SESSION['id_usuario'];

$productos = json_decode($_POST['productos'] ?? '', true);
$total = floatval($_POST['total'] ?? 0);

if (!$productos || count($productos) == 0) {
    die("No hay productos en la venta");
}

if ($total <= 0) {
    die("Total inválido");
}

$conexion->begin_transaction();

try {

    /*
    1. Registrar venta principal
    */
    $sqlVenta = "
    INSERT INTO ventas
    (
        id_producto,
        id_usuario,
        cantidad,
        total
    )
    VALUES
    (
        NULL,
        ?,
        0,
        ?
    )
    ";

    $stmtVenta = $conexion->prepare($sqlVenta);
    $stmtVenta->bind_param("id", $id_usuario, $total);
    $stmtVenta->execute();

    $id_venta = $conexion->insert_id;

    /*
    2. Registrar cada producto vendido
    */
    foreach ($productos as $productoCarrito) {

        $id_producto = intval($productoCarrito['id']);

        $sqlProducto = "
        SELECT *
        FROM productos
        WHERE id_producto = ?
        ";

        $stmtProducto = $conexion->prepare($sqlProducto);
        $stmtProducto->bind_param("i", $id_producto);
        $stmtProducto->execute();

        $resultadoProducto = $stmtProducto->get_result();

        if ($resultadoProducto->num_rows == 0) {
            throw new Exception("Producto no encontrado");
        }

        $producto = $resultadoProducto->fetch_assoc();

        if ($producto['stock'] <= 0) {
            throw new Exception("No hay stock para: " . $producto['nombre_producto']);
        }

        $cantidad = 1;
        $precio = floatval($producto['precio_venta']);
        $subtotal = $precio * $cantidad;

        /*
        3. Insertar detalle de venta
        */
        $sqlDetalle = "
        INSERT INTO detalle_ventas
        (
            id_venta,
            id_producto,
            codigo,
            descripcion,
            cantidad,
            precio,
            subtotal
        )
        VALUES
        (
            ?, ?, ?, ?, ?, ?, ?
        )
        ";

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

        /*
        4. Descontar stock
        */
        $sqlStock = "
        UPDATE productos
        SET stock = stock - 1
        WHERE id_producto = ?
        ";

        $stmtStock = $conexion->prepare($sqlStock);
        $stmtStock->bind_param("i", $id_producto);
        $stmtStock->execute();
    }

    /*
    5. Auditoría
    */
    $ip = $_SERVER['REMOTE_ADDR'];
    $so = $_SERVER['HTTP_USER_AGENT'];

    $descripcion = "Venta registrada. Total: $" . $total;

    $sqlAuditoria = "
    INSERT INTO auditoria
    (
        id_usuario,
        accion,
        tabla_afectada,
        descripcion,
        ip_usuario,
        sistema_operativo
    )
    VALUES
    (
        ?,
        'VENTA',
        'ventas',
        ?,
        ?,
        ?
    )
    ";

    $stmtAuditoria = $conexion->prepare($sqlAuditoria);

    $stmtAuditoria->bind_param(
        "isss",
        $id_usuario,
        $descripcion,
        $ip,
        $so
    );

    $stmtAuditoria->execute();

    $conexion->commit();

    header("Location: ../vista/ticket.php?id=" . $id_venta);
    exit();

} catch (Exception $e) {

    $conexion->rollback();

    die("Error al vender: " . $e->getMessage());
}

?>