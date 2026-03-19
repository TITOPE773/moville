<?php

include("../backend/conexion.php");

$sql = "SELECT id,usuario FROM usuarios";

$result = $conexion->query($sql);

$usuarios = [];

while($fila = $result->fetch_assoc()){

$usuarios[] = $fila;

}

echo json_encode($usuarios);

?>