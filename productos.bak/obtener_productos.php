<?php
include '../shared/conexion.php';

$sql = "SELECT * FROM productos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td><img src='" . $row["imagen"] . "' alt='" . $row["nombre"] . "' class='img-thumbnail'></td>";
    echo "<td>" . $row["codigo"] . "</td>";
    echo "<td>" . $row["nombre"] . "</td>";
    echo "<td>" . $row["precio_publico"] . "</td>";
    echo "<td>" . $row["pv_publico"] . "</td>";
    echo "<td>" . $row["precio_afiliado"] . "</td>";
    echo "<td>" . $row["pv_afiliado"] . "</td>";
    echo "<td>" . $row["precio_junior"] . "</td>";
    echo "<td>" . $row["pv_junior"] . "</td>";
    echo "<td>" . $row["precio_senior"] . "</td>";
    echo "<td>" . $row["pv_senior"] . "</td>";
    echo "<td>" . $row["precio_master"] . "</td>";
    echo "<td>" . $row["pv_master"] . "</td>";
    echo "<td>";
    echo "<button type='button' class='btn btn-primary btn-sm btnEditar' data-id='" . $row["id"] . "'>Editar</button>";
    echo "<button type='button' class='btn btn-danger btn-sm btnEliminar' data-id='" . $row["id"] . "'>Eliminar</button>";
    echo "</td>";
    echo "</tr>";
  }
} else {
  echo "<tr><td colspan='14'>No se encontraron productos.</td></tr>";
}

$conn->close();
?>