<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");

$id_venta = $_GET['id_venta'];

$sql = "
SELECT 
    v.id_venta,
    v.total,
    v.fecha_venta,
    u.usuario,
    dv.descripcion,
    dv.cantidad,
    dv.precio,
    dv.subtotal
FROM ventas v
INNER JOIN usuarios u ON v.id_usuario = u.id_usuario
INNER JOIN detalle_ventas dv ON v.id_venta = dv.id_venta
WHERE v.id_venta = ?
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_venta);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    die("Ticket no encontrado");
}

$venta = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ticket</title>

<style>
body{font-family:Arial;background:white}
.ticket{width:300px;margin:30px auto;border:1px dashed #333;padding:20px}
h2{text-align:center}
p{margin:6px 0}
.total{font-size:22px;font-weight:bold;text-align:center}
.btn{display:block;margin:15px auto;padding:10px;background:#2563eb;color:white;text-align:center;text-decoration:none;border-radius:8px}
@media print{.btn{display:none}}
</style>
</head>

<body>

<div class="ticket">

<h2>Punto de Venta</h2>

<p><strong>Ticket:</strong> <?php echo $venta['id_venta']; ?></p>
<p><strong>Fecha:</strong> <?php echo $venta['fecha_venta']; ?></p>
<p><strong>Cajero:</strong> <?php echo $venta['usuario']; ?></p>

<hr>

<p><strong>Producto:</strong> <?php echo $venta['descripcion']; ?></p>
<p><strong>Cantidad:</strong> <?php echo $venta['cantidad']; ?></p>
<p><strong>Precio:</strong> $<?php echo number_format($venta['precio'],2); ?></p>
<p><strong>Subtotal:</strong> $<?php echo number_format($venta['subtotal'],2); ?></p>

<hr>

<p class="total">Total: $<?php echo number_format($venta['total'],2); ?></p>

<p style="text-align:center;">Gracias por su compra</p>

<a href="#" onclick="window.print()" class="btn">Imprimir Ticket</a>
<a href="caja.php" class="btn">Nueva venta</a>
<a href="dashboard.php" class="btn">Dashboard</a>

</div>

</body>
</html>