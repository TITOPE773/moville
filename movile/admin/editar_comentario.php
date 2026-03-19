<?php
session_start();
header('Content-Type: application/json');

include("../backend/conexion.php");

$id = $_POST['id'];
$comentario = $_POST['comentario'];

$sql = "UPDATE opiniones SET comentario='$comentario' WHERE id='$id'";

if($conexion->query($sql)){
    echo json_encode(["status"=>"ok"]);
}else{
    echo json_encode(["status"=>"error"]);
}
?>