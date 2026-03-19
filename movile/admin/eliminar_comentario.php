<?php
session_start();
header('Content-Type: application/json');

include("../backend/conexion.php");

$id = $_POST['id'] ?? '';

if(!$id){
    echo json_encode(["status"=>"error"]);
    exit();
}

$sql = "DELETE FROM opiniones WHERE id='$id'";

if($conexion->query($sql)){
    echo json_encode(["status"=>"ok"]);
}else{
    echo json_encode(["status"=>"error"]);
}
?>