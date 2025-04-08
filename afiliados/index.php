<?php

// Conexión a la base de datos
include '../shared/conexion.php';

?>

<!DOCTYPE html>
<html>

<head>
  <title>Lista de Personas con DataTables</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    /* Estilos para el popup (modal) */
    .modal-dialog {
      max-width: 800px;
    }
  </style>
</head>

<body>

  <div class="container">

    <h1>Lista de Personas</h1>

    <button id="btnNuevaPersona" class="btn btn-success">
      <i class="fas fa-plus"></i> Crear nueva persona
    </button>

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

  <script>
    $(document).ready(function() {

      // Función para verificar si el código ya existe
      function verificarCodigo(codigo, id) {
        console.log("Verificando código:", codigo, "con ID:", id);
        return $.ajax({
          url: 'verificar_codigo.php',
          type: 'GET',
          data: {
            codigo: codigo,
            id: id
          },
          dataType: 'json',
          error: function(xhr, status, error) {
            console.error("Error en la verificación del código:", error);
          }
        });
      }

      // Inicializar DataTables
      $('#tablaPersonas').DataTable({
        "ajax": "obtener_personas.php",
        "columns": [{
            "data": "id",
            "visible": false
          }, // ID oculto
          {
            "data": "codigo",
            "render": function(data, type, row) {
              return '<a href="#" class="enlaceEditar" data-id="' + row.id + '">' + data.toUpperCase() + '</a>';
            }
          },
          {
            "data": "descuento"
          },
          {
            "data": "nombre",
            "render": function(data, type, row) {
              return data.toUpperCase();
            }
          },
          {
            "data": "apellido",
            "render": function(data, type, row) {
              return data.toUpperCase();
            }
          },
          {
            "data": "telefono"
          },
          {
            "data": "ruc"
          },
          {
            "data": "patrocinador",
            "render": function(data, type, row) {
              return data.toUpperCase();
            }
          },
          {
            "data": null,
            "render": function(data, type, row) {
              return '<td class="d-flex flex-column"> ' +
                '<button class="btn btn-primary btn-sm btnEditar" data-id="' + row.id + '"><i class="fas fa-edit"></i></button> ' +
                '<button class="btn btn-danger btn-sm btnEliminar" data-id="' + row.id + '"><i class="fas fa-trash"></i></button> ' +
                '</td>';
            }
          }
        ]
      });

      // Mostrar el popup al hacer clic en "Crear nueva persona"
      $("#btnNuevaPersona").click(function() {
        $("#btnModalEliminar").hide(); // Ocultar el botón Eliminar
        $("#personaPopup").modal("show");
        $("#personaForm")[0].reset();
        $("#personaId").val("");
      });

      // Mostrar el popup al hacer clic en el enlace del código (delegación de eventos)
      $(document).on("click", ".enlaceEditar", function(event) {
        event.preventDefault(); // Evitar que el enlace siga su comportamiento normal
        var personaId = $(this).data("id");
        console.log("Editando persona con ID:", personaId);

        // Realizar la llamada AJAX para obtener los datos de la persona
        $.ajax({
          url: "obtener_persona.php",
          type: "GET",
          data: {
            id: personaId
          },
          success: function(response) {
            console.log("Respuesta AJAX (obtener_persona.php):", response);
            // Parsear la respuesta JSON
            var persona = JSON.parse(response);

            // Rellenar el formulario con los datos de la persona
            $("#personaId").val(persona.id);
            $("#nombre").val(persona.nombre);
            $("#apellido").val(persona.apellido);
            $("#codigo").val(persona.codigo);
            $("#telefono").val(persona.telefono);
            $("#ruc").val(persona.ruc);
            $("#patrocinador").val(persona.patrocinador);
            $("#descuento").val(persona.descuento);

            // Actualizar el data-id del botón Eliminar en el modal
            $("#btnModalEliminar").data("id", persona.id);

            // Mostrar el botón Eliminar
            $("#btnModalEliminar").show();

            // Mostrar el modal
            $("#personaPopup").modal("show");
          },
          error: function(xhr, status, error) {
            console.error("Error al obtener los datos de la persona:", error);
          }
        });
      });

      // Mostrar el popup al hacer clic en "Editar" (delegación de eventos)
      $(document).on("click", ".btnEditar", function() {
        var personaId = $(this).data("id");
        console.log("ID de la persona a editar (botón):", personaId);

        // Realizar la llamada AJAX para obtener los datos de la persona
        $.ajax({
          url: "obtener_persona.php", // Archivo PHP que devuelve los datos de la persona
          type: "GET",
          data: { id: personaId },
          success: function(response) {
            console.log("Respuesta AJAX (obtener_persona.php):", response);
            // Parsear la respuesta JSON
            var persona = JSON.parse(response);

            // Rellenar el formulario con los datos de la persona
            $("#personaId").val(persona.id);
            $("#nombre").val(persona.nombre);
            $("#apellido").val(persona.apellido);
            $("#codigo").val(persona.codigo);
            $("#telefono").val(persona.telefono);
            $("#ruc").val(persona.ruc);
            $("#patrocinador").val(persona.patrocinador);
            $("#descuento").val(persona.descuento);

            // Actualizar el data-id del botón Eliminar en el modal
            $("#btnModalEliminar").data("id", persona.id);

            // Mostrar el botón Eliminar
            $("#btnModalEliminar").show();

            // Mostrar el modal
            $("#personaPopup").modal("show");
          },
          error: function(xhr, status, error) {
            console.error("Error al obtener los datos de la persona:", error);
          }
        });
      });

      // Cerrar los popups
      $("#personaPopup").on("hidden.bs.modal", function() {
        $("#personaForm")[0].reset();
      });
      $("#confirmarEliminarPopup").on("hidden.bs.modal", function() {
        $("#personaPopup").modal("show"); // Volver a mostrar el modal de edición
      });

      // Eliminar persona al hacer clic en "Eliminar" en la tabla (delegación de eventos)
      $(document).on("click", ".btnEliminar", function() {
        var personaId = $(this).data("id");
        console.log("ID de la persona a eliminar:", personaId);

        // Mostrar el modal de confirmación
        $("#btnModalEliminar").data("id", personaId); // Pasar el ID al botón del modal
        $("#confirmarEliminarPopup").modal("show");
      });


      // Mostrar el modal de confirmación al hacer clic en "Eliminar" en el modal de edición
      $("#btnModalEliminar").click(function() {
        $("#confirmarEliminarPopup").modal("show");
      });

      // Eliminar persona al hacer clic en "Eliminar" en el modal de confirmación
      $("#btnConfirmarEliminar").click(function() {
        var personaId = $("#btnModalEliminar").data("id");
        console.log("ID de la persona a eliminar (confirmación):", personaId);

        // Realizar la llamada AJAX para eliminar la persona
        $.ajax({
          url: "eliminar_persona.php",
          type: "POST",
          data: {
            id: personaId
          },
          success: function(response) {
            console.log("Respuesta del servidor (eliminar_persona.php):", response);
            // Cerrar el modal de confirmación
            $("#confirmarEliminarPopup").modal("hide");
            // Recargar la página o actualizar la tabla
            location.reload();
          },
          error: function(xhr, status, error) {
            console.error("Error al eliminar la persona:", error);
          }
        });
      });

      // Enviar el formulario (AJAX)
      $("#personaForm").submit(function(event) {
        event.preventDefault();

        // Obtener el código y el ID del formulario
        var codigo = $("#codigo").val();
        var id = $("#personaId").val();
        console.log("Enviando formulario con ID:", id); // Depuración

        // Verificar si se está creando un nuevo registro
        if (id === "") {
          // Verificar si el código ya existe (solo al crear)
          verificarCodigo(codigo, id).done(function(response) {
            if (response.existe) {
              // Mostrar un mensaje de error
              alert("El código ya existe.");
            } else {
              enviarFormulario("crear_persona.php");
            }
          }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la llamada AJAX (verificar_codigo.php):", textStatus, errorThrown);
          });
        } else {
          enviarFormulario("guardar_persona.php");
        }

        function enviarFormulario(url) {
          // Obtener los datos del formulario
          var formData = $("#personaForm").serialize();
          console.log("Datos del formulario:", formData);

          // Realizar la llamada AJAX para guardar los datos
          $.ajax({
            url: url,
            type: "POST",
            data: formData,
            success: function(response) {
              console.log("Respuesta del servidor:", response);
              // Mostrar el modal de éxito
              $("#mensajeExitoPopup").modal("show");

              // Ocultar el modal automáticamente después de 3 segundos
              setTimeout(function() {
                $("#mensajeExitoPopup").modal("hide");
                $("#personaPopup").modal("hide");
              }, 2000);

              // Recargar la tabla después de que el modal se oculte
              $('#mensajeExitoPopup').on('hidden.bs.modal', function() {
                $('#tablaPersonas').DataTable().ajax.reload();
              });
            },
            error: function(xhr, status, error) {
              console.error("Error al guardar los datos:", error);
            }
          });
        }
      });
    });
  </script>

</body>

</html>