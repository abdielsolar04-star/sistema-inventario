<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");

$productos = $conexion->query("
    SELECT * FROM productos
    WHERE estado = 'activo'
    ORDER BY nombre_producto ASC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Punto de Venta</title>

<script src="https://unpkg.com/html5-qrcode"></script>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#f1f5f9;
    padding:20px;
}

.contenedor{
    max-width:1400px;
    margin:auto;
}

.top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    gap:15px;
    flex-wrap:wrap;
}

.btn{
    background:#2563eb;
    color:white;
    text-decoration:none;
    padding:12px 20px;
    border-radius:10px;
}

.grid{
    display:grid;
    grid-template-columns:1fr 380px;
    gap:25px;
}

.card{
    background:white;
    border-radius:20px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.busqueda{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-bottom:15px;
}

.busqueda input{
    flex:1;
    min-width:200px;
    padding:14px;
    border:1px solid #d1d5db;
    border-radius:10px;
}

.busqueda button{
    background:#16a34a;
    color:white;
    border:none;
    padding:14px;
    border-radius:10px;
    cursor:pointer;
}

#reader{
    width:100%;
    max-width:350px;
    display:none;
    margin:15px auto;
}

.tabla-responsive{
    width:100%;
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
    min-width:650px;
}

th{
    background:#2563eb;
    color:white;
    padding:14px;
}

td{
    padding:12px;
    border-bottom:1px solid #e5e7eb;
    text-align:center;
}

.btn-agregar{
    background:#16a34a;
    color:white;
    border:none;
    padding:10px 14px;
    border-radius:8px;
    cursor:pointer;
}

.lista-carrito{
    max-height:350px;
    overflow:auto;
    margin-top:15px;
}

.item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
    padding:12px 0;
    border-bottom:1px solid #e5e7eb;
}

.btn-eliminar{
    background:#dc2626;
    color:white;
    border:none;
    padding:6px 10px;
    border-radius:8px;
    cursor:pointer;
}

.total{
    font-size:38px;
    color:#16a34a;
    font-weight:bold;
    margin:20px 0;
}

.btn-vender,
.btn-limpiar{
    width:100%;
    border:none;
    padding:16px;
    font-size:18px;
    border-radius:12px;
    cursor:pointer;
    margin-top:10px;
    color:white;
}

.btn-vender{
    background:#2563eb;
}

.btn-limpiar{
    background:#dc2626;
}

@media(max-width:900px){
    .grid{
        grid-template-columns:1fr;
    }

    body{
        padding:12px;
    }

    .total{
        font-size:30px;
    }
}
</style>
</head>

<body>

<div class="contenedor">

    <div class="top">
        <h1>🛒 Punto de Venta</h1>
        <a href="dashboard.php" class="btn">← Regresar</a>
    </div>

    <div class="grid">

        <div class="card">

            <div class="busqueda">
                <input type="text" id="buscar" placeholder="Buscar producto o código..." onkeyup="buscarProductos()">
                <button type="button" onclick="abrirScanner()">📷 Escanear</button>
            </div>

            <div id="reader"></div>

            <div class="tabla-responsive">
                <table id="tablaProductos">
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acción</th>
                    </tr>

                    <?php while($p = $productos->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['codigo']); ?></td>
                        <td><?php echo htmlspecialchars($p['nombre_producto']); ?></td>
                        <td>$<?php echo number_format($p['precio_venta'],2); ?></td>
                        <td><?php echo $p['stock']; ?></td>
                        <td>
                            <button
                                class="btn-agregar"
                                onclick='agregarProducto(
                                    <?php echo json_encode($p["id_producto"]); ?>,
                                    <?php echo json_encode($p["nombre_producto"]); ?>,
                                    <?php echo json_encode($p["precio_venta"]); ?>
                                )'>
                                Agregar
                            </button>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>

        </div>

        <div class="card">

            <h2>Venta actual</h2>

            <div class="lista-carrito" id="carrito"></div>

            <div class="total" id="total">$0.00</div>

            <form action="../controlador/ventaController.php" method="POST" id="formVenta">
                <input type="hidden" name="productos" id="productosInput">
                <input type="hidden" name="total" id="totalInput">

                <button type="submit" class="btn-vender">
                    Finalizar venta
                </button>
            </form>

            <button type="button" class="btn-limpiar" onclick="limpiarVenta()">
                Limpiar venta
            </button>

        </div>

    </div>

</div>

<script>
let carrito = [];
let total = 0;
let scannerActivo = false;
let html5QrCode = null;

function agregarProducto(id, nombre, precio){
    precio = parseFloat(precio);

    carrito.push({
        id:id,
        nombre:nombre,
        precio:precio
    });

    renderCarrito();
}

function renderCarrito(){
    const contenedor = document.getElementById("carrito");
    contenedor.innerHTML = "";
    total = 0;

    carrito.forEach((item,index)=>{
        total += item.precio;

        contenedor.innerHTML += `
            <div class="item">
                <div>
                    <strong>${item.nombre}</strong><br>
                    $${item.precio.toFixed(2)}
                </div>

                <button class="btn-eliminar" onclick="eliminarProducto(${index})">
                    X
                </button>
            </div>
        `;
    });

    document.getElementById("total").innerHTML = "$" + total.toFixed(2);
    document.getElementById("productosInput").value = JSON.stringify(carrito);
    document.getElementById("totalInput").value = total.toFixed(2);
}

function eliminarProducto(index){
    carrito.splice(index,1);
    renderCarrito();
}

function limpiarVenta(){
    carrito = [];
    total = 0;
    renderCarrito();
}

function buscarProductos(){
    const input = document.getElementById("buscar").value.toLowerCase();
    const filas = document.querySelectorAll("#tablaProductos tr");

    for(let i=1; i<filas.length; i++){
        let texto = filas[i].innerText.toLowerCase();

        if(texto.includes(input)){
            filas[i].style.display = "";
        }else{
            filas[i].style.display = "none";
        }
    }
}

function abrirScanner(){
    const reader = document.getElementById("reader");
    reader.style.display = "block";

    if(scannerActivo){
        return;
    }

    html5QrCode = new Html5Qrcode("reader");

    html5QrCode.start(
        { facingMode: "environment" },
        { fps:10, qrbox:250 },
        function(decodedText){
            document.getElementById("buscar").value = decodedText;
            buscarProductos();

            html5QrCode.stop().then(()=>{
                reader.style.display = "none";
                scannerActivo = false;
            });
        },
        function(errorMessage){}
    ).then(()=>{
        scannerActivo = true;
    }).catch(err=>{
        alert("No se pudo abrir la cámara. Revisa permisos.");
    });
}

document.getElementById("formVenta").addEventListener("submit", function(e){
    if(carrito.length <= 0){
        e.preventDefault();
        alert("Agrega productos antes de finalizar la venta");
    }
});
</script>

</body>
</html>