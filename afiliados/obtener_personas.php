<?php

// Conexi��n a la base de datos
$servername = "localhost";
$username = "tienslima_shopu";
$password = "Mar11ine!shop";
$dbname = "tienslima_shop";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexi��n
if ($conn->connect_error) {
    die("Error de conexi��n: " . $conn->connect_error);
}

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