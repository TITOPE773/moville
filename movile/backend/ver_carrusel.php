<?php
// 1. Cabecera obligatoria para que el navegador sepa que enviamos datos, no una página web
header('Content-Type: application/json');
error_reporting(0); // Apaga avisos de texto que rompen el JSON

// 2. Conexión
$conn = new mysqli("localhost", "root", "", "movilian_db");

if ($conn->connect_error) {
    die(json_encode(["error" => "Fallo de conexión"]));
}

// 3. Consulta (Usando tus columnas: imagen_binaria y tipo_mime)
$sql = "SELECT id, titulo, subtitulo, imagen_binaria, tipo_mime FROM carrusel_inicio";
$result = $conn->query($sql);

$slides = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $slides[] = [
            "id" => $row['id'],
            "titulo" => $row['titulo'],
            "subtitulo" => $row['subtitulo'],
            "imagen" => "data:" . $row['tipo_mime'] . ";base64," . base64_encode($row['imagen_binaria'])
        ];
    }
}

// 4. Salida única de datos
echo json_encode($slides);
$conn->close();
?>