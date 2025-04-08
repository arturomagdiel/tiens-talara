<?php

// Conexión a la base de datos
include '../shared/conexion.php';

// Consulta para obtener todos los contactos
$sql = "SELECT * FROM personas";
$result = $conn->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

// Devolver los datos en formato JSON para DataTables
echo json_encode(array("data" => $data));

$conn->close();

?>