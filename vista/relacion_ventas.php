<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");

if ($_SESSION['rol'] != 'Administrador') {
    die("No tienes permiso");
}

$fecha = $_GET['fecha'] ?? date("Y-m-d");

$sql = $conexion->prepare("
    SELECT 
        ventas.id_venta,
        ventas.fecha_venta,
        usuarios.nombre AS empleado,
        detalle_ventas.codigo,
        detalle_ventas.descripcion,
        detalle_ventas.cantidad,
        detalle_ventas.precio,
        detalle_ventas.subtotal
    FROM detalle_ventas
    INNER JOIN ventas ON detalle_ventas.id_venta = ventas.id_venta
    INNER JOIN usuarios ON ventas.id_usuario = usuarios.id_usuario
    WHERE DATE(ventas.fecha_venta) = ?
    ORDER BY ventas.fecha_venta DESC
");

$sql->bind_param("s", $fecha);
$sql->execute();
$resultado = $sql->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Relación de ventas</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>

<div class="contenedor">

    <a href="dashboard.php" class="btn">← Regresar</a>

    <h1>Relación de ventas</h1>

    <form method="GET">
        <input type="date" name="fecha" value="<?php echo $fecha; ?>">
        <button type="submit">Buscar</button>
    </form>

    <table>
        <tr>
            <th>Hora</th>
            <th>Folio</th>
            <th>Empleado</th>
            <th>Código</th>
            <th>Descripción del artículo</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Total</th>
        </tr>

        <?php while($v = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?php echo date("H:i", strtotime($v['fecha_venta'])); ?></td>
            <td><?php echo $v['id_venta']; ?></td>
            <td><?php echo $v['empleado']; ?></td>
            <td><?php echo $v['codigo']; ?></td>
            <td><?php echo $v['descripcion']; ?></td>
            <td><?php echo $v['cantidad']; ?></td>
            <td>$<?php echo number_format($v['precio'], 2); ?></td>
            <td>$<?php echo number_format($v['subtotal'], 2); ?></td>
        </tr>
        <?php } ?>
    </table>

</div>

</body>
=======
<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");

if ($_SESSION['rol'] != 'Administrador') {
    die("No tienes permiso");
}

$fecha = $_GET['fecha'] ?? date("Y-m-d");

$sql = $conexion->prepare("
    SELECT 
        ventas.id_venta,
        ventas.fecha_venta,
        usuarios.nombre AS empleado,
        detalle_ventas.codigo,
        detalle_ventas.descripcion,
        detalle_ventas.cantidad,
        detalle_ventas.precio,
        detalle_ventas.subtotal
    FROM detalle_ventas
    INNER JOIN ventas ON detalle_ventas.id_venta = ventas.id_venta
    INNER JOIN usuarios ON ventas.id_usuario = usuarios.id_usuario
    WHERE DATE(ventas.fecha_venta) = ?
    ORDER BY ventas.fecha_venta DESC
");

$sql->bind_param("s", $fecha);
$sql->execute();
$resultado = $sql->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Relación de ventas</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>

<div class="contenedor">

    <a href="dashboard.php" class="btn">← Regresar</a>

    <h1>Relación de ventas</h1>

    <form method="GET">
        <input type="date" name="fecha" value="<?php echo $fecha; ?>">
        <button type="submit">Buscar</button>
    </form>

    <table>
        <tr>
            <th>Hora</th>
            <th>Folio</th>
            <th>Empleado</th>
            <th>Código</th>
            <th>Descripción del artículo</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Total</th>
        </tr>

        <?php while($v = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?php echo date("H:i", strtotime($v['fecha_venta'])); ?></td>
            <td><?php echo $v['id_venta']; ?></td>
            <td><?php echo $v['empleado']; ?></td>
            <td><?php echo $v['codigo']; ?></td>
            <td><?php echo $v['descripcion']; ?></td>
            <td><?php echo $v['cantidad']; ?></td>
            <td>$<?php echo number_format($v['precio'], 2); ?></td>
            <td>$<?php echo number_format($v['subtotal'], 2); ?></td>
        </tr>
        <?php } ?>
    </table>

</div>

</body>
>>>>>>> 452094ddd0ffd458f6b759cd9dfb5fbf40ef7bb2
</html>