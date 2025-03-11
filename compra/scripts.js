$(document).ready(function() {
    function cargarProductos() {
        var tipoPrecio = $('#tipo-precio').val();
        console.log("Tipo de precio:", tipoPrecio);

        $.ajax({
            url: 'obtener_productos.php',
            type: 'GET',
            data: { tipo: tipoPrecio },
            success: function(response) {
                console.log("Respuesta AJAX:", response);
                $('#product-list').html(response);

                $('.product-button').click(function() {
                    var id = $(this).data('id');
                    var nombre = $(this).data('nombre');
                    var imagen = $(this).data('imagen');
                    var precio = $(this).data('precio_' + tipoPrecio);
                    var pv = $(this).data('pv_' + tipoPrecio);

                    console.log("ID:", id);
                    console.log("Precio:", precio);
                    console.log("PV:", pv);

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

    cargarProductos();

    $('#tipo-precio').change(function() {
        cargarProductos();
        actualizarCarrito();
    });

    function agregarAlCarrito(id, nombre, imagen, precio, pv, tipoPrecio,
        precio_publico, pv_publico, precio_afiliado, pv_afiliado,
        precio_junior, pv_junior, precio_senior, pv_senior,
        precio_master, pv_master) {
        // ... (resto de tu función agregarAlCarrito)
    }

    function actualizarCarrito() {
        // ... (resto de tu función actualizarCarrito)
    }

    function eliminarDelCarrito(id) {
        // ... (resto de tu función eliminarDelCarrito)
    }

    $('#limpiar-carrito').click(function() {
        localStorage.removeItem('carrito');
        actualizarCarrito();
    });

    actualizarCarrito();
});