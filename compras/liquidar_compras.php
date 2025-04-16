<?php
include '../shared/conexion.php';

header('Content-Type: application/json'); // Asegúrate de que la respuesta sea JSON

$data = json_decode(file_get_contents('php://input'), true);

$ids = $data['ids'];
$numeroLiquidacion = $data['numeroLiquidacion'];
$notaLiquidacion = $data['notaLiquidacion'];
$fechaLiquidacion = date('Y-m-d H:i:s'); // Fecha actual en formato MySQL

if (!empty($ids) && !empty($numeroLiquidacion)) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conn->prepare("UPDATE compras SET estado = 'liquidado', liquidacion_numero = ?, liquidacion_nota = ?, liquidacion_fecha = ? WHERE id IN ($placeholders)");

    // Vincula los parámetros correctamente
    $types = 'sss' . str_repeat('i', count($ids)); // 'sss' para numeroLiquidacion, notaLiquidacion y fechaLiquidacion, 'i' para los IDs
    $params = array_merge([$numeroLiquidacion, $notaLiquidacion, $fechaLiquidacion], $ids);

    // Usa call_user_func_array para vincular dinámicamente los parámetros
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
}

$conn->close();