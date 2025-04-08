<?php
include '../shared/conexion.php';

// Obtener los datos enviados desde el frontend
$data = json_decode(file_get_contents('php://input'), true);
$persona_id = $data['persona_id'] ?? null;
$estado = $data['estado'] ?? 'pendiente'; // Por defecto, mostrar pendientes

if (!$persona_id) {
    echo json_encode([]);
    exit;
}

// Consultar las compras de la persona seleccionada con el estado especificado
$stmt = $conn->prepare("
    SELECT 
        c.id, 
        c.fecha_compra, 
        c.productos_codigo, 
        p.nombre AS producto_nombre, 
        c.productos_precio, 
        c.productos_pv, 
        c.estado, 
        c.liquidacion_numero, 
        c.liquidacion_fecha, 
        c.liquidacion_nota, 
        c.personas_descuento AS descuento
    FROM compras c
    INNER JOIN productos p ON c.productos_id = p.id
    WHERE c.personas_id = ? AND c.estado = ?
");
$stmt->bind_param("is", $persona_id, $estado);
$stmt->execute();
$result = $stmt->get_result();

$compras = [];
if ($result) {
    $compras = $result->fetch_all(MYSQLI_ASSOC);
}

header('Content-Type: application/json');
echo json_encode($compras);

$stmt->close();
$conn->close();
?>