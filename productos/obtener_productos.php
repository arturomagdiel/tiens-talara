<?php
// Proteger endpoint con autenticación
require_once '../shared/auth.php';
requireAuth();

include '../shared/conexion.php';

// Ordenar por la columna 'codigo' en orden ascendente
$sql = "SELECT * FROM productos ORDER BY codigo ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    // Calcular los valores con descuento y redondear a 2 decimales
    $varPrecio5 = round($row["precio_afiliado"] - ($row["precio_afiliado"] * 0.05), 2);
    $varPv5 = round($row["pv_afiliado"] - ($row["pv_afiliado"] * 0.05), 2);
    $varPrecio8 = round($row["precio_afiliado"] - ($row["precio_afiliado"] * 0.08), 2);
    $varPv8 = round($row["pv_afiliado"] - ($row["pv_afiliado"] * 0.08), 2);
    $varPrecio15 = round($row["precio_afiliado"] - ($row["precio_afiliado"] * 0.15), 2);
    $varPv15 = round($row["pv_afiliado"] - ($row["pv_afiliado"] * 0.15), 2);

    // Determinar la clase de la fila según el estado del producto
    $rowClass = $row["activo"] ? "" : "table-danger";

    echo "<tr class='$rowClass'>";
    $src = (!empty($row['imagen'])) ? $row['imagen'] : '../uploads/tiens-logo-verde.jpg';
    echo '<td><img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($row['nombre']) . '" class="img-thumbnail"></td>';
    echo "<td class='align-middle'>" . $row["codigo"] . "</td>";
    echo "<td class='align-middle'>" . $row["nombre"] . "</td>";
    echo "<td class='text-end align-middle'>" . number_format($row["pv_afiliado"], 2) . "</td>";
    echo "<td class='text-end align-middle fw-bold'>" . number_format($row["precio_afiliado"], 2) . "</td>";
    echo "<td class='text-end align-middle color5'>" . number_format($varPv5, 2) . "</td>";
    echo "<td class='text-end align-middle color5 fw-bold'>" . number_format($varPrecio5, 2) . "</td>";
    echo "<td class='text-end align-middle color8'>" . number_format($varPv8, 2) . "</td>";
    echo "<td class='text-end align-middle color8 fw-bold'>" . number_format($varPrecio8, 2) . "</td>";
    echo "<td class='text-end align-middle color15'>" . number_format($varPv15, 2) . "</td>";
    echo "<td class='text-end align-middle color15 fw-bold'>" . number_format($varPrecio15, 2) . "</td>";
    echo "<td class='text-end align-middle'>" . number_format($row["precio_publico"], 2) . "</td>";
    echo "<td class='align-middle'>";
    echo "<button type='button' class='btn btn-primary btn-sm btnEditar' data-id='" . $row["id"] . "' title='Editar'><i class='bi bi-pencil'></i></button>";
    echo "<button type='button' class='btn btn-danger btn-sm btnEliminar' data-id='" . $row["id"] . "' title='Eliminar'><i class='bi bi-trash'></i></button>";
    echo "</td>";
    echo "</tr>";
  }
} else {
  echo "<tr><td colspan='15'>No se encontraron productos.</td></tr>";
}

$conn->close();
?>