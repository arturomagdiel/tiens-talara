<?php
        // Conexión a la base de datos
        $servername = "localhost";
        $username = "tienslima_shopu";
        $password = "Mar11ine!shop";
        $dbname = "tienslima_shop";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}

// Obtener el ID del producto a editar
$id = $_GET["id"];

// Consulta SQL para obtener los datos del producto
$sql = "SELECT * FROM productos WHERE id = $id";
$result = $conn->query($sql);

// Mostrar el formulario de edición
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  ?>
  <!DOCTYPE html>
  <html>
  <head>
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
      .img-thumbnail {
        max-width: 100px;
        max-height: 100px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h1>Editar Producto</h1>
      <form action="actualizar_producto.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
        <div class="mb-3">
          <label for="codigo" class="form-label">Código:</label>
          <input type="text" class="form-control" id="codigo" name="codigo" value="<?php echo $row["codigo"]; ?>" required>
        </div>
        <div class="mb-3">
          <label for="nombre" class="form-label">Nombre:</label>
          <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $row["nombre"]; ?>" required>
        </div>
        <div class="mb-3">
          <label for="imagen" class="form-label">Imagen:</label>
          <img src="<?php echo $row["imagen"]; ?>" alt="<?php echo $row["nombre"]; ?>" class="img-thumbnail mb-2">
          <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
        </div>
        <div class="mb-3">
          <label for="precio_publico" class="form-label">Precio Público:</label>
          <input type="number" class="form-control" id="precio_publico" name="precio_publico" value="<?php echo $row["precio_publico"]; ?>" step="0.01" required>
        </div>
        <div class="mb-3">
          <label for="pv_publico" class="form-label">PV Público:</label>
          <input type="number" class="form-control" id="pv_publico" name="pv_publico" value="<?php echo $row["pv_publico"]; ?>" required>
        </div>
        <div class="mb-3">
          <label for="precio_afiliado" class="form-label">Precio Afiliado:</label>
          <input type="number" class="form-control" id="precio_afiliado" name="precio_afiliado" value="<?php echo $row["precio_afiliado"]; ?>" step="0.01" required>
        </div>
        <div class="mb-3">
          <label for="pv_afiliado" class="form-label">PV Afiliado:</label>
          <input type="number" class="form-control" id="pv_afiliado" name="pv_afiliado" value="<?php echo $row["pv_afiliado"]; ?>" required>
        </div>
        <div class="mb-3">
          <label for="precio_junior" class="form-label">Precio Junior:</label>
          <input type="number" class="form-control" id="precio_junior" name="precio_junior" value="<?php echo $row["precio_junior"]; ?>" step="0.01" required>
        </div>
        <div class="mb-3">
          <label for="pv_junior" class="form-label">PV Junior:</label>
          <input type="number" class="form-control" id="pv_junior" name="pv_junior" value="<?php echo $row["pv_junior"]; ?>" required>
        </div>
        <div class="mb-3">
          <label for="precio_senior" class="form-label">Precio Senior:</label>
          <input type="number" class="form-control" id="precio_senior" name="precio_senior" value="<?php echo $row["precio_senior"]; ?>" step="0.01" required>
        </div>
        <div class="mb-3">
          <label for="pv_senior" class="form-label">PV Senior:</label>
          <input type="number" class="form-control" id="pv_senior" name="pv_senior" value="<?php echo $row["pv_senior"]; ?>" required>
        </div>
        <div class="mb-3">
          <label for="precio_master" class="form-label">Precio Master:</label>
          <input type="number" class="form-control" id="precio_master" name="precio_master" value="<?php echo $row["precio_master"]; ?>" step="0.01" required>
        </div>
        <div class="mb-3">
          <label for="pv_master" class="form-label">PV Master:</label>
          <input type="number" class="form-control" id="pv_master" name="pv_master" value="<?php echo $row["pv_master"]; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
      </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
  </html>
  <?php
} else {
  echo "No se encontró el producto.";
}

$conn->close();
?>