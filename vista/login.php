<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Papelería</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body class="login-body">

<div class="login-card">

    <h1>punto_ventas</h1>
    <p>Sistema de Inventario y Punto de Venta</p>

    <form action="../controlador/loginController.php" method="POST">

        <input type="text" name="usuario" placeholder="Usuario" required>

        <input type="password" name="password" placeholder="Contraseña" required>

        <button type="submit" style="width:100%; margin-top:15px;">
            Iniciar sesión
        </button>

    </form>

    <?php if(isset($_GET['error'])) { ?>
        <div class="error">
            Usuario o contraseña incorrectos
        </div>
    <?php } ?>

</div>

<script>
history.pushState(null, null, location.href);
window.onpopstate = function () {
    history.go(1);
};
</script>

</body>
</html>