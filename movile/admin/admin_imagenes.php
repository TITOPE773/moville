<?php 
echo "PHP FUNCIONANDO";
$conn = new mysqli("localhost","root","","movilian_db");
$conn = new mysqli("localhost","root","","movilian_db");

if($conn->connect_error){
die("Error de conexión");
}

$result = $conn->query("SELECT * FROM carrusel_inicio ORDER BY id DESC");

if($result && $result->num_rows > 0){

while($row = $result->fetch_assoc()){
?>

<div class="admin-img">

<img src="<?php echo $row['imagen']; ?>" width="120">

<p><?php echo $row['titulo']; ?></p>

<a href="/movile/admin/eliminar_imagen.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('¿Eliminar esta imagen?')"
class="btn-delete-img">
Eliminar
</a>

</div>

<?php 
}

}else{

echo "<p>No hay imágenes subidas.</p>";

}

?>