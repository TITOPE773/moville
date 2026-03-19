<?php
session_start();
include("conexion.php");

$usuario = $_POST['usuario'];
$password = $_POST['password'];

$sql = "SELECT * FROM usuarios 
WHERE usuario='$usuario' 
AND password='$password'
AND verificado=1";

$resultado = $conexion->query($sql);

if($resultado->num_rows > 0){

    $fila = $resultado->fetch_assoc();

    // verificar si la cuenta ya está activada
    if($fila['verificado'] == 1){

        $_SESSION['usuario'] = $usuario;

        header("Location: ../admin/dashboard.php");
        exit();

    }else{

        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({
            icon:'warning',
            title:'Cuenta no verificada',
            text:'Debes verificar tu cuenta con el código.'
        }).then(()=>{
            window.location='/movile/frontend/verificar.html';
        });
        </script>
        ";

    }

}else{

echo "
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<script>
Swal.fire({
    icon:'error',
    title:'Error',
    text:'Usuario o contraseña incorrectos'
}).then(()=>{
    window.location='/movile/frontend/login.php';
});
</script>
";

}
?>