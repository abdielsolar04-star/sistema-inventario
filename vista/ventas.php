<?php
error_reporting(0);
ini_set('display_errors', 0);

include("../controlador/seguridad.php");
include("../controlador/permisos.php");
include("../modelo/conexion.php");

if (esAdmin()) {
    $sql = "SELECT v.*, u.usuario, p.nombre_producto
            FROM ventas v
            LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario
            LEFT JOIN productos p ON v.id_producto = p.id_producto
            ORDER BY v.id_venta DESC";
} else {
    $id_usuario = $_SESSION['id_usuario'];

    $sql = "SELECT v.*, u.usuario, p.nombre_producto
            FROM ventas v
            LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario
            LEFT JOIN productos p ON v.id_producto = p.id_producto
            WHERE v.id_usuario='$id_usuario'
            ORDER BY v.id_venta DESC";
}

$ventas = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ventas</title>
<style>
body{font-family:Arial;background:#f1f5f9;padding:30px;}
.card{background:white;padding:25px;border-radius:18px;}
table{width:100%;border-collapse:collapse;}
th{background:#2563eb;color:white;padding:12px;}
td{padding:12px;border-bottom:1px solid #ddd;text-align:center;}
.btn{background:#2563eb;color:white;padding:10px 15px;border-radius:8px;text-decoration:none;}
</style>
</head>
<body>

<div class="card">
<h1>Ventas</h1>

<table>
<tr>
    <th>ID</th>
    <th>Producto</th>
    <th>Usuario</th>
    <th>Cantidad</th>
    <th>Total</th>
    <th>Fecha</th>
    <th>Ticket</th>
</tr>

<?php while($v = $ventas->fetch_assoc()) { ?>
<tr>
    <td><?php echo $v['id_venta']; ?></td>
    <td><?php echo $v['nombre_producto']; ?></td>
    <td><?php echo $v['usuario']; ?></td>
    <td><?php echo $v['cantidad']; ?></td>
    <td>$<?php echo number_format($v['total'],2); ?></td>
    <td><?php echo $v['fecha_venta']; ?></td>
    <td>
        <a class="btn" href="ticket.php?id=<?php echo $v['id_venta']; ?>">
            Imprimir
        </a>
    </td>
</tr>
<?php } ?>

</table>

<br>
<a class="btn" href="dashboard.php">Volver</a>

</div>

</body>
</html>