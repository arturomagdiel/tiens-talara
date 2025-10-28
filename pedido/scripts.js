$(document).ready(function () {
    let carrito = []; // Array para almacenar los productos del carrito
    let tipoPrecioSeleccionado = '15'; // Tipo de precio seleccionado por defecto

    // Cargar el carrito desde localStorage al iniciar
    function cargarCarritoDesdeLocalStorage() {
        const carritoGuardado = localStorage.getItem('carrito');
        if (carritoGuardado) {
            carrito = JSON.parse(carritoGuardado);
            actualizarPreciosCarrito(tipoPrecioSeleccionado); // Actualizar los precios para el tipo de precio seleccionado
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
        const precio = parseFloat($(this).data(`precio-${tipoPrecioSeleccionado}`)); // Obtener el precio según el tipo seleccionado
        const pv = parseFloat($(this).data(`pv-${tipoPrecioSeleccionado}`)); // Obtener el PV según el tipo seleccionado

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
        
        // Mostrar modal de confirmación
        mostrarModalProductoAgregado(nombre);
    }
    
    // Función para mostrar modal de producto agregado
    function mostrarModalProductoAgregado(nombreProducto) {
        // Actualizar el nombre del producto en el modal
        document.getElementById('nombreProductoAgregado').textContent = nombreProducto;
        
        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('modalProductoAgregado'));
        modal.show();
        
        // Auto-cerrar el modal después de 2 segundos
        setTimeout(() => {
            modal.hide();
        }, 2000);
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
                    <div class="col-3">
                        <img src="${producto.imagen}" alt="${producto.nombre}" class="img-fluid rounded" style="max-width: 100%; height: auto;">
                    </div>
                    <div class="col-6">
                        <div class="fw-bold">${producto.nombre}</div>
                        <small>${producto.cantidad} x S/${producto.precio.toFixed(2)} (${producto.pv.toFixed(2)} PV)</small>
                    </div>
                    <div class="col-3 text-end">
                        <button class="btn btn-sm btn-warning reducir-producto" data-id="${producto.id}">
                            <i class="bi bi-dash-lg"></i>
                        </button>
                        <button class="btn btn-sm btn-success aumentar-producto ms-1" data-id="${producto.id}">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>                        
                    </div>
                </li>
            `);
        });

        $('#carrito-precio').text(totalPrecio.toFixed(2));
        $('#carrito-pv').text(totalPV.toFixed(2));

        if (carrito.length > 0) {
            $('#limpiar-carrito').show();
            $('#pedido-btn').show();
        } else {
            $('#limpiar-carrito').hide();
            $('#pedido-btn').hide();
        }
    }

    // Nueva función para cambiar la cantidad de un producto
    function cambiarCantidad(id, accion) {
        const producto = carrito.find(producto => producto.id === id);

        if (producto) {
            if (accion === 'aumentar') {
                producto.cantidad++; // Aumentar la cantidad en 1
            } else if (accion === 'reducir') {
                producto.cantidad--; // Reducir la cantidad en 1
                if (producto.cantidad <= 0) {
                    carrito = carrito.filter(producto => producto.id !== id); // Eliminar el producto si la cantidad es 0
                }
            }
        }

        guardarCarritoEnLocalStorage(); // Guardar el carrito actualizado en localStorage
        actualizarCarrito(); // Actualizar el resumen del carrito
        playClickSound(); // Reproducir el sonido de clic
    }

    // Delegación de eventos para manejar el clic en el botón de reducir producto
    $(document).on('click', '.reducir-producto', function () {
        const id = $(this).data('id');
        cambiarCantidad(id, 'reducir'); // Llamar a la función con la acción "reducir"
    });

    // Delegación de eventos para manejar el clic en el botón de aumentar producto
    $(document).on('click', '.aumentar-producto', function () {
        const id = $(this).data('id');
        cambiarCantidad(id, 'aumentar'); // Llamar a la función con la acción "aumentar"
    });

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

    // Cambiar el tipo de precio al seleccionar un radio button
    $('input[name="tipo-precio"]').change(function () {
        tipoPrecioSeleccionado = $(this).val(); // Actualizar el tipo de precio seleccionado
        cargarProductos(tipoPrecioSeleccionado);
        actualizarPreciosCarrito(tipoPrecioSeleccionado); // Actualizar los precios del carrito
    });

    // Cargar productos inicialmente con el tipo de precio "15"
    cargarProductos(tipoPrecioSeleccionado);

    // Cargar el carrito desde localStorage
    cargarCarritoDesdeLocalStorage();

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

document.addEventListener('DOMContentLoaded', function () {
    const pageTitle = document.getElementById('page-title');
    if (pageTitle) {
        pageTitle.textContent = 'Registrar Pedido'; // Cambia este texto según la página
    }
});