// Afiliados - Gestión Moderna con Bootstrap 5
document.addEventListener('DOMContentLoaded', function () {
    // Configurar título de página
    const pageTitle = document.getElementById('page-title');
    if (pageTitle) {
        pageTitle.textContent = 'Gestión de Afiliados';
    }
});

$(document).ready(function () {
    // Variables para instancias de modales Bootstrap 5
    const personaModal = new bootstrap.Modal(document.getElementById('personaPopup'));
    const confirmarModal = new bootstrap.Modal(document.getElementById('confirmarEliminarPopup'));
    const exitoModal = new bootstrap.Modal(document.getElementById('mensajeExitoPopup'));

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

    // Inicializar DataTables con configuración moderna
    const tabla = $('#tablaPersonas').DataTable({
        ajax: "obtener_personas.php",
        responsive: false, // Deshabilitamos responsive automático para control manual
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        pageLength: 25,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        columns: [
            { data: "id", visible: false },
            {
                data: "codigo",
                render: function (data, type, row) {
                    return `<a href="#" class="enlaceEditar text-decoration-none fw-bold" data-id="${row.id}">${data.toUpperCase()}</a>`;
                }
            },
            { 
                data: "descuento",
                render: function (data, type, row) {
                    // En móvil mostrar iconos, en desktop mostrar descuento
                    if (window.innerWidth <= 768) {
                        return `
                            <div class="btn-group" role="group">
                                <button class="btn btn-action btn-edit btn-sm btnEditar" data-id="${row.id}" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-action btn-delete btn-sm btnEliminar" data-id="${row.id}" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        `;
                    } else {
                        return `<span class="badge bg-success">${data}%</span>`;
                    }
                }
            },
            {
                data: "nombre",
                render: function (data, type, row) {
                    // En móvil mostrar nombre completo, en desktop solo nombre
                    if (window.innerWidth <= 768) {
                        return `<strong>${data.toUpperCase()} ${row.apellido.toUpperCase()}</strong>`;
                    } else {
                        return data.toUpperCase();
                    }
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
                    return data ? data.toUpperCase() : '';
                }
            },
            {
                data: null,
                orderable: false,
                render: function (data, type, row) {
                    return `
                        <div class="btn-group" role="group">
                            <button class="btn btn-action btn-edit btn-sm btnEditar" data-id="${row.id}" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-action btn-delete btn-sm btnEliminar" data-id="${row.id}" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ]
    });

    // Funcionalidad para expandir filas en móvil
    let expandedRows = {};

    // Click en fila para expandir/contraer (solo en móvil)
    $('#tablaPersonas tbody').on('click', 'tr', function(e) {
        // Solo en móvil y si no se hizo click en un botón
        if (window.innerWidth <= 768 && !$(e.target).closest('button').length && !$(e.target).closest('a').length) {
            const tr = $(this);
            const row = tabla.row(tr);
            const rowData = row.data();
            
            if (!rowData) return;
            
            if (expandedRows[rowData.id]) {
                // Contraer
                tr.next('.details-row').remove();
                delete expandedRows[rowData.id];
                tr.removeClass('expanded');
            } else {
                // Expandir
                const detailsHtml = `
                    <tr class="details-row">
                        <td colspan="9">
                            <div class="details-content">
                                <div class="detail-item">
                                    <span class="detail-label">
                                        <i class="bi bi-hash me-1"></i>Código:
                                    </span>
                                    <span class="detail-value">${rowData.codigo.toUpperCase()}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">
                                        <i class="bi bi-percent me-1"></i>Descuento:
                                    </span>
                                    <span class="detail-value">${rowData.descuento}%</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">
                                        <i class="bi bi-telephone me-1"></i>Teléfono:
                                    </span>
                                    <span class="detail-value">${rowData.telefono || 'No registrado'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">
                                        <i class="bi bi-building me-1"></i>RUC:
                                    </span>
                                    <span class="detail-value">${rowData.ruc || 'No registrado'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">
                                        <i class="bi bi-person-check me-1"></i>Patrocinador:
                                    </span>
                                    <span class="detail-value">${rowData.patrocinador ? rowData.patrocinador.toUpperCase() : 'No asignado'}</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
                
                tr.after(detailsHtml);
                expandedRows[rowData.id] = true;
                tr.addClass('expanded');
            }
        }
    });

    // Limpiar filas expandidas al recargar tabla
    tabla.on('draw', function() {
        expandedRows = {};
    });

    // Reajustar tabla al cambiar tamaño de ventana
    $(window).on('resize', function() {
        if ($.fn.DataTable.isDataTable('#tablaPersonas')) {
            tabla.columns.adjust().draw();
            // Limpiar expansiones al cambiar a desktop
            if (window.innerWidth > 768) {
                $('.details-row').remove();
                expandedRows = {};
                $('#tablaPersonas tbody tr').removeClass('expanded');
            }
        }
    });

    // Evento: Nuevo afiliado
    $("#btnNuevaPersona").click(function () {
        $("#personaForm")[0].reset();
        $("#personaId").val("");
        $("#personaModalLabel").html('<i class="bi bi-person-plus me-2"></i>Nuevo Afiliado');
        $("#btnModalEliminar").hide();
        personaModal.show();
    });

    // Evento: Editar afiliado desde enlace de código
    $(document).on("click", ".enlaceEditar", function (e) {
        e.preventDefault();
        const personaId = $(this).data("id");
        editarPersona(personaId);
    });

    // Evento: Editar afiliado desde botón
    $(document).on("click", ".btnEditar", function () {
        const personaId = $(this).data("id");
        editarPersona(personaId);
    });

    // Función para editar persona
    function editarPersona(personaId) {
        $.ajax({
            url: "obtener_persona.php",
            type: "GET",
            data: { id: personaId },
            dataType: "json",
            success: function (persona) {
                $("#personaId").val(persona.id);
                $("#nombre").val(persona.nombre);
                $("#apellido").val(persona.apellido);
                $("#codigo").val(persona.codigo);
                $("#telefono").val(persona.telefono);
                $("#ruc").val(persona.ruc);
                $("#patrocinador").val(persona.patrocinador);
                $("#descuento").val(persona.descuento);
                
                $("#personaModalLabel").html('<i class="bi bi-person-gear me-2"></i>Editar Afiliado');
                $("#btnModalEliminar").data("id", persona.id).show();
                personaModal.show();
            },
            error: function () {
                alert("Error al cargar los datos del afiliado.");
            }
        });
    }

    // Evento: Eliminar afiliado
    $(document).on("click", ".btnEliminar", function () {
        const personaId = $(this).data("id");
        $("#btnConfirmarEliminar").data("id", personaId);
        confirmarModal.show();
    });

    // Evento: Eliminar desde modal de edición
    $("#btnModalEliminar").click(function () {
        const personaId = $(this).data("id");
        $("#btnConfirmarEliminar").data("id", personaId);
        personaModal.hide();
        confirmarModal.show();
    });

    // Evento: Confirmar eliminación
    $("#btnConfirmarEliminar").click(function () {
        const personaId = $(this).data("id");
        
        $.ajax({
            url: "eliminar_persona.php",
            type: "POST",
            data: { id: personaId },
            dataType: "json",
            success: function (response) {
                confirmarModal.hide();
                
                if (response.success) {
                    tabla.ajax.reload();
                    
                    // Mostrar mensaje de éxito
                    exitoModal.show();
                    
                    // Auto-cerrar después de 2 segundos
                    setTimeout(() => {
                        exitoModal.hide();
                    }, 2000);
                } else {
                    alert("Error al eliminar el afiliado: " + (response.message || "Error desconocido"));
                }
            },
            error: function () {
                confirmarModal.hide();
                alert("Error de conexión al eliminar el afiliado.");
            }
        });
    });

    // Evento: Guardar formulario
    $("#personaForm").submit(function (e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const personaId = $("#personaId").val();
        const codigo = $("#codigo").val();
        
        // Validar campos requeridos
        if (!$("#nombre").val() || !$("#apellido").val() || !$("#codigo").val()) {
            alert("Por favor, completa todos los campos obligatorios.");
            return;
        }

        // Verificar código duplicado
        verificarCodigo(codigo, personaId).done(function (response) {
            if (response.existe) {
                alert("El código ya existe. Por favor, elige otro código.");
                $("#codigo").focus();
                return;
            }

            // Guardar datos
            $.ajax({
                url: "guardar_persona.php",
                type: "POST",
                data: formData,
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        tabla.ajax.reload();
                        personaModal.hide();
                        
                        // Mostrar mensaje de éxito
                        exitoModal.show();
                        
                        // Auto-cerrar después de 2 segundos
                        setTimeout(() => {
                            exitoModal.hide();
                        }, 2000);
                    } else {
                        alert("Error al guardar: " + (response.message || "Error desconocido"));
                    }
                },
                error: function () {
                    alert("Error de conexión al guardar los datos.");
                }
            });
        });
    });

    // Mejorar la experiencia de usuario
    $("#codigo").on('input', function() {
        $(this).val($(this).val().toUpperCase());
    });

    $("#nombre, #apellido, #patrocinador").on('input', function() {
        $(this).val($(this).val().toUpperCase());
    });

    // Validación en tiempo real
    $("#telefono").on('input', function() {
        const value = $(this).val().replace(/\D/g, ''); // Solo números
        $(this).val(value);
    });

    $("#ruc").on('input', function() {
        const value = $(this).val().replace(/\D/g, ''); // Solo números
        $(this).val(value);
    });

    console.log('🎨 Gestión de Afiliados - Diseño Moderno Cargado');
});