<?php

include("../backend/conexion.php");

$id = $_GET['id'];

$sql = "DELETE FROM usuarios WHERE id='$id'";

if($conexion->query($sql)){

echo json_encode(["status"=>"ok"]);

}else{

echo json_encode(["status"=>"error"]);

}

?>