<?php
// Configuración de conexión
$host = "localhost";
$user = "root";
$pass = "";
$db   = "movilian_db"; // CAMBIA ESTO

$conn = new mysqli($host, $user, $pass, $db);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imagen'])) {
    $titulo = $_POST['titulo'];
    $subtitulo = $_POST['subtitulo'];
    
    // Obtener datos del archivo
    $fileTmpPath = $_FILES['imagen']['tmp_name'];
    $fileType = $_FILES['imagen']['type'];
    
    // Leer el archivo en formato binario
    $fp = fopen($fileTmpPath, 'rb');
    $content = fread($fp, filesize($fileTmpPath));
    fclose($fp);

    // Preparar la consulta SQL
    $sql = "INSERT INTO carrusel_inicio (titulo, subtitulo, imagen_binaria, tipo_mime) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // "ssbs" significa: string, string, blob, string
    $stmt->bind_param("ssbs", $titulo, $subtitulo, $null, $fileType);
    $stmt->send_long_data(2, $content); // Envía el binario en la posición 2 (imagen_binaria)

    if ($stmt->execute()) {
        echo "<script>alert('¡Imagen subida con éxito!'); window.location.href='/movile/frontend/movile.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>