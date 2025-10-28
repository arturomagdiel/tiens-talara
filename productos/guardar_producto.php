<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
  $rutaDestino = __DIR__ . '/../uploads/' . $nombreImagen; // Ruta absoluta
  $rutaBD = '../uploads/' . $nombreImagen; // Ruta relativa para la BD
  if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
    $imagen = $rutaBD;
  }
}

// Si no se proporciona una imagen al crear un producto nuevo, usar la imagen predeterminada
if (!$id && !$imagen) {
  $imagen = '../uploads/tiens-logo-verde.jpg';
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
    $stmt->bind_param("ssddssii", $codigo, $nombre, $precio_publico, $precio_afiliado, $pv_afiliado, $imagen, $activo, $id);
  } else {
    $sql = "UPDATE productos SET codigo = ?, nombre = ?, precio_publico = ?, precio_afiliado = ?, pv_afiliado = ?, activo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
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