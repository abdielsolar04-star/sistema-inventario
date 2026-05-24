<?php
error_reporting(0);
ini_set('display_errors', 0);

include("../modelo/conexion.php");

$id = $_GET['id'] ?? 0;

$sql = "SELECT v.*, u.usuario
        FROM ventas v
        LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario
        WHERE v.id_venta=?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$venta = $stmt->get_result()->fetch_assoc();

$detalles = $conexion->query("SELECT * FROM detalle_ventas WHERE id_venta='$id'");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ticket</title>
<style>
body{
    font-family:Arial;
    width:300px;
    font-size:14px;
}
h2,p{
    text-align:center;
}
table{
    width:100%;
    border-collapse:collapse;
}
td,th{
    border-bottom:1px dashed #999;
    padding:5px;
    text-align:left;
}
.total{
    font-size:18px;
    font-weight:bold;
    text-align:right;
}
</style>
</head>
<body>

<h2>Punto de Venta</h2>
<p>Ticket de venta</p>

<hr>

<p>Venta: <?php echo $venta['id_venta']; ?></p>
<p>Usuario: <?php echo $venta['usuario']; ?></p>
<p>Fecha: <?php echo $venta['fecha_venta']; ?></p>

<table>
<tr>
    <th>Producto</th>
    <th>Cant.</th>
    <th>Sub.</th>
</tr>

<?php while($d = $detalles->fetch_assoc()) { ?>
<tr>
    <td><?php echo $d['descripcion']; ?></td>
    <td><?php echo $d['cantidad']; ?></td>
    <td>$<?php echo number_format($d['subtotal'],2); ?></td>
</tr>
<?php } ?>

</table>

<p class="total">
Total: $<?php echo number_format($venta['total'],2); ?>
</p>

<p>Gracias por su compra</p>

<script>
window.print();
</script>

</body>
</html>