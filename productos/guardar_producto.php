<?php
include '../shared/conexion.php';

$id = isset($_POST['id']) ? $_POST['id'] : null;
$codigo = isset($_POST['codigo']) ? $_POST['codigo'] : null;
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
$precio_publico = isset($_POST['precio_publico']) ? $_POST['precio_publico'] : null;
$precio_afiliado = isset($_POST['precio_afiliado']) ? $_POST['precio_afiliado'] : null;
$pv_afiliado = isset($_POST['pv_afiliado']) ? $_POST['pv_afiliado'] : null;

$activo = isset($_POST['activo']) ? $_POST['activo'] : 1; // Por defecto, activo

$imagen = null;

// Manejar la subida de la imagen
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
  $nombreImagen = uniqid() . '_' . $_FILES['imagen']['name'];
  $rutaDestino = '../uploads/' . $nombreImagen;
  if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
    $imagen = $rutaDestino; // Guardar la ruta completa de lsa imagen
  }
}

// Si no se proporciona una imagen al crear un producto nuevo, usar la imagen predeterminada
if (!$id && !$imagen) {
  $imagen = '../uploads/tiens-logo-verde.jpg'; // Ruta completa de la imagen predeterminada
}

// Verificar si el código ya existe
$sqlVerificar = "SELECT id FROM productos WHERE codigo = ? AND id != ?";
$stmtVerificar = $conn->prepare($sqlVerificar);
$stmtVerificar->bind_param("si", $codigo, $id);
$stmtVerificar->execute();
$stmtVerificar->store_result();

if ($stmtVerificar->num_rows > 0) {
  echo "El código del producto ya existe.";
  $stmtVerificar->close();
  $conn->close();
  exit;
}
$stmtVerificar->close();

if ($id) {
  // Editar producto
  if ($imagen) {
    $sql = "UPDATE productos SET codigo = ?, nombre = ?, precio_publico = ?, precio_afiliado = ?, pv_afiliado = ?, imagen = ?, activo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    error_log("Consulta SQL: " . $sql);
    error_log("Consulta SQL con valores: UPDATE productos SET codigo = '$codigo', nombre = '$nombre', precio_publico = $precio_publico, precio_afiliado = $precio_afiliado, pv_afiliado = $pv_afiliado, activo = $activo WHERE id = $id");
    error_log("Valor final de PV Afiliado antes de la consulta: " . $pv_afiliado);
    $stmt->bind_param("ssddssi", $codigo, $nombre, $precio_publico, $precio_afiliado, $pv_afiliado, $imagen, $activo);
  } else {
    $sql = "UPDATE productos SET codigo = ?, nombre = ?, precio_publico = ?, precio_afiliado = ?, pv_afiliado = ?, activo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    error_log("Consulta SQL: " . $sql);
    error_log("Consulta SQL con valores: UPDATE productos SET codigo = '$codigo', nombre = '$nombre', precio_publico = $precio_publico, precio_afiliado = $precio_afiliado, pv_afiliado = $pv_afiliado, activo = $activo WHERE id = $id");
    error_log("Valor final de PV Afiliado antes de la consulta: " . $pv_afiliado);
    $stmt->bind_param("ssddiii", $codigo, $nombre, $precio_publico, $precio_afiliado, $pv_afiliado, $activo, $id);
  }
} else {
  // Agregar producto
  $sql = "INSERT INTO productos (codigo, nombre, precio_publico, precio_afiliado, pv_afiliado, imagen, activo) VALUES (?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssddssi", $codigo, $nombre, $precio_publico, $precio_afiliado, $pv_afiliado, $imagen, $activo);
}

if ($stmt->execute()) {
  echo "Producto guardado correctamente.";
} else {
  echo "Error al guardar el producto.";
}

$stmt->close();
$conn->close();
?>