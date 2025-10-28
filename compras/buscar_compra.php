<?php
// filepath: d:\Users\artur\Documents\GitHub\tiens-talara\compras\buscar_compra.php
include '../shared/conexion.php';

$id = $_POST['id'] ?? '';

header('Content-Type: application/json');

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID no válido']);
    exit;
}

// Buscar la compra por ID (incluyendo eliminadas) con datos del afiliado
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
        c.personas_descuento AS descuento,
        per.nombre AS persona_nombre,
        per.codigo AS persona_codigo
    FROM compras c
    LEFT JOIN productos p ON c.productos_id = p.id
    LEFT JOIN personas per ON c.personas_id = per.id
    WHERE c.id = ?
");

$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $compra = $result->fetch_assoc();
    echo json_encode(['success' => true, 'compra' => $compra]);
} else {
    echo json_encode(['success' => false, 'message' => 'Compra no encontrada']);
}

$stmt->close();
$conn->close();
?>