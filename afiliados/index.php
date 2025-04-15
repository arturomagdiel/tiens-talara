<?php

// Conexión a la base de datos
include '../shared/conexion.php';

?>

<!DOCTYPE html>
<html>

<head>
  <title>Lista de Personas con DataTables</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    /* Estilos para el popup (modal) */
    .modal-dialog {
      max-width: 800px;
    }
  </style>
</head>

<body>

<?php include '../shared/header.php'; ?>

  <div class="container">

    <div class="table-responsive">
      <table id="tablaPersonas" class="table table-striped">
        <thead class="thead-dark">
          <tr>
            <th class="d-none">ID</th>
            <th>Código</th>
            <th>Descuento</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Teléfono</th>
            <th>RUC</th>
            <th>Patrocinador</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    <div class="container">
        <!-- Botón Agregar Afiliado -->
<button class="btn btn-success btn-sm me-2 d-flex align-items-center justify-content-center m-2" 
    id="btnNuevaPersona" 
    title="Agregar Afiliado">
    <i class="bi bi-plus"></i> Agregar Afiliado
</button>
    </div>

    <div id="personaPopup" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Editar/Crear Persona</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="personaForm">
              <input type="hidden" id="personaId" name="id">

              <div class="form-group row">
                <label for="nombre" class="col-sm-2 col-form-label">Nombre:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="nombre" name="nombre">
                </div>
              </div>

              <div class="form-group row">
                <label for="apellido" class="col-sm-2 col-form-label">Apellido:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="apellido" name="apellido">
                </div>
              </div>

              <div class="form-group row">
                <label for="codigo" class="col-sm-2 col-form-label">Código:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="codigo" name="codigo">
                </div>
              </div>

              <div class="form-group row">
                <label for="telefono" class="col-sm-2 col-form-label">Teléfono:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="telefono" name="telefono">
                </div>
              </div>

              <div class="form-group row">
                <label for="ruc" class="col-sm-2 col-form-label">RUC:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="ruc" name="ruc">
                </div>
              </div>

              <div class="form-group row">
                <label for="patrocinador" class="col-sm-2 col-form-label">Patrocinador:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="patrocinador" name="patrocinador">
                </div>
              </div>

              <div class="form-group row">
                <label for="descuento" class="col-sm-2 col-form-label">Descuento:</label>
                <div class="col-sm-10">
                  <select class="form-control" id="descuento" name="descuento">
                    <option value="0">0%</option>
                    <option value="5">5%</option>
                    <option value="8">8%</option>
                    <option value="15">15%</option>
                  </select>
                </div>
              </div>

            </form>
          </div>
          <div class="modal-footer">
            <button type="submit" form="personaForm" class="btn btn-primary">Guardar</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-danger" data-id="" id="btnModalEliminar">Eliminar</button>
          </div>
        </div>
      </div>
    </div>

    <div id="confirmarEliminarPopup" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirmar Eliminación</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>¿Estás seguro de que quieres eliminar esta persona?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          </div>
        </div>

      </div>

    </div>

    <div id="mensajeExitoPopup" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Éxito</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>¡Los datos se han guardado correctamente!</p>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="index.js"></script>

</body>

</html>