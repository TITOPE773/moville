<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "movilian_db";

$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>