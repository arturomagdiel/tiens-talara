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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST["id"];

  // Eliminar el producto de la base de datos
  $sql = "DELETE FROM productos WHERE id = $id";

  if ($conn->query($sql) === TRUE) {
    echo "Producto eliminado con éxito";
  } else {
    echo "Error al eliminar el producto: " . $conn->error;
  }
}

$conn->close();
?>