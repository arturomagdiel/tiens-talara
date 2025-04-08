<?php
include '../shared/conexion.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// Verificar los datos recibidos
error_log(print_r($data, true));

if (!$data || !isset($data['compra_id']) || !isset($data['numero_liquidacion']) || !isset($data['fecha_liquidacion'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}

$compraId = $data['compra_id'];
$numeroLiquidacion = $data['numero_liquidacion'];
$fechaLiquidacion = $data['fecha_liquidacion'];
$nuevaNota = $data['nota'] ?? '';

// Obtener el contenido actual de liquidacion_nota
$querySelect = "SELECT liquidacion_nota FROM compras WHERE id = ?";
$stmtSelect = $conn->prepare($querySelect);
$stmtSelect->bind_param('i', $compraId);
$stmtSelect->execute();
$result = $stmtSelect->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $notaActual = $row['liquidacion_nota'];
} else {
    echo json_encode(['success' => false, 'message' => 'Compra no encontrada.']);
    exit;
}
$stmtSelect->close();

// Concatenar la nueva nota con la existente
$notaActualizada = trim($notaActual) . "\n" . trim($nuevaNota);

// Actualizar el campo liquidacion_nota, número de liquidación y fecha de liquidación
$queryUpdate = "UPDATE compras SET estado = 'liquidado', liquidacion_nota = ?, liquidacion_numero = ?, liquidacion_fecha = ? WHERE id = ?";
$stmtUpdate = $conn->prepare($queryUpdate);
$stmtUpdate->bind_param('sssi', $notaActualizada, $numeroLiquidacion, $fechaLiquidacion, $compraId);

if ($stmtUpdate->execute()) {
    echo json_encode(['success' => true, 'message' => 'Compra liquidada con éxito.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al liquidar la compra.']);
}

$stmtUpdate->close();
$conn->close();
?>