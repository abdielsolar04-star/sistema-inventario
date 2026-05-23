<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");

if ($_SESSION['rol'] != 'Administrador') {
    die("No tienes permiso");
}

$ventas = $conexion->query("
    SELECT ventas.*, productos.nombre_producto, usuarios.nombre, usuarios.usuario
    FROM ventas
    INNER JOIN productos ON ventas.id_producto = productos.id_producto
    INNER JOIN usuarios ON ventas.id_usuario = usuarios.id_usuario
    ORDER BY ventas.fecha_venta DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas de empleados</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>

<div class="contenedor">
    <a href="dashboard.php" class="btn">← Regresar</a>

    <h1>Ventas realizadas por empleados</h1>

    <table>
        <tr>
            <th>Empleado</th>
            <th>Usuario</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Total</th>
            <th>Fecha y hora</th>
        </tr>

        <?php while($v = $ventas->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $v['nombre']; ?></td>
            <td><?php echo $v['usuario']; ?></td>
            <td><?php echo $v['nombre_producto']; ?></td>
            <td><?php echo $v['cantidad']; ?></td>
            <td>$<?php echo $v['total']; ?></td>
            <td><?php echo $v['fecha_venta']; ?></td>
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

$ventas = $conexion->query("
    SELECT ventas.*, productos.nombre_producto, usuarios.nombre, usuarios.usuario
    FROM ventas
    INNER JOIN productos ON ventas.id_producto = productos.id_producto
    INNER JOIN usuarios ON ventas.id_usuario = usuarios.id_usuario
    ORDER BY ventas.fecha_venta DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas de empleados</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>

<div class="contenedor">
    <a href="dashboard.php" class="btn">← Regresar</a>

    <h1>Ventas realizadas por empleados</h1>

    <table>
        <tr>
            <th>Empleado</th>
            <th>Usuario</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Total</th>
            <th>Fecha y hora</th>
        </tr>

        <?php while($v = $ventas->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $v['nombre']; ?></td>
            <td><?php echo $v['usuario']; ?></td>
            <td><?php echo $v['nombre_producto']; ?></td>
            <td><?php echo $v['cantidad']; ?></td>
            <td>$<?php echo $v['total']; ?></td>
            <td><?php echo $v['fecha_venta']; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
>>>>>>> 452094ddd0ffd458f6b759cd9dfb5fbf40ef7bb2
</html>