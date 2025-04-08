<?php
include '../shared/conexion.php'; // Incluir la conexión

// Obtener el tipo de precio
$tipoPrecio = $_GET["tipo"];

// Consulta SQL para obtener los productos activos
$sql = "SELECT * FROM productos WHERE activo = 1 ORDER BY codigo";
$result = $conn->query($sql);

// Mostrar los productos como botones en un grid
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Calcular el precio y PV según el tipo seleccionado
        if ($tipoPrecio === 'publico') {
            $precio = $row["precio_publico"];
            $pv = 0; // PV es 0 para el precio público
        } elseif ($tipoPrecio === 'afiliado') {
            $precio = $row["precio_afiliado"];
            $pv = $row["pv_afiliado"];
        } elseif ($tipoPrecio === '5') {
            $precio = round($row["precio_afiliado"] - ($row["precio_afiliado"] * 0.05), 2);
            $pv = round($row["pv_afiliado"] - ($row["pv_afiliado"] * 0.05), 2);
        } elseif ($tipoPrecio === '8') {
            $precio = round($row["precio_afiliado"] - ($row["precio_afiliado"] * 0.08), 2);
            $pv = round($row["pv_afiliado"] - ($row["pv_afiliado"] * 0.08), 2);
        } elseif ($tipoPrecio === '15') {
            $precio = round($row["precio_afiliado"] - ($row["precio_afiliado"] * 0.15), 2);
            $pv = round($row["pv_afiliado"] - ($row["pv_afiliado"] * 0.15), 2);
        } else {
            $precio = 0; // Valor predeterminado si no se reconoce el tipo
            $pv = 0;
        }

        // Generar el HTML para cada producto
        echo "<div class='col'>";
        echo "<a href='#' class='card product-button' 
                  data-id='" . $row["id"] . "' 
                  data-nombre='" . $row["nombre"] . "' 
                  data-imagen='" . $row["imagen"] . "' 
                  data-precio='" . round($row["precio_afiliado"] - ($row["precio_afiliado"] * 0.15), 2) . "' 
                  data-pv='" . round($row["pv_afiliado"] - ($row["pv_afiliado"] * 0.15), 2) . "' 
                  data-precio-publico='" . $row["precio_publico"] . "' 
                  data-precio-afiliado='" . $row["precio_afiliado"] . "' 
                  data-precio-5='" . round($row["precio_afiliado"] - ($row["precio_afiliado"] * 0.05), 2) . "' 
                  data-precio-8='" . round($row["precio_afiliado"] - ($row["precio_afiliado"] * 0.08), 2) . "' 
                  data-precio-15='" . round($row["precio_afiliado"] - ($row["precio_afiliado"] * 0.15), 2) . "' 
                  data-pv-publico='0' 
                  data-pv-afiliado='" . $row["pv_afiliado"] . "' 
                  data-pv-5='" . round($row["pv_afiliado"] - ($row["pv_afiliado"] * 0.05), 2) . "' 
                  data-pv-8='" . round($row["pv_afiliado"] - ($row["pv_afiliado"] * 0.08), 2) . "' 
                  data-pv-15='" . round($row["pv_afiliado"] - ($row["pv_afiliado"] * 0.15), 2) . "'>";
        echo "<img src='" . $row["imagen"] . "' class='card-img-top' alt='" . $row["nombre"] . "'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>" . $row["nombre"] . "</h5>";
        echo "<p class='card-text'><strong>S/" . number_format($precio, 2) . "</strong> (" . number_format($pv, 2) . "PV)</p>";
        echo "</div>";
        echo "</a>";
        echo "</div>";
    }
} else {
    echo "<p>No se encontraron productos.</p>";
}

$conn->close();
?>