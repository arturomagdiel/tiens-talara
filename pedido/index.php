<!DOCTYPE html>
<html>
<head>
    <title>Calcula tu compra Tiens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="../images/tiens.ico">
</head>
<body>
<audio id="errorSound" src="../sonidos/click1.mp3" preload="auto"></audio>
<audio id="successSound" src="../sonidos/click1.mp3" preload="auto"></audio>   
<audio id="clickSound" src="../sonidos/click1.mp3" preload="auto"></audio>

<div class="container">
    <div class="row d-flex flex-wrap">
        <!-- Columna izquierda: Productos -->
        <div class="col-12 col-md-9" id="col-izq">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h3>Calcula tu Compra</h3>
                </div>
                <div class="col-md-6">
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
            </div>
            <div class="row">
                <div class="col">
                    <div id="product-list" class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4">
                        <!-- Los productos se cargarán aquí mediante AJAX -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna derecha: Carrito -->
        <div class="col-12 col-md-3" id="col-der">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h3>Resumen de Compra</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" id="resumen-compra">
                    <ul id="carrito-lista" class="list-group mb-3">
                        <!-- Productos del carrito -->
                    </ul>
                    <p class="text-end"><strong>Total:</strong> S/<span id="carrito-precio">0.00</span> - <span id="carrito-pv">0.00</span> PV</p>
                    <div class="row">
                        <div class="col-md-6">
                            <button id="limpiar-carrito" class="btn btn-danger btn-sm w-100">Limpiar Carrito</button>
                        </div>
                        <div class="col-md-6">
                            <button id="pedido-btn" class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#pedidoModal">PEDIDO</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="scripts.js"></script> <!-- Vincular el archivo scripts.js -->
</body>
</html>