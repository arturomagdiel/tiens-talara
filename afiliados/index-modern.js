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
        columnDefs: [
            // Ocultar columnas en móvil
            {
                targets: [1], // Columna código
                visible: window.innerWidth > 768
            },
            {
                targets: [2], // Columna descuento  
                visible: window.innerWidth > 768
            },
            {
                targets: [4], // Columna apellido
                visible: window.innerWidth > 768
            },
            {
                targets: [5], // Columna teléfono
                visible: window.innerWidth > 768
            },
            {
                targets: [6], // Columna RUC
                visible: window.innerWidth > 768
            },
            {
                targets: [7], // Columna patrocinador
                visible: window.innerWidth > 768
            },
            {
                targets: [8], // Columna acciones
                visible: window.innerWidth > 768
            }
        ],
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
                render: function (data) {
                    return `<span class="badge bg-success">${data}%</span>`;
                }
            },
            {
                data: "nombre",
                render: function (data, type, row) {
                    // En móvil mostrar nombre completo, en desktop solo nombre
                    if (window.innerWidth <= 768) {
                        return `<div class="text-center">
                            <span class="fs-6 fw-normal">${data.toUpperCase()} ${row.apellido.toUpperCase()}</span>
                        </div>`;
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

    // Función para controlar visibilidad inicial de columnas
    function ajustarColumnasSegunPantalla() {
        const isMobile = window.innerWidth <= 768;
        
        if (isMobile) {
            // En móvil, ocultar todas las columnas excepto nombre
            tabla.column(1).visible(false); // Código
            tabla.column(2).visible(false); // Descuento
            tabla.column(4).visible(false); // Apellido
            tabla.column(5).visible(false); // Teléfono
            tabla.column(6).visible(false); // RUC
            tabla.column(7).visible(false); // Patrocinador
            tabla.column(8).visible(false); // Acciones
        }
        tabla.columns.adjust();
    }

    // Ejecutar ajuste inicial después de cargar datos
    tabla.on('init', function() {
        ajustarColumnasSegunPantalla();
    });

    tabla.on('draw', function() {
        ajustarColumnasSegunPantalla();
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
                                        <i class="bi bi-hash me-1"></i>Cod:
                                    </span>
                                    <span class="detail-value">${rowData.codigo.toUpperCase()}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">
                                        <i class="bi bi-percent me-1"></i>Desc:
                                    </span>
                                    <span class="detail-value">
                                        <span class="badge bg-success">${rowData.descuento}%</span>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">
                                        <i class="bi bi-telephone me-1"></i>Tel:
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
                                        <i class="bi bi-person-check me-1"></i>Pat:
                                    </span>
                                    <span class="detail-value">${rowData.patrocinador ? rowData.patrocinador.toUpperCase() : 'No asignado'}</span>
                                </div>
                                <div class="detail-item mt-3 pt-2" style="border-top: 2px solid rgba(102, 126, 234, 0.2);">
                                    <span class="detail-label"></span>
                                    <span class="detail-value">
                                        <div class="d-flex justify-content-end gap-3">
                                            <button class="btn-icon btnEditar" data-id="${rowData.id}" title="Editar Afiliado">
                                                <i class="bi bi-pencil-square text-success fs-4"></i>
                                            </button>
                                            <button class="btn-icon btnEliminar" data-id="${rowData.id}" title="Eliminar Afiliado">
                                                <i class="bi bi-trash3 text-danger fs-4"></i>
                                            </button>
                                        </div>
                                    </span>
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
            // Controlar visibilidad de columnas según tamaño de pantalla
            const isMobile = window.innerWidth <= 768;
            
            // Ocultar/mostrar columnas dinámicamente
            tabla.column(1).visible(!isMobile); // Código
            tabla.column(2).visible(!isMobile); // Descuento
            tabla.column(4).visible(!isMobile); // Apellido
            tabla.column(5).visible(!isMobile); // Teléfono
            tabla.column(6).visible(!isMobile); // RUC
            tabla.column(7).visible(!isMobile); // Patrocinador
            tabla.column(8).visible(!isMobile); // Acciones
            
            // Limpiar expansiones al cambiar a desktop
            if (!isMobile) {
                $('.details-row').remove();
                expandedRows = {};
                $('#tablaPersonas tbody tr').removeClass('expanded');
            }
            
            // Redibujar tabla
            tabla.columns.adjust().draw(false);
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