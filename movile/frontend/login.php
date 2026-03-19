<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login | Movilian</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>

body{
    margin:0;
    font-family:'Inter',sans-serif;
    background:linear-gradient(135deg,#0f172a,#1e293b);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.modal-login{
    background:white;
    padding:40px;
    width:350px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
    animation:fadeIn .6s ease;
}

.modal-login h2{
    text-align:center;
    margin-bottom:20px;
    color:#0f172a;
}

.modal-login input{
    width:100%;
    padding:12px;
    margin-bottom:12px;
    border:1px solid #cbd5e1;
    border-radius:6px;
}

.modal-login button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:6px;
    background:#0f172a;
    color:white;
    cursor:pointer;
}

.modal-login button:hover{
    background:#1e293b;
}

.logo{
    text-align:center;
    font-size:26px;
    font-weight:800;
    margin-bottom:10px;
}

.logo span{
    color:#3b82f6;
}

hr{
margin:25px 0;
}

</style>
</head>

<body>

<div class="modal-login">

<div class="logo">MOVI<span>LIAN</span></div>

<!-- LOGIN -->
<h2>Iniciar sesión</h2>

<form action="/movile/backend/verificar_login.php" method="POST">

<input type="text" name="usuario" placeholder="Usuario" required>

<input type="password" name="password" placeholder="Contraseña" required>

<button type="submit">Entrar</button>

</form>

<hr>

<!-- REGISTRO -->
<h2>Registrarse</h2>

<form action="/movile/backend/guardar_usuario.php" method="POST">

<input type="text" name="usuario" placeholder="Nuevo usuario" required>

<input type="password" name="password" placeholder="Nueva contraseña" required>

<button type="submit">Crear cuenta</button>


</div>

</body>
</html>