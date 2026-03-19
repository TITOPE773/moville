<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $destinatario = "luisalbertolanda04@gmail.com";
    
    // Recibimos y limpiamos los datos
    $nombre  = htmlspecialchars($_POST['nombre']);
    $correo  = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $marca   = htmlspecialchars($_POST['marca']);
    $modelo  = htmlspecialchars($_POST['modelo']);
    $mensaje = htmlspecialchars($_POST['mensaje']);

    $asunto = "LABORATORIO: Revisión de " . strtoupper($marca) . " " . $modelo;
    
    $cuerpo = "Nueva solicitud de diagnóstico en Movilian:\n\n";
    $cuerpo .= "Cliente: $nombre\n";
    $cuerpo .= "Email: $correo\n";
    $cuerpo .= "Equipo a revisar: " . ucfirst($marca) . " - $modelo\n";
    $cuerpo .= "Falla reportada:\n$mensaje\n";

    $headers = "From: laboratorio@movilian.com\r\n";
    $headers .= "Reply-To: $correo";

    // Intentamos enviar el correo
    @mail($destinatario, $asunto, $cuerpo, $headers);

    // Mostramos la alerta de éxito y regresamos al inicio
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body style='background:#0f172a'>
    <script>
        Swal.fire({
            title: '¡Equipo Registrado!',
            text: 'Hemos recibido la solicitud para tu $marca $modelo. Te contactaremos pronto.',
            icon: 'success',
            confirmButtonColor: '#0ea5e9'
        }).then(() => { window.location.href = '/movile/frontend/index.html'; });
    </script>
    </body>
    </html>";
}
?>