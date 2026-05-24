<?php
error_reporting(0);
ini_set('display_errors', 0);

include("../controlador/seguridad.php");
include("../controlador/permisos.php");
include("../modelo/conexion.php");

proteger("punto_venta");

$productos = $conexion->query("SELECT * FROM productos WHERE estado='activo' ORDER BY nombre_producto ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Caja</title>

<style>
body{
    font-family:Arial;
    background:#f1f5f9;
    padding:35px;
}
.card{
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}
h1{
    margin-bottom:25px;
}
table{
    width:100%;
    border-collapse:collapse;
}
th{
    background:#2563eb;
    color:white;
    padding:14px;
}
td{
    padding:12px;
}
select,input{
    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:8px;
}
.btn{
    background:#2563eb;
    color:white;
    border:0;
    padding:14px 22px;
    border-radius:10px;
    cursor:pointer;
    margin-top:20px;
}
.btn:hover{
    background:#1d4ed8;
}
</style>
</head>

<body>

<div class="card">

<h1>Caja / Punto de Venta</h1>

<form action="../controlador/ventaController.php" method="POST">

<table>
<tr>
    <th>Escanear código</th>
    <th>Producto</th>
    <th>Cantidad</th>
</tr>

<tr>
<td>
    <input 
        type="text" 
        name="codigo" 
        id="codigo" 
        placeholder="Escanea el código"
        autocomplete="off"
        autofocus>
</td>

<td>
    <select name="id_producto" id="id_producto">
        <option value="">Selecciona producto</option>

        <?php while($p = $productos->fetch_assoc()) { ?>
            <option value="<?php echo $p['id_producto']; ?>">
                <?php echo $p['codigo']." - ".$p['nombre_producto']." - $".$p['precio_venta']." - Stock: ".$p['stock']; ?>
            </option>
        <?php } ?>

    </select>
</td>

<td>
    <input type="number" name="cantidad" value="1" min="1">
</td>
</tr>
</table>

<button class="btn" type="submit">
    Realizar venta e imprimir ticket
</button>

<a href="dashboard.php" class="btn" style="text-decoration:none;">
    Volver
</a>

</form>

</div>

<script>
const codigo = document.getElementById("codigo");
codigo.focus();

document.addEventListener("click", () => {
    codigo.focus();
});

codigo.addEventListener("keypress", function(e){
    if(e.key === "Enter"){
        e.preventDefault();
        document.querySelector("form").submit();
    }
});
</script>

</body>
</html>