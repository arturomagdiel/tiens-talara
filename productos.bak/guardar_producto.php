<?php
include '../shared/conexion.php';

$id = $_POST['id'];
$codigo = $_POST['codigo'];
$nombre = $_POST['nombre'];
$precio_publico = $_POST['precio_publico'];
$pv_publico = $_POST['pv_publico'];
$precio_afiliado = $_POST['precio_afiliado'];
$pv_afiliado = $_POST['pv_afiliado'];
$precio_junior = $_POST['precio_junior'];
$pv_junior = $_POST['pv_junior'];
$precio_senior = $_POST['precio_senior'];
$pv_senior = $_POST['pv_senior'];
$precio_master = $_POST['precio_master'];
$pv_master = $_POST['pv_master'];
$imagen = null;

// Manejar la subida de la imagen
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
  $nombreImagen = uniqid() . '_' . $_FILES['imagen']['name'];
  $rutaDestino = '../uploads/' . $nombreImagen;
  if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
    $imagen = '../uploads/' . $nombreImagen; // Guardar la ruta completa en la base de datos
  }
}

if ($id) {
  // Editar producto
  if ($imagen) {
    $sql = "UPDATE productos SET codigo = ?, nombre = ?, precio_publico = ?, pv_publico = ?, precio_afiliado = ?, pv_afiliado = ?, precio_junior = ?, pv_junior = ?, precio_senior = ?, pv_senior = ?, precio_master = ?, pv_master = ?, imagen = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddddddddddsi", $codigo, $nombre, $precio_publico, $pv_publico, $precio_afiliado, $pv_afiliado, $precio_junior, $pv_junior, $precio_senior, $pv_senior, $precio_master, $pv_master, $imagen, $id);
  } else {
    $sql = "UPDATE productos SET codigo = ?, nombre = ?, precio_publico = ?, pv_publico = ?, precio_afiliado = ?, pv_afiliado = ?, precio_junior = ?, pv_junior = ?, precio_senior = ?, pv_senior = ?, precio_master = ?, pv_master = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddddddddddi", $codigo, $nombre, $precio_publico, $pv_publico, $precio_afiliado, $pv_afiliado, $precio_junior, $pv_junior, $precio_senior, $pv_senior, $precio_master, $pv_master, $id);
  }
} else {
  // Agregar producto
  $sql = "INSERT INTO productos (codigo, nombre, precio_publico, pv_publico, precio_afiliado, pv_afiliado, precio_junior, pv_junior, precio_senior, pv_senior, precio_master, pv_master, imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssdddddddddds", $codigo, $nombre, $precio_publico, $pv_publico, $precio_afiliado, $pv_afiliado, $precio_junior, $pv_junior, $precio_senior, $pv_senior, $precio_master, $pv_master, $imagen);
}

if ($stmt->execute()) {
  echo "Producto guardado correctamente.";
} else {
  echo "Error al guardar el producto.";
}

$stmt->close();
$conn->close();
?>