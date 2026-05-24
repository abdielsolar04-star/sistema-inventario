<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");
include("../controlador/permisos.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Papelería</title>

    <link rel="stylesheet" href="../assets/css/estilo.css">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, Helvetica, sans-serif;
        }

        body{
            background:#f1f5f9;
        }

        .inicio{
            min-height:100vh;
            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
            padding:30px;
        }

        .contenedor-logo{
            background:white;
            width:100%;
            max-width:850px;
            border-radius:25px;
            padding:40px;
            text-align:center;
            box-shadow:0 10px 30px rgba(0,0,0,0.08);
            margin-bottom:30px;
        }

        .logo-central{
            width:250px;
            height:250px;
            object-fit:contain;
            display:block;
            margin:0 auto 20px auto;
        }

        .titulo{
            font-size:48px;
            color:#111827;
            margin-bottom:10px;
        }

        .subtitulo{
            color:#64748b;
            font-size:22px;
            margin-bottom:20px;
        }

        .usuario{
            display:inline-block;
            background:#2563eb;
            color:white;
            padding:12px 25px;
            border-radius:30px;
            font-size:17px;
            font-weight:bold;
        }

        .botones{
            width:100%;
            max-width:1100px;
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
            gap:20px;
        }

        .btn-modulo{
            background:white;
            color:#111827;
            text-decoration:none;
            padding:28px;
            border-radius:18px;
            text-align:center;
            font-size:21px;
            font-weight:bold;
            transition:0.3s;
            box-shadow:0 10px 25px rgba(0,0,0,0.08);
        }

        .btn-modulo:hover{
            background:#2563eb;
            color:white;
            transform:translateY(-5px);
        }

        .salir{
            background:#dc2626;
            color:white;
        }

        @media(max-width:768px){

            .logo-central{
                width:180px;
                height:180px;
            }

            .titulo{
                font-size:34px;
            }

            .subtitulo{
                font-size:18px;
            }

            .btn-modulo{
                font-size:18px;
                padding:20px;
            }

        }

    </style>

</head>
<body>

<div class="inicio">

    <div class="contenedor-logo">

        <img 
            src="../assets/img/logo1.jpeg"
            class="logo-central"
        >

        <h1 class="titulo">PUNTO_VENTAS</h1>

        <p class="subtitulo">
            Inventario & Punto de Venta
        </p>

        <span class="usuario">
            <?php echo $_SESSION['rol']; ?>:
            <?php echo $_SESSION['nombre']; ?>
        </span>

    </div>

    <div class="botones">

        <?php if ($_SESSION['rol'] == 'Administrador') { ?>

            <a href="caja.php" class="btn-modulo">
                🛒 Punto de venta
            </a>

            <a href="productos.php" class="btn-modulo">
                📦 Productos
            </a>

            <a href="usuarios.php" class="btn-modulo">
                👤 Usuarios
            </a>

            <a href="ventas_admin.php" class="btn-modulo">
                💰 Ventas
            </a>

            <a href="relacion_ventas.php" class="btn-modulo">
                📑 Relación ventas
            </a>

            <a href="corte_dia.php" class="btn-modulo">
                📅 Corte del día
            </a>

            <a href="ganancias.php" class="btn-modulo">
                📈 Ganancias
            </a>

            <a href="auditoria.php" class="btn-modulo">
                🛡 Auditoría
            </a>

        <?php } ?>

        <?php if ($_SESSION['rol'] == 'Empleado') { ?>

            <?php if (tienePermiso($conexion, $_SESSION['id_usuario'], "punto_venta")) { ?>
                <a href="caja.php" class="btn-modulo">
                    🛒 Punto de venta
                </a>
            <?php } ?>

            <?php if (tienePermiso($conexion, $_SESSION['id_usuario'], "ver_productos")) { ?>
                <a href="productos.php" class="btn-modulo">
                    📦 Productos
                </a>
            <?php } ?>

            <?php if (tienePermiso($conexion, $_SESSION['id_usuario'], "corte_dia")) { ?>
                <a href="corte_dia.php" class="btn-modulo">
                    📅 Corte del día
                </a>
            <?php } ?>

            <?php if (tienePermiso($conexion, $_SESSION['id_usuario'], "ver_ganancias")) { ?>
                <a href="ganancias.php" class="btn-modulo">
                    📈 Ganancias
                </a>
            <?php } ?>

        <?php } ?>

        <a 
            href="../controlador/logout.php"
            class="btn-modulo salir"
        >
            🚪 Cerrar sesión
        </a>

    </div>

</div>

<script>

history.pushState(null, null, location.href);

window.onpopstate = function () {
    history.go(1);
};

</script>

</body>
</html>