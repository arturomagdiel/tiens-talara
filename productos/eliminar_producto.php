<?php
include '../shared/conexion.php';

$id = isset($_POST['id']) ? $_POST['id'] : null;

if ($id) {
  // Cambiar el campo activo a 0
  $sql = "UPDATE productos SET activo = 0 WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    echo "Producto eliminado correctamente.";
  } else {
    echo "Error al eliminar el producto.";
  }

  $stmt->close();
} else {
  echo "ID de producto no proporcionado.";
}

$conn->close();
?>