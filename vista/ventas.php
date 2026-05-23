<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");

if ($_SESSION['rol'] != 'Empleado') {
    die("Solo el empleado puede registrar ventas aquí");
}

$mis_ventas = $conexion->query("
    SELECT ventas.*, productos.nombre_producto, productos.codigo
    FROM ventas
    INNER JOIN productos ON ventas.id_producto = productos.id_producto
    WHERE ventas.id_usuario = " . $_SESSION['id_usuario'] . "
    ORDER BY ventas.fecha_venta DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Venta con QR</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>

<div class="contenedor">

    <a href="dashboard.php" class="btn">← Regresar</a>

    <h1>Registrar venta con escáner / QR</h1>

    <form action="../controlador/ventaScanController.php" method="POST">

        <input 
            type="text" 
            name="codigo" 
            id="codigo" 
            placeholder="Escanea o escribe el código del producto" 
            autofocus
            required
        >

        <input 
            type="number" 
            name="cantidad" 
            placeholder="Cantidad" 
            value="1" 
            min="1" 
            required
        >

        <button type="submit">Registrar venta</button>

    </form>

    <h2>Mis ventas registradas</h2>

    <table>
        <tr>
            <th>Código</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Total</th>
            <th>Fecha y hora</th>
        </tr>

        <?php while($v = $mis_ventas->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $v['codigo']; ?></td>
            <td><?php echo $v['nombre_producto']; ?></td>
            <td><?php echo $v['cantidad']; ?></td>
            <td>$<?php echo number_format($v['total'], 2); ?></td>
            <td><?php echo $v['fecha_venta']; ?></td>
        </tr>
        <?php } ?>
    </table>

</div>

<script>
document.getElementById("codigo").focus();
</script>

</body>
=======
<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");

if ($_SESSION['rol'] != 'Empleado') {
    die("Solo el empleado puede registrar ventas aquí");
}

$mis_ventas = $conexion->query("
    SELECT ventas.*, productos.nombre_producto, productos.codigo
    FROM ventas
    INNER JOIN productos ON ventas.id_producto = productos.id_producto
    WHERE ventas.id_usuario = " . $_SESSION['id_usuario'] . "
    ORDER BY ventas.fecha_venta DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Venta con QR</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>

<div class="contenedor">

    <a href="dashboard.php" class="btn">← Regresar</a>

    <h1>Registrar venta con escáner / QR</h1>

    <form action="../controlador/ventaScanController.php" method="POST">

        <input 
            type="text" 
            name="codigo" 
            id="codigo" 
            placeholder="Escanea o escribe el código del producto" 
            autofocus
            required
        >

        <input 
            type="number" 
            name="cantidad" 
            placeholder="Cantidad" 
            value="1" 
            min="1" 
            required
        >

        <button type="submit">Registrar venta</button>

    </form>

    <h2>Mis ventas registradas</h2>

    <table>
        <tr>
            <th>Código</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Total</th>
            <th>Fecha y hora</th>
        </tr>

        <?php while($v = $mis_ventas->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $v['codigo']; ?></td>
            <td><?php echo $v['nombre_producto']; ?></td>
            <td><?php echo $v['cantidad']; ?></td>
            <td>$<?php echo number_format($v['total'], 2); ?></td>
            <td><?php echo $v['fecha_venta']; ?></td>
        </tr>
        <?php } ?>
    </table>

</div>

<script>
document.getElementById("codigo").focus();
</script>

</body>
>>>>>>> 452094ddd0ffd458f6b759cd9dfb5fbf40ef7bb2
</html>