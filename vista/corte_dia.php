<?php
include("../controlador/seguridad.php");
include("../controlador/permisos.php");
include("../modelo/conexion.php");

proteger("corte_dia");

$sqlTotal = "
SELECT 
    SUM(total) AS total_dia,
    COUNT(*) AS total_ventas
FROM ventas
WHERE DATE(fecha_venta) = CURDATE()
";

$resultadoTotal = $conexion->query($sqlTotal);
$datos = $resultadoTotal->fetch_assoc();

$totalDia = $datos['total_dia'] ?? 0;
$totalVentas = $datos['total_ventas'] ?? 0;

$sqlVentas = "
SELECT 
    v.id_venta,
    v.total,
    v.fecha_venta,
    u.usuario
FROM ventas v
LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario
WHERE DATE(v.fecha_venta) = CURDATE()
ORDER BY v.id_venta DESC
";

$ventas = $conexion->query($sqlVentas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Corte del Día</title>

<style>
body{font-family:Arial;background:#f1f5f9;padding:30px}
.contenedor{max-width:1200px;margin:auto}
.card{background:white;padding:25px;border-radius:20px;box-shadow:0 5px 15px rgba(0,0,0,.1);margin-bottom:20px}
.resumen{display:flex;gap:20px;flex-wrap:wrap}
.box{flex:1;background:#2563eb;color:white;padding:25px;border-radius:15px}
.box p{font-size:30px;font-weight:bold}
table{width:100%;border-collapse:collapse}
th{background:#2563eb;color:white;padding:12px}
td{padding:12px;border-bottom:1px solid #ddd;text-align:center;background:white}
.btn{display:inline-block;margin-top:20px;background:#2563eb;color:white;padding:12px 20px;border-radius:10px;text-decoration:none}
@media print{.btn{display:none}}
</style>
</head>

<body>

<div class="contenedor">

<div class="card">
<h1>Corte del Día</h1>

<div class="resumen">
    <div class="box">
        <h3>Total vendido hoy</h3>
        <p>$<?php echo number_format($totalDia,2); ?></p>
    </div>

    <div class="box">
        <h3>Ventas realizadas</h3>
        <p><?php echo $totalVentas; ?></p>
    </div>
</div>
</div>

<div class="card">
<h2>Ventas del día</h2>

<table>
<tr>
    <th>ID Venta</th>
    <th>Cajero</th>
    <th>Total</th>
    <th>Fecha</th>
    <th>Ticket</th>
</tr>

<?php while($v = $ventas->fetch_assoc()){ ?>
<tr>
    <td><?php echo $v['id_venta']; ?></td>
    <td><?php echo $v['usuario']; ?></td>
    <td>$<?php echo number_format($v['total'],2); ?></td>
    <td><?php echo $v['fecha_venta']; ?></td>
    <td><a href="ticket.php?id_venta=<?php echo $v['id_venta']; ?>">Ver</a></td>
</tr>
<?php } ?>

</table>

<a href="#" onclick="window.print()" class="btn">Imprimir Corte</a>
<a href="dashboard.php" class="btn">Volver</a>

</div>

</div>

</body>
</html>