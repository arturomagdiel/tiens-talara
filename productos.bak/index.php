<!DOCTYPE html>
<html>
<head>
  <title>Lista de Productos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .img-thumbnail {
      max-width: 100px;
      max-height: 100px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Lista de Productos</h2>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalProducto" id="btnAgregarProducto">
      Agregar Producto
    </button>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>Imagen</th>
          <th>Código</th>
          <th>Nombre</th>
          <th>Precio Público</th>
          <th>PV Público</th>
          <th>Precio Afiliado</th>
          <th>PV Afiliado</th>
          <th>Precio Junior</th>
          <th>PV Junior</th>
          <th>Precio Senior</th>
          <th>PV Senior</th>
          <th>Precio Master</th>
          <th>PV Master</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="tablaProductos">
        <!-- Los datos de los productos se cargarán aquí mediante AJAX -->
      </tbody>
    </table>

    <!-- Modal para agregar/editar productos -->
    <div class="modal fade" id="modalProducto" tabindex="-1" aria-labelledby="modalProductoLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalProductoLabel">Agregar/Editar Producto</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="formProducto" enctype="multipart/form-data">
              <input type="hidden" id="productoId" name="id">
              
              <!-- Código y Nombre -->
              <div class="mb-3">
                <label for="codigo" class="form-label">Código</label>
                <input type="text" class="form-control" id="codigo" name="codigo" required>
              </div>
              <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
              </div>

              <!-- Precios y PV -->
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="precio_publico" class="form-label">Precio Público</label>
                    <input type="number" class="form-control" id="precio_publico" name="precio_publico" step="0.01" required>
                  </div>
                  <div class="mb-3">
                    <label for="pv_publico" class="form-label">PV Público</label>
                    <input type="number" class="form-control" id="pv_publico" name="pv_publico" step="0.01" required>
                  </div>
                  <div class="mb-3">
                    <label for="precio_afiliado" class="form-label">Precio Afiliado</label>
                    <input type="number" class="form-control" id="precio_afiliado" name="precio_afiliado" step="0.01" required>
                  </div>
                  <div class="mb-3">
                    <label for="pv_afiliado" class="form-label">PV Afiliado</label>
                    <input type="number" class="form-control" id="pv_afiliado" name="pv_afiliado" step="0.01" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="precio_junior" class="form-label">Precio Junior</label>
                    <input type="number" class="form-control" id="precio_junior" name="precio_junior" step="0.01" required>
                  </div>
                  <div class="mb-3">
                    <label for="pv_junior" class="form-label">PV Junior</label>
                    <input type="number" class="form-control" id="pv_junior" name="pv_junior" step="0.01" required>
                  </div>
                  <div class="mb-3">
                    <label for="precio_senior" class="form-label">Precio Senior</label>
                    <input type="number" class="form-control" id="precio_senior" name="precio_senior" step="0.01" required>
                  </div>
                  <div class="mb-3">
                    <label for="pv_senior" class="form-label">PV Senior</label>
                    <input type="number" class="form-control" id="pv_senior" name="pv_senior" step="0.01" required>
                  </div>
                  <div class="mb-3">
                    <label for="precio_master" class="form-label">Precio Master</label>
                    <input type="number" class="form-control" id="precio_master" name="precio_master" step="0.01" required>
                  </div>
                  <div class="mb-3">
                    <label for="pv_master" class="form-label">PV Master</label>
                    <input type="number" class="form-control" id="pv_master" name="pv_master" step="0.01" required>
                  </div>
                </div>
              </div>

              <!-- Imagen -->
              <div class="mb-3">
                <label for="imagen" class="form-label">Imagen</label>
                <input type="file" class="form-control" id="imagen" name="imagen">
                <img id="imagenPreview" src="" alt="Vista previa de la imagen" class="img-thumbnail mt-2" style="max-width: 150px; display: none;">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="btnGuardarProducto">Guardar</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      // Cargar productos en la tabla
      function cargarProductos() {
        $.ajax({
          url: 'obtener_productos.php', // Archivo PHP para obtener todos los productos
          type: 'GET',
          success: function(response) {
            $('#tablaProductos').html(response); // Insertar los datos en la tabla
          },
          error: function() {
            alert('Error al cargar los productos.');
          }
        });
      }

      cargarProductos(); // Llamar a la función al cargar la página

      // Guardar producto (Agregar o Editar)
      $('#btnGuardarProducto').click(function() {
        var formData = new FormData($('#formProducto')[0]); // Crear un FormData para manejar archivos

        $.ajax({
          url: 'guardar_producto.php', // Archivo PHP para guardar los datos
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            $('#modalProducto').modal('hide'); // Cerrar el modal
            cargarProductos(); // Recargar la tabla
          },
          error: function() {
            alert('Error al guardar el producto.');
          }
        });
      });

      $('#btnAgregarProducto').click(function() {
        $('#modalProductoLabel').text('Agregar Producto');
        $('#formProducto')[0].reset(); // Limpiar todos los campos del formulario
        $('#productoId').val(''); // Limpiar el campo oculto del ID
        $('#imagenPreview').hide().attr('src', ''); // Ocultar la vista previa de la imagen
      });

      $(document).on('click', '.btnEditar', function() {
        var idProducto = $(this).data('id'); // Obtener el ID del producto
        $('#modalProductoLabel').text('Editar Producto');

        // Realizar una solicitud AJAX para obtener los datos del producto
        $.ajax({
          url: 'obtener_producto.php', // Archivo PHP para obtener los datos del producto
          type: 'GET',
          data: { id: idProducto },
          success: function(response) {
            var producto = JSON.parse(response);
            $('#productoId').val(producto.id);
            $('#codigo').val(producto.codigo);
            $('#nombre').val(producto.nombre);
            $('#precio_publico').val(producto.precio_publico);
            $('#pv_publico').val(producto.pv_publico);
            $('#precio_afiliado').val(producto.precio_afiliado);
            $('#pv_afiliado').val(producto.pv_afiliado);
            $('#precio_junior').val(producto.precio_junior);
            $('#pv_junior').val(producto.pv_junior);
            $('#precio_senior').val(producto.precio_senior);
            $('#pv_senior').val(producto.pv_senior);
            $('#precio_master').val(producto.precio_master);
            $('#pv_master').val(producto.pv_master);

            // Mostrar la imagen en la vista previa
            if (producto.imagen) {
              $('#imagenPreview').attr('src', producto.imagen).show();
            } else {
              $('#imagenPreview').hide();
            }

            // Mostrar el modal
            $('#modalProducto').modal('show');
          },
          error: function() {
            alert('Error al obtener los datos del producto.');
          }
        });
      });

      $('#imagen').change(function(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e) {
            $('#imagenPreview').attr('src', e.target.result).show(); // Mostrar la imagen cargada
          };
          reader.readAsDataURL(input.files[0]); // Leer la imagen como URL
        } else {
          $('#imagenPreview').hide(); // Ocultar la vista previa si no hay imagen
        }
      });
    });
  </script>
</body>
</html>