document.addEventListener('DOMContentLoaded', function () {
    const pageTitle = document.getElementById('page-title');
    if (pageTitle) {
        pageTitle.textContent = 'Mantenimiento Afiliados'; // Cambia este texto según la página
    }
});


$(document).ready(function () {
    // Función para verificar si el código ya existe
    function verificarCodigo(codigo, id) {
        return $.ajax({
            url: 'verificar_codigo.php',
            type: 'GET',
            data: { codigo: codigo, id: id },
            dataType: 'json',
            error: function (xhr, status, error) {
                console.error("Error en la verificación del código:", error);
            }
        });
    }

    // Inicializar DataTables
    $('#tablaPersonas').DataTable({
        ajax: "obtener_personas.php",
        columns: [
            { data: "id", visible: false }, // ID oculto
            {
                data: "codigo",
                render: function (data, type, row) {
                    return `<a href="#" class="enlaceEditar" data-id="${row.id}">${data.toUpperCase()}</a>`;
                }
            },
            { data: "descuento" },
            {
                data: "nombre",
                render: function (data) {
                    return data.toUpperCase();
                }
            },
            {
                data: "apellido",
                render: function (data) {
                    return data.toUpperCase();
                }
            },
            { data: "telefono" },
            { data: "ruc" },
            {
                data: "patrocinador",
                render: function (data) {
                    return data.toUpperCase();
                }
            },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-primary btn-sm btnEditar" data-id="${row.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btnEliminar" data-id="${row.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ]
    });

    // Mostrar el popup al hacer clic en "Crear nueva persona"
    $("#btnNuevaPersona").click(function () {
        $("#btnModalEliminar").hide(); // Ocultar el botón Eliminar
        $("#personaPopup").modal("show");
        $("#personaForm")[0].reset();
        $("#personaId").val("");
    });

    // Mostrar el popup al hacer clic en el enlace del código
    $(document).on("click", ".enlaceEditar", function (event) {
        event.preventDefault();
        var personaId = $(this).data("id");

        $.ajax({
            url: "obtener_persona.php",
            type: "GET",
            data: { id: personaId },
            success: function (response) {
                var persona = JSON.parse(response);
                $("#personaId").val(persona.id);
                $("#nombre").val(persona.nombre);
                $("#apellido").val(persona.apellido);
                $("#codigo").val(persona.codigo);
                $("#telefono").val(persona.telefono);
                $("#ruc").val(persona.ruc);
                $("#patrocinador").val(persona.patrocinador);
                $("#descuento").val(persona.descuento);
                $("#btnModalEliminar").data("id", persona.id).show();
                $("#personaPopup").modal("show");
            },
            error: function (xhr, status, error) {
                console.error("Error al obtener los datos de la persona:", error);
            }
        });
    });

    // Mostrar el popup al hacer clic en "Editar"
    $(document).on("click", ".btnEditar", function () {
        var personaId = $(this).data("id");

        $.ajax({
            url: "obtener_persona.php",
            type: "GET",
            data: { id: personaId },
            success: function (response) {
                var persona = JSON.parse(response);
                $("#personaId").val(persona.id);
                $("#nombre").val(persona.nombre);
                $("#apellido").val(persona.apellido);
                $("#codigo").val(persona.codigo);
                $("#telefono").val(persona.telefono);
                $("#ruc").val(persona.ruc);
                $("#patrocinador").val(persona.patrocinador);
                $("#descuento").val(persona.descuento);
                $("#btnModalEliminar").data("id", persona.id).show();
                $("#personaPopup").modal("show");
            },
            error: function (xhr, status, error) {
                console.error("Error al obtener los datos de la persona:", error);
            }
        });
    });

    // Eliminar persona
    $(document).on("click", ".btnEliminar", function () {
        var personaId = $(this).data("id");
        $("#btnModalEliminar").data("id", personaId);
        $("#confirmarEliminarPopup").modal("show");
    });

    $("#btnModalEliminar").click(function () {
        $("#confirmarEliminarPopup").modal("show");
    });

    $("#btnConfirmarEliminar").click(function () {
        var personaId = $("#btnModalEliminar").data("id");

        $.ajax({
            url: "eliminar_persona.php",
            type: "POST",
            data: { id: personaId },
            success: function () {
                $("#confirmarEliminarPopup").modal("hide");
                location.reload();
            },
            error: function (xhr, status, error) {
                console.error("Error al eliminar la persona:", error);
            }
        });
    });

    // Enviar el formulario
    $("#personaForm").submit(function (event) {
        event.preventDefault();
        var codigo = $("#codigo").val();
        var id = $("#personaId").val();

        if (id === "") {
            verificarCodigo(codigo, id).done(function (response) {
                if (response.existe) {
                    alert("El código ya existe.");
                } else {
                    enviarFormulario("crear_persona.php");
                }
            });
        } else {
            enviarFormulario("guardar_persona.php");
        }

        function enviarFormulario(url) {
            var formData = $("#personaForm").serialize();

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                success: function () {
                    $("#mensajeExitoPopup").modal("show");
                    setTimeout(function () {
                        $("#mensajeExitoPopup").modal("hide");
                        $("#personaPopup").modal("hide");
                    }, 2000);
                    $('#mensajeExitoPopup').on('hidden.bs.modal', function () {
                        $('#tablaPersonas').DataTable().ajax.reload();
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error al guardar los datos:", error);
                }
            });
        }
    });
});