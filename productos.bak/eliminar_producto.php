<?php
include '../shared/conexion.php';

$id = $_POST['id'];

$sql = "DELETE FROM productos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  echo "Producto eliminado correctamente.";
} else {
  echo "Error al eliminar el producto.";
}

$stmt->close();
$conn->close();
?>