$(document).ready(function() {
    var tipoPrecio = "master"; // Establecer "master" como predeterminado

    function cargarProductos() {
        //console.log("Tipo de precio:", tipoPrecio);

        $.ajax({
            url: 'obtener_productos.php',
            type: 'GET',
            data: { tipo: tipoPrecio },
            success: function(response) {
                //console.log("Respuesta AJAX:", response);
                $('#product-list').html(response);

                $('.product-button').click(function() {
                    var id = $(this).data('id');
                    var nombre = $(this).data('nombre');
                    var imagen = $(this).data('imagen');
                    var precio = $(this).data('precio_' + tipoPrecio);
                    var pv = $(this).data('pv_' + tipoPrecio);

                    // console.log("ID:", id);
                    // console.log("Precio:", precio);
                    // console.log("PV:", pv);

                    agregarAlCarrito(id, nombre, imagen, precio, pv, tipoPrecio,
                        $(this).data('precio_publico'), $(this).data('pv_publico'),
                        $(this).data('precio_afiliado'), $(this).data('pv_afiliado'),
                        $(this).data('precio_junior'), $(this).data('pv_junior'),
                        $(this).data('precio_senior'), $(this).data('pv_senior'),
                        $(this).data('precio_master'), $(this).data('pv_master'));
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.error(xhr.status);
                console.error(thrownError);
            }
        });
    }

    

    $('input[name="tipo-precio"]').change(function() {
        tipoPrecio = $(this).val();
        cargarProductos();
        actualizarCarrito();
        $('input[name="tipo-precio"]').next('label').removeClass('active');
        $(this).next('label').addClass('active');
    });

    function agregarAlCarrito(id, nombre, imagen, precio, pv, tipoPrecio,
        precio_publico, pv_publico, precio_afiliado, pv_afiliado,
        precio_junior, pv_junior, precio_senior, pv_senior,
        precio_master, pv_master) {
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        let productoExistente = carrito.find(item => item.id === id);

        if (productoExistente) {
            productoExistente.cantidad++;
        } else {
            precio = parseFloat(precio);
            pv = parseFloat(pv);

            let nuevoProducto = {
                id: id,
                nombre: nombre,
                imagen: imagen,
                cantidad: 1,
                precio_publico: parseFloat(precio_publico),
                pv_publico: parseFloat(pv_publico),
                precio_afiliado: parseFloat(precio_afiliado),
                pv_afiliado: parseFloat(pv_afiliado),
                precio_junior: parseFloat(precio_junior),
                pv_junior: parseFloat(pv_junior),
                precio_senior: parseFloat(precio_senior),
                pv_senior: parseFloat(pv_senior),
                precio_master: parseFloat(precio_master),
                pv_master: parseFloat(pv_master)
            };
            carrito.push(nuevoProducto);
        }

        const clickSound = document.getElementById("clickSound");
        clickSound.play();
        localStorage.setItem('carrito', JSON.stringify(carrito));
        actualizarCarrito();
    }

    function actualizarCarrito() {

        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        console.log("Carrito:", carrito);

        var cantidad = 0;
        var precioTotal = 0;
        var pvTotal = 0;

        carrito.forEach(function(item) {
            cantidad += item.cantidad;

            var precio = parseFloat(item["precio_" + tipoPrecio]).toFixed(2);
            var pv = parseFloat(item["pv_" + tipoPrecio]).toFixed(2);

            precioTotal = (parseFloat(precioTotal) + parseFloat(precio) * item.cantidad).toFixed(2);
            pvTotal = (parseFloat(pvTotal) + parseFloat(pv) * item.cantidad).toFixed(2);
        });

        $('#carrito-cantidad').text(cantidad);
        $('#carrito-precio').text(precioTotal);
        $('#carrito-pv').text(pvTotal);

        var carritoLista = $('#carrito-lista');
        carritoLista.empty();

        carrito.forEach(function(item) {
            var listItem = $("<li></li>");
            listItem.html(`
                <div class="card mb-1">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="card-title mb-0">${item.cantidad} ${item.nombre}</h6>
                            <p class="card-text mb-0">S/${parseFloat(item["precio_" + tipoPrecio]).toFixed(2)} (${parseFloat(item["pv_" + tipoPrecio]).toFixed(2)})PV</p>
                        </div>
                        <button class="btn btn-outline-danger btn-sm eliminar-producto" data-id="${item.id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `);
            carritoLista.append(listItem);
        });

        $('.eliminar-producto').click(function() {
            var id = $(this).data('id');
            eliminarDelCarrito(id);
        });

        if (carrito.length > 0) {
            $('#limpiar-carrito, #solicitar-whatsapp').show();
        } else {
            $('#limpiar-carrito, #solicitar-whatsapp').hide();
        }
    }

    function eliminarDelCarrito(id) {
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const index = carrito.findIndex(item => item.id === id);

        if (index > -1) {
            if (carrito[index].cantidad > 1) {
                carrito[index].cantidad--;
            } else {
                carrito.splice(index, 1);
            }

            const clickSound = document.getElementById("clickSound");
            clickSound.play();
            localStorage.setItem('carrito', JSON.stringify(carrito));
            actualizarCarrito();
        }
    }

    $('#limpiar-carrito').click(function() {
        localStorage.removeItem('carrito');
        actualizarCarrito();
    });

    $('#enviar-whatsapp').click(function() {
        var nombreCodigo = $('#nombre-codigo').val();
        var notasPedido = $('#notas-pedido').val();
        var carrito = JSON.parse(localStorage.getItem('carrito')) || [];

        var fechaActual = new Date();
        var anio = fechaActual.getFullYear();
        var mes = String(fechaActual.getMonth() + 1).padStart(2, '0');
        var dia = String(fechaActual.getDate()).padStart(2, '0');
        var fechaFormateada = anio + '-' + mes + '-' + dia;

        // Calcular totales
        var totalProductos = 0;
        var totalPV = 0;
        var totalPagar = 0;

        carrito.forEach(function(item) {
            totalProductos += item.cantidad;
            totalPV += item.cantidad * parseFloat(item["pv_" + tipoPrecio]);
            totalPagar += item.cantidad * parseFloat(item["precio_" + tipoPrecio]);
        });

        var mensaje = "Pedido Tiens\n\nFecha del Pedido: " + fechaFormateada + "\nNombre y Codigo: " + nombreCodigo + "\nNotas del Pedido: " + notasPedido + "\n\nProductos:\n";

        carrito.forEach(function(item) {
            mensaje += "- " + item.cantidad + " " + item.nombre + " (S/" + parseFloat(item["precio_" + tipoPrecio]).toFixed(2) + ")\n";
        });

        mensaje += "\nTotal Productos: " + totalProductos;
        mensaje += "\nTotal PV: " + totalPV.toFixed(2);
        mensaje += "\nTotal a Pagar: S/" + totalPagar.toFixed(2);

        var numeroWhatsApp = "+51969640856";
        var urlWhatsApp = "https://wa.me/" + numeroWhatsApp + "?text=" + encodeURIComponent(mensaje);

        window.open(urlWhatsApp, '_blank');
        $('#modal-whatsapp').modal('hide');
    });

    actualizarCarrito();
    cargarProductos();
});