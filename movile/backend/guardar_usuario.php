<?php

include("conexion.php");

$usuario = $_POST['usuario'];
$password = $_POST['password'];

$codigo = rand(100000,999999); // código de 6 dígitos

$sql = "INSERT INTO usuarios(usuario,password,verificado,codigo_verificacion)
VALUES('$usuario','$password',0,'$codigo')";

$conexion->query($sql);

/* TU CORREO */
$destinatario = "luisalbertolanda04@gmail.com";

$asunto = "Código de verificación Movilian";

$cuerpo = "Nuevo usuario registrado\n\n";
$cuerpo .= "Usuario: $usuario\n";
$cuerpo .= "Código de verificación: $codigo\n";

mail($destinatario,$asunto,$cuerpo);

echo "
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<script>
Swal.fire({
title:'Registro enviado',
text:'Escribe el código que te dará el administrador.',
icon:'success'
}).then(()=>{
window.location='verificar_codigo.html';
});
</script>
";
/* redirigir */
header("Location: /movile/frontend/verificar.html");
exit();
?>