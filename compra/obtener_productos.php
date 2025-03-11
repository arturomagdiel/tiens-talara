<?php
include '../shared/conexion.php'; // Incluir la conexión

// Obtener el tipo de precio
$tipoPrecio = $_GET["tipo"];

// Consulta SQL para obtener los productos
$sql = "SELECT * FROM productos ORDER BY codigo";
$result = $conn->query($sql);

// Mostrar los productos como botones en un grid
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    // Obtener el precio y PV correspondiente al tipo de precio
    $precio = $row["precio_" . $tipoPrecio];
    $pv = $row["pv_" . $tipoPrecio];

    // Imprimir los datos del producto en la consola (depuraci��n)
    echo "<script>console.log(" . json_encode($row) . ");</script>";

    echo "<div class='col'>";
    echo "<button class='product-button' data-id='" . $row["id"] . "' 
                 data-nombre='" . $row["nombre"] . "' 
                 data-imagen='" . $row["imagen"] . "'
                 data-precio_publico='" . $row["precio_publico"] . "' 
                 data-pv_publico='" . $row["pv_publico"] . "'
                 data-precio_afiliado='" . $row["precio_afiliado"] . "' 
                 data-pv_afiliado='" . $row["pv_afiliado"] . "'
                 data-precio_junior='" . $row["precio_junior"] . "' 
                 data-pv_junior='" . $row["pv_junior"] . "'
                 data-precio_senior='" . $row["precio_senior"] . "' 
                 data-pv_senior='" . $row["pv_senior"] . "'
                 data-precio_master='" . $row["precio_master"] . "' 
                 data-pv_master='" . $row["pv_master"] . "'>"; 
    echo "<img src='" . $row["imagen"] . "' alt='" . $row["nombre"] . "'>";
    echo "<br>";
    echo $row["nombre"];
    echo "<br>";
    // Mostrar el precio con dos decimales
    echo "Precio: " . number_format($precio, 2);
    echo "<br>";
    // Mostrar el PV con dos decimales
    echo "PV: " . number_format($pv, 2); 
    echo "</button>";
    echo "</div>";
  }
} else {
  echo "No se encontraron productos.";
}

$conn->close();
?>