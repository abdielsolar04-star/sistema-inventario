<div class="sidebar">

    <div class="logo-box-sidebar">
        <img src="../assets/img/logo.jpeg" class="logo-sidebar">
        <h2>Papelería</h2>
        <p>Inventario & Punto de Venta</p>
    </div>

    <a href="dashboard.php">🏠 Inicio</a>

    <?php if ($_SESSION['rol'] == 'Administrador') { ?>

        <a href="caja.php">🛒 Punto de venta</a>
        <a href="productos.php">📦 Productos</a>
        <a href="proveedores.php">🚚 Proveedores</a>
        <a href="usuarios.php">👤 Usuarios</a>
        <a href="ventas_admin.php">💰 Ventas</a>
        <a href="relacion_ventas.php">📑 Relación ventas</a>
        <a href="corte_dia.php">📅 Corte del día</a>
        <a href="ganancias.php">📈 Ganancias</a>
        <a href="auditoria.php">🛡 Auditoría</a>

    <?php } ?>

    <?php if ($_SESSION['rol'] == 'Empleado') { ?>

        <?php if (tienePermiso($conexion, $_SESSION['id_usuario'], "punto_venta")) { ?>
            <a href="caja.php">🛒 Punto de venta</a>
        <?php } ?>

        <?php if (tienePermiso($conexion, $_SESSION['id_usuario'], "ver_productos")) { ?>
            <a href="productos.php">📦 Productos</a>
        <?php } ?>

        <?php if (tienePermiso($conexion, $_SESSION['id_usuario'], "corte_dia")) { ?>
            <a href="corte_dia.php">📅 Corte del día</a>
        <?php } ?>

        <?php if (tienePermiso($conexion, $_SESSION['id_usuario'], "ver_ganancias")) { ?>
            <a href="ganancias.php">📈 Ganancias</a>
        <?php } ?>

    <?php } ?>

    <a href="../controlador/logout.php" class="logout">🚪 Cerrar sesión</a>

</div>

<div class="contenido">