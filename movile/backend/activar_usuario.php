<?php

include("conexion.php");

$codigo = trim($_POST['codigo']);

$sql = "SELECT * FROM usuarios WHERE codigo_verificacion='$codigo'";
$resultado = $conexion->query($sql);

if($resultado->num_rows > 0){

    $conexion->query("UPDATE usuarios 
    SET verificado=1 
    WHERE codigo_verificacion='$codigo'");

    echo "<script>
    alert('Cuenta verificada correctamente');
    window.location='/movile/frontend/login.php';
    </script>";

}else{

    echo "<script>
    alert('Código incorrecto');
    window.location='/movile/frontend/verificar.html';
    </script>";

}

?>