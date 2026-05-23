<?php
include("../controlador/seguridad.php");
include("../controlador/permisos.php");
include("../modelo/conexion.php");

if (!esAdmin()) {
    die("No tienes permiso");
}

$usuarios = $conexion->query("SELECT * FROM usuarios ORDER BY id_usuario DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Usuarios</title>

<style>
body{font-family:Arial;background:#f1f5f9;padding:30px}
.card{background:white;padding:25px;border-radius:20px;box-shadow:0 5px 15px rgba(0,0,0,.1);margin-bottom:20px}
input,select{padding:10px;width:100%;border:1px solid #ccc;border-radius:8px;margin-bottom:10px}
button,.btn{background:#2563eb;color:white;padding:10px 18px;border:none;border-radius:8px;text-decoration:none}
table{width:100%;border-collapse:collapse}
th{background:#2563eb;color:white;padding:12px}
td{padding:12px;border-bottom:1px solid #ddd;text-align:center}
</style>
</head>

<body>

<div class="card">
<h1>Crear Usuario</h1>

<form action="../controlador/usuarioController.php" method="POST">

<input type="hidden" name="accion" value="crear">

<input type="text" name="nombre" placeholder="Nombre" required>
<input type="text" name="usuario" placeholder="Usuario" required>
<input type="password" name="password" placeholder="Contraseña" required>

<select name="id_rol" required>
    <option value="1">Administrador</option>
    <option value="2">Empleado</option>
</select>

<button type="submit">Crear usuario</button>

</form>
</div>

<div class="card">
<h2>Usuarios</h2>

<table>
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Usuario</th>
    <th>Rol</th>
    <th>Permisos</th>
</tr>

<?php while($u = $usuarios->fetch_assoc()){ ?>
<tr>
    <td><?php echo $u['id_usuario']; ?></td>
    <td><?php echo $u['nombre']; ?></td>
    <td><?php echo $u['usuario']; ?></td>
    <td><?php echo $u['id_rol'] == 1 ? 'Admin' : 'Empleado'; ?></td>
    <td>
        <a href="permisos_usuario.php?id_usuario=<?php echo $u['id_usuario']; ?>">
            Otorgar permisos
        </a>
    </td>
</tr>
<?php } ?>

</table>

<br>
<a href="dashboard.php" class="btn">Volver</a>

</div>

</body>
</html>