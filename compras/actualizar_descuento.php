<?php
// Proteger endpoint con autenticación
require_once '../shared/auth.php';
requireAuth();

include '../shared/conexion.php';

// Obtener los datos enviados desde el frontend
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$descuento = $data['descuento'];

// Validar los datos
if (!is_numeric($descuento) || $descuento < 0 || $descuento > 100) {
    echo json_encode(['success' => false, 'message' => 'Descuento inválido.']);
    exit;
}

// Actualizar el descuento en la base de datos
$query = "UPDATE personas SET descuento = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('di', $descuento, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Descuento actualizado correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el descuento.']);
}

$stmt->close();
$conn->close();
?>