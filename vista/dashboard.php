<?php
include("../controlador/seguridad.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Punto de Venta</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#f1f5f9;
    min-height:100vh;
}

.header{
    background:#111827;
    color:white;
    padding:20px 35px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header h1{
    font-size:26px;
}

.header a{
    background:#dc2626;
    color:white;
    padding:10px 16px;
    border-radius:8px;
    text-decoration:none;
}

.contenedor{
    padding:35px;
}

.bienvenida{
    background:white;
    padding:25px;
    border-radius:18px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    margin-bottom:30px;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.card{
    background:white;
    padding:28px;
    border-radius:18px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    text-align:center;
}

.card h2{
    font-size:22px;
    color:#111827;
    margin-bottom:10px;
}

.card p{
    color:#64748b;
    margin-bottom:18px;
}

.card a{
    display:block;
    background:#2563eb;
    color:white;
    padding:12px;
    border-radius:10px;
    text-decoration:none;
    font-weight:bold;
}

.card a:hover{
    background:#1d4ed8;
}
</style>
</head>

<body>

<div class="header">
    <h1>Sistema de Inventario</h1>
    <a href="../controlador/logout.php">Cerrar sesión</a>
</div>

<div class="contenedor">

    <div class="bienvenida">
        <h2>Bienvenido, <?php echo $_SESSION['usuario']; ?></h2>
        <p>Panel principal del sistema de inventario y punto de venta.</p>
    </div>

    <div class="grid">

        <div class="card">
            <h2>Caja</h2>
            <p>Realizar ventas y generar tickets.</p>
            <a href="caja.php">Entrar</a>
        </div>

        <div class="card">
            <h2>Productos</h2>
            <p>Agregar, editar y consultar productos.</p>
            <a href="productos.php">Entrar</a>
        </div>

        <div class="card">
            <h2>Proveedores</h2>
            <p>Administrar proveedores registrados.</p>
            <a href="proveedores.php">Entrar</a>
        </div>

        <div class="card">
            <h2>Ventas</h2>
            <p>Consultar historial de ventas.</p>
            <a href="ventas.php">Entrar</a>
        </div>

        <div class="card">
            <h2>Corte del día</h2>
            <p>Ver resumen de ingresos diarios.</p>
            <a href="corte_dia.php">Entrar</a>
        </div>

        <div class="card">
            <h2>Ganancias</h2>
            <p>Consultar ganancias del negocio.</p>
            <a href="ganancias.php">Entrar</a>
        </div>

        <div class="card">
            <h2>Movimientos</h2>
            <p>Entradas y salidas de inventario.</p>
            <a href="movimientos.php">Entrar</a>
        </div>

        <div class="card">
            <h2>Usuarios</h2>
            <p>Administrar usuarios del sistema.</p>
            <a href="usuarios.php">Entrar</a>
        </div>

    </div>

</div>

<script>
window.history.pushState(null, "", window.location.href);

window.addEventListener("popstate", function () {
    window.history.pushState(null, "", window.location.href);
});
</script>

</body>
</html>