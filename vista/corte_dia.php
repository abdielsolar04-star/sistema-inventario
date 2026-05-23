<?php
session_start();

include("../modelo/conexion.php");
include("../controlador/permisos.php");

/* =========================
   VALIDAR PERMISO
========================= */

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
WHERE DATE(fecha_venta) = CURDATE()
";

$resultadoTotal = $conexion->query($sqlTotal);

if(!$resultadoTotal){
    die("Error SQL: " . $conexion->error);
}

$datos = $resultadoTotal->fetch_assoc();

$totalDia = $datos['total_dia'] ?? 0;
$totalVentas = $datos['total_ventas'] ?? 0;

/* =========================
   LISTA DE VENTAS
========================= */

$sqlVentas = "
SELECT *
FROM ventas
WHERE DATE(fecha_venta) = CURDATE()
ORDER BY id_venta DESC
";

$ventas = $conexion->query($sqlVentas);

if(!$ventas){
    die("Error SQL: " . $conexion->error);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Corte del Día</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    background:#f1f5f9;
    padding:30px;
}

.contenedor{
    max-width:1200px;
    margin:auto;
}

.card{
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    margin-bottom:25px;
}

h1{
    color:#111827;
    margin-bottom:10px;
}

h2{
    margin-bottom:20px;
    color:#111827;
}

.resumen{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
}

.box{
    flex:1;
    min-width:250px;
    background:#2563eb;
    color:white;
    padding:25px;
    border-radius:15px;
}

.box h3{
    font-size:18px;
    margin-bottom:10px;
}

.box p{
    font-size:32px;
    font-weight:bold;
}

table{
    width:100%;
    border-collapse:collapse;
    overflow:hidden;
    border-radius:15px;
}

table th{
    background:#2563eb;
    color:white;
    padding:15px;
}

table td{
    padding:15px;
    background:white;
    border-bottom:1px solid #ddd;
    text-align:center;
}

.btn{
    display:inline-block;
    margin-top:20px;
    padding:12px 20px;
    background:#2563eb;
    color:white;
    text-decoration:none;
    border-radius:10px;
    transition:0.3s;
}

.btn:hover{
    background:#1d4ed8;
}

.sin-ventas{
    text-align:center;
    padding:30px;
    background:white;
    border-radius:15px;
}

</style>

</head>

<body>

<div class="contenedor">

    <!-- RESUMEN -->

    <div class="card">

        <h1>Corte del Día</h1>

        <div class="resumen">

            <div class="box">
                <h3>Total vendido hoy</h3>

                <p>
                    $<?php echo number_format($totalDia,2); ?>
                </p>
            </div>

            <div class="box">
                <h3>Ventas realizadas</h3>

                <p>
                    <?php echo $totalVentas; ?>
                </p>
            </div>

        </div>

    </div>

    <!-- TABLA -->

    <div class="card">

        <h2>Ventas del Día</h2>

        <?php if($ventas->num_rows > 0){ ?>

        <table>

            <tr>
                <th>ID</th>
                <th>Total</th>
                <th>Fecha</th>
            </tr>

            <?php while($v = $ventas->fetch_assoc()){ ?>

            <tr>

                <td>
                    <?php echo $v['id_venta']; ?>
                </td>

                <td>
                    $<?php echo number_format($v['total'],2); ?>
                </td>

                <td>
                    <?php echo $v['fecha_venta']; ?>
                </td>

            </tr>

            <?php } ?>

        </table>

        <?php } else { ?>

        <div class="sin-ventas">
            No hay ventas registradas hoy.
        </div>

        <?php } ?>

        <a href="dashboard.php" class="btn">
            Volver al Dashboard
        </a>

    </div>

</div>

</body>
</html>