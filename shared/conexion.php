<?php
// Conexi��n a la base de datos
$servername = "localhost";
$username = "tienslima_shopu";
$password = "Mar11ine!shop";
$dbname = "tienslima_shop";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexi��n
if ($conn->connect_error) {
  die("Error de conexion: " . $conn->connect_error);
}
?>