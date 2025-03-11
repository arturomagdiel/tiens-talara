<!DOCTYPE html>
<html>
<head>
  <title>Lista de Productos</title>
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
    <h2>Lista de Productos</h2> 
    <a href="https://tienslima.com/compra/formulario_productos.php">AGREGAR NUEVO</a> 
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
      <tbody>
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

        // Consulta SQL para obtener los productos
        $sql = "SELECT * FROM productos";
        $result = $conn->query($sql);

        // Mostrar los productos en la tabla
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><img src='" . $row["imagen"] . "' alt='" . $row["nombre"] . "' class='img-thumbnail'></td>";
            echo "<td>" . $row["codigo"] . "</td>";
            echo "<td>" . $row["nombre"] . "</td>";
            echo "<td>" . $row["precio_publico"] . "</td>";
            echo "<td>" . $row["pv_publico"] . "</td>";
            echo "<td>" . $row["precio_afiliado"] . "</td>";
            echo "<td>" . $row["pv_afiliado"] . "</td>";
            echo "<td>" . $row["precio_junior"] . "</td>";
            echo "<td>" . $row["pv_junior"] . "</td>";
            echo "<td>" . $row["precio_senior"] . "</td>";
            echo "<td>" . $row["pv_senior"] . "</td>";
            echo "<td>" . $row["precio_master"] . "</td>";
            echo "<td>" . $row["pv_master"] . "</td>";
            echo "<td>";
            echo "<a href='editar_producto.php?id=" . $row["id"] . "' class='btn btn-primary btn-sm'>Editar</a> ";
            echo "<button type='button' class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#modalEliminar' data-id='" . $row["id"] . "'>Eliminar</button>"; 
            echo "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='14'>No se encontraron productos.</td></tr>";
        }

        $conn->close();
        ?>
      </tbody>
    </table>

    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEliminarLabel">Confirmar eliminación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            ¿Estás seguro de que quieres eliminar este producto?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    var idProductoEliminar; // Variable para almacenar el ID del producto a eliminar

    $('#modalEliminar').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget); // Botón que activó el modal
      idProductoEliminar = button.data('id'); // Extraer la información del ID del data-id
    });

    $('#btnConfirmarEliminar').click(function() {
      // Aquí va el código AJAX para eliminar el producto de la base de datos
      $.ajax({
        url: 'eliminar_producto.php', // Reemplaza con la URL de tu script PHP para eliminar
        type: 'POST',
        data: { id: idProductoEliminar },
        success: function(response) {
          // Cerrar el modal
          $('#modalEliminar').modal('hide');
          // Actualizar la tabla de productos (puedes recargar la página o usar AJAX para actualizar la tabla)
          location.reload(); 
        },
        error: function() {
          alert("Error al eliminar el producto.");
        }
      });
    });
  </script>
</body>
</html>