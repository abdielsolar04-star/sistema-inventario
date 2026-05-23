<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");

$proveedores = $conexion->query("
SELECT * FROM proveedores
ORDER BY id_proveedor DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Proveedores</title>

<style>

body{
    font-family:Arial;
    background:#f1f5f9;
    padding:30px;
}

.contenedor{
    max-width:1200px;
    margin:auto;
}

.card{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    margin-bottom:25px;
}

h1{
    margin-bottom:20px;
}

input{
    width:100%;
    padding:14px;
    margin-bottom:15px;
    border:1px solid #d1d5db;
    border-radius:10px;
}

button{
    background:#2563eb;
    color:white;
    border:none;
    padding:14px 25px;
    border-radius:10px;
    cursor:pointer;
    font-size:16px;
}

button:hover{
    background:#1d4ed8;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#2563eb;
    color:white;
    padding:15px;
}

table td{
    padding:14px;
    border-bottom:1px solid #e5e7eb;
}

.btn-danger{
    background:#dc2626;
    color:white;
    padding:8px 14px;
    border-radius:8px;
    text-decoration:none;
}

.btn-regresar{
    display:inline-block;
    margin-bottom:20px;
    background:#111827;
    color:white;
    padding:12px 20px;
    border-radius:10px;
    text-decoration:none;
}

</style>

</head>
<body>

<div class="contenedor">

<a href="dashboard.php" class="btn-regresar">
← Regresar
</a>

<div class="card">

<h1>Registrar proveedor</h1>

<form action="../controlador/proveedorController.php" method="POST">

<input type="hidden" name="accion" value="guardar">

<input
type="text"
name="nombre_proveedor"
placeholder="Nombre del proveedor"
required
>

<input
type="text"
name="telefono"
placeholder="Teléfono"
>

<input
type="text"
name="direccion"
placeholder="Dirección"
>

<button type="submit">
Guardar proveedor
</button>

</form>

</div>

<div class="card">

<h1>Lista de proveedores</h1>

<table>

<tr>
<th>ID</th>
<th>Proveedor</th>
<th>Teléfono</th>
<th>Dirección</th>
<th>Acción</th>
</tr>

<?php while($p = $proveedores->fetch_assoc()) { ?>

<tr>

<td><?php echo $p['id_proveedor']; ?></td>

<td><?php echo $p['nombre_proveedor']; ?></td>

<td><?php echo $p['telefono']; ?></td>

<td><?php echo $p['direccion']; ?></td>

<td>

<a
href="../controlador/proveedorController.php?eliminar=<?php echo $p['id_proveedor']; ?>"
class="btn-danger"
onclick="return confirm('¿Eliminar proveedor?')"
>
Eliminar
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>