<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");

if ($_SESSION['rol'] != 'Administrador') {
    die("No tienes permiso para ver esta página");
}

$sql = "SELECT auditoria.*, usuarios.usuario
        FROM auditoria
        LEFT JOIN usuarios ON auditoria.id_usuario = usuarios.id_usuario
        ORDER BY fecha_hora DESC";

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Auditoría</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>

<div class="contenedor">
    <a href="dashboard.php" class="btn">← Regresar</a>

    <h1>Auditoría del sistema</h1>

    <table>
        <tr>
            <th>Usuario</th>
            <th>Acción</th>
            <th>Tabla</th>
            <th>Descripción</th>
            <th>Fecha y hora</th>
            <th>IP</th>
            <th>Sistema operativo</th>
        </tr>

        <?php while($row = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['usuario']; ?></td>
            <td><?php echo $row['accion']; ?></td>
            <td><?php echo $row['tabla_afectada']; ?></td>
            <td><?php echo $row['descripcion']; ?></td>
            <td><?php echo $row['fecha_hora']; ?></td>
            <td><?php echo $row['ip_usuario']; ?></td>
            <td><?php echo $row['sistema_operativo']; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>