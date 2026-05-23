<?php
include("../controlador/seguridad.php");
include("../modelo/conexion.php");
include("../controlador/permisos.php");

if ($_SESSION['rol'] != 'Administrador' && 
    !tienePermiso($conexion, $_SESSION['id_usuario'], "punto_venta")) {
    die("No tienes permiso para usar punto de venta");
}

$productos = $conexion->query("
    SELECT * FROM productos
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

<link rel="stylesheet" href="../assets/css/estilo.css">

<style>
body{
    background:#f1f5f9;
    padding:20px;
    font-family:Arial, sans-serif;
}

.contenedor-caja{
    max-width:1450px;
    margin:auto;
}

.top-caja{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    gap:15px;
    flex-wrap:wrap;
}

.top-caja h1{
    font-size:34px;
    color:#111827;
}

.grid-caja{
    display:grid;
    grid-template-columns:1fr 420px;
    gap:25px;
}

.card-caja{
    background:white;
    border-radius:22px;
    padding:25px;
    box-shadow:0 12px 28px rgba(0,0,0,0.10);
}

.busqueda-caja{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-bottom:20px;
}

.busqueda-caja input{
    flex:1;
    min-width:240px;
    padding:15px;
    border:1px solid #d1d5db;
    border-radius:12px;
    font-size:16px;
}

.btn-scan{
    background:#16a34a;
    color:white;
    border:none;
    padding:15px 20px;
    border-radius:12px;
    cursor:pointer;
    font-weight:bold;
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
    min-width:700px;
}

th{
    background:#2563eb;
    color:white;
    padding:15px;
}

td{
    padding:13px;
    border-bottom:1px solid #e5e7eb;
    text-align:center;
}

tr:hover{
    background:#f8fafc;
}

.btn-agregar{
    background:#16a34a;
    color:white;
    border:none;
    padding:10px 15px;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
}

.btn-agregar:hover{
    background:#15803d;
}

.lista-carrito{
    max-height:380px;
    overflow:auto;
    margin-top:15px;
}

.item-carrito{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
    padding:14px 0;
    border-bottom:1px solid #e5e7eb;
}

.item-carrito strong{
    color:#111827;
}

.cantidad-box{
    display:flex;
    align-items:center;
    gap:6px;
    margin-top:8px;
}

.cantidad-box button{
    background:#2563eb;
    color:white;
    border:none;
    width:28px;
    height:28px;
    border-radius:8px;
    cursor:pointer;
}

.btn-eliminar{
    background:#dc2626;
    color:white;
    border:none;
    padding:7px 11px;
    border-radius:8px;
    cursor:pointer;
}

.total-caja{
    font-size:42px;
    color:#16a34a;
    font-weight:bold;
    margin:22px 0;
    text-align:center;
}

.btn-vender,
.btn-limpiar{
    width:100%;
    border:none;
    padding:17px;
    font-size:19px;
    border-radius:14px;
    cursor:pointer;
    margin-top:10px;
    color:white;
    font-weight:bold;
}

.btn-vender{
    background:#2563eb;
}

.btn-vender:hover{
    background:#1d4ed8;
}

.btn-limpiar{
    background:#dc2626;
}

.btn-regresar{
    background:#111827;
    color:white;
    text-decoration:none;
    padding:13px 20px;
    border-radius:12px;
    font-weight:bold;
}

.stock-bajo{
    background:#dc2626;
    color:white;
    padding:6px 10px;
    border-radius:8px;
    font-weight:bold;
}

.stock-ok{
    background:#16a34a;
    color:white;
    padding:6px 10px;
    border-radius:8px;
    font-weight:bold;
}

@media(max-width:900px){
    .grid-caja{
        grid-template-columns:1fr;
    }

    body{
        padding:12px;
    }

    .top-caja h1{
        font-size:26px;
    }

    .total-caja{
        font-size:32px;
    }
}
</style>
</head>

<body>

<div class="contenedor-caja">

    <div class="top-caja">
        <h1>🛒 Punto de Venta</h1>

        <a href="dashboard.php" class="btn-regresar">
            ← Regresar
        </a>
    </div>

    <div class="grid-caja">

        <div class="card-caja">

            <h2>Buscar producto</h2>

            <div class="busqueda-caja">
                <input 
                    type="text" 
                    id="buscar" 
                    placeholder="Buscar producto o escanear código..."
                    onkeyup="buscarProductos()"
                    autofocus
                >

                <button type="button" class="btn-scan" onclick="abrirScanner()">
                    📷 Escanear
                </button>
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

                        <td>$<?php echo number_format($p['precio_venta'], 2); ?></td>

                        <td>
                            <?php if($p['stock'] <= 5) { ?>
                                <span class="stock-bajo"><?php echo $p['stock']; ?></span>
                            <?php } else { ?>
                                <span class="stock-ok"><?php echo $p['stock']; ?></span>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if($p['stock'] > 0) { ?>
                                <button
                                    class="btn-agregar"
                                    onclick='agregarProducto(
                                        <?php echo json_encode($p["id_producto"]); ?>,
                                        <?php echo json_encode($p["nombre_producto"]); ?>,
                                        <?php echo json_encode($p["precio_venta"]); ?>,
                                        <?php echo json_encode($p["stock"]); ?>
                                    )'>
                                    Agregar
                                </button>
                            <?php } else { ?>
                                Sin stock
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>

                </table>

            </div>

        </div>

        <div class="card-caja">

            <h2>Venta actual</h2>

            <div class="lista-carrito" id="carrito"></div>

            <div class="total-caja" id="total">
                $0.00
            </div>

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

function agregarProducto(id, nombre, precio, stock){
    precio = parseFloat(precio);
    stock = parseInt(stock);

    let existente = carrito.find(p => p.id == id);

    if(existente){
        if(existente.cantidad < existente.stock){
            existente.cantidad++;
            existente.subtotal = existente.cantidad * existente.precio;
        }else{
            alert("No hay más stock disponible");
        }
    }else{
        carrito.push({
            id:id,
            nombre:nombre,
            precio:precio,
            cantidad:1,
            stock:stock,
            subtotal:precio
        });
    }

    renderCarrito();
}

function renderCarrito(){
    const contenedor = document.getElementById("carrito");
    contenedor.innerHTML = "";
    total = 0;

    carrito.forEach((item,index)=>{
        total += item.subtotal;

        contenedor.innerHTML += `
            <div class="item-carrito">
                <div>
                    <strong>${item.nombre}</strong><br>
                    $${item.precio.toFixed(2)}

                    <div class="cantidad-box">
                        <button type="button" onclick="restarCantidad(${index})">-</button>
                        <span>${item.cantidad}</span>
                        <button type="button" onclick="sumarCantidad(${index})">+</button>
                    </div>
                </div>

                <div>
                    <strong>$${item.subtotal.toFixed(2)}</strong><br>
                    <button class="btn-eliminar" onclick="eliminarProducto(${index})">
                        X
                    </button>
                </div>
            </div>
        `;
    });

    document.getElementById("total").innerHTML = "$" + total.toFixed(2);
    document.getElementById("productosInput").value = JSON.stringify(carrito);
    document.getElementById("totalInput").value = total.toFixed(2);
}

function sumarCantidad(index){
    if(carrito[index].cantidad < carrito[index].stock){
        carrito[index].cantidad++;
        carrito[index].subtotal = carrito[index].cantidad * carrito[index].precio;
        renderCarrito();
    }else{
        alert("No hay suficiente stock");
    }
}

function restarCantidad(index){
    if(carrito[index].cantidad > 1){
        carrito[index].cantidad--;
        carrito[index].subtotal = carrito[index].cantidad * carrito[index].precio;
    }else{
        carrito.splice(index,1);
    }

    renderCarrito();
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

    for(let i = 1; i < filas.length; i++){
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