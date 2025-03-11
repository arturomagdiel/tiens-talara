<!DOCTYPE html>
<html>
<head>
  <title>Ingresar Producto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
  <div class="container"> 
    <h1 class="text-center mb-4">Ingresar nuevo producto</h1> 
    <form action="procesar_producto.php" method="post" enctype="multipart/form-data">
      <div class="row"> 
        <div class="col-md-6 mb-3"> 
          <label for="codigo" class="form-label">Código:</label>
          <input type="text" class="form-control" id="codigo" name="codigo" required>
        </div>
        <div class="col-md-6 mb-3"> 
          <label for="nombre" class="form-label">Nombre:</label>
          <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
      </div>

      <div class="mb-3"> 
        <label for="imagen" class="form-label">Imagen:</label>
        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
      </div>

      <div class="row"> 
        <div class="col-md-6 mb-3">
          <label for="precio_publico" class="form-label">Precio Público:</label>
          <input type="number" class="form-control" id="precio_publico" name="precio_publico" step="0.01" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="pv_publico" class="form-label">PV Público:</label>
          <input type="number" class="form-control" id="pv_publico" name="pv_publico" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="precio_afiliado" class="form-label">Precio Afiliado:</label>
          <input type="number" class="form-control" id="precio_afiliado" name="precio_afiliado" step="0.01" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="pv_afiliado" class="form-label">PV Afiliado:</label>
          <input type="number" class="form-control" id="pv_afiliado" name="pv_afiliado" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="precio_junior" class="form-label">Precio Junior:</label>
          <input type="number" class="form-control" id="precio_junior" name="precio_junior" step="0.01" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="pv_junior" class="form-label">PV Junior:</label>
          <input type="number" class="form-control" id="pv_junior" name="pv_junior" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="precio_senior" class="form-label">Precio Senior:</label>
          <input type="number" class="form-control" id="precio_senior" name="precio_senior" step="0.01" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="pv_senior" class="form-label">PV Senior:</label>
          <input type="number" class="form-control" id="pv_senior" name="pv_senior" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="precio_master" class="form-label">Precio Master:</label>
          <input type="number" class="form-control" id="precio_master" name="precio_master" step="0.01" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="pv_master" class="form-label">PV Master:</label>
          <input type="number" class="form-control" id="pv_master" name="pv_master" required>
        </div>
      </div>

      <div class="d-grid gap-2"> 
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>