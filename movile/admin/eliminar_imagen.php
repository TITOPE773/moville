<?php
// movile/admin/eliminar_imagen.php
header('Content-Type: application/json; charset=utf-8');
session_start();

// opcional: protección (si usas sesión para admin)
if (!isset($_SESSION['usuario'])) {
    echo json_encode(["status"=>"error","message"=>"No autorizado"]);
    exit;
}

// conexión (ajusta si usas un include de conexion.php)
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "movilian_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status"=>"error","message"=>"Fallo de conexión: ".$conn->connect_error]);
    exit;
}

// id seguro
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo json_encode(["status"=>"error","message"=>"ID inválido"]);
    exit;
}

// Para debugging: archivo de log (opcional)
$logfile = __DIR__ . "/elim_log.txt";
function logmsg($s){ global $logfile; @file_put_contents($logfile, date("[Y-m-d H:i:s] ").$s.PHP_EOL, FILE_APPEND); }

// Intentamos seleccionar varias columnas posibles (imagen = ruta, imagen_binaria = blob, ruta = nombre archivo)
$sql = "SELECT imagen_binaria FROM carrusel_inicio WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    logmsg("Prepare fallo: " . $conn->error);
    echo json_encode(["status"=>"error","message"=>"Error interno (prepare)."]);
    exit;
}
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows === 0) {
    logmsg("No existe id: $id");
    echo json_encode(["status"=>"error","message"=>"Registro no encontrado"]);
    exit;
}

$row = $res->fetch_assoc();



// si stored es data:... -> no hay archivo físico
$deletedFile = false;
$deletedFileName = "";

if ($stored !== "") {
    $storedTrim = trim($stored);
    if (strpos($storedTrim, 'data:') === 0) {
        // imagen guardada como data URI en DB: no hay archivo que borrar
        logmsg("ID $id: imagen en DB como data-uri, no se borra archivo.");
    } else {
        // construir ruta absoluta segura: evita borrar fuera de carpeta uploads
        // si la ruta es absoluta (empieza con / o c:\) intenta usarla, si es relativa asume carpeta uploads/
        $projectRoot = realpath(__DIR__ . "/.."); // movile/
        $candidate = $storedTrim;
        // si es relativa (no empieza con / o con letra y :), la asumimos relativa a projectRoot/uploads
        if ($candidate[0] !== '/' && !preg_match('/^[a-zA-Z]:\\\\/', $candidate)) {
            // ajusta aquí si tus uploads están en otra carpeta
            $candidate = $projectRoot . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . ltrim($candidate, '/\\');
        }

        $real = @realpath($candidate);

        // comprueba existencia y que el archivo esté dentro de projectRoot para seguridad
        if ($real && strpos($real, $projectRoot) === 0 && is_file($real) && is_writable($real)) {
            if (@unlink($real)) {
                $deletedFile = true;
                $deletedFileName = $real;
                logmsg("ID $id: archivo eliminado -> $real");
            } else {
                logmsg("ID $id: fallo al unlink $real (permisos?)");
            }
        } else {
            logmsg("ID $id: archivo no encontrado o fuera de projectRoot. candidate=$candidate real=$real");
        }
    }
} else {
    logmsg("ID $id: no hay ruta de archivo (posible BLOB en DB).");
}

// Ahora borramos el registro de la BD (prepared)
$del = $conn->prepare("DELETE FROM carrusel_inicio WHERE id = ?");
if (!$del) {
    logmsg("Prepare delete fallo: " . $conn->error);
    echo json_encode(["status"=>"error","message"=>"Error interno (delete prepare)."]);
    exit;
}
$del->bind_param("i",$id);
if ($del->execute()) {
    echo json_encode([
        "status"=>"ok",
        "message"=>"Imagen eliminada",
        "file_deleted" => $deletedFile ? true : false,
        "deleted_file" => $deletedFileName
    ]);
    logmsg("ID $id: registro eliminado de la BD.");
} else {
    logmsg("ID $id: fallo delete: " . $conn->error);
    echo json_encode(["status"=>"error","message"=>"No se pudo eliminar (DB).", "db_error"=>$conn->error]);
}

$stmt->close();
$del->close();
$conn->close();
exit;