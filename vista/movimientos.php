<<<<<<< HEAD
<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");

$productos = $conexion->query("SELECT * FROM productos");
$movimientos = $conexion->query("
    SELECT movimientos.*, productos.nombre_producto, usuarios.usuario
    FROM movimientos
    INNER JOIN productos ON movimientos.id_producto = productos.id_producto
    INNER JOIN usuarios ON movimientos.id_usuario = usuarios.id_usuario
    ORDER BY fecha_movimiento DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entradas y Salidas</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>

<div class="contenedor">
    <a href="dashboard.php" class="btn">← Regresar</a>

    <h1>Entradas y Salidas</h1>

    <form action="../controlador/movimientoController.php" method="POST">
        <select name="id_producto" required>
            <option value="">Selecciona producto</option>
            <?php while($p = $productos->fetch_assoc()) { ?>
                <option value="<?php echo $p['id_producto']; ?>">
                    <?php echo $p['nombre_producto']; ?>
                </option>
            <?php } ?>
        </select>

        <select name="tipo_movimiento" required>
            <option value="Entrada">Entrada</option>
            <option value="Salida">Salida</option>
        </select>

        <input type="number" name="cantidad" placeholder="Cantidad" required>
        <input type="text" name="observacion" placeholder="Observación">

        <button type="submit">Registrar movimiento</button>
    </form>

    <table>
        <tr>
            <th>Producto</th>
            <th>Usuario</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Fecha</th>
            <th>Observación</th>
        </tr>

        <?php while($m = $movimientos->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $m['nombre_producto']; ?></td>
            <td><?php echo $m['usuario']; ?></td>
            <td><?php echo $m['tipo_movimiento']; ?></td>
            <td><?php echo $m['cantidad']; ?></td>
            <td><?php echo $m['fecha_movimiento']; ?></td>
            <td><?php echo $m['observacion']; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
=======
<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");

$productos = $conexion->query("SELECT * FROM productos");
$movimientos = $conexion->query("
    SELECT movimientos.*, productos.nombre_producto, usuarios.usuario
    FROM movimientos
    INNER JOIN productos ON movimientos.id_producto = productos.id_producto
    INNER JOIN usuarios ON movimientos.id_usuario = usuarios.id_usuario
    ORDER BY fecha_movimiento DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entradas y Salidas</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>

<div class="contenedor">
    <a href="dashboard.php" class="btn">← Regresar</a>

    <h1>Entradas y Salidas</h1>

    <form action="../controlador/movimientoController.php" method="POST">
        <select name="id_producto" required>
            <option value="">Selecciona producto</option>
            <?php while($p = $productos->fetch_assoc()) { ?>
                <option value="<?php echo $p['id_producto']; ?>">
                    <?php echo $p['nombre_producto']; ?>
                </option>
            <?php } ?>
        </select>

        <select name="tipo_movimiento" required>
            <option value="Entrada">Entrada</option>
            <option value="Salida">Salida</option>
        </select>

        <input type="number" name="cantidad" placeholder="Cantidad" required>
        <input type="text" name="observacion" placeholder="Observación">

        <button type="submit">Registrar movimiento</button>
    </form>

    <table>
        <tr>
            <th>Producto</th>
            <th>Usuario</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Fecha</th>
            <th>Observación</th>
        </tr>

        <?php while($m = $movimientos->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $m['nombre_producto']; ?></td>
            <td><?php echo $m['usuario']; ?></td>
            <td><?php echo $m['tipo_movimiento']; ?></td>
            <td><?php echo $m['cantidad']; ?></td>
            <td><?php echo $m['fecha_movimiento']; ?></td>
            <td><?php echo $m['observacion']; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
>>>>>>> 452094ddd0ffd458f6b759cd9dfb5fbf40ef7bb2
</html>