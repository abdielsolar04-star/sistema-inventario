<<<<<<< HEAD
<?php

$servidor = "sql10.freesqldatabase.com";
$usuario = "sql10827970";
$password = "UHKdqXFFlB";
$base_datos = "sql10827970";
$puerto = 3306;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    $conexion = new mysqli(
        $servidor,
        $usuario,
        $password,
        $base_datos,
        $puerto
    );

    $conexion->set_charset("utf8mb4");

    date_default_timezone_set("America/Mexico_City");

    echo "Conexion exitosa";

} catch (mysqli_sql_exception $e) {

    die("Error de conexión FreeSQLDatabase: " . $e->getMessage());

}

=======
<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "inventario_seguro";

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
date_default_timezone_set("America/Mexico_City");
>>>>>>> 452094ddd0ffd458f6b759cd9dfb5fbf40ef7bb2
?>