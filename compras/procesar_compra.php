<?php
include '../shared/conexion.php';

header('Content-Type: application/json');

// Obtener los datos enviados desde el frontend
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['persona']['id']) || !isset($data['persona']['codigo']) || !isset($data['persona']['descuento']) || !isset($data['productos']) || !isset($data['liquidacion_nota'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}

$personaId = $data['persona']['id'];
$personaCodigo = $data['persona']['codigo'];
$personaDescuento = $data['persona']['descuento']; // Recibir el descuento de la persona
$productos = $data['productos'];
$liquidacionNota = $data['liquidacion_nota'];

$conn->begin_transaction();

try {
    foreach ($productos as $producto) {
        $stmt = $conn->prepare("
            INSERT INTO compras (personas_id, personas_codigo, personas_descuento, productos_id, productos_codigo, productos_precio, productos_pv, liquidacion_nota, fecha_compra)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("isissdds", $personaId, $personaCodigo, $personaDescuento, $producto['id'], $producto['codigo'], $producto['precio'], $producto['pv'], $liquidacionNota);
        $stmt->execute();
        $stmt->close();
    }

    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();