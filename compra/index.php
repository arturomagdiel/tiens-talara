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
<audio id="clickSound" src="../sonidos/click1.mp3" preload="auto"></audio>

<div class="container">
    <div class="row d-flex flex-wrap">
        <div class="col-12 d-md-none" id="col-der-movil">
            <div class="row">
                <h3>Carrito (<span id="carrito-cantidad">0</span>)</h3>
                <div class="col sidebar-fixed">
                    <ul id="carrito-lista-movil">
                        <div class="carrito-items-container">
                        </div>
                    </ul>
                    <div id="carrito">
                        <p>S/<span id="carrito-precio">0</span> - <span id="carrito-pv">0</span>PV</p>
                    </div>
                    <button id="limpiar-carrito" class="btn btn-secondary btn-sm">
                        <i class="bi bi-trash"></i> Limpiar
                    </button>
                    <button id="solicitar-whatsapp" class="btn btn-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modal-whatsapp">
                        <i class="bi bi-whatsapp"></i> Enviar
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-9" id="col-izq">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h3>Calcula tu Compra</h3>
                </div>
                <div class="col-md-6">
                    <div class="btn-group" role="group" aria-label="Tipo de precio">
                        <input type="radio" class="btn-check" name="tipo-precio" id="publico" autocomplete="off"
                               value="publico">
                        <label class="btn btn-outline-dark" for="publico">Publico</label>
                        <input type="radio" class="btn-check" name="tipo-precio" id="afiliado" autocomplete="off"
                               value="afiliado">
                        <label class="btn btn-outline-secondary" for="afiliado">Afiliado</label>
                        <input type="radio" class="btn-check" name="tipo-precio" id="junior" autocomplete="off"
                               value="junior">
                        <label class="btn btn-outline-primary" for="junior">5%</label>
                        <input type="radio" class="btn-check" name="tipo-precio" id="senior" autocomplete="off"
                               value="senior">
                        <label class="btn btn-outline-warning" for="senior">8%</label>
                        <input type="radio" class="btn-check" name="tipo-precio" id="master" autocomplete="off"
                               value="master" checked>
                        <label class="btn btn-outline-danger active" for="master">15%</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div id="product-list" class="row row-cols-3 row-cols-sm-3 row-cols-md-3 row-cols-lg-5 g-4">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 d-none d-md-block" id="col-der">
            <div class="row">
                <h3>Carrito (<span id="carrito-cantidad">0</span>)</h3>
                <div class="col sidebar-fixed">
                    <ul id="carrito-lista"></ul>
                    <div id="carrito">
                        <p>S/<span id="carrito-precio">0</span> - <span id="carrito-pv">0</span>PV</p>
                    </div>
                    <button id="limpiar-carrito" class="btn btn-secondary btn-sm">
                        <i class="bi bi-trash"></i> Limpiar
                    </button>
                    <button id="solicitar-whatsapp" class="btn btn-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modal-whatsapp">
                        <i class="bi bi-whatsapp"></i> Enviar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-whatsapp" tabindex="-1" aria-labelledby="modal-whatsapp-label"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-whatsapp-label">Enviar por WhatsApp</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre-codigo" class="form-label">Nombre y Codigo</label>
                        <input type="text" class="form-control" id="nombre-codigo">
                    </div>
                    <div class="mb-3">
                        <label for="notas-pedido" class="form-label">Notas</label>
                        <textarea class="form-control" id="notas-pedido" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="enviar-whatsapp">Enviar a WhatsApp</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="scripts.js"></script>
</body>
</html>