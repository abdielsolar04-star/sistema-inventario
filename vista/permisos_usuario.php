<?php
include("../controlador/seguridad.php");
include("../controlador/permisos.php");
include("../modelo/conexion.php");

if (!esAdmin()) {
    die("No tienes permiso");
}

$id_usuario = $_GET['id_usuario'];

$usuario = $conexion->query("SELECT * FROM usuarios WHERE id_usuario=$id_usuario")->fetch_assoc();

$permisos = [
    "punto_venta" => "Caja / Punto de venta",
    "corte_dia" => "Corte del día",
    "ver_ganancias" => "Ganancias",
    "ver_productos" => "Ver productos",
    "agregar_producto" => "Agregar productos",
    "eliminar_producto" => "Eliminar productos"
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Permisos</title>

<style>
body{font-family:Arial;background:#f1f5f9;padding:30px}
.card{background:white;padding:25px;border-radius:20px;box-shadow:0 5px 15px rgba(0,0,0,.1)}
label{display:block;margin:12px 0}
button,.btn{background:#2563eb;color:white;padding:10px 18px;border:none;border-radius:8px;text-decoration:none}
</style>
</head>

<body>

<div class="card">

<h1>Permisos de <?php echo $usuario['usuario']; ?></h1>

<form action="../controlador/permisosController.php" method="POST">

<input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">

<?php foreach($permisos as $clave => $texto){ 

$check = $conexion->query("SELECT * FROM usuario_permiso WHERE id_usuario=$id_usuario AND permiso='$clave'");

?>

<label>
    <input type="checkbox" name="permisos[]" value="<?php echo $clave; ?>" <?php echo $check->num_rows > 0 ? 'checked' : ''; ?>>
    <?php echo $texto; ?>
</label>

<?php } ?>

<button type="submit">Guardar permisos</button>
<a href="usuarios.php" class="btn">Volver</a>

</form>

</div>

</body>
</html>