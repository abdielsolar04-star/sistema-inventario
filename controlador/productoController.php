<<<<<<< HEAD
<?php

include("seguridad.php");
include("../modelo/conexion.php");
include("permisos.php");

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

function registrarAuditoria($conexion, $id_usuario, $accion, $tabla, $descripcion) {

    $ip = $_SERVER['REMOTE_ADDR'];
    $so = $_SERVER['HTTP_USER_AGENT'];

    $sql = "INSERT INTO auditoria
    (
        id_usuario,
        accion,
        tabla_afectada,
        descripcion,
        ip_usuario,
        sistema_operativo
    )
    VALUES
    (
        ?, ?, ?, ?, ?, ?
    )";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param(
        "isssss",
        $id_usuario,
        $accion,
        $tabla,
        $descripcion,
        $ip,
        $so
    );

    $stmt->execute();
}

if ($accion == "crear") {

    if (
        $_SESSION['rol'] != 'Administrador' &&
        !tienePermiso($conexion, $_SESSION['id_usuario'], "agregar_producto")
    ) {
        die("No tienes permiso");
    }

    $codigo = trim($_POST['codigo']);
    $nombre = trim($_POST['nombre_producto']);
    $descripcion = trim($_POST['descripcion']);

    $id_proveedor = $_POST['id_proveedor'];
    $nuevo_proveedor = trim($_POST['nuevo_proveedor'] ?? '');

    $precio_compra = floatval($_POST['precio_compra']);
    $precio_venta = floatval($_POST['precio_venta']);
    $stock = intval($_POST['stock']);

    if ($codigo == "" || $nombre == "") {
        die("Código y nombre obligatorios");
    }

    // VERIFICAR CÓDIGO DUPLICADO
    $verificar = $conexion->prepare("
        SELECT id_producto
        FROM productos
        WHERE codigo = ?
    ");

    $verificar->bind_param("s", $codigo);

    $verificar->execute();

    $resultado = $verificar->get_result();

    if ($resultado->num_rows > 0) {
        die("Ese código ya existe");
    }

    // NUEVO PROVEEDOR
    if ($id_proveedor == "nuevo") {

        if ($nuevo_proveedor == "") {
            die("Escribe el nuevo proveedor");
        }

        $buscarProveedor = $conexion->prepare("
            SELECT id_proveedor
            FROM proveedores
            WHERE nombre_proveedor = ?
            LIMIT 1
        ");

        $buscarProveedor->bind_param(
            "s",
            $nuevo_proveedor
        );

        $buscarProveedor->execute();

        $resultadoProveedor = $buscarProveedor->get_result();

        if ($resultadoProveedor->num_rows > 0) {

            $prov = $resultadoProveedor->fetch_assoc();

            $id_proveedor = intval($prov['id_proveedor']);

        } else {

            $insertProveedor = $conexion->prepare("
                INSERT INTO proveedores
                (
                    nombre_proveedor,
                    telefono,
                    direccion
                )
                VALUES
                (
                    ?, '', ''
                )
            ");

            $insertProveedor->bind_param(
                "s",
                $nuevo_proveedor
            );

            $insertProveedor->execute();

            $id_proveedor = $conexion->insert_id;
        }

    } else {

        $id_proveedor = intval($id_proveedor);
    }

    $sql = "
    INSERT INTO productos
    (
        codigo,
        nombre_producto,
        descripcion,
        id_proveedor,
        precio_compra,
        precio_venta,
        stock
    )
    VALUES
    (
        ?, ?, ?, ?, ?, ?, ?
    )
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param(
        "sssiddi",
        $codigo,
        $nombre,
        $descripcion,
        $id_proveedor,
        $precio_compra,
        $precio_venta,
        $stock
    );

    if ($stmt->execute()) {

        registrarAuditoria(
            $conexion,
            $_SESSION['id_usuario'],
            "CREAR PRODUCTO",
            "productos",
            "Producto agregado: $nombre"
        );

        header("Location: ../vista/productos.php");
        exit();

    } else {

        die("Error al guardar producto");
    }
}

if ($accion == "eliminar") {

    if (
        $_SESSION['rol'] != 'Administrador' &&
        !tienePermiso($conexion, $_SESSION['id_usuario'], "eliminar_producto")
    ) {
        die("No tienes permiso");
    }

    $id = intval($_GET['id']);

    $conexion->begin_transaction();

    try {

        // BORRAR DETALLE VENTAS
        $detalle = $conexion->prepare("
            DELETE FROM detalle_ventas
            WHERE id_producto = ?
        ");

        $detalle->bind_param("i", $id);

        $detalle->execute();

        // BORRAR MOVIMIENTOS
        $mov = $conexion->prepare("
            DELETE FROM movimientos
            WHERE id_producto = ?
        ");

        $mov->bind_param("i", $id);

        $mov->execute();

        // BORRAR PRODUCTO
        $producto = $conexion->prepare("
            DELETE FROM productos
            WHERE id_producto = ?
        ");

        $producto->bind_param("i", $id);

        $producto->execute();

        registrarAuditoria(
            $conexion,
            $_SESSION['id_usuario'],
            "ELIMINAR PRODUCTO",
            "productos",
            "Producto eliminado ID: $id"
        );

        $conexion->commit();

        header("Location: ../vista/productos.php");
        exit();

    } catch (Exception $e) {

        $conexion->rollback();

        die("Error al eliminar producto");
    }
}

header("Location: ../vista/productos.php");
exit();

=======
<?php

include("seguridad.php");
include("../modelo/conexion.php");
include("permisos.php");

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

function registrarAuditoria($conexion, $id_usuario, $accion, $tabla, $descripcion) {

    $ip = $_SERVER['REMOTE_ADDR'];
    $so = $_SERVER['HTTP_USER_AGENT'];

    $sql = "INSERT INTO auditoria
    (
        id_usuario,
        accion,
        tabla_afectada,
        descripcion,
        ip_usuario,
        sistema_operativo
    )
    VALUES
    (
        ?, ?, ?, ?, ?, ?
    )";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param(
        "isssss",
        $id_usuario,
        $accion,
        $tabla,
        $descripcion,
        $ip,
        $so
    );

    $stmt->execute();
}

if ($accion == "crear") {

    if (
        $_SESSION['rol'] != 'Administrador' &&
        !tienePermiso($conexion, $_SESSION['id_usuario'], "agregar_producto")
    ) {
        die("No tienes permiso");
    }

    $codigo = trim($_POST['codigo']);
    $nombre = trim($_POST['nombre_producto']);
    $descripcion = trim($_POST['descripcion']);

    $id_proveedor = $_POST['id_proveedor'];
    $nuevo_proveedor = trim($_POST['nuevo_proveedor'] ?? '');

    $precio_compra = floatval($_POST['precio_compra']);
    $precio_venta = floatval($_POST['precio_venta']);
    $stock = intval($_POST['stock']);

    if ($codigo == "" || $nombre == "") {
        die("Código y nombre obligatorios");
    }

    // VERIFICAR CÓDIGO DUPLICADO
    $verificar = $conexion->prepare("
        SELECT id_producto
        FROM productos
        WHERE codigo = ?
    ");

    $verificar->bind_param("s", $codigo);

    $verificar->execute();

    $resultado = $verificar->get_result();

    if ($resultado->num_rows > 0) {
        die("Ese código ya existe");
    }

    // NUEVO PROVEEDOR
    if ($id_proveedor == "nuevo") {

        if ($nuevo_proveedor == "") {
            die("Escribe el nuevo proveedor");
        }

        $buscarProveedor = $conexion->prepare("
            SELECT id_proveedor
            FROM proveedores
            WHERE nombre_proveedor = ?
            LIMIT 1
        ");

        $buscarProveedor->bind_param(
            "s",
            $nuevo_proveedor
        );

        $buscarProveedor->execute();

        $resultadoProveedor = $buscarProveedor->get_result();

        if ($resultadoProveedor->num_rows > 0) {

            $prov = $resultadoProveedor->fetch_assoc();

            $id_proveedor = intval($prov['id_proveedor']);

        } else {

            $insertProveedor = $conexion->prepare("
                INSERT INTO proveedores
                (
                    nombre_proveedor,
                    telefono,
                    direccion
                )
                VALUES
                (
                    ?, '', ''
                )
            ");

            $insertProveedor->bind_param(
                "s",
                $nuevo_proveedor
            );

            $insertProveedor->execute();

            $id_proveedor = $conexion->insert_id;
        }

    } else {

        $id_proveedor = intval($id_proveedor);
    }

    $sql = "
    INSERT INTO productos
    (
        codigo,
        nombre_producto,
        descripcion,
        id_proveedor,
        precio_compra,
        precio_venta,
        stock
    )
    VALUES
    (
        ?, ?, ?, ?, ?, ?, ?
    )
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param(
        "sssiddi",
        $codigo,
        $nombre,
        $descripcion,
        $id_proveedor,
        $precio_compra,
        $precio_venta,
        $stock
    );

    if ($stmt->execute()) {

        registrarAuditoria(
            $conexion,
            $_SESSION['id_usuario'],
            "CREAR PRODUCTO",
            "productos",
            "Producto agregado: $nombre"
        );

        header("Location: ../vista/productos.php");
        exit();

    } else {

        die("Error al guardar producto");
    }
}

if ($accion == "eliminar") {

    if (
        $_SESSION['rol'] != 'Administrador' &&
        !tienePermiso($conexion, $_SESSION['id_usuario'], "eliminar_producto")
    ) {
        die("No tienes permiso");
    }

    $id = intval($_GET['id']);

    $conexion->begin_transaction();

    try {

        // BORRAR DETALLE VENTAS
        $detalle = $conexion->prepare("
            DELETE FROM detalle_ventas
            WHERE id_producto = ?
        ");

        $detalle->bind_param("i", $id);

        $detalle->execute();

        // BORRAR MOVIMIENTOS
        $mov = $conexion->prepare("
            DELETE FROM movimientos
            WHERE id_producto = ?
        ");

        $mov->bind_param("i", $id);

        $mov->execute();

        // BORRAR PRODUCTO
        $producto = $conexion->prepare("
            DELETE FROM productos
            WHERE id_producto = ?
        ");

        $producto->bind_param("i", $id);

        $producto->execute();

        registrarAuditoria(
            $conexion,
            $_SESSION['id_usuario'],
            "ELIMINAR PRODUCTO",
            "productos",
            "Producto eliminado ID: $id"
        );

        $conexion->commit();

        header("Location: ../vista/productos.php");
        exit();

    } catch (Exception $e) {

        $conexion->rollback();

        die("Error al eliminar producto");
    }
}

header("Location: ../vista/productos.php");
exit();

>>>>>>> 452094ddd0ffd458f6b759cd9dfb5fbf40ef7bb2
?>