<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");
include("../controlador/permisos.php");

if ($_SESSION['rol'] != 'Administrador' && 
    !tienePermiso($conexion, $_SESSION['id_usuario'], "corte_dia")) {
    die("No tienes permiso para hacer corte del día");
}

$fecha = $_GET['fecha'] ?? date("Y-m-d");

/* TOTAL DEL DÍA Y GANANCIA */
$totalDia = $conexion->prepare("
    SELECT 
        COUNT(DISTINCT v.id_venta) AS ventas_dia,
        SUM(dv.cantidad) AS piezas_dia,
        SUM(dv.subtotal) AS total_vendido,
        SUM((p.precio_venta - p.precio_compra) * dv.cantidad) AS ganancia_dia
    FROM ventas v
    INNER JOIN detalle_ventas dv ON v.id_venta = dv.id_venta
    INNER JOIN productos p ON dv.id_producto = p.id_producto
    WHERE DATE(v.fecha_venta) = ?
");

$totalDia->bind_param("s", $fecha);
$totalDia->execute();
$datosTotal = $totalDia->get_result()->fetch_assoc();

$ventas_dia = $datosTotal['ventas_dia'] ?? 0;
$piezas_dia = $datosTotal['piezas_dia'] ?? 0;
$total_vendido = $datosTotal['total_vendido'] ?? 0;
$ganancia_dia = $datosTotal['ganancia_dia'] ?? 0;

/* RESUMEN POR EMPLEADO */
$resumen = $conexion->prepare("
    SELECT 
        u.nombre,
        u.usuario,
        COUNT(DISTINCT v.id_venta) AS total_ventas,
        SUM(dv.cantidad) AS piezas_vendidas,
        SUM(dv.subtotal) AS total_vendido,
        SUM((p.precio_venta - p.precio_compra) * dv.cantidad) AS ganancia_total
    FROM ventas v
    INNER JOIN usuarios u ON v.id_usuario = u.id_usuario
    INNER JOIN detalle_ventas dv ON v.id_venta = dv.id_venta
    INNER JOIN productos p ON dv.id_producto = p.id_producto
    WHERE DATE(v.fecha_venta) = ?
    GROUP BY u.id_usuario, u.nombre, u.usuario
");

$resumen->bind_param("s", $fecha);
$resumen->execute();
$resultadoResumen = $resumen->get_result();

/* DETALLE DE VENTAS */
$detalle = $conexion->prepare("
    SELECT 
        v.id_venta,
        v.fecha_venta,
        u.nombre AS empleado,
        dv.descripcion,
        dv.cantidad,
        dv.precio,
        dv.subtotal,
        ((p.precio_venta - p.precio_compra) * dv.cantidad) AS ganancia_producto
    FROM ventas v
    INNER JOIN usuarios u ON v.id_usuario = u.id_usuario
    INNER JOIN detalle_ventas dv ON v.id_venta = dv.id_venta
    INNER JOIN productos p ON dv.id_producto = p.id_producto
    WHERE DATE(v.fecha_venta) = ?
    ORDER BY v.fecha_venta DESC
");

$detalle->bind_param("s", $fecha);
$detalle->execute();
$resultadoDetalle = $detalle->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Corte del día</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">

    <style>
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .card {
            background: #0d6efd;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .card h2 {
            margin: 0;
            font-size: 28px;
        }

        .ganancia-card {
            background: #198754;
        }

        @media print {
            .btn, form {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="contenedor">

    <a href="dashboard.php" class="btn">← Regresar</a>

    <h1>Corte del día</h1>

    <form method="GET">
        <label>Selecciona fecha:</label>
        <input type="date" name="fecha" value="<?php echo $fecha; ?>">
        <button type="submit">Consultar</button>
       
        <button type="button" onclick="imprimirCorte()">
    Imprimir corte del día
</button>

<script>
function imprimirCorte(){
    if(window.Android){
        Android.imprimirCorte();
    }else{
        window.print();
    }
}
</script>
    </form>

    <h2>Fecha: <?php echo $fecha; ?></h2>

    <div class="cards">

        <div class="card">
            <h3>Ventas</h3>
            <h2><?php echo $ventas_dia; ?></h2>
        </div>

        <div class="card">
            <h3>Piezas vendidas</h3>
            <h2><?php echo $piezas_dia; ?></h2>
        </div>

        <div class="card">
            <h3>Total vendido</h3>
            <h2>$<?php echo number_format($total_vendido, 2); ?></h2>
        </div>

        <div class="card ganancia-card">
            <h3>Ganancia total</h3>
            <h2>$<?php echo number_format($ganancia_dia, 2); ?></h2>
        </div>

    </div>

    <h2>Resumen por empleado</h2>

    <table>
        <tr>
            <th>Empleado</th>
            <th>Usuario</th>
            <th>Ventas</th>
            <th>Piezas</th>
            <th>Total vendido</th>
            <th>Ganancia</th>
        </tr>

        <?php while($r = $resultadoResumen->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $r['nombre']; ?></td>
            <td><?php echo $r['usuario']; ?></td>
            <td><?php echo $r['total_ventas']; ?></td>
            <td><?php echo $r['piezas_vendidas']; ?></td>
            <td>$<?php echo number_format($r['total_vendido'], 2); ?></td>
            <td>$<?php echo number_format($r['ganancia_total'], 2); ?></td>
        </tr>
        <?php } ?>
    </table>

    <h2>Detalle de ventas</h2>

    <table>
        <tr>
            <th>Folio</th>
            <th>Empleado</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Total</th>
            <th>Ganancia</th>
            <th>Hora</th>
        </tr>

        <?php while($d = $resultadoDetalle->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $d['id_venta']; ?></td>
            <td><?php echo $d['empleado']; ?></td>
            <td><?php echo $d['descripcion']; ?></td>
            <td><?php echo $d['cantidad']; ?></td>
            <td>$<?php echo number_format($d['precio'], 2); ?></td>
            <td>$<?php echo number_format($d['subtotal'], 2); ?></td>
            <td>$<?php echo number_format($d['ganancia_producto'], 2); ?></td>
            <td><?php echo date("H:i", strtotime($d['fecha_venta'])); ?></td>
        </tr>
        <?php } ?>
    </table>

</div>

</body>
</html>