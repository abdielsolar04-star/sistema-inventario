<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Punto de Venta</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    min-height:100vh;
    background:#f1f5f9;
    display:flex;
    justify-content:center;
    align-items:center;
}

.login-box{
    background:white;
    width:380px;
    padding:35px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
    text-align:center;
}

.login-box h1{
    margin-bottom:10px;
    color:#111827;
}

.login-box p{
    margin-bottom:25px;
    color:#64748b;
}

.login-box input{
    width:100%;
    padding:14px;
    margin-bottom:15px;
    border:1px solid #d1d5db;
    border-radius:10px;
    font-size:16px;
}

.login-box button{
    width:100%;
    padding:14px;
    background:#2563eb;
    color:white;
    border:none;
    border-radius:10px;
    font-size:17px;
    cursor:pointer;
}

.login-box button:hover{
    background:#1d4ed8;
}

.error{
    background:#fee2e2;
    color:#991b1b;
    padding:10px;
    border-radius:8px;
    margin-bottom:15px;
}
</style>
</head>

<body>

<div class="login-box">

    <h1>Punto de Venta</h1>
    <p>Sistema de Inventario y Punto de Venta</p>

    <?php if(isset($_GET['error'])){ ?>
        <div class="error">Usuario o contraseña incorrectos</div>
    <?php } ?>

    <form action="../controlador/loginController.php" method="POST">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>

        <button type="submit">Iniciar sesión</button>
    </form>

</div>

</body>
</html><<<<<<< HEAD
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
=======
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
>>>>>>> 452094ddd0ffd458f6b759cd9dfb5fbf40ef7bb2
</html>