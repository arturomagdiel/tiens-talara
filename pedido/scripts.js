$(document).ready(function () {
    let carrito = []; // Array para almacenar los productos del carrito

    // Cargar el carrito desde localStorage al iniciar
    function cargarCarritoDesdeLocalStorage() {
        const carritoGuardado = localStorage.getItem('carrito');
        if (carritoGuardado) {
            carrito = JSON.parse(carritoGuardado);
            actualizarPreciosCarrito('15'); // Actualizar los precios para el tipo de precio predeterminado (15%)
            actualizarCarrito(); // Actualizar el resumen del carrito
        }
    }

    // Guardar el carrito en localStorage
    function guardarCarritoEnLocalStorage() {
        localStorage.setItem('carrito', JSON.stringify(carrito));
    }

    // Función para cargar los productos
    function cargarProductos(tipoPrecio) {
        $.ajax({
            url: 'obtener_productos.php', // Archivo PHP que devuelve los productos
            type: 'GET',
            data: { tipo: tipoPrecio },
            success: function (response) {
                $('#product-list').html(response); // Insertar los productos en el contenedor
                actualizarPreciosCarrito(tipoPrecio); // Actualizar los precios del carrito
            },
            error: function () {
                alert('Error al cargar los productos.');
            }
        });
    }

    // Función para actualizar los precios de los productos en el carrito
    function actualizarPreciosCarrito(tipoPrecio) {
        carrito.forEach(producto => {
            // Buscar el producto en la lista de productos cargados
            const productoEnLista = $(`.product-button[data-id="${producto.id}"]`);

            if (productoEnLista.length > 0) {
                // Obtener los nuevos precios y PV desde los atributos data-*
                const nuevoPrecio = parseFloat(productoEnLista.data(`precio-${tipoPrecio}`));
                const nuevoPV = parseFloat(productoEnLista.data(`pv-${tipoPrecio}`));

                // Actualizar los precios en el carrito
                if (!isNaN(nuevoPrecio) && !isNaN(nuevoPV)) {
                    producto.precio = nuevoPrecio;
                    producto.pv = nuevoPV;
                }
            }
        });

        // Actualizar el resumen del carrito
        actualizarCarrito();
    }

    // Delegación de eventos para manejar el clic en las imágenes de los productos
    $(document).on('click', '.product-button', function (e) {
        e.preventDefault(); // Evitar que el enlace provoque el desplazamiento al inicio de la página

        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        const imagen = $(this).data('imagen');
        const precio = parseFloat($(this).data('precio'));
        const pv = parseFloat($(this).data('pv'));

        if (isNaN(precio) || isNaN(pv)) {
            alert('Error: El precio o PV no se cargaron correctamente.');
            return;
        }

        agregarAlCarrito(id, nombre, imagen, precio, pv);
        playClickSound(); // Reproducir el sonido de clic
    });

    // Función para agregar un producto al carrito
    function agregarAlCarrito(id, nombre, imagen, precio, pv) {
        const productoExistente = carrito.find(producto => producto.id === id);

        if (productoExistente) {
            productoExistente.cantidad++;
        } else {
            carrito.push({ id, nombre, imagen, precio, pv, cantidad: 1 });
        }

        guardarCarritoEnLocalStorage(); // Guardar el carrito en localStorage
        actualizarCarrito();
    }

    // Función para actualizar el resumen del carrito
    function actualizarCarrito() {
        let totalPrecio = 0;
        let totalPV = 0;
        $('#carrito-lista').empty(); // Limpiar la lista del carrito

        carrito.forEach(producto => {
            totalPrecio += producto.precio * producto.cantidad;
            totalPV += producto.pv * producto.cantidad;

            $('#carrito-lista').append(`
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="${producto.imagen}" alt="${producto.nombre}" class="img-thumbnail me-2" style="width: 50px; height: 50px;">
                        <div>
                            ${producto.nombre}<br> <!-- Elimina <strong> para quitar la negrita -->
                            <small>${producto.cantidad} x S/${producto.precio.toFixed(2)} (${producto.pv.toFixed(2)} PV)</small>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-warning reducir-producto" data-id="${producto.id}">
                        <i class="bi bi-dash-lg"></i> <!-- Icono de Bootstrap para el símbolo "-" -->
                    </button>
                </li>
            `);
        });

        $('#carrito-precio').text(totalPrecio.toFixed(2));
        $('#carrito-pv').text(totalPV.toFixed(2));

        if (carrito.length > 0) {
            $('#limpiar-carrito').show();
        } else {
            $('#limpiar-carrito').hide();
        }
    }

    // Función para eliminar un producto del carrito
    function eliminarDelCarrito(id) {
        carrito = carrito.filter(producto => producto.id !== id);
        guardarCarritoEnLocalStorage(); // Guardar el carrito en localStorage
        actualizarCarrito();
    }

    // Función para limpiar el carrito
    $('#limpiar-carrito').click(function () {
        carrito = []; // Vaciar el carrito
        guardarCarritoEnLocalStorage(); // Guardar el carrito vacío en localStorage
        actualizarCarrito(); // Actualizar el resumen del carrito
        playClickSound(); // Reproducir el sonido de clic
    });

    // Cargar productos inicialmente con el tipo de precio "15"
    cargarProductos('15');

    // Cargar el carrito desde localStorage
    cargarCarritoDesdeLocalStorage();

    // Cambiar el tipo de precio al seleccionar un radio button
    $('input[name="tipo-precio"]').change(function () {
        const tipoPrecio = $(this).val();
        cargarProductos(tipoPrecio);
        actualizarPreciosCarrito(tipoPrecio); // Actualizar los precios del carrito
    });

    // Delegación de eventos para manejar el clic en el botón de reducir producto
    $(document).on('click', '.reducir-producto', function () {
        const id = $(this).data('id');
        const producto = carrito.find(producto => producto.id === id);

        if (producto) {
            producto.cantidad--; // Reducir la cantidad en 1
            if (producto.cantidad <= 0) {
                carrito = carrito.filter(producto => producto.id !== id); // Eliminar el producto si la cantidad es 0
            }
        }

        guardarCarritoEnLocalStorage(); // Guardar el carrito actualizado en localStorage
        actualizarCarrito(); // Actualizar el resumen del carrito
        playClickSound(); // Reproducir el sonido de clic
    });

    // Función para reproducir el sonido de clic
    function playClickSound() {
        const clickSound = document.getElementById('clickSound');
        if (clickSound) {
            clickSound.currentTime = 0; // Reiniciar el sonido
            clickSound.play(); // Reproducir el sonido
        }
    }

    $('#enviar-pedido').click(function () {
        // Obtener los datos del formulario
        const nombre = $('#nombre').val();
        const codigo = $('#codigo').val();
        const nota = $('#nota').val();

        if (!nombre || !codigo) {
            alert('Por favor, completa los campos obligatorios.');
            return;
        }

        // Obtener el descuento seleccionado
        const descuentoSeleccionado = $('input[name="tipo-precio"]:checked').next('label').text();

        // Generar la lista de productos del carrito
        let mensaje = `*Pedido Tiens*\n\n`;
        mensaje += `*Fecha:* ${new Date().toLocaleDateString()}\n`;
        mensaje += `*Nombre:* ${nombre}\n`;
        mensaje += `*Código:* ${codigo} (${descuentoSeleccionado})\n`;
       
        if (nota) {
            mensaje += `*Nota:* ${nota}\n`;
        }
        mensaje += `\n*Productos:*\n`;

        carrito.forEach(producto => {
            mensaje += `- ${producto.cantidad} x ${producto.nombre} (S/${producto.precio.toFixed(2)} - ${producto.pv.toFixed(2)} PV)\n`;
        });

        // Agregar el total
        const totalPrecio = carrito.reduce((total, producto) => total + producto.precio * producto.cantidad, 0);
        const totalPV = carrito.reduce((total, producto) => total + producto.pv * producto.cantidad, 0);
        mensaje += `\n*Total:* S/${totalPrecio.toFixed(2)} - ${totalPV.toFixed(2)} PV`;

        // Enviar el mensaje a WhatsApp
        const telefono = '+51969640856'; // Reemplaza con el número de WhatsApp
        const url = `https://wa.me/${telefono}?text=${encodeURIComponent(mensaje)}`;
        window.open(url, '_blank');

        // Cerrar el modal
        $('#pedidoModal').modal('hide');
    });
});