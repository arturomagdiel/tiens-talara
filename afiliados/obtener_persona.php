<?php

// Conexiиоn a la base de datos
$servername = "localhost";
$username = "tienslima_shopu";
$password = "Mar11ine!shop";
$dbname = "tienslima_shop";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexiиоn
if ($conn->connect_error) {
    die("Error de conexiиоn: " . $conn->connect_error);
}

// Obtener el ID de la persona
$personaId = $_GET["id"];

// Obtener los datos de la persona de la base de datos
$sql = "SELECT * FROM personas WHERE id = $personaId";
$result = $conn->query($sql);

// Convertir los datos a formato JSON
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  echo json_encode($row);
}

$conn->close();

?>