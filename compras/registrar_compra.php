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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<?php include '../shared/header.php'; ?>

<div class="container mt-4">


        <div class="card mt-4">
            <div class="card-body">
            <h5 class="card-title">Buscar Persona</h5>
                            <!-- Selección de persona -->
            <div class="mb-3 d-flex align-items-center">
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

            </div>
        </div>
        
        <div class="card mt-4">
        <div class="card-body">

        <form id="compra-form">



            <!-- Lista de productos -->
           
            <div class="mb-3 position-relative">
            <h5 class="card-title">Buscar Producto</h5>
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
            </div>
        </form>


</div>
</div>
        

    <div class="container mt-5">
 

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
            
    </div>
    <script>
        const personas = <?= json_encode($personas); ?>; // Pasar datos de personas al script
        const productos = <?= json_encode($productos); ?>; // Pasar datos de productos al script
    </script>
    <script src="registrar_compra.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>