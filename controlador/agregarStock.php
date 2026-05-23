<<<<<<< HEAD
<?php
include("seguridad.php");
include("../modelo/conexion.php");
include("permisos.php");

if ($_SESSION['rol'] != 'Administrador' && 
    !tienePermiso($conexion, $_SESSION['id_usuario'], "agregar_producto")) {
    die("No tienes permiso para agregar stock");
}

$id_producto = intval($_POST['id_producto'] ?? 0);
$cantidad = intval($_POST['cantidad'] ?? 0);

if ($id_producto <= 0 || $cantidad <= 0) {
    die("Datos inválidos");
}

$sql = "UPDATE productos SET stock = stock + ? WHERE id_producto = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $cantidad, $id_producto);

if ($stmt->execute()) {

    $ip = $_SERVER['REMOTE_ADDR'];
    $so = $_SERVER['HTTP_USER_AGENT'];
    $id_usuario = $_SESSION['id_usuario'];

    $descripcion = "Se agregó stock al producto ID: $id_producto. Cantidad agregada: $cantidad";

    $auditoria = "INSERT INTO auditoria
    (id_usuario, accion, tabla_afectada, descripcion, ip_usuario, sistema_operativo)
    VALUES (?, 'AGREGAR STOCK', 'productos', ?, ?, ?)";

    $stmtAud = $conexion->prepare($auditoria);
    $stmtAud->bind_param("isss", $id_usuario, $descripcion, $ip, $so);
    $stmtAud->execute();

    header("Location: ../vista/productos.php");
    exit();

} else {
    die("Error al agregar stock: " . $stmt->error);
}
=======
<?php
include("seguridad.php");
include("../modelo/conexion.php");
include("permisos.php");

if ($_SESSION['rol'] != 'Administrador' && 
    !tienePermiso($conexion, $_SESSION['id_usuario'], "agregar_producto")) {
    die("No tienes permiso para agregar stock");
}

$id_producto = intval($_POST['id_producto'] ?? 0);
$cantidad = intval($_POST['cantidad'] ?? 0);

if ($id_producto <= 0 || $cantidad <= 0) {
    die("Datos inválidos");
}

$sql = "UPDATE productos SET stock = stock + ? WHERE id_producto = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $cantidad, $id_producto);

if ($stmt->execute()) {

    $ip = $_SERVER['REMOTE_ADDR'];
    $so = $_SERVER['HTTP_USER_AGENT'];
    $id_usuario = $_SESSION['id_usuario'];

    $descripcion = "Se agregó stock al producto ID: $id_producto. Cantidad agregada: $cantidad";

    $auditoria = "INSERT INTO auditoria
    (id_usuario, accion, tabla_afectada, descripcion, ip_usuario, sistema_operativo)
    VALUES (?, 'AGREGAR STOCK', 'productos', ?, ?, ?)";

    $stmtAud = $conexion->prepare($auditoria);
    $stmtAud->bind_param("isss", $id_usuario, $descripcion, $ip, $so);
    $stmtAud->execute();

    header("Location: ../vista/productos.php");
    exit();

} else {
    die("Error al agregar stock: " . $stmt->error);
}
>>>>>>> 452094ddd0ffd458f6b759cd9dfb5fbf40ef7bb2
?>