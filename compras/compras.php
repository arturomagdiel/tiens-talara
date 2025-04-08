<?php
include '../shared/conexion.php';

// Obtener la lista de personas
$personas = [];
$result = $conn->query("SELECT id, codigo, nombre FROM personas");
if ($result) {
    $personas = $result->fetch_all(MYSQLI_ASSOC);
}

// Consultar las compras de la persona seleccionada
$compras = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['persona_id'])) {
    $persona_id = $_POST['persona_id'];

    $stmt = $conn->prepare("
        SELECT 
            id, 
            fecha_compra, 
            productos_codigo, 
            productos_precio, 
            productos_pv, 
            estado, 
            liquidacion_numero, 
            liquidacion_fecha 
        FROM compras 
        WHERE personas_id = ?
    ");
    $stmt->bind_param("i", $persona_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $compras = $result->fetch_all(MYSQLI_ASSOC);
    }
    $stmt->close();
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
</head>
<body>
    <div class="container mt-4">
        <div class="row align-items-center">
            <!-- Columna izquierda: Título -->
            <div class="col-md-6">
                <h1 class="text-center text-md-start">Gestión de Compras</h1>
            </div>

            <!-- Columna derecha: Botones -->
            <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
            <button class="btn btn-primary btn-sm me-2 d-flex align-items-center justify-content-center" 
            onclick="window.location.href='registrar_compra.php'" 
            title="Registrar Compra">
        <i class="bi bi-cart-plus"></i>
    </button>
    <button class="btn btn-secondary btn-sm me-2 d-flex align-items-center justify-content-center" 
            onclick="window.location.href='buscar_compra.php'" 
            title="Buscar Producto">
        <i class="bi bi-search"></i>
    </button>
    <button class="btn btn-dark btn-sm d-flex align-items-center justify-content-center" 
            onclick="window.location.href='../index.php'" 
            title="Menú Principal">
        <i class="bi bi-house-door"></i>
    </button>
            </div>
        </div>

        <!-- Formulario para buscar y seleccionar una persona -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Buscar Persona</h5>
                <form id="formSeleccionarPersona">
                    <div class="mb-3">
                        <input type="text" id="buscarPersona" class="form-control" placeholder="Ingrese nombre o código">
                        <input type="hidden" id="personaId" name="persona_id">
                    </div>
                    <ul id="listaPersonas" class="list-group">
                        <!-- Aquí se cargarán las personas dinámicamente -->
                    </ul>
                </form>
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
                    <form id="formPasarCompra">
                        <div class="mb-3">
                            <label for="buscarPersonaPasar" class="form-label">Buscar Persona</label>
                            <input type="text" id="buscarPersonaPasar" class="form-control" placeholder="Buscar por nombre o código">
                            <ul id="listaPersonasPasar" class="list-group mt-2">
                                <!-- Aquí se cargarán las personas dinámicamente -->
                            </ul>
                        </div>
                        <div class="mb-3">
                        <label for="notaPasarCompra" class="form-label">Nota</label>
                        <input type="text" id="notaPasarCompra" class="form-control" placeholder="Ingrese detalles del pago">
                        </div>
                        <input type="hidden" id="compraIdPasar">
                        <input type="hidden" id="personaIdPasar">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmarPasarCompra">Continuar</button>
                </div>
            </div>
        </div>
    </div>

        <!-- Tabla para mostrar las compras -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title" id="tituloCompras">Compras de la Persona Seleccionada</h5>
                <div class="mb-3">
                    <label class="form-label">Filtrar por Estado:</label>
                    <div>
                        <input type="radio" id="filtroPendientes" name="filtroEstado" value="pendiente" checked>
                        <label for="filtroPendientes">Pendientes</label>
                        <input type="radio" id="filtroLiquidadas" name="filtroEstado" value="liquidado">
                        <label for="filtroLiquidadas">Liquidadas</label>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>PV</th>
                                <th>Estado</th>
                                <th>Notas</th>
                                <th class="liquidacion-column">Liquidación #</th>
                                <th class="liquidacion-column">Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Las compras se cargarán dinámicamente aquí -->
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
                            <input type="text" id="numeroLiquidacion" class="form-control" readonly>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="compras.js"></script>
</body>
</html>