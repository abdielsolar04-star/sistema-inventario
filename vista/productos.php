<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");
include("../controlador/permisos.php");

if ($_SESSION['rol'] != 'Administrador' && 
    !tienePermiso($conexion, $_SESSION['id_usuario'], "ver_productos")) {
    die("No tienes permiso para ver productos");
}

$proveedores = $conexion->query("
    SELECT * FROM proveedores
    ORDER BY nombre_proveedor ASC
");

$productos = $conexion->query("
    SELECT productos.*, proveedores.nombre_proveedor
    FROM productos
    LEFT JOIN proveedores 
    ON productos.id_proveedor = proveedores.id_proveedor
    ORDER BY productos.id_producto DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Productos</title>

<style>

body{
    font-family:Arial;
    background:#f1f5f9;
    margin:0;
    padding:30px;
}

.contenedor{
    max-width:1400px;
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

input,select{
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
    background:white;
    border-radius:15px;
    overflow:hidden;
}

table th{
    background:#2563eb;
    color:white;
    padding:16px;
}

table td{
    padding:14px;
    border-bottom:1px solid #e5e7eb;
    text-align:center;
}

.btn-danger{
    background:#dc2626;
    color:white;
    padding:8px 14px;
    border-radius:8px;
    text-decoration:none;
}

.btn-stock{
    background:#16a34a;
    color:white;
    padding:8px 14px;
    border-radius:8px;
    border:none;
    cursor:pointer;
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

.stock-bajo{
    background:#dc2626;
    color:white;
    padding:6px 12px;
    border-radius:8px;
    font-weight:bold;
}

.stock-ok{
    background:#16a34a;
    color:white;
    padding:6px 12px;
    border-radius:8px;
    font-weight:bold;
}

</style>

</head>
<body>

<div class="contenedor">

<a href="dashboard.php" class="btn-regresar">
← Regresar
</a>

<div class="card">

<h1>Registrar producto</h1>

<form action="../controlador/productoController.php" method="POST">

<input type="hidden" name="accion" value="crear">

<input
type="text"
name="codigo"
id="codigo"
placeholder="Código o QR"
required
>

<button type="button" onclick="abrirScanner()">
Escanear código
</button>

<div id="reader" style="width:300px; display:none;"></div>

<input
type="text"
name="nombre_producto"
placeholder="Nombre del producto"
required
>

<input
type="text"
name="descripcion"
placeholder="Descripción"
>

<label>Proveedor</label>

<select name="id_proveedor" id="id_proveedor" required>

<option value="">Selecciona proveedor</option>

<?php while($prov = $proveedores->fetch_assoc()) { ?>

<option value="<?php echo $prov['id_proveedor']; ?>">

<?php echo $prov['nombre_proveedor']; ?>

</option>

<?php } ?>

<option value="nuevo">
+ Agregar nuevo proveedor
</option>

</select>

<input
type="text"
name="nuevo_proveedor"
id="nuevo_proveedor"
placeholder="Escribe el nuevo proveedor"
style="display:none;"
>

<input
type="number"
step="0.01"
name="precio_compra"
placeholder="Precio compra"
required
>

<input
type="number"
step="0.01"
name="precio_venta"
placeholder="Precio venta"
required
>

<input
type="number"
name="stock"
placeholder="Stock inicial"
required
>

<button type="submit">
Guardar producto
</button>

</form>

</div>

<div class="card">

<h1>Productos registrados</h1>

<input
type="text"
id="buscadorTabla"
placeholder="Buscar producto..."
onkeyup="buscarTabla()"
>

<table>

<tr>
<th>Código / QR</th>
<th>Producto</th>
<th>Descripción</th>
<th>Proveedor</th>
<th>Compra</th>
<th>Venta</th>
<th>Stock</th>
<th>Acciones</th>
</tr>

<?php while($p = $productos->fetch_assoc()) { ?>

<tr>

<td><?php echo $p['codigo']; ?></td>

<td><?php echo $p['nombre_producto']; ?></td>

<td><?php echo $p['descripcion']; ?></td>

<td><?php echo $p['nombre_proveedor']; ?></td>

<td>$<?php echo number_format($p['precio_compra'],2); ?></td>

<td>$<?php echo number_format($p['precio_venta'],2); ?></td>

<td>

<?php if($p['stock'] <= 5){ ?>

<span class="stock-bajo">
<?php echo $p['stock']; ?>
</span>

<?php } else { ?>

<span class="stock-ok">
<?php echo $p['stock']; ?>
</span>

<?php } ?>

</td>

<td>

<button
class="btn-stock"
onclick="abrirModal(
'<?php echo $p['id_producto']; ?>',
'<?php echo $p['nombre_producto']; ?>'
)"
>
+ Stock
</button>

<a
href="../controlador/productoController.php?accion=eliminar&id=<?php echo $p['id_producto']; ?>"
class="btn-danger"
onclick="return confirm('¿Eliminar producto?')"
>
Eliminar
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

<div id="modalStock" style="
display:none;
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.5);
justify-content:center;
align-items:center;
z-index:999;
">

<div style="
background:white;
padding:30px;
border-radius:20px;
width:400px;
">

<h2>Agregar stock</h2>

<form action="../controlador/agregarStock.php" method="POST">

<input type="hidden" name="id_producto" id="id_producto">

<input type="text" id="nombre_producto" readonly>

<input
type="number"
name="cantidad"
placeholder="Cantidad"
required
min="1"
>

<button type="submit">
Guardar
</button>

<button
type="button"
onclick="cerrarModal()"
class="btn-danger"
>
Cancelar
</button>

</form>

</div>

</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>

document.getElementById("id_proveedor").addEventListener("change", function(){

    let nuevo = document.getElementById("nuevo_proveedor");

    if(this.value == "nuevo"){

        nuevo.style.display = "block";
        nuevo.required = true;

    }else{

        nuevo.style.display = "none";
        nuevo.required = false;
    }

});

function buscarTabla(){

    let input = document.getElementById("buscadorTabla").value.toLowerCase();

    let filas = document.querySelectorAll("table tr");

    for(let i=1;i<filas.length;i++){

        let texto = filas[i].innerText.toLowerCase();

        if(texto.includes(input)){

            filas[i].style.display="";

        }else{

            filas[i].style.display="none";
        }
    }
}

function abrirModal(id,nombre){

    document.getElementById("modalStock").style.display="flex";

    document.getElementById("id_producto").value=id;

    document.getElementById("nombre_producto").value=nombre;
}

function cerrarModal(){

    document.getElementById("modalStock").style.display="none";
}

let scannerActivo = false;
let html5QrCode;

function abrirScanner(){

    const reader = document.getElementById("reader");

    reader.style.display = "block";

    if(scannerActivo){
        return;
    }

    html5QrCode = new Html5Qrcode("reader");

    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps:10,
            qrbox:250
        },
        function(decodedText){

            document.getElementById("codigo").value = decodedText;

            html5QrCode.stop().then(()=>{

                reader.style.display="none";
                scannerActivo=false;

            });

        },
        function(errorMessage){}
    ).then(()=>{

        scannerActivo=true;

    }).catch(err=>{

        alert("No se pudo abrir la cámara");

    });
}

</script>

</body>
=======
<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");
include("../controlador/permisos.php");

if ($_SESSION['rol'] != 'Administrador' && 
    !tienePermiso($conexion, $_SESSION['id_usuario'], "ver_productos")) {
    die("No tienes permiso para ver productos");
}

$proveedores = $conexion->query("
    SELECT * FROM proveedores
    ORDER BY nombre_proveedor ASC
");

$productos = $conexion->query("
    SELECT productos.*, proveedores.nombre_proveedor
    FROM productos
    LEFT JOIN proveedores 
    ON productos.id_proveedor = proveedores.id_proveedor
    ORDER BY productos.id_producto DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Productos</title>

<style>

body{
    font-family:Arial;
    background:#f1f5f9;
    margin:0;
    padding:30px;
}

.contenedor{
    max-width:1400px;
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

input,select{
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
    background:white;
    border-radius:15px;
    overflow:hidden;
}

table th{
    background:#2563eb;
    color:white;
    padding:16px;
}

table td{
    padding:14px;
    border-bottom:1px solid #e5e7eb;
    text-align:center;
}

.btn-danger{
    background:#dc2626;
    color:white;
    padding:8px 14px;
    border-radius:8px;
    text-decoration:none;
}

.btn-stock{
    background:#16a34a;
    color:white;
    padding:8px 14px;
    border-radius:8px;
    border:none;
    cursor:pointer;
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

.stock-bajo{
    background:#dc2626;
    color:white;
    padding:6px 12px;
    border-radius:8px;
    font-weight:bold;
}

.stock-ok{
    background:#16a34a;
    color:white;
    padding:6px 12px;
    border-radius:8px;
    font-weight:bold;
}

</style>

</head>
<body>

<div class="contenedor">

<a href="dashboard.php" class="btn-regresar">
← Regresar
</a>

<div class="card">

<h1>Registrar producto</h1>

<form action="../controlador/productoController.php" method="POST">

<input type="hidden" name="accion" value="crear">

<input
type="text"
name="codigo"
id="codigo"
placeholder="Código o QR"
required
>

<button type="button" onclick="abrirScanner()">
Escanear código
</button>

<div id="reader" style="width:300px; display:none;"></div>

<input
type="text"
name="nombre_producto"
placeholder="Nombre del producto"
required
>

<input
type="text"
name="descripcion"
placeholder="Descripción"
>

<label>Proveedor</label>

<select name="id_proveedor" id="id_proveedor" required>

<option value="">Selecciona proveedor</option>

<?php while($prov = $proveedores->fetch_assoc()) { ?>

<option value="<?php echo $prov['id_proveedor']; ?>">

<?php echo $prov['nombre_proveedor']; ?>

</option>

<?php } ?>

<option value="nuevo">
+ Agregar nuevo proveedor
</option>

</select>

<input
type="text"
name="nuevo_proveedor"
id="nuevo_proveedor"
placeholder="Escribe el nuevo proveedor"
style="display:none;"
>

<input
type="number"
step="0.01"
name="precio_compra"
placeholder="Precio compra"
required
>

<input
type="number"
step="0.01"
name="precio_venta"
placeholder="Precio venta"
required
>

<input
type="number"
name="stock"
placeholder="Stock inicial"
required
>

<button type="submit">
Guardar producto
</button>

</form>

</div>

<div class="card">

<h1>Productos registrados</h1>

<input
type="text"
id="buscadorTabla"
placeholder="Buscar producto..."
onkeyup="buscarTabla()"
>

<table>

<tr>
<th>Código / QR</th>
<th>Producto</th>
<th>Descripción</th>
<th>Proveedor</th>
<th>Compra</th>
<th>Venta</th>
<th>Stock</th>
<th>Acciones</th>
</tr>

<?php while($p = $productos->fetch_assoc()) { ?>

<tr>

<td><?php echo $p['codigo']; ?></td>

<td><?php echo $p['nombre_producto']; ?></td>

<td><?php echo $p['descripcion']; ?></td>

<td><?php echo $p['nombre_proveedor']; ?></td>

<td>$<?php echo number_format($p['precio_compra'],2); ?></td>

<td>$<?php echo number_format($p['precio_venta'],2); ?></td>

<td>

<?php if($p['stock'] <= 5){ ?>

<span class="stock-bajo">
<?php echo $p['stock']; ?>
</span>

<?php } else { ?>

<span class="stock-ok">
<?php echo $p['stock']; ?>
</span>

<?php } ?>

</td>

<td>

<button
class="btn-stock"
onclick="abrirModal(
'<?php echo $p['id_producto']; ?>',
'<?php echo $p['nombre_producto']; ?>'
)"
>
+ Stock
</button>

<a
href="../controlador/productoController.php?accion=eliminar&id=<?php echo $p['id_producto']; ?>"
class="btn-danger"
onclick="return confirm('¿Eliminar producto?')"
>
Eliminar
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

<div id="modalStock" style="
display:none;
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.5);
justify-content:center;
align-items:center;
z-index:999;
">

<div style="
background:white;
padding:30px;
border-radius:20px;
width:400px;
">

<h2>Agregar stock</h2>

<form action="../controlador/agregarStock.php" method="POST">

<input type="hidden" name="id_producto" id="id_producto">

<input type="text" id="nombre_producto" readonly>

<input
type="number"
name="cantidad"
placeholder="Cantidad"
required
min="1"
>

<button type="submit">
Guardar
</button>

<button
type="button"
onclick="cerrarModal()"
class="btn-danger"
>
Cancelar
</button>

</form>

</div>

</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>

document.getElementById("id_proveedor").addEventListener("change", function(){

    let nuevo = document.getElementById("nuevo_proveedor");

    if(this.value == "nuevo"){

        nuevo.style.display = "block";
        nuevo.required = true;

    }else{

        nuevo.style.display = "none";
        nuevo.required = false;
    }

});

function buscarTabla(){

    let input = document.getElementById("buscadorTabla").value.toLowerCase();

    let filas = document.querySelectorAll("table tr");

    for(let i=1;i<filas.length;i++){

        let texto = filas[i].innerText.toLowerCase();

        if(texto.includes(input)){

            filas[i].style.display="";

        }else{

            filas[i].style.display="none";
        }
    }
}

function abrirModal(id,nombre){

    document.getElementById("modalStock").style.display="flex";

    document.getElementById("id_producto").value=id;

    document.getElementById("nombre_producto").value=nombre;
}

function cerrarModal(){

    document.getElementById("modalStock").style.display="none";
}

let scannerActivo = false;
let html5QrCode;

function abrirScanner(){

    const reader = document.getElementById("reader");

    reader.style.display = "block";

    if(scannerActivo){
        return;
    }

    html5QrCode = new Html5Qrcode("reader");

    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps:10,
            qrbox:250
        },
        function(decodedText){

            document.getElementById("codigo").value = decodedText;

            html5QrCode.stop().then(()=>{

                reader.style.display="none";
                scannerActivo=false;

            });

        },
        function(errorMessage){}
    ).then(()=>{

        scannerActivo=true;

    }).catch(err=>{

        alert("No se pudo abrir la cámara");

    });
}

</script>

</body>
>>>>>>> 452094ddd0ffd458f6b759cd9dfb5fbf40ef7bb2
</html>