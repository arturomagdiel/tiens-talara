<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiens Talara - Calcula tu compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        body { background: #f8f9fa; }
        .product-card { transition: box-shadow .2s; cursor: pointer; }
        .product-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.12); }
        .product-img {
  height: 140px;
  object-fit: contain;
  padding: 10px;
  background-color: #fff;
}
        .offcanvas-cart .offcanvas-body { padding: 0; }
        .cart-product-img { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; }
        @media (max-width: 991px) {
            .cart-panel { display: none !important; }
            .offcanvas-cart {
    top: 56px !important; /* Altura navbar Bootstrap por defecto */
    height: calc(100% - 56px) !important;
  }
        }
        @media (min-width: 992px) {
            .offcanvas-cart { display: none !important; }
        }
    </style>
</head>
<body>
    <!-- Barra superior -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm mb-3 sticky-top" style="top:0;z-index:1040;">
  <div class="container">
    <div class="w-100">
      <!-- Fila 1: Mi Compra + Público / Afiliado -->
      <div class="row align-items-center gy-2">
        <div class="col-12 col-lg-auto d-flex align-items-center gap-2 flex-wrap">
          <a class="navbar-brand fw-bold text-success me-2" href="#"><i class="bi bi-leaf"></i> Mi Compra</a>
          <div class="btn-group" role="group" aria-label="Tipo de precio">
            <input type="radio" class="btn-check" name="tipo-precio" id="publico" value="publico" autocomplete="off">
            <label class="btn btn-outline-dark" for="publico">Público</label>
            <input type="radio" class="btn-check" name="tipo-precio" id="afiliado" value="afiliado" autocomplete="off" checked>
            <label class="btn btn-outline-success" for="afiliado">Afiliado</label>
          </div>
        </div>

        <!-- Fila 2 (si móvil) o columna derecha (si desktop) -->
        <div class="col-12 col-lg text-lg-end d-flex flex-wrap justify-content-start justify-content-lg-end gap-2 mt-2 mt-lg-0">
          <div class="btn-group" role="group" aria-label="Descuentos">
            <input type="radio" class="btn-check" name="descuento" id="desc0" value="0" autocomplete="off">
            <label class="btn btn-outline-secondary" for="desc0">Sin desc.</label>
            <input type="radio" class="btn-check" name="descuento" id="desc5" value="5" autocomplete="off">
            <label class="btn btn-outline-primary" for="desc5">5%</label>
            <input type="radio" class="btn-check" name="descuento" id="desc8" value="8" autocomplete="off">
            <label class="btn btn-outline-warning" for="desc8">8%</label>
            <input type="radio" class="btn-check" name="descuento" id="desc15" value="15" autocomplete="off" checked>
            <label class="btn btn-outline-danger" for="desc15">15%</label>
          </div>

          <!-- Carrito -->
          <button class="btn btn-success d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
            <i class="bi bi-cart4 fs-5"></i>
            <span class="cart-count-mobile badge bg-danger ms-1">0</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</nav>


    <div class="container">
        <div class="row">
            <!-- Grid de productos -->
            <div class="col-12 col-lg-9 mb-4">
                <!-- Elimina el botón "Ver resumen de compra" aquí -->
                <div class="row g-4 row-cols-2 row-cols-md-3 row-cols-lg-5" id="product-list">
                    <!-- Productos AJAX aquí -->
                </div>
            </div>
            <!-- Panel lateral carrito escritorio -->
            <div class="col-lg-3 cart-panel">
                <div class="card shadow-sm sticky-top" style="top: 80px;">
                    <div class="card-header bg-success text-white text-center">
                        <h5 class="mb-0"><i class="bi bi-cart4"></i> Resumen de Compra</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush" id="cart-list"></ul>
                        <div class="p-3 border-top">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total:</span>
                                <span class="fw-bold text-success">S/<span id="cart-total">0.00</span></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Puntos PV:</span>
                                <span class="fw-bold text-primary"><span id="cart-pv">0.00</span> PV</span>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-danger w-50 btn-sm" id="cart-clear">Limpiar</button>
                                <button class="btn btn-success w-50 btn-sm" id="cart-order" data-bs-toggle="modal" data-bs-target="#orderModal">Pedido</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas carrito móvil -->
    <div class="offcanvas offcanvas-end offcanvas-cart" tabindex="-1" id="cartOffcanvas">
        <div class="offcanvas-header bg-success text-white">
            <h5 class="offcanvas-title"><i class="bi bi-cart4"></i> Resumen de Compra</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <ul class="list-group list-group-flush" id="cart-list-mobile"></ul>
            <div class="p-3 border-top">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total:</span>
                    <span class="fw-bold text-success">S/<span id="cart-total-mobile">0.00</span></span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Puntos PV:</span>
                    <span class="fw-bold text-primary"><span id="cart-pv-mobile">0.00</span> PV</span>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-danger w-50 btn-sm" id="cart-clear-mobile">Limpiar</button>
                    <button class="btn btn-success w-50 btn-sm" id="cart-order-mobile" data-bs-toggle="modal" data-bs-target="#orderModal">Pedido</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pedido -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="order-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Realizar Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" class="form-control" id="codigo" required>
                    </div>
                    <div class="mb-3">
                        <label for="nota" class="form-label">Nota</label>
                        <textarea class="form-control" id="nota" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Enviar Pedido</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    // --- CONFIGURACIÓN ---
    const PRODUCTOS_URL = 'obtener-productos2.php';

    // --- ESTADO ---
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    let tipoPrecio = localStorage.getItem('tipoPrecio') || 'afiliado';
    let descuento = localStorage.getItem('descuento') !== null ? parseInt(localStorage.getItem('descuento')) : 15;
    let productos = [];

    // --- FUNCIONES DE UI ---
    function renderProductos() {
        $('#product-list').html('');
        productos.forEach(prod => {
            let precio = getPrecio(prod);
            let pv = getPV(prod);
            $('#product-list').append(`
                <div class="col">
                    <div class="card product-card h-100" data-id="${prod.id}">
                        <img src="${prod.imagen}" class="product-img card-img-top" alt="${prod.nombre}">
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title fw-bold mb-1">${prod.nombre}</h6>
                            <div class="mb-2">
                                <span class="fw-bold text-success">S/${precio.toFixed(2)}</span>
                                <span class="text-primary ms-2">${pv.toFixed(2)} PV</span>
                            </div>
                            <button class="btn btn-outline-success mt-auto w-100 btn-add-cart">Agregar</button>
                        </div>
                    </div>
                </div>
            `);
        });
    }

    function renderCarrito() {
        // Desktop
        let html = '';
        let total = 0, totalPV = 0, totalItems = 0;
        carrito.forEach(item => {
            let precio = getPrecio(item);
            let pv = getPV(item);
            total += precio * item.cantidad;
            totalPV += pv * item.cantidad;
            totalItems += item.cantidad;
            html += `
                <li class="list-group-item d-flex align-items-center gap-2">
                    <img src="${item.imagen}" class="cart-product-img me-2" alt="${item.nombre}">
                    <div class="flex-grow-1">
                        <div class="fw-bold">${item.nombre}</div>
                        <div class="small text-success">S/${precio.toFixed(2)} <span class="text-primary ms-2">${pv.toFixed(2)} PV</span></div>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <button class="btn btn-sm btn-outline-secondary btn-restar" data-id="${item.id}">-</button>
                        <span class="mx-1">${item.cantidad}</span>
                        <button class="btn btn-sm btn-outline-secondary btn-sumar" data-id="${item.id}">+</button>
                    </div>
                </li>
            `;
        });
        $('#cart-list').html(html);
        $('#cart-total').text(total.toFixed(2));
        $('#cart-pv').text(totalPV.toFixed(2));
        $('.cart-count-mobile').text(totalItems); // Si tienes más de uno, usa esta clase

        // Mobile
        $('#cart-list-mobile').html(html);
        $('#cart-total-mobile').text(total.toFixed(2));
        $('#cart-pv-mobile').text(totalPV.toFixed(2));
    }

    // --- LÓGICA DE PRECIOS ---
    function round2(num) {
        // Convierte a string con 3 decimales
        let str = num.toFixed(3);
        let parts = str.split('.');
        if (parts.length === 2 && parts[1].length === 3) {
            let third = parseInt(parts[1][2]);
            if (third >= 5) {
                // Redondea hacia arriba solo si hay tercer decimal >= 5
                return (Math.floor(num * 100) + 1) / 100;
            } else {
                // Trunca a dos decimales
                return Math.floor(num * 100) / 100;
            }
        }
        // Si no hay tercer decimal, retorna con dos decimales normal
        return parseFloat(num.toFixed(2));
    }

    function getPrecio(prod) {
        if (tipoPrecio === 'publico') return parseFloat(prod.precio_publico);
        let base = parseFloat(prod.precio_afiliado);
        let precio = base - (base * descuento / 100);
        return round2(precio);
    }
    function getPV(prod) {
        if (tipoPrecio === 'publico') return 0;
        let base = parseFloat(prod.pv_afiliado);
        let pv = base - (base * descuento / 100);
        return round2(pv);
    }

    // --- EVENTOS ---
    $(document).on('click', '.product-card', function(e) {
        // Evita que el botón "Agregar" duplique el evento
        if ($(e.target).hasClass('btn-add-cart')) return;
        let id = $(this).data('id');
        let prod = productos.find(p => p.id == id);
        let item = carrito.find(p => p.id == id);
        if (item) item.cantidad++;
        else carrito.push({...prod, cantidad: 1});
        saveCarrito();
        renderCarrito();
    });

    $(document).on('click', '.btn-add-cart', function(e) {
        e.stopPropagation(); // Evita doble evento
        let id = $(this).closest('.product-card').data('id');
        let prod = productos.find(p => p.id == id);
        let item = carrito.find(p => p.id == id);
        if (item) item.cantidad++;
        else carrito.push({...prod, cantidad: 1});
        saveCarrito();
        renderCarrito();
    });

    $(document).on('click', '.btn-restar', function() {
        let id = $(this).data('id');
        let item = carrito.find(p => p.id == id);
        if (item) {
            item.cantidad--;
            if (item.cantidad <= 0) carrito = carrito.filter(p => p.id != id);
            saveCarrito();
            renderCarrito();
        }
    });

    $(document).on('click', '.btn-sumar', function() {
        let id = $(this).data('id');
        let item = carrito.find(p => p.id == id);
        if (item) {
            item.cantidad++;
            saveCarrito();
            renderCarrito();
        }
    });

    $('#cart-clear, #cart-clear-mobile').click(function() {
        carrito = [];
        saveCarrito();
        renderCarrito();
    });

    $('input[name="tipo-precio"]').change(function() {
        tipoPrecio = $(this).val();
        localStorage.setItem('tipoPrecio', tipoPrecio);

        if (tipoPrecio === 'publico') {
            // Desmarca todos los descuentos y marca solo "Sin desc."
            $('input[name="descuento"]').prop('checked', false);
            //$('#desc0').prop('checked', true);
        }

        renderProductos();
        renderCarrito();
    });

    // Cuando se marca un descuento, activa "Afiliado" automáticamente
    $('input[name="descuento"]').change(function() {
        descuento = parseInt($(this).val());
        localStorage.setItem('descuento', descuento);

        // Si se marca un descuento, activa "Afiliado"
        $('#afiliado').prop('checked', true);
        tipoPrecio = 'afiliado';
        localStorage.setItem('tipoPrecio', tipoPrecio);

        renderProductos();
        renderCarrito();
    });

    // --- PEDIDO ---
    $('#order-form').submit(function(e){
        e.preventDefault();

        // Obtener datos del formulario
        const nombre = $('#nombre').val().trim();
        const codigo = $('#codigo').val().trim();
        const nota = $('#nota').val().trim();

        // Fecha y hora actual
        const now = new Date();
        const fecha = now.toLocaleDateString();
        const hora = now.toLocaleTimeString();

        // Construir lista de productos
        let productosTxt = '';
        let total = 0, totalPV = 0;
        carrito.forEach(item => {
            const precio = getPrecio(item);
            const pv = getPV(item);
            total += precio * item.cantidad;
            totalPV += pv * item.cantidad;
            productosTxt += `• ${item.nombre} x${item.cantidad} (S/${precio.toFixed(2)}, ${pv.toFixed(2)} PV)\n`;
        });

        // Mensaje final con iconos Unicode
        const mensaje = 
`🗓️ Fecha: ${fecha}
⏰ Hora: ${hora}
👤 Nombre: ${nombre} (COD: ${codigo})
📝 Nota: ${nota ? nota : '-'}
--------------------------
📦 LISTA DE PRODUCTOS:
${productosTxt}
--------------------------
💰 Total: S/${total.toFixed(2)}
🏅 Total PV: ${totalPV.toFixed(2)}
`;

        // Enviar a WhatsApp
        const telefono = '51969640856';
        const url = `https://wa.me/${telefono}?text=${encodeURIComponent(mensaje)}`;
        window.open(url, '_blank');

        $('#orderModal').modal('hide');
    });

    // --- LOCALSTORAGE ---
    function saveCarrito() {
        localStorage.setItem('carrito', JSON.stringify(carrito));
    }

    // --- CARGA DE PRODUCTOS ---
    function cargarProductos() {
        $.getJSON(PRODUCTOS_URL + '?tipo=' + tipoPrecio, function(data){
            productos = data;
            renderProductos();
            renderCarrito();
        });
    }

    // --- INICIALIZACIÓN ---
    $(function(){
        // Fuerza valores iniciales si no existen en localStorage
        if (!localStorage.getItem('tipoPrecio')) {
            tipoPrecio = 'afiliado';
            localStorage.setItem('tipoPrecio', tipoPrecio);
            $('#afiliado').prop('checked', true);
        }
        if (!localStorage.getItem('descuento')) {
            descuento = 15;
            localStorage.setItem('descuento', descuento);
            $('#desc15').prop('checked', true);
        } else {
            // Sincroniza radios con localStorage
            $('#desc' + descuento).prop('checked', true);
            if (tipoPrecio === 'afiliado') $('#afiliado').prop('checked', true);
            else $('#publico').prop('checked', true);
        }

        renderProductos();
        renderCarrito();
        cargarProductos();
    });
    </script>

    <script>
        function playClickSound() {
  const ctx = new (window.AudioContext || window.webkitAudioContext)();
  const oscillator = ctx.createOscillator();
  const gain = ctx.createGain();

  oscillator.type = 'sine'; // También puedes probar: 'sine', 'triangle', 'sawtooth'
  oscillator.frequency.setValueAtTime(600, ctx.currentTime); // Frecuencia en Hz

  gain.gain.setValueAtTime(0.1, ctx.currentTime); // Volumen
  gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.05); // Desvanecer rápido

  oscillator.connect(gain);
  gain.connect(ctx.destination);
  oscillator.start();
  oscillator.stop(ctx.currentTime + 0.05); // duración corta
}

$(document).on('click', '.btn-add-cart, .btn-restar, .btn-sumar, #cart-clear, #cart-clear-mobile, input[name="tipo-precio"], input[name="descuento"], #cart-order, #cart-order-mobile, .product-card', function(e) {
    // Solo ejecuta el sonido si el click no fue sobre el botón "Agregar" dentro del card (para evitar doble sonido)
    if ($(this).hasClass('product-card') && $(e.target).hasClass('btn-add-cart')) return;
    playClickSound();
});

    </script>

    <!-- Modal de confirmación de producto agregado -->
    <div class="modal fade" id="modalProductoAgregado" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="modal-title mb-2">¡Producto agregado!</h5>
                    <p class="mb-0" id="mensajeProductoAgregado">Se agregó <strong id="nombreProductoAgregado"></strong> al carrito</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>