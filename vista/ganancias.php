<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");
include("../controlador/permisos.php");

if ($_SESSION['rol'] != 'Administrador' && 
    !tienePermiso($conexion, $_SESSION['id_usuario'], "ver_ganancias")) {
    die("No tienes permiso para ver ganancias");
}

$sqlTotal = "
SELECT 
    SUM((p.precio_venta - p.precio_compra) * dv.cantidad) AS ganancia_general,
    SUM(dv.subtotal) AS total_vendido,
    SUM(dv.cantidad) AS total_piezas
FROM detalle_ventas dv
INNER JOIN productos p ON dv.id_producto = p.id_producto
";

$resTotal = $conexion->query($sqlTotal);

if (!$resTotal) {
    die("Error en ganancia total: " . $conexion->error);
}

$total = $resTotal->fetch_assoc();

$gananciaGeneral = $total['ganancia_general'] ?? 0;
$totalVendido = $total['total_vendido'] ?? 0;
$totalPiezas = $total['total_piezas'] ?? 0;

$sql = "
SELECT 
    p.id_producto,
    p.codigo,
    p.nombre_producto,
    p.precio_compra,
    p.precio_venta,
    SUM(dv.cantidad) AS piezas_vendidas,
    SUM(dv.subtotal) AS total_vendido_producto,
    (p.precio_venta - p.precio_compra) AS ganancia_unidad,
    SUM((p.precio_venta - p.precio_compra) * dv.cantidad) AS ganancia_total_producto
FROM detalle_ventas dv
INNER JOIN productos p ON dv.id_producto = p.id_producto
GROUP BY 
    p.id_producto,
    p.codigo,
    p.nombre_producto,
    p.precio_compra,
    p.precio_venta
ORDER BY ganancia_total_producto DESC
";

$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error en consulta de productos: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ganancias</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">

    <style>
        .cards-ganancias {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .card-ganancia {
            background: #0d6efd;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .card-ganancia h2 {
            margin: 10px 0;
            font-size: 30px;
        }

        .ganancia {
            color: green;
            font-weight: bold;
        }

        .perdida {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="contenedor">

    <a href="dashboard.php" class="btn">← Regresar</a>

    <h1>Reporte de ganancias</h1>

    <div class="cards-ganancias">

        <div class="card-ganancia">
            <h3>Total vendido</h3>
            <h2>$<?php echo number_format($totalVendido, 2); ?></h2>
        </div>

        <div class="card-ganancia">
            <h3>Ganancia total</h3>
            <h2>$<?php echo number_format($gananciaGeneral, 2); ?></h2>
        </div>

        <div class="card-ganancia">
            <h3>Piezas vendidas</h3>
            <h2><?php echo $totalPiezas; ?></h2>
        </div>

    </div>

    <h2>Ganancia por producto</h2>

    <table>
        <tr>
            <th>Código</th>
            <th>Producto</th>
            <th>Precio compra</th>
            <th>Precio venta</th>
            <th>Piezas vendidas</th>
            <th>Total vendido</th>
            <th>Ganancia por unidad</th>
            <th>Ganancia total</th>
        </tr>

        <?php while($fila = $resultado->fetch_assoc()) { ?>

        <?php
            $gananciaUnidad = $fila['ganancia_unidad'];
            $gananciaTotal = $fila['ganancia_total_producto'];
        ?>

        <tr>
            <td><?php echo $fila['codigo']; ?></td>

            <td><?php echo $fila['nombre_producto']; ?></td>

            <td>$<?php echo number_format($fila['precio_compra'], 2); ?></td>

            <td>$<?php echo number_format($fila['precio_venta'], 2); ?></td>

            <td><?php echo $fila['piezas_vendidas']; ?></td>

            <td>$<?php echo number_format($fila['total_vendido_producto'], 2); ?></td>

            <td class="<?php echo ($gananciaUnidad >= 0) ? 'ganancia' : 'perdida'; ?>">
                $<?php echo number_format($gananciaUnidad, 2); ?>
            </td>

            <td class="<?php echo ($gananciaTotal >= 0) ? 'ganancia' : 'perdida'; ?>">
                $<?php echo number_format($gananciaTotal, 2); ?>
            </td>
        </tr>

        <?php } ?>

    </table>

</div>

</body>
=======
<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");
include("../controlador/permisos.php");

if ($_SESSION['rol'] != 'Administrador' && 
    !tienePermiso($conexion, $_SESSION['id_usuario'], "ver_ganancias")) {
    die("No tienes permiso para ver ganancias");
}

$sqlTotal = "
SELECT 
    SUM((p.precio_venta - p.precio_compra) * dv.cantidad) AS ganancia_general,
    SUM(dv.subtotal) AS total_vendido,
    SUM(dv.cantidad) AS total_piezas
FROM detalle_ventas dv
INNER JOIN productos p ON dv.id_producto = p.id_producto
";

$resTotal = $conexion->query($sqlTotal);

if (!$resTotal) {
    die("Error en ganancia total: " . $conexion->error);
}

$total = $resTotal->fetch_assoc();

$gananciaGeneral = $total['ganancia_general'] ?? 0;
$totalVendido = $total['total_vendido'] ?? 0;
$totalPiezas = $total['total_piezas'] ?? 0;

$sql = "
SELECT 
    p.id_producto,
    p.codigo,
    p.nombre_producto,
    p.precio_compra,
    p.precio_venta,
    SUM(dv.cantidad) AS piezas_vendidas,
    SUM(dv.subtotal) AS total_vendido_producto,
    (p.precio_venta - p.precio_compra) AS ganancia_unidad,
    SUM((p.precio_venta - p.precio_compra) * dv.cantidad) AS ganancia_total_producto
FROM detalle_ventas dv
INNER JOIN productos p ON dv.id_producto = p.id_producto
GROUP BY 
    p.id_producto,
    p.codigo,
    p.nombre_producto,
    p.precio_compra,
    p.precio_venta
ORDER BY ganancia_total_producto DESC
";

$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error en consulta de productos: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ganancias</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">

    <style>
        .cards-ganancias {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .card-ganancia {
            background: #0d6efd;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .card-ganancia h2 {
            margin: 10px 0;
            font-size: 30px;
        }

        .ganancia {
            color: green;
            font-weight: bold;
        }

        .perdida {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="contenedor">

    <a href="dashboard.php" class="btn">← Regresar</a>

    <h1>Reporte de ganancias</h1>

    <div class="cards-ganancias">

        <div class="card-ganancia">
            <h3>Total vendido</h3>
            <h2>$<?php echo number_format($totalVendido, 2); ?></h2>
        </div>

        <div class="card-ganancia">
            <h3>Ganancia total</h3>
            <h2>$<?php echo number_format($gananciaGeneral, 2); ?></h2>
        </div>

        <div class="card-ganancia">
            <h3>Piezas vendidas</h3>
            <h2><?php echo $totalPiezas; ?></h2>
        </div>

    </div>

    <h2>Ganancia por producto</h2>

    <table>
        <tr>
            <th>Código</th>
            <th>Producto</th>
            <th>Precio compra</th>
            <th>Precio venta</th>
            <th>Piezas vendidas</th>
            <th>Total vendido</th>
            <th>Ganancia por unidad</th>
            <th>Ganancia total</th>
        </tr>

        <?php while($fila = $resultado->fetch_assoc()) { ?>

        <?php
            $gananciaUnidad = $fila['ganancia_unidad'];
            $gananciaTotal = $fila['ganancia_total_producto'];
        ?>

        <tr>
            <td><?php echo $fila['codigo']; ?></td>

            <td><?php echo $fila['nombre_producto']; ?></td>

            <td>$<?php echo number_format($fila['precio_compra'], 2); ?></td>

            <td>$<?php echo number_format($fila['precio_venta'], 2); ?></td>

            <td><?php echo $fila['piezas_vendidas']; ?></td>

            <td>$<?php echo number_format($fila['total_vendido_producto'], 2); ?></td>

            <td class="<?php echo ($gananciaUnidad >= 0) ? 'ganancia' : 'perdida'; ?>">
                $<?php echo number_format($gananciaUnidad, 2); ?>
            </td>

            <td class="<?php echo ($gananciaTotal >= 0) ? 'ganancia' : 'perdida'; ?>">
                $<?php echo number_format($gananciaTotal, 2); ?>
            </td>
        </tr>

        <?php } ?>

    </table>

</div>

</body>

</html>