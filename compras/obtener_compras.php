<?php
// filepath: c:\Users\artur\Documents\GitHub\tiens-talara\compras\obtener_compras.php

// Proteger endpoint con autenticación
require_once '../shared/auth.php';
requireAuth();

// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configurar la zona horaria
date_default_timezone_set('America/Lima');

// Incluir la conexión a la base de datos
include '../shared/conexion.php';

// Obtener los parámetros enviados por GET
$fecha_inicio = $_GET['fecha_inicio'] ?? null;
$fecha_fin = $_GET['fecha_fin'] ?? null;

// Validar que los parámetros sean válidos
if (!$fecha_inicio || !$fecha_fin) {
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit;
}

// Ajustar las fechas para incluir todo el día
$fecha_inicio .= ' 00:00:00';
$fecha_fin .= ' 23:59:59';

// Preparar la consulta SQL con los nombres de columnas corregidos
$query = "
    SELECT p.id AS persona_id, CONCAT(UPPER(p.nombre), ' ', p.apellido) AS nombre_completo, 
           c.ID AS compra_id, c.fecha_compra, c.productos_codigo, prod.nombre AS producto_nombre, 
           c.productos_pv AS producto_pv, c.liquidacion_nota AS compra_notas, 
           c.productos_precio, c.estado
    FROM personas p
    INNER JOIN compras c ON p.id = c.personas_id
    INNER JOIN productos prod ON c.productos_codigo = prod.codigo
    WHERE c.fecha_compra >= ? AND c.fecha_compra <= ?
    ORDER BY p.nombre ASC, c.fecha_compra ASC
";

$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['error' => 'Error al preparar la consulta']);
    exit;
}

// Vincular los parámetros a la consulta
$stmt->bind_param("ss", $fecha_inicio, $fecha_fin);

// Ejecutar la consulta
if (!$stmt->execute()) {
    echo json_encode(['error' => 'Error al ejecutar la consulta']);
    exit;
}

// Obtener los resultados
$result = $stmt->get_result();
$personas = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $persona_id = $row['persona_id'];
        if (!isset($personas[$persona_id])) {
            $personas[$persona_id] = [
                'nombre_completo' => $row['nombre_completo'],
                'compras' => []
            ];
        }
        $personas[$persona_id]['compras'][] = [
            'compra_id' => $row['compra_id'],
            'fecha_compra' => $row['fecha_compra'],
            'productos_codigo' => $row['productos_codigo'],
            'producto_nombre' => $row['producto_nombre'],
            'producto_pv' => $row['producto_pv'],
            'productos_precio' => $row['productos_precio'],
            'compra_notas' => nl2br($row['compra_notas']), // Convertir saltos de línea a <br>
            'estado' => $row['estado']
        ];
    }
}

// Devolver los resultados como JSON
echo json_encode(array_values($personas));
?>