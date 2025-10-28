<?php
// Proteger endpoint con autenticación
require_once '../shared/auth.php';
requireAuth();

// Conexión a la base de datos
include '../shared/conexion.php';

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