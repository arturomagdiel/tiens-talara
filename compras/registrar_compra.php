<?php
include '../shared/conexion.php';

// Consultar la lista de personas
$queryPersonas = "SELECT id, codigo, nombre, descuento FROM personas";
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Registrar Compra</h1>
        <form id="compra-form">
            <!-- Selección de persona -->
            <div class="mb-3 d-flex align-items-center">
                <label for="persona-busqueda" class="form-label me-2">Buscar Persona:</label>
                <input type="text" class="form-control w-50 me-2" id="persona-busqueda" placeholder="Ingrese nombre o código de la persona" autocomplete="off">
                <select class="form-select w-25 me-2" id="descuento-persona">
                    <option value="0">0%</option>
                    <option value="5">5%</option>
                    <option value="8">8%</option>
                    <option value="15">15%</option>
                </select>
                <button type="button" id="actualizar-descuento" class="btn btn-primary">Actualizar Descuento</button>
            </div>
            <div class="list-group position-absolute w-100" id="persona-lista" style="z-index: 1000;">
                <!-- Resultados de búsqueda aparecerán aquí -->
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
                            <input type="text" id="pago-nota" class="form-control" placeholder="Ingrese detalles del pago">
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

            <!-- Lista de productos -->
           
            <div class="mb-3 position-relative">
                <label for="producto-busqueda" class="form-label">Buscar Producto</label>
                <input type="text" class="form-control" id="producto-busqueda" placeholder="Ingrese nombre o código del producto" autocomplete="off">
                <div class="list-group position-absolute w-100" id="producto-lista" style="z-index: 1000;">
                    <!-- Resultados de búsqueda aparecerán aquí -->
                </div>
            </div>

            <!-- Tabla de productos agregados -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Código</th>
                        <th>Precio</th>
                        <th>PV</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="productos-lista">
                    <!-- Los productos agregados aparecerán aquí -->
                </tbody>
            </table>

            <!-- Totales -->
            <div class="text-end">
                <p><strong>Total a Pagar:</strong> S/<span id="total-pagar">0.00</span></p>
                <p><strong>Total PV:</strong> <span id="total-pv-display">0.00</span></p>
            </div>

            <!-- Botones de acción -->
            <div class="text-end">
                <button type="button" id="guardar-compra" class="btn btn-success" style="display: none;">Guardar Compra</button>
                <button type="button" id="comenzar-nuevo" class="btn btn-warning" style="display: none;">Comenzar de nuevo</button>
                <a href="index.php" class="btn btn-secondary">Volver</a>
            </div>

            <!-- Modal de confirmación -->
            <!-- <div class="modal fade" id="modalConfirmarCompra" tabindex="-1" aria-labelledby="modalConfirmarCompraLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalConfirmarCompraLabel">Confirmar Compra</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ¿Desea acumular la compra o liquidarla?
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="acumular-compra" class="btn btn-primary" data-bs-dismiss="modal">Acumular</button>
                            <button type="button" id="liquidar-compra" class="btn btn-danger" data-bs-dismiss="modal">Liquidar</button>
                        </div>
                    </div>
                </div>
            </div> -->
        </form>
    </div>
    <script>
        const personas = <?= json_encode($personas); ?>; // Pasar datos de personas al script
        const productos = <?= json_encode($productos); ?>; // Pasar datos de productos al script
    </script>
    <script src="scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>