<?php
session_start();
header('Content-Type: application/json');

include("../backend/conexion.php");

$sql = "SELECT id, nombre, comentario, fecha FROM opiniones ORDER BY id DESC";
$result = $conexion->query($sql);

$comentarios = [];

while($fila = $result->fetch_assoc()){
    $comentarios[] = $fila;
}

echo json_encode($comentarios);
?>