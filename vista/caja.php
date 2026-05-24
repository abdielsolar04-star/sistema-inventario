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
*{box-sizing:border-box;font-family:Arial}
body{background:#f1f5f9;padding:30px}
.card{background:white;padding:30px;border-radius:22px;box-shadow:0 10px 25px #0002}
h1{margin-bottom:20px}
.grid{display:grid;grid-template-columns:1fr 1fr 120px 120px;gap:12px;margin-bottom:15px}
input,select{padding:13px;border:1px solid #ddd;border-radius:10px;width:100%}
button,.btn{background:#2563eb;color:white;border:0;padding:13px 18px;border-radius:10px;text-decoration:none;cursor:pointer}
.btn-red{background:#dc2626}
.btn-green{background:#16a34a}
table{width:100%;border-collapse:collapse;margin-top:20px}
th{background:#2563eb;color:white;padding:12px}
td{padding:12px;border-bottom:1px solid #ddd;text-align:center}
.total{font-size:28px;font-weight:bold;text-align:right;margin-top:20px}
</style>
</head>

<body>

<div class="card">
<h1>Caja / Punto de Venta</h1>

<form action="../controlador/ventaController.php" method="POST" id="formVenta">

<div class="grid">
    <input type="text" id="codigo" placeholder="Escanear código" autocomplete="off" autofocus>

    <select id="producto">
        <option value="">Selecciona producto</option>
        <?php while($p = $productos->fetch_assoc()) { ?>
            <option 
                value="<?php echo $p['id_producto']; ?>"
                data-codigo="<?php echo $p['codigo']; ?>"
                data-nombre="<?php echo $p['nombre_producto']; ?>"
                data-precio="<?php echo $p['precio_venta']; ?>">
                <?php echo $p['codigo']." - ".$p['nombre_producto']." - $".$p['precio_venta']; ?>
            </option>
        <?php } ?>
    </select>

    <input type="number" id="cantidad" value="1" min="1">

    <button type="button" onclick="agregarProducto()">Agregar</button>
</div>

<table id="tablaVenta">
<thead>
<tr>
    <th>Código</th>
    <th>Producto</th>
    <th>Cantidad</th>
    <th>Precio</th>
    <th>Subtotal</th>
    <th>Quitar</th>
</tr>
</thead>
<tbody></tbody>
</table>

<div class="total">
Total: $<span id="totalTexto">0.00</span>
</div>

<input type="hidden" name="productos_json" id="productos_json">

<br>

<button type="submit" class="btn-green">Finalizar venta</button>
<button type="button" class="btn-red" onclick="limpiarVenta()">Limpiar venta</button>
<a href="dashboard.php" class="btn">Volver</a>

</form>
</div>

<script>
let carrito = [];

const codigoInput = document.getElementById("codigo");
const productoSelect = document.getElementById("producto");
const cantidadInput = document.getElementById("cantidad");

codigoInput.focus();

document.addEventListener("click", () => codigoInput.focus());

codigoInput.addEventListener("keypress", function(e){
    if(e.key === "Enter"){
        e.preventDefault();

        let codigo = codigoInput.value.trim();
        if(codigo === "") return;

        let encontrado = false;

        for(let option of productoSelect.options){
            if(option.dataset.codigo === codigo){
                productoSelect.value = option.value;
                encontrado = true;
                agregarProducto();
                break;
            }
        }

        if(!encontrado){
            alert("Producto no encontrado");
        }

        codigoInput.value = "";
        codigoInput.focus();
    }
});

function agregarProducto(){
    let option = productoSelect.options[productoSelect.selectedIndex];

    if(!option || productoSelect.value === ""){
        alert("Selecciona o escanea un producto");
        return;
    }

    let id = productoSelect.value;
    let codigo = option.dataset.codigo;
    let nombre = option.dataset.nombre;
    let precio = parseFloat(option.dataset.precio);
    let cantidad = parseInt(cantidadInput.value);

    if(cantidad <= 0) cantidad = 1;

    let existente = carrito.find(p => p.id_producto == id);

    if(existente){
        existente.cantidad += cantidad;
        existente.subtotal = existente.cantidad * existente.precio;
    }else{
        carrito.push({
            id_producto:id,
            codigo:codigo,
            nombre:nombre,
            precio:precio,
            cantidad:cantidad,
            subtotal:precio*cantidad
        });
    }

    productoSelect.value = "";
    cantidadInput.value = 1;
    actualizarTabla();
    codigoInput.focus();
}

function actualizarTabla(){
    let tbody = document.querySelector("#tablaVenta tbody");
    tbody.innerHTML = "";

    let total = 0;

    carrito.forEach((p,index)=>{
        total += p.subtotal;

        tbody.innerHTML += `
        <tr>
            <td>${p.codigo}</td>
            <td>${p.nombre}</td>
            <td>${p.cantidad}</td>
            <td>$${p.precio.toFixed(2)}</td>
            <td>$${p.subtotal.toFixed(2)}</td>
            <td><button type="button" class="btn-red" onclick="quitar(${index})">X</button></td>
        </tr>`;
    });

    document.getElementById("totalTexto").innerText = total.toFixed(2);
    document.getElementById("productos_json").value = JSON.stringify(carrito);
}

function quitar(index){
    carrito.splice(index,1);
    actualizarTabla();
}

function limpiarVenta(){
    carrito = [];
    actualizarTabla();
    codigoInput.value = "";
    codigoInput.focus();
}

document.getElementById("formVenta").addEventListener("submit", function(e){
    if(carrito.length === 0){
        e.preventDefault();
        alert("Agrega productos a la venta");
    }
});
</script>

</body>
</html>