<?php
include("../controlador/seguridad.php");
include("../controlador/permisos.php");
include("../modelo/conexion.php");

proteger("punto_venta");

$productos = $conexion->query("SELECT * FROM productos WHERE estado='activo' ORDER BY nombre_producto ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Caja</title>

<style>
body{font-family:Arial;background:#f1f5f9;padding:30px}
.contenedor{max-width:1100px;margin:auto}
.card{background:white;padding:25px;border-radius:20px;box-shadow:0 5px 15px rgba(0,0,0,.1)}
h1{margin-bottom:20px}
table{width:100%;border-collapse:collapse;margin-top:20px}
th{background:#2563eb;color:white;padding:12px}
td{padding:12px;border-bottom:1px solid #ddd;text-align:center}
input,select{padding:10px;width:100%;border:1px solid #ccc;border-radius:8px}
button,.btn{background:#2563eb;color:white;border:none;padding:12px 20px;border-radius:10px;cursor:pointer;text-decoration:none}
button:hover,.btn:hover{background:#1d4ed8}
.total{font-size:28px;font-weight:bold;margin-top:20px}
</style>
</head>

<body>

<div class="contenedor">
<div class="card">

<h1>Caja / Punto de Venta</h1>

<form action="../controlador/ventaController.php" method="POST">

<table>
<tr>
    <th>Producto</th>
    <th>Cantidad</th>
</tr>

<tr>
    <td>
        <select name="id_producto" required>
            <option value="">Selecciona producto</option>
            <?php while($p = $productos->fetch_assoc()){ ?>
                <option value="<?php echo $p['id_producto']; ?>">
                    <?php echo $p['nombre_producto']; ?> - $<?php echo $p['precio_venta']; ?> - Stock: <?php echo $p['stock']; ?>
                </option>
            <?php } ?>
        </select>
    </td>

    <td>
        <input type="number" name="cantidad" min="1" required>
    </td>
</tr>
</table>

<br>

<button type="submit">Realizar venta e imprimir ticket</button>

<a href="dashboard.php" class="btn">Volver</a>

</form>

</div>
</div>

</body>
</html>