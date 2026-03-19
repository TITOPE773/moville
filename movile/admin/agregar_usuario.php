<?php

include("../backend/conexion.php");

$usuario = $_POST['usuario'];
$password = $_POST['password'];

$codigo = rand(100000,999999);

$sql = "INSERT INTO usuarios(usuario,password,verificado,codigo_verificacion)
VALUES('$usuario','$password',1,'$codigo')";

if($conexion->query($sql)){

echo json_encode(["status"=>"ok"]);

}else{

echo json_encode(["status"=>"error"]);

}

?>