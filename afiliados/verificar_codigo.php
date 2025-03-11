<?php

// Asegurar que se envía la cabecera JSON
header('Content-Type: application/json');

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

// Obtener el código y el ID
$codigo = $_GET["codigo"];
$id = $_GET["id"];

// Verificar si el código ya existe en la base de datos (ignorando el propio registro)
$sql = "SELECT COUNT(*) as count FROM personas WHERE codigo = '$codigo'";
if (!empty($id)) {
  $sql .= " AND id != $id";
}

$result = $conn->query($sql);
$row = $result->fetch_assoc();
$existe = ($row['count'] > 0);

// Devolver la respuesta en formato JSON
echo json_encode(array('existe' => $existe));

$conn->close();
?>