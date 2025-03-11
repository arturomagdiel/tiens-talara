<?php

// Conexión a la base de datos
$servername = "localhost";
$username = "tienslima_shopu";
$password = "Mar11ine!shop";
$dbname = "tienslima_shop";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el ID de la persona a eliminar
$personaId = $_POST["id"];

// Eliminar la persona de la base de datos
$sql = "DELETE FROM personas WHERE id = $personaId";

if ($conn->query($sql) === TRUE) {
  echo "Persona eliminada correctamente";
} else {
  echo "Error al eliminar la persona: " . $conn->error;
}

$conn->close();
?>