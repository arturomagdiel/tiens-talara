<?php
// Proteger endpoint con autenticación
require_once '../shared/auth.php';
requireAuth();

// Asegurar que se envía la cabecera JSON
header('Content-Type: application/json');

// Conexión a la base de datos
include '../shared/conexion.php';
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