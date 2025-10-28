<?php
// filepath: d:\Users\artur\Documents\GitHub\tiens-talara\compras\compras.php
include '../shared/conexion.php';

// Obtener la lista de personas
$personas = [];
$result = $conn->query("SELECT id, codigo, UPPER(nombre) AS nombre, apellido, descuento FROM personas ORDER BY nombre ASC");
if ($result) {
    $personas = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="compras.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php
// Incluir sistema de autenticación y requerir login
require_once '../shared/auth.php';
requireAuth();
?>

<?php include '../shared/header_compras.php'; ?>

<script>
// Establecer el título específico para esta página
setPageTitle('Ver Compras');
</script>

    <div class="container-fluid mt-4">

        <!-- Fila con dos secciones: Buscar Persona y Buscar Compra -->
        <div class="row">
            <!-- Columna izquierda: Buscar Persona (50%) -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Buscar Persona</h5>
                        <form id="formSeleccionarPersona" autocomplete="off">
                            <div class="mb-3">
                                <input type="text" id="buscarPersona" class="form-control" placeholder="Ingrese nombre o código" autocomplete="off">
                                <input type="hidden" id="personaId" name="persona_id" autocomplete="off">
                            </div>
                            <ul id="listaPersonas" class="list-group">
                                <!-- Aquí se cargarán las personas dinámicamente -->
                            </ul>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Columna derecha: Buscar Compra (50%) -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Buscar Compra por ID</h5>
                        <form id="formBuscarCompra" autocomplete="off">
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="number" id="buscarCompraId" class="form-control" placeholder="Ingrese ID de compra (ej: 166)" autocomplete="off">
                                    <button type="button" class="btn btn-primary" id="btnBuscarCompra">
                                        <i class="bi bi-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                            <div id="resultadoBusqueda" class="d-none">
                                <div class="alert alert-info alert-dismissible">
                                    <strong>Compra encontrada:</strong>
                                    <button type="button" class="btn-close" aria-label="Close" onclick="cerrarResultado()"></button>
                                    <div id="infoCompra"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para seleccionar a otra persona -->
        <div class="modal fade" id="modalPasarCompra" tabindex="-1" aria-labelledby="modalPasarCompraLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalPasarCompraLabel">Pasar Compra</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formPasarCompra" onsubmit="return false;" autocomplete="off">
                            <div class="mb-3">
                                <label for="buscarPersonaPasar" class="form-label">Buscar Persona</label>
                                <input type="text" id="buscarPersonaPasar" class="form-control" placeholder="Buscar por nombre o código" autocomplete="off">
                                <ul id="listaPersonasPasar" class="list-group mt-2">
                                    <!-- Aquí se cargarán las personas dinámicamente -->
                                </ul>
                            </div>
                            <div class="mb-3">
                                <label for="notaPasarCompra" class="form-label">Nota</label>
                                <input type="text" id="notaPasarCompra" class="form-control" placeholder="Ingrese detalles del pago" autocomplete="off">
                            </div>
                            <input type="hidden" id="compraIdPasar" autocomplete="off">
                            <input type="hidden" id="personaIdPasar" autocomplete="off">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="confirmarPasarCompra" type="button" class="btn btn-primary">Confirmar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla para mostrar las compras -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title" id="tituloCompras">Compras de la Persona Seleccionada</h5>
                <div class="row mb-3">
                    <!-- Columna izquierda: Filtrar por Estado -->
                    <div class="col-md-6">
                        <label class="form-label">Filtrar por Estado:</label>
                        <div>
                            <input type="radio" id="filtroPendientes" name="filtroEstado" value="pendiente" checked>
                            <label for="filtroPendientes">Pendientes</label>
                            <input type="radio" id="filtroLiquidadas" name="filtroEstado" value="liquidado">
                            <label for="filtroLiquidadas">Liquidadas</label>
                            <input type="radio" id="filtroEliminadas" name="filtroEstado" value="eliminado">
                            <label for="filtroEliminadas">Eliminadas</label>
                        </div>
                    </div>

                    <!-- Columna derecha: Total PV -->
                    <div class="col-md-6">
                        <div>
                            <p><strong>Total PV seleccionados:</strong> <span id="total-pv">0</span></p>
                            <button id="liquidar-seleccionados" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalLiquidarSeleccionados" disabled>Liquidar Seleccionados</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>PV</th>
                                <th>Dcto</th>
                                <th>Estado</th>
                                <th>Notas</th>
                                <th class="liquidacion-column">Liquidación #</th>
                                <th class="liquidacion-column">Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="compras-list">
                            <!-- Las compras se cargan dinámicamente con JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para liquidar producto -->
    <div class="modal fade" id="modalLiquidar" tabindex="-1" aria-labelledby="modalLiquidarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLiquidarLabel">Liquidar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formLiquidar">
                        <div class="mb-3">
                            <label for="numeroLiquidacion" class="form-label">Número de Liquidación</label>
                            <input type="text" id="numeroLiquidacion" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="notaLiquidacion" class="form-label">Nota</label>
                            <textarea id="notaLiquidacion" class="form-control" rows="3" placeholder="Ingrese una nota (opcional)"></textarea>
                        </div>
                        <input type="hidden" id="compraIdLiquidar">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmarLiquidacion">Confirmar Liquidación</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para liquidar múltiples compras -->
    <div class="modal fade" id="modalLiquidarSeleccionados" tabindex="-1" aria-labelledby="modalLiquidarSeleccionadosLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLiquidarSeleccionadosLabel">Liquidar Compras Seleccionadas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formLiquidarSeleccionados">
                        <div class="mb-3">
                            <label for="numeroLiquidacionSeleccionados" class="form-label">Número de Liquidación</label>
                            <input type="text" id="numeroLiquidacionSeleccionados" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="notaLiquidacionSeleccionados" class="form-label">Nota</label>
                            <textarea id="notaLiquidacionSeleccionados" class="form-control" rows="3" placeholder="Ingrese una nota (opcional)"></textarea>
                        </div>
                        <p><strong>Total PV seleccionados:</strong> <span id="totalPvModal">0</span></p>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmarLiquidacionSeleccionados">Confirmar Liquidación</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de éxito -->
    <div class="modal fade" id="modalExito" tabindex="-1" aria-labelledby="modalExitoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalExitoLabel">Éxito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Operación realizada con éxito.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Error por Descuento -->
    <div class="modal fade" id="modalErrorDescuento" tabindex="-1" aria-labelledby="modalErrorDescuentoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalErrorDescuentoLabel">Error de Descuento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="mensajeErrorDescuento" class="mb-0"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="modalEliminar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">⚠️ Confirmar Eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">¿Estás seguro de que deseas eliminar esta compra?</p>
                    <div class="mb-3">
                        <label for="claveEliminar" class="form-label">Ingresa la clave de seguridad:</label>
                        <input type="password" class="form-control" id="claveEliminar" placeholder="Clave requerida">
                    </div>
                    <div class="mb-3">
                        <label for="razonEliminar" class="form-label">Razón de eliminación:</label>
                        <textarea class="form-control" id="razonEliminar" rows="3" placeholder="Ingrese la razón por la cual elimina esta compra" required></textarea>
                    </div>
                    <input type="hidden" id="idCompraEliminar">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="eliminarCompra()">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variable para el timeout
        let timeoutResultado;

        function confirmarEliminar(id) {
            document.getElementById('idCompraEliminar').value = id;
            document.getElementById('claveEliminar').value = '';
            document.getElementById('razonEliminar').value = '';
            new bootstrap.Modal(document.getElementById('modalEliminar')).show();
        }

        function eliminarCompra() {
            const id = document.getElementById('idCompraEliminar').value;
            const clave = document.getElementById('claveEliminar').value;
            const razon = document.getElementById('razonEliminar').value;
            
            if (!clave) {
                alert('Debes ingresar la clave de seguridad');
                return;
            }
            
            if (!razon.trim()) {
                alert('Debes ingresar la razón de eliminación');
                return;
            }
            
            $.ajax({
                url: 'eliminar_compra.php',
                method: 'POST',
                dataType: 'json',
                data: { id: id, clave: clave, razon: razon },
                success: function(response) {
                    if (response.success) {
                        // Cerrar el modal primero
                        bootstrap.Modal.getInstance(document.getElementById('modalEliminar')).hide();
                        
                        // Mostrar mensaje de éxito
                        alert('Compra eliminada correctamente');
                        
                        // Recargar la tabla del usuario actual con un pequeño delay
                        const personaIdValue = document.getElementById('personaId').value;
                        if (personaIdValue) {
                            setTimeout(function() {
                                // Determinar el estado actual del filtro
                                let estadoActual = 'pendiente';
                                let filtroActivo = document.getElementById('filtroPendientes');
                                
                                if (document.getElementById('filtroLiquidadas').checked) {
                                    estadoActual = 'liquidado';
                                    filtroActivo = document.getElementById('filtroLiquidadas');
                                } else if (document.getElementById('filtroEliminadas').checked) {
                                    estadoActual = 'eliminado';
                                    filtroActivo = document.getElementById('filtroEliminadas');
                                }
                                
                                // Actualizar las clases CSS del body según el filtro
                                document.body.classList.remove('estado-liquidado', 'estado-eliminado');
                                if (estadoActual === 'liquidado') {
                                    document.body.classList.add('estado-liquidado');
                                } else if (estadoActual === 'eliminado') {
                                    document.body.classList.add('estado-eliminado');
                                }
                                
                                // Llamar a la función global cargarCompras
                                if (typeof window.cargarCompras === 'function') {
                                    window.cargarCompras(personaIdValue, estadoActual);
                                } else {
                                    // Fallback: recargar la página si la función no está disponible
                                    location.reload();
                                }
                            }, 500); // Delay de 500ms para asegurar que la DB se actualice
                        }
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error al eliminar la compra');
                }
            });
        }

        // Función para cerrar resultado manualmente
        function cerrarResultado() {
            $('#resultadoBusqueda').addClass('d-none');
            if (timeoutResultado) {
                clearTimeout(timeoutResultado);
            }
        }

        // Función para buscar compra por ID
        $('#btnBuscarCompra').click(function() {
            const compraId = $('#buscarCompraId').val();
            if (!compraId) {
                alert('Ingrese un ID de compra');
                return;
            }

            $.ajax({
                url: 'buscar_compra.php',
                method: 'POST',
                dataType: 'json',
                data: { id: compraId },
                success: function(response) {
                    if (response.success) {
                        const compra = response.compra;
                        const estadoBadge = compra.estado === 'eliminado' ? 
                            '<span class="badge bg-danger">Eliminado</span>' : 
                            `<span class="badge bg-success">${compra.estado}</span>`;
                        
                        $('#infoCompra').html(`
                            <strong>ID:</strong> ${compra.id}<br>
                            <strong>Afiliado:</strong> ${compra.persona_nombre || 'No encontrado'} (${compra.persona_codigo || 'N/A'})<br>
                            <strong>Fecha:</strong> ${compra.fecha_compra}<br>
                            <strong>Producto:</strong> ${compra.productos_codigo} - ${compra.producto_nombre || 'N/A'}<br>
                            <strong>Estado:</strong> ${estadoBadge}<br>
                            <strong>Notas:</strong> ${compra.liquidacion_nota || 'Sin notas'}
                        `);
                        $('#resultadoBusqueda').removeClass('d-none');
                        
                        // Auto-cerrar después de 1 minuto
                        if (timeoutResultado) {
                            clearTimeout(timeoutResultado);
                        }
                        timeoutResultado = setTimeout(function() {
                            cerrarResultado();
                        }, 60000); // 60 segundos
                        
                    } else {
                        alert('Compra no encontrada');
                        $('#resultadoBusqueda').addClass('d-none');
                    }
                },
                error: function() {
                    alert('Error al buscar la compra');
                }
            });
        });

        // Prevenir submit del formulario al presionar Enter
        $('#formBuscarCompra').submit(function(e) {
            e.preventDefault();
            return false;
        });

        // Prevenir que Enter active la búsqueda
        $('#buscarCompraId').keypress(function(e) {
            if (e.which === 13) {
                e.preventDefault();
                return false;
            }
        });

        const personas = <?= json_encode($personas); ?>; // Pasar las personas al frontend
    </script>
    <script src="compras.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>