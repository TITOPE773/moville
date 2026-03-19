<?php
include 'conexion.php'; // Usamos el puente que creamos arriba

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

// --- GUARDAR COMENTARIO ---
if ($accion == 'guardar') {
    $nombre = $_POST['nombre'];
    $texto = $_POST['texto'];
    
    $query = "INSERT INTO opiniones (nombre, texto) VALUES ('$nombre', '$texto')";
    if(mysqli_query($conexion, $query)) {
        echo json_encode(['status' => 'ok']);
    }
}

// --- LEER COMENTARIOS ---
else {
    $query = "SELECT * FROM opiniones ORDER BY fecha DESC";
    $resultado = mysqli_query($conexion, $query);
    $comentarios = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    echo json_encode($comentarios);
}
?>