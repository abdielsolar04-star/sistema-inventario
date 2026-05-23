<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");

$id_venta = intval($_GET['id'] ?? $_GET['id_venta'] ?? 0);

if ($id_venta <= 0) {
    die("Ticket no válido");
}

$venta = $conexion->prepare("
    SELECT ventas.*, usuarios.nombre
    FROM ventas
    INNER JOIN usuarios ON ventas.id_usuario = usuarios.id_usuario
    WHERE ventas.id_venta = ?
");

$venta->bind_param("i", $id_venta);
$venta->execute();
$ventaData = $venta->get_result()->fetch_assoc();

if (!$ventaData) {
    die("Ticket no encontrado");
}

$detalle = $conexion->prepare("
    SELECT 
        detalle_ventas.*,
        productos.nombre_producto
    FROM detalle_ventas
    INNER JOIN productos 
        ON detalle_ventas.id_producto = productos.id_producto
    WHERE detalle_ventas.id_venta = ?
");

$detalle->bind_param("i", $id_venta);
$detalle->execute();
$resultadoDetalle = $detalle->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Ticket</title>

<style>
*{
    box-sizing:border-box;
}

body {
    background:#f3f4f6;
    font-family:monospace;
    margin:0;
    padding:15px;
}

.ticket {
    width:360px;
    max-width:100%;
    margin:20px auto;
    background:white;
    padding:22px;
    border-radius:18px;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
}

.center {
    text-align:center;
}

.ticket h2 {
    margin-bottom:5px;
}

.ticket p {
    margin:4px 0;
}

table {
    width:100%;
    font-size:12px;
    border-collapse:collapse;
}

td {
    padding:5px 0;
    vertical-align:top;
}

.total {
    font-size:24px;
    font-weight:bold;
    text-align:right;
    margin-top:15px;
}

.btn {
    display:block;
    background:#2563eb;
    color:white;
    padding:12px;
    text-align:center;
    text-decoration:none;
    border-radius:10px;
    margin-top:10px;
    border:none;
    width:100%;
    cursor:pointer;
    font-size:15px;
}

.btn:hover {
    background:#1e40af;
}

.btn-regresar {
    background:#16a34a;
}

@media print {
    body {
        background:white;
        padding:0;
    }

    .btn {
        display:none;
    }

    .ticket {
        box-shadow:none;
        margin:0;
        width:100%;
        border-radius:0;
    }
}
</style>
</head>

<body>

<div class="ticket">

    <div class="center">
        <h2>PAPELERÍA</h2>
        <p>Sistema de Inventario y Punto de Venta</p>
        <p>Folio: <?php echo $ventaData['id_venta']; ?></p>
        <p>Empleado: <?php echo htmlspecialchars($ventaData['nombre']); ?></p>
        <p>Fecha: <?php echo $ventaData['fecha_venta']; ?></p>
    </div>

    <hr>

    <table>
        <?php while($d = $resultadoDetalle->fetch_assoc()) { ?>
        <tr>
            <td>
                <?php echo htmlspecialchars($d['nombre_producto']); ?>
                x<?php echo $d['cantidad']; ?>
            </td>

            <td style="text-align:right;">
                $<?php echo number_format($d['subtotal'], 2); ?>
            </td>
        </tr>
        <?php } ?>
    </table>

    <hr>

    <p class="total">
        TOTAL: $<?php echo number_format($ventaData['total'], 2); ?>
    </p>

    <div class="center">
        <p>Gracias por su compra</p>
    </div>

    <button type="button" onclick="imprimirTicket()" class="btn">
        Imprimir / Guardar PDF
    </button>

    <a href="caja.php" class="btn btn-regresar">
        Regresar a caja
    </a>

</div>

<script>
function imprimirTicket(){
    if (typeof Android !== "undefined" && Android.imprimir) {
        Android.imprimir();
    } else {
        window.print();
    }
}
</script>

</body>
</html>