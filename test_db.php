<?php

$host = "sql10.freesqldatabase.com";
$user = "sql10828040";
$pass = "AQUI_TU_PASSWORD_REAL";
$db   = "sql10828040";
$port = 3306;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("ERROR BD: " . $conn->connect_error);
}

echo "CONEXIÓN EXITOSA";

?>