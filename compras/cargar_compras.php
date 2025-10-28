<?php
// Proteger endpoint con autenticación
require_once '../shared/auth.php';
requireAuth();

include '../shared/conexion.php';

// Obtener los datos enviados desde el frontend
$data = json_decode(file_get_contents('php://input'), true);
$persona_id = $data['persona_id'] ?? null;
$estado = $data['estado'] ?? 'pendiente'; // Por defecto, mostrar pendientes

if (!$persona_id) {
    echo json_encode([]);
    exit;
}

// Consulta base para todos los estados
$sql = "
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
    LEFT JOIN productos p ON c.productos_id = p.id
    WHERE c.personas_id = ? AND c.estado = ?
    ORDER BY c.updated_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('is', $persona_id, $estado);
$stmt->execute();
$result = $stmt->get_result();

$compras = [];
while ($row = $result->fetch_assoc()) {
    $compras[] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($compras);
?>