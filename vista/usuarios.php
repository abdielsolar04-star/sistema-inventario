<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");

if ($_SESSION['rol'] != 'Administrador') {
    die("No tienes permiso");
}

$usuarios = $conexion->query("
    SELECT usuarios.*, roles.nombre_rol
    FROM usuarios
    INNER JOIN roles ON usuarios.id_rol = roles.id_rol
    ORDER BY usuarios.id_usuario DESC
");

$permisosDisponibles = [
    "agregar_producto" => "Agregar productos",
    "eliminar_producto" => "Eliminar productos",
    "ver_productos" => "Ver productos",
    "punto_venta" => "Usar punto de venta",
    "corte_dia" => "Hacer corte del día",
    "ver_ganancias" => "Ver ganancias"
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios y permisos</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>

<div class="contenedor">

    <a href="dashboard.php" class="btn">← Regresar</a>

    <h1>Crear usuario</h1>

    <form action="../controlador/usuarioController.php" method="POST">

        <input type="hidden" name="accion" value="crear">

        <input type="text" name="nombre" placeholder="Nombre completo" required>

        <input type="text" name="usuario" placeholder="Usuario" required>

        <input type="password" name="password" placeholder="Contraseña" required>

        <select name="id_rol" required>
            <option value="2">Empleado</option>
            <option value="1">Administrador</option>
        </select>

        <button type="submit">Crear usuario</button>

    </form>

    <h2>Usuarios registrados</h2>

    <?php while($u = $usuarios->fetch_assoc()) { ?>

        <div style="background:#f8f9fa; padding:20px; margin:20px 0; border-radius:10px;">

            <h3>
                <?php echo $u['nombre']; ?> 
                - <?php echo $u['nombre_rol']; ?>
            </h3>

            <p>
                Usuario: <?php echo $u['usuario']; ?>
            </p>

            <?php if ($u['nombre_rol'] == 'Empleado') { ?>

                <?php
                $id_usuario_permiso = $u['id_usuario'];

                $consultaPermisos = $conexion->prepare("
                    SELECT permiso 
                    FROM usuario_permiso 
                    WHERE id_usuario = ?
                ");

                $consultaPermisos->bind_param("i", $id_usuario_permiso);
                $consultaPermisos->execute();

                $resultadoPermisos = $consultaPermisos->get_result();

                $permisosUsuario = [];

                while($p = $resultadoPermisos->fetch_assoc()) {
                    $permisosUsuario[] = $p['permiso'];
                }
                ?>

                <h4>Permisos del empleado</h4>

                <form action="../controlador/usuarioController.php" method="POST">

                    <input type="hidden" name="accion" value="permisos">
                    <input type="hidden" name="id_usuario" value="<?php echo $u['id_usuario']; ?>">

                    <?php foreach($permisosDisponibles as $clave => $texto) { ?>

                        <label style="display:block; margin:8px 0;">
                            <input 
                                type="checkbox" 
                                name="permisos[]" 
                                value="<?php echo $clave; ?>"
                                <?php echo in_array($clave, $permisosUsuario) ? 'checked' : ''; ?>
                            >
                            <?php echo $texto; ?>
                        </label>

                    <?php } ?>

                    <button type="submit">
                        Guardar permisos
                    </button>

                </form>

            <?php } ?>

        </div>

    <?php } ?>

</div>

</body>
=======
<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");

if ($_SESSION['rol'] != 'Administrador') {
    die("No tienes permiso");
}

$usuarios = $conexion->query("
    SELECT usuarios.*, roles.nombre_rol
    FROM usuarios
    INNER JOIN roles ON usuarios.id_rol = roles.id_rol
    ORDER BY usuarios.id_usuario DESC
");

$permisosDisponibles = [
    "agregar_producto" => "Agregar productos",
    "eliminar_producto" => "Eliminar productos",
    "ver_productos" => "Ver productos",
    "punto_venta" => "Usar punto de venta",
    "corte_dia" => "Hacer corte del día",
    "ver_ganancias" => "Ver ganancias"
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios y permisos</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>

<div class="contenedor">

    <a href="dashboard.php" class="btn">← Regresar</a>

    <h1>Crear usuario</h1>

    <form action="../controlador/usuarioController.php" method="POST">

        <input type="hidden" name="accion" value="crear">

        <input type="text" name="nombre" placeholder="Nombre completo" required>

        <input type="text" name="usuario" placeholder="Usuario" required>

        <input type="password" name="password" placeholder="Contraseña" required>

        <select name="id_rol" required>
            <option value="2">Empleado</option>
            <option value="1">Administrador</option>
        </select>

        <button type="submit">Crear usuario</button>

    </form>

    <h2>Usuarios registrados</h2>

    <?php while($u = $usuarios->fetch_assoc()) { ?>

        <div style="background:#f8f9fa; padding:20px; margin:20px 0; border-radius:10px;">

            <h3>
                <?php echo $u['nombre']; ?> 
                - <?php echo $u['nombre_rol']; ?>
            </h3>

            <p>
                Usuario: <?php echo $u['usuario']; ?>
            </p>

            <?php if ($u['nombre_rol'] == 'Empleado') { ?>

                <?php
                $id_usuario_permiso = $u['id_usuario'];

                $consultaPermisos = $conexion->prepare("
                    SELECT permiso 
                    FROM usuario_permiso 
                    WHERE id_usuario = ?
                ");

                $consultaPermisos->bind_param("i", $id_usuario_permiso);
                $consultaPermisos->execute();

                $resultadoPermisos = $consultaPermisos->get_result();

                $permisosUsuario = [];

                while($p = $resultadoPermisos->fetch_assoc()) {
                    $permisosUsuario[] = $p['permiso'];
                }
                ?>

                <h4>Permisos del empleado</h4>

                <form action="../controlador/usuarioController.php" method="POST">

                    <input type="hidden" name="accion" value="permisos">
                    <input type="hidden" name="id_usuario" value="<?php echo $u['id_usuario']; ?>">

                    <?php foreach($permisosDisponibles as $clave => $texto) { ?>

                        <label style="display:block; margin:8px 0;">
                            <input 
                                type="checkbox" 
                                name="permisos[]" 
                                value="<?php echo $clave; ?>"
                                <?php echo in_array($clave, $permisosUsuario) ? 'checked' : ''; ?>
                            >
                            <?php echo $texto; ?>
                        </label>

                    <?php } ?>

                    <button type="submit">
                        Guardar permisos
                    </button>

                </form>

            <?php } ?>

        </div>

    <?php } ?>

</div>

</body>
>>>>>>> 452094ddd0ffd458f6b759cd9dfb5fbf40ef7bb2
</html>