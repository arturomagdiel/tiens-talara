<?php
include '../shared/conexion.php';

// Consultar la lista de personas
$queryPersonas = "SELECT id, codigo, UPPER(nombre) AS nombre, descuento FROM personas ORDER BY nombre ASC";
$resultPersonas = $conn->query($queryPersonas);

$personas = [];
if ($resultPersonas->num_rows > 0) {
    while ($row = $resultPersonas->fetch_assoc()) {
        $personas[] = $row;
    }
}

// Consultar la lista de productos
$queryProductos = "SELECT id, codigo, nombre, precio_afiliado, pv_afiliado FROM productos";
$resultProductos = $conn->query($queryProductos);

$productos = [];
if ($resultProductos->num_rows > 0) {
    while ($row = $resultProductos->fetch_assoc()) {
        $productos[] = $row;
    }
}

// echo '<pre>';
// print_r($productos);
// echo '</pre>';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../pwa-styles.css">
    <link rel="stylesheet" href="registrar_compra.css">
</head>
<body class="context-app">

<?php
// Incluir sistema de autenticación y requerir login
require_once '../shared/auth.php';
requireAuth();
?>

<?php include '../shared/header_compras.php'; ?>

<script>
// Establecer el título específico para esta página
setPageTitle('Registrar Compra');
</script>

<div class="container-fluid px-3 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <!-- Sección de búsqueda de persona -->
            <div class="modern-card mb-4">
                <div class="card-body p-4">
                    <div class="section-header mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-fill me-2 text-primary fs-4"></i>
                            <i class="bi bi-person-search me-2 text-primary fs-5"></i>
                            <h5 class="section-title mb-0">Buscar Persona</h5>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="modern-input-group position-relative">
                                <input type="text" class="form-control modern-input" id="persona-busqueda" 
                                       placeholder="Nombre o código de la persona" autocomplete="off">
                                <span class="input-icon">
                                    <i class="bi bi-search"></i>
                                </span>
                                <!-- Dropdown de personas directamente aquí -->
                                <div class="list-group modern-dropdown" id="persona-lista" style="display: none;">
                                    <!-- Resultados de búsqueda de personas aparecerán aquí -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select modern-select" id="descuento-persona">
                                <option value="0">Descuento: 0%</option>
                                <option value="5">Descuento: 5%</option>
                                <option value="8">Descuento: 8%</option>
                                <option value="15">Descuento: 15%</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="actualizar-descuento" class="btn btn-primary modern-btn w-100">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                <span class="btn-text">Actualizar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sección de productos -->
            <div class="modern-card mb-4">
                <div class="card-body p-4">
                    <form id="compra-form">
                        <div class="section-header mb-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-box-seam me-2 text-primary fs-5"></i>
                                <h5 class="section-title mb-0">Productos</h5>
                            </div>
                        </div>

                        <!-- Búsqueda de productos -->
                        <div class="mb-4">
                            <div class="modern-input-group position-relative">
                                <input type="text" class="form-control modern-input" id="producto-busqueda" 
                                       placeholder="Buscar producto por nombre o código" autocomplete="off">
                                <span class="input-icon">
                                    <i class="bi bi-search"></i>
                                </span>
                                <!-- Dropdown de productos directamente aquí -->
                                <div class="list-group modern-dropdown" id="producto-lista" style="display: none;">
                                    <!-- Resultados de búsqueda de productos aparecerán aquí -->
                                </div>
                            </div>
                        </div>

                        <!-- Vista para productos agregados -->
                        <!-- Vista Desktop: Tabla tradicional -->
                        <div class="table-container d-none d-md-block">
                            <div class="table-responsive">
                                <table class="table modern-table">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap">
                                                <i class="bi bi-box me-1"></i>
                                                Producto
                                            </th>
                                            <th class="text-nowrap">
                                                <i class="bi bi-upc me-1"></i>
                                                Código
                                            </th>
                                            <th class="text-nowrap">
                                                <i class="bi bi-currency-dollar me-1"></i>
                                                Precio
                                            </th>
                                            <th class="text-nowrap">
                                                <i class="bi bi-star me-1"></i>
                                                PV
                                            </th>
                                            <th class="text-nowrap">
                                                <i class="bi bi-123 me-1"></i>
                                                Cantidad
                                            </th>
                                            <th class="text-nowrap">
                                                <i class="bi bi-calculator me-1"></i>
                                                Subtotal
                                            </th>
                                            <th class="text-nowrap">
                                                <i class="bi bi-star-fill me-1"></i>
                                                Subtotal PV
                                            </th>
                                            <th class="text-nowrap text-center">
                                                <i class="bi bi-gear-fill"></i>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="productos-lista">
                                        <!-- Los productos agregados aparecerán aquí -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Vista Mobile: Tarjetas modernas -->
                        <div class="mobile-products-container d-md-none" id="productos-lista-mobile">
                            <!-- Los productos aparecerán como tarjetas aquí -->
                        </div>

                        <!-- Panel de totales -->
                        <div class="totals-panel mt-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="total-card">
                                        <div class="total-icon">
                                            <i class="bi bi-cash-coin"></i>
                                        </div>
                                        <div class="total-info">
                                            <div class="total-label">Total a Pagar</div>
                                            <div class="total-amount">S/<span id="total-pagar">0.00</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="total-card">
                                        <div class="total-icon">
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                        <div class="total-info">
                                            <div class="total-label">Total PV</div>
                                            <div class="total-amount"><span id="total-pv-display">0.00</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="action-buttons mt-4">
                            <div class="d-flex gap-3 justify-content-end flex-wrap">
                                <button type="button" id="guardar-compra" class="btn btn-success modern-btn-success" style="display: none;">
                                    <i class="bi bi-check-circle me-2"></i>
                                    <span class="btn-text">Guardar Compra</span>
                                </button>
                                <button type="button" id="comenzar-nuevo" class="btn btn-warning modern-btn-warning" style="display: none;">
                                    <i class="bi bi-arrow-clockwise me-2"></i>
                                    <span class="btn-text">Comenzar de nuevo</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
 

            <!-- Modal de confirmación -->
            <div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalConfirmacionLabel">Confirmación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Descuento actualizado correctamente.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de confirmación de acumulación -->
            <div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalConfirmacionLabel">Compra Acumulada</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            La compra ha sido acumulada correctamente.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de confirmación de pago -->
            <div class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="modalPagoLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalPagoLabel">Confirmación de Pago</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <label for="pago-nota" class="form-label">¿Cómo se realizó el pago?</label>
                            <input type="text" id="pago-nota" class="form-control" placeholder="Ingrese detalles del pago" autocomplete="off">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" id="confirmar-pago" class="btn btn-primary">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de confirmación de compra -->
            <div class="modal fade" id="modalConfirmacionCompra" tabindex="-1" aria-labelledby="modalConfirmacionCompraLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalConfirmacionCompraLabel">Compra Registrada</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <p>La compra se registró con éxito.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="cerrar-confirmacion" data-bs-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de alerta de pago -->
            <div class="modal fade" id="modalAlertaPago" tabindex="-1" aria-labelledby="modalAlertaPagoLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalAlertaPagoLabel">Atención</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            Por favor, ingrese detalles del pago.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal de alerta para detalles del pago -->
            <div class="modal fade" id="modalAlertaPago" tabindex="-1" aria-labelledby="modalAlertaPagoLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalAlertaPagoLabel">Detalles del Pago</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Por favor, ingrese los detalles del pago antes de continuar.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de advertencia para seleccionar persona -->
            <div class="modal fade" id="modalSeleccionarPersona" tabindex="-1" aria-labelledby="modalSeleccionarPersonaLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalSeleccionarPersonaLabel">
                                <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                                Selecciona una persona
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">
                                <i class="bi bi-person-x display-1 text-muted mb-3"></i>
                                <p class="mb-0">Debes seleccionar una persona antes de agregar productos a la compra.</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                                <i class="bi bi-check-lg me-2"></i>Entendido
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de advertencia -->
            <div class="modal fade" id="modalProductoExistente" tabindex="-1" aria-labelledby="modalProductoExistenteLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalProductoExistenteLabel">Producto ya en la lista</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            El producto que intentas agregar ya está en la lista.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
            
    </div>
    
    <script>
        const personas = <?= json_encode($personas); ?>; // Pasar datos de personas al script
        const productos = <?= json_encode($productos); ?>; // Pasar datos de productos al script
    </script>
    <script src="registrar_compra.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Indicador de versión PWA -->
    <div class="version-indicator">PWA v1.6.1</div>
</body>
</html>