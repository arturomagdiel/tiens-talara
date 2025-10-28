<?php
include '../shared/conexion.php';

// Obtener el tipo de precio
$tipoPrecio = $_GET["tipo"] ?? 'afiliado';

// Consulta SQL para productos activos
$sql = "SELECT id, nombre, imagen, precio_publico, precio_afiliado, pv_afiliado FROM productos WHERE activo = 1 ORDER BY codigo";
$result = $conn->query($sql);

$productos = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = [
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'imagen' => $row['imagen'],
            'precio_publico' => floatval($row['precio_publico']),
            'precio_afiliado' => floatval($row['precio_afiliado']),
            'pv_afiliado' => floatval($row['pv_afiliado'])
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($productos);

$conn->close();
?>