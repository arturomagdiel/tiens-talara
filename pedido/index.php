<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcula tu compra Tiens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/x-icon" href="../images/tiens.ico">
</head>
<body>
    <audio id="errorSound" src="../sonidos/click1.mp3" preload="auto"></audio>
    <audio id="successSound" src="../sonidos/click1.mp3" preload="auto"></audio>   
    <audio id="clickSound" src="../sonidos/click1.mp3" preload="auto"></audio>

    <div class="container mt-4">
        <div class="row">
            <!-- Columna izquierda: Productos -->
            <div class="col-12 col-lg-9 mb-4">
            
            <div id="top-bar" class="d-flex justify-content-between align-items-center text-white bg-dark py-2 w-100">
    <span class="fw-bold m-2">Productos</span>
    <a href="../index.php" class="text-white">
        <i class="bi bi-house fs-4 m-2"></i>
    </a>
</div>

            <div id="fila-sup" class="d-flex justify-content-center align-items-center mb-3">
    <div class="btn-group" role="group" aria-label="Tipo de precio">
        <input type="radio" class="btn-check" name="tipo-precio" id="publico" value="publico" autocomplete="off">
        <label class="btn btn-outline-dark" for="publico">Precio Público</label>

        <input type="radio" class="btn-check" name="tipo-precio" id="afiliado" value="afiliado" autocomplete="off">
        <label class="btn btn-outline-secondary" for="afiliado">Precio Afiliado</label>

        <input type="radio" class="btn-check" name="tipo-precio" id="5" value="5" autocomplete="off">
        <label class="btn btn-outline-primary" for="5">5%</label>

        <input type="radio" class="btn-check" name="tipo-precio" id="8" value="8" autocomplete="off">
        <label class="btn btn-outline-warning" for="8">8%</label>

        <input type="radio" class="btn-check" name="tipo-precio" id="15" value="15" autocomplete="off" checked>
        <label class="btn btn-outline-danger" for="15">15%</label>
    </div>
</div>

                <!-- Lista de productos -->
                <div id="product-list" class="row row-cols-4 row-cols-sm-4 row-cols-lg-6 g-4">
                    <!-- Los productos se cargarán aquí mediante AJAX -->
                </div>
            </div>

            <!-- Botón para abrir/cerrar el carrito en móviles -->
            <div class="d-lg-none text-center mb-2">
                <button class="btn btn-primary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#carrito-collapse" aria-expanded="false" aria-controls="carrito-collapse">
                    Mostrar/Ocultar Carrito
                </button>
            </div>

            <!-- Columna derecha: Carrito -->
            <div class="col-12 col-lg-3 collapse show" id="carrito-collapse">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="fw-bold mb-0">Resumen de Compra</h4>
                    </div>
                    <div class="card-body">
                        <ul id="carrito-lista" class="list-group mb-3">
                            <!-- Productos del carrito -->
                        </ul>
                        <p class="text-end"><strong>Total:</strong> S/<span id="carrito-precio">0.00</span> - <span id="carrito-pv">0.00</span> PV</p>
                        <div class="d-flex gap-2">
                            <button id="limpiar-carrito" class="btn btn-danger btn-sm w-50">Limpiar</button>
                            <button id="pedido-btn" class="btn btn-primary btn-sm w-50" data-bs-toggle="modal" data-bs-target="#pedidoModal">Pedido</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ícono flotante para ir al final de la página -->
    <a href="#footer" id="go-bottom" class="btn btn-primary btn-floating">
        <i class="bi bi-arrow-down-circle fs-3"></i>
    </a>

    <!-- Ícono flotante para ir al inicio de la página -->
    <a href="#top" id="go-top" class="btn btn-primary btn-floating d-none">
        <i class="bi bi-arrow-up-circle fs-3"></i>
    </a>

    <!-- Modal de Pedido -->
    <div class="modal fade" id="pedidoModal" tabindex="-1" aria-labelledby="pedidoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pedidoModalLabel">Enviar Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="pedido-form">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" placeholder="Ingresa tu nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="codigo" class="form-label">Código</label>
                            <input type="text" class="form-control" id="codigo" placeholder="Ingresa tu código" required>
                        </div>
                        <div class="mb-3">
                            <label for="nota" class="form-label">Nota</label>
                            <textarea class="form-control" id="nota" rows="3" placeholder="Ingresa una nota (opcional)"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" id="enviar-pedido" class="btn btn-primary">Enviar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="scripts.js"></script>
</body>
</html>