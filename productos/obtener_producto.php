<?php
// Proteger endpoint con autenticación
require_once '../shared/auth.php';
requireAuth();

include '../shared/conexion.php';

$id = intval($_GET['id']);
$sql = "SELECT * FROM productos WHERE id = $id LIMIT 1";
$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    // Asegura que los datos estén en UTF-8
    array_walk_recursive($row, function(&$item) {
        $item = mb_convert_encoding($item, 'UTF-8', 'auto');
    });
    echo json_encode($row, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([]);
}
$conn->close();
?>