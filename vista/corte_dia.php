<?php
session_start();

include("../modelo/conexion.php");
include("../controlador/permisos.php");

if (!tienePermiso('ventas')) {
    die("No tienes permiso");
}

/* =========================
   TOTAL DEL DÍA
========================= */

$sqlTotal = "
SELECT 
    SUM(total) AS total_dia,
    COUNT(*) AS total_ventas
FROM ventas
WHERE DATE(fecha) = CURDATE()
";

$resultadoTotal = $conexion->query($sqlTotal);

$datos = $resultadoTotal->fetch_assoc();

$totalDia = $datos['total_dia'] ?? 0;
$totalVentas = $datos['total_ventas'] ?? 0;

/* =========================
   LISTA DE VENTAS
========================= */

$sqlVentas = "
SELECT *
FROM ventas
WHERE DATE(fecha) = CURDATE()
ORDER BY id_venta DESC
";

$ventas = $conexion->query($sqlVentas);

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Corte del Día</title>

<style>

body{
    font-family:Arial;
    background:#f1f5f9;
    padding:30px;
}

.card{
    background:white;
    padding:25px;
    border-radius:15px;
    margin-bottom:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

h1{
    margin-bottom:10px;
}

.total{
    font-size:30px;
    color:#16a34a;
    font-weight:bold;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:15px;
    overflow:hidden;
}

table th{
    background:#2563eb;
    color:white;
    padding:12px;
}

table td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:center;
}

.btn{
    display:inline-block;
    margin-top:20px;
    background:#2563eb;
    color:white;
    padding:12px 20px;
    border-radius:10px;
    text-decoration:none;
}

</style>

</head>
<body>

<div class="card">
    <h1>Corte del Día</h1>

    <p>Total vendido hoy:</p>

    <div class="total">
        $<?php echo number_format($totalDia,2); ?>
    </div>

    <p>
        Ventas realizadas:
        <strong><?php echo $totalVentas; ?></strong>
    </p>
</div>

<div class="card">

<h2>Ventas del día</h2>

<table>

<tr>
    <th>ID</th>
    <th>Total</th>
    <th>Fecha</th>
</tr>

<?php while($v = $ventas->fetch_assoc()) { ?>

<tr>
    <td><?php echo $v['id_venta']; ?></td>
    <td>$<?php echo number_format($v['total'],2); ?></td>
    <td><?php echo $v['fecha']; ?></td>
</tr>

<?php } ?>

</table>

<a href="dashboard.php" class="btn">
    Volver
</a>

</div>

</body>
</html>