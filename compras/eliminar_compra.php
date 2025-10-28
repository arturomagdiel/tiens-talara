<?php
// filepath: d:\Users\artur\Documents\GitHub\tiens-talara\compras\eliminar_compra.php

// Proteger endpoint con autenticación
require_once '../shared/auth.php';
requireAuth();

ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../shared/conexion.php';

$id = $_POST['id'] ?? '';
$clave = $_POST['clave'] ?? '';
$razon = $_POST['razon'] ?? '';

header('Content-Type: application/json');

// Log para debug
error_log("Eliminando compra - ID: $id, Clave: $clave, Razón: $razon");

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID no válido']);
    exit;
}

if (!$clave) {
    echo json_encode(['success' => false, 'message' => 'Debe ingresar una clave']);
    exit;
}

if (!$razon || trim($razon) === '') {
    echo json_encode(['success' => false, 'message' => 'La razón de eliminación es obligatoria']);
    exit;
}

try {
    // Verificar si la clave ingresada coincide con alguna que inicie con 'pass_'
    $stmt = $conn->prepare("SELECT clave, valor FROM configuraciones WHERE clave LIKE 'pass_%' AND valor = ?");
    if (!$stmt) {
        throw new Exception("Error preparando consulta de claves: " . $conn->error);
    }
    
    $stmt->bind_param('s', $clave);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Clave incorrecta. Contacte al administrador.']);
        $stmt->close();
        $conn->close();
        exit;
    }

    // Obtener la clave específica que se usó
    $claveData = $result->fetch_assoc();
    $claveUsada = $claveData['clave'];
    $stmt->close();

    // Obtener fecha y hora actual
    $fechaHora = date('d/m/Y H:i:s');

    // Crear la nota automática con fecha, hora, clave y razón
    $notaCompleta = "Eliminado el {$fechaHora} con la clave {$claveUsada}. Razón: " . trim($razon);

    // Verificar que la compra existe antes de actualizarla
    $stmtCheck = $conn->prepare("SELECT id FROM compras WHERE id = ?");
    $stmtCheck->bind_param('i', $id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    
    if ($resultCheck->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'La compra no existe']);
        $stmtCheck->close();
        $conn->close();
        exit;
    }
    $stmtCheck->close();

    // Cambiar estado a 'eliminado' y guardar la nota completa en liquidacion_nota
    $stmtUpdate = $conn->prepare("UPDATE compras SET estado = 'eliminado', liquidacion_nota = ? WHERE id = ?");
    if (!$stmtUpdate) {
        throw new Exception("Error preparando consulta de actualización: " . $conn->error);
    }
    
    $stmtUpdate->bind_param('si', $notaCompleta, $id);
    
    if ($stmtUpdate->execute()) {
        echo json_encode(['success' => true, 'message' => 'Compra eliminada correctamente']);
    } else {
        throw new Exception("Error ejecutando actualización: " . $stmtUpdate->error);
    }
    
    $stmtUpdate->close();

} catch (Exception $e) {
    error_log("Error en eliminar_compra.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}

$conn->close();
?>