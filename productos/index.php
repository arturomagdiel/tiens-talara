<?php
// Proteger página con autenticación
require_once __DIR__ . '/../shared/auth.php';
requireAuth();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Lista de Productos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .img-thumbnail {
      max-width: 35%; /* Cambia el tamaño al 60% */
      max-height: 35%; /* Cambia el tamaño al 60% */
    }
    .color5 {
    background-color: #DCE6F1 !important;
  }
  .color5h {
    background-color: #366092 !important;
    color: #FFFFFF !important;
  }

    .color8h {
      background-color: #FFC000 !important;
      color: #FFFFFF !important;
    }
    .color8 {
      background-color: #CCC0DA !important;
    }

    .color15 {
        background-color: #CCFF99 !important;
  }
  .color15h {
        background-color: #F79646 !important;
        color: #FFFFFF !important;
  }

.headernegro {
  background-color:rgb(0, 0, 0) !important;
        color: #FFFFFF !important;
}
  </style>

</head>
<body>
<?php include '../shared/header.php'; ?>
  <div class="container">
    <h2>Lista de Productos</h2>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalProducto" id="btnAgregarProducto">
      Agregar Producto
    </button>

    <table class="table table-bordered ">
      <thead>
        <tr>
          <th rowspan="2" class="headernegro align-middle">Imagen</th> <!-- Combina las celdas de "Imagen" -->
          <th rowspan="2" class="headernegro align-middle">Código</th>
          <th rowspan="2" class="headernegro align-middle">Nombre</th>
          <th rowspan="2" class="text-center headernegro align-middle">PV</th>
          <th rowspan="2" class="text-end headernegro align-middle">Precio Unit</th>
          <th class="text-center color5h align-middle" colspan="2">Dcto 5%</th>
          <th class="text-center color8h align-middle" colspan="2">Dcto 8%</th>
          <th class="text-center color15h align-middle" colspan="2">Dcto 15%</th>
          <th rowspan="2" class="text-end headernegro align-middle">Precio Público</th>
          <th rowspan="2" class="headernegro align-middle">Acciones</th>
        </tr>
        <tr>
          <th class="text-center color5h align-middle">PV</th>
          <th class="text-end color5h align-middle">Precio</th>
          <th class="text-center color8h align-middle">PV</th>
          <th class="text-end color8h align-middle">Precio</th>
          <th class="text-center color15h align-middle">PV</th>
          <th class="text-end color15h align-middle">Precio</th>
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

              <!-- Código y Nombre en una sola fila -->
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="codigo" class="form-label">Código</label>
                    <input type="text" class="form-control" id="codigo" name="codigo" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                  </div>
                </div>
              </div>

              <!-- Precio Público, Precio Afiliado y PV Afiliado en una sola fila -->
              <div class="row">
                <div class="col-md-4">
                  <div class="mb-3">
                    <label for="precio_publico" class="form-label">Precio Público</label>
                    <input type="number" class="form-control" id="precio_publico" name="precio_publico" step="0.01" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label for="precio_afiliado" class="form-label">Precio Afiliado</label>
                    <input type="number" class="form-control" id="precio_afiliado" name="precio_afiliado" step="0.01" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label for="pv_afiliado" class="form-label">PV Afiliado</label>
                    <input type="number" class="form-control" id="pv_afiliado" name="pv_afiliado" step="0.01" required>
                  </div>
                </div>
              </div>

              <!-- Checkbox para el estado activo -->
              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="activo" name="activo">
                <label class="form-check-label" for="activo">Activo</label>
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

    <!-- Modal de confirmación -->
    <div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalConfirmacionLabel">Confirmar Eliminación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            ¿Estás seguro de que deseas eliminar este producto?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de mensajes -->
    <div class="modal fade" id="modalMensaje" tabindex="-1" aria-labelledby="modalMensajeLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-body text-center">
            <span id="mensajeTexto"></span>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      var idProductoAEliminar = null; // Variable para almacenar el ID del producto a eliminar

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
        var formData = new FormData($('#formProducto')[0]); // Crear un FormData para manejar archivos y datos
        formData.delete('activo');
        formData.append('activo', $('#activo').is(':checked') ? 1 : 0); // Agregar el estado activo al FormData
        

        $.ajax({
          url: 'guardar_producto.php', // Archivo PHP para guardar los datos
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            if (response.trim() === "Producto guardado correctamente.") {
              $('#modalProducto').modal('hide'); // Cerrar el modal
              mostrarMensaje(response); // Mostrar mensaje de éxito
              cargarProductos(); // Recargar la tabla
            } else {
              mostrarMensaje(response); // Mostrar mensaje de error
            }
          },
          error: function() {
            mostrarMensaje('Error al guardar el producto.'); // Mostrar mensaje de error
          }
        });
      });

      // Limpiar el formulario al agregar un producto
      $('#btnAgregarProducto').click(function() {
        $('#modalProductoLabel').text('Agregar Producto');
        $('#formProducto')[0].reset();
        $('#productoId').val('');
        $('#imagenPreview').hide().attr('src', '');
      });

      // Cargar datos al editar un producto
      $(document).on('click', '.btnEditar', function() {
        var idProducto = $(this).data('id');
        $('#modalProductoLabel').text('Editar Producto');

        $.ajax({
          url: 'obtener_producto.php',
          type: 'GET',
          data: { id: idProducto },
          success: function(response) {
            var producto = JSON.parse(response);
            $('#productoId').val(producto.id);
            $('#codigo').val(producto.codigo);
            $('#nombre').val(producto.nombre);
            $('#precio_publico').val(producto.precio_publico);
            $('#precio_afiliado').val(producto.precio_afiliado);
            $('#pv_afiliado').val(producto.pv_afiliado);

            // Cargar el estado activo
            $('#activo').prop('checked', producto.activo == 1);

            // Mostrar imagen o placeholder
            if (producto.imagen && producto.imagen.trim() !== "") {
              $('#imagenPreview').attr('src', producto.imagen).show();
            } else {
              $('#imagenPreview').attr('src', '../uploads/tiens-logo-verde.jpg').show();
            }

            $('#modalProducto').modal('show');
          },
          error: function() {
            alert('Error al obtener los datos del producto.');
          }
        });
      });

      // Mostrar vista previa de la imagen
      $('#imagen').change(function(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e) {
            $('#imagenPreview').attr('src', e.target.result).show();
          };
          reader.readAsDataURL(input.files[0]);
        } else {
          $('#imagenPreview').hide();
        }
      });

      // Mostrar el modal de confirmación al hacer clic en el botón Eliminar
      $(document).on('click', '.btnEliminar', function() {
        idProductoAEliminar = $(this).data('id'); // Obtener el ID del producto
        $('#modalConfirmacion').modal('show'); // Mostrar el modal de confirmación
      });

      // Confirmar la eliminación del producto
      $('#btnConfirmarEliminar').click(function() {
        if (idProductoAEliminar) {
          $.ajax({
            url: 'eliminar_producto.php', // Archivo PHP para cambiar el estado del producto
            type: 'POST',
            data: { id: idProductoAEliminar },
            success: function(response) {
              $('#modalConfirmacion').modal('hide'); // Cerrar el modal de confirmación
              mostrarMensaje(response); // Mostrar el mensaje en el modal
              cargarProductos(); // Recargar la lista de productos
            },
            error: function() {
              mostrarMensaje('Error al eliminar el producto.'); // Mostrar mensaje de error
            }
          });
        }
      });

      // Función para mostrar el modal de mensajes
      function mostrarMensaje(mensaje) {
        $('#mensajeTexto').text(mensaje); // Establecer el texto del mensaje
        $('#modalMensaje').modal('show'); // Mostrar el modal de mensajes

        // Ocultar el modal automáticamente después de 1 segundo
        setTimeout(function() {
          $('#modalMensaje').modal('hide');
        }, 1000);
      }
    });
  </script>
</body>
</html>