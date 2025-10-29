document.addEventListener('DOMContentLoaded', function () {
    const pageTitle = document.getElementById('page-title');
    if (pageTitle) {
        pageTitle.textContent = 'Registrar Compras'; // Cambia este texto según la página
    }
});


document.addEventListener('DOMContentLoaded', function () {
    const personaBusqueda = document.getElementById('persona-busqueda');
    const personaLista = document.getElementById('persona-lista');
    const productoBusqueda = document.getElementById('producto-busqueda');
    const productoLista = document.getElementById('producto-lista');
    const productosLista = document.getElementById('productos-lista');
    const totalPagar = document.getElementById('total-pagar');
    const totalPVDisplay = document.getElementById('total-pv-display'); // Referenciar el nuevo elemento
    const comenzarNuevo = document.getElementById('comenzar-nuevo');
    const descuentoPersona = document.getElementById('descuento-persona');
    const actualizarDescuento = document.getElementById('actualizar-descuento');
    const modalConfirmacion = new bootstrap.Modal(document.getElementById('modalConfirmacion'));
    const guardarCompra = document.getElementById('guardar-compra');
        const modalPago = new bootstrap.Modal(document.getElementById('modalPago'));
    const confirmarPagoBtn = document.getElementById('confirmar-pago');
    const modalConfirmacionCompra = new bootstrap.Modal(document.getElementById('modalConfirmacionCompra'));
    const modalAlertaPago = new bootstrap.Modal(document.getElementById('modalAlertaPago'));

    let personaSeleccionada = null; // Variable para almacenar la persona seleccionada
    let descuentoSeleccionado = 0; // Inicializar el descuento seleccionado con 0
    let liquidacionNota = ''; // Variable para almacenar la nota del pago

    // Deshabilitar búsqueda de productos inicialmente
    productoBusqueda.disabled = true;
    productoBusqueda.placeholder = 'Primero selecciona una persona';
    productoBusqueda.style.backgroundColor = '#f8f9fa';
    productoBusqueda.style.color = '#6c757d';

    // Manejar la selección de una persona
    personaBusqueda.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        personaLista.innerHTML = ''; // Limpiar resultados anteriores

        // No buscar si hay menos de 3 caracteres
        if (query.length < 3) {
            return;
        }

        const resultados = personas.filter(persona =>
            persona.nombre.toLowerCase().includes(query) || persona.codigo.toLowerCase().includes(query)
        );

        if (resultados.length === 0) {
            personaLista.innerHTML = '<p class="text-muted">No se encontraron resultados.</p>';
            return;
        }

        resultados.forEach(persona => {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'list-group-item list-group-item-action';
            item.textContent = `${persona.nombre} (${persona.codigo}) - Descuento: ${persona.descuento}`;
            item.dataset.id = persona.id;
            item.dataset.codigo = persona.codigo;
            item.dataset.nombre = persona.nombre;
            item.dataset.descuento = persona.descuento;

            // Seleccionar persona al hacer clic
            item.addEventListener('click', function () {
                personaBusqueda.value = `${persona.nombre} (${persona.codigo})`;
                personaSeleccionada = persona; // Guardar la persona seleccionada
                console.log('Persona seleccionada:', personaSeleccionada);

                // Actualizar el valor del descuento seleccionado
                descuentoSeleccionado = parseInt(persona.descuento.replace('%', '')) || 0;

                // Actualizar el valor del dropdown de descuento
                descuentoPersona.value = descuentoSeleccionado;

                personaLista.innerHTML = ''; // Limpiar la lista
  
                // Deshabilitar el campo de búsqueda para evitar cambios
                personaBusqueda.disabled = true;

                // Cambiar el fondo del campo de búsqueda a gris
                personaBusqueda.style.backgroundColor = '#e9ecef'; // Color gris claro (Bootstrap-like)

                // Habilitar búsqueda de productos
                productoBusqueda.disabled = false;
                productoBusqueda.placeholder = 'Buscar producto por nombre o código';
                productoBusqueda.style.backgroundColor = '';
                productoBusqueda.style.color = '';
                productoBusqueda.focus();

                // Mostrar el botón "Comenzar de nuevo"
                comenzarNuevo.style.display = 'inline-block';
            });

            personaLista.appendChild(item);
        });
    });

    // Manejar la búsqueda de productos
    productoBusqueda.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        productoLista.innerHTML = ''; // Limpiar resultados anteriores

        if (query) {
            const resultados = productos.filter(producto =>
                producto.nombre.toLowerCase().includes(query) || producto.codigo.toLowerCase().includes(query)
            );

            resultados.forEach(producto => {
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'list-group-item list-group-item-action';
                item.textContent = `${producto.nombre} (${producto.codigo}) - Precio: S/${producto.precio_afiliado} - PV: ${producto.pv_afiliado}`;
                item.dataset.id = producto.id;
                item.dataset.codigo = producto.codigo;
                item.dataset.nombre = producto.nombre;
                item.dataset.precioAfiliado = producto.precio_afiliado;
                item.dataset.pvAfiliado = producto.pv_afiliado;

                // Seleccionar producto al hacer clic
                item.addEventListener('click', function () {
                    // Validar que se haya seleccionado una persona
                    if (!personaSeleccionada) {
                        // Mostrar modal de advertencia
                        const modalSeleccionarPersona = new bootstrap.Modal(document.getElementById('modalSeleccionarPersona'));
                        modalSeleccionarPersona.show();
                        return;
                    }
                    
                    agregarProductoALista(producto, descuentoSeleccionado);
                    productoLista.innerHTML = ''; // Limpiar la lista de resultados
                    productoBusqueda.value = ''; // Limpiar el campo de búsqueda
                });

                productoLista.appendChild(item);
            });
        }
    });

    // Función para redondear hacia arriba a 2 decimales
    function redondearHaciaArriba(valor) {
        return Math.ceil(valor * 100) / 100;
    }

    // Función para verificar si hay productos en la lista
    function verificarProductosEnLista() {
        const filas = productosLista.querySelectorAll('tr');
        if (filas.length > 0) {
            guardarCompra.style.display = 'inline-block'; // Mostrar el botón
            comenzarNuevo.style.display = 'inline-block'; // Mostrar el botón
        } else {
            guardarCompra.style.display = 'none'; // Ocultar el botón
            comenzarNuevo.style.display = 'none'; // Mostrar el botón
        }
    }

    // Función para agregar un producto a la lista
    function agregarProductoALista(producto, descuento) {
        // Verificar si el producto ya está en la lista
        const filas = document.querySelectorAll('#productos-lista tr');
        for (let fila of filas) {
            if (fila.dataset.id === producto.id.toString()) {
                // Mostrar el modal si el producto ya está en la lista
                const modalProductoExistente = new bootstrap.Modal(document.getElementById('modalProductoExistente'));
                modalProductoExistente.show();

                // Limpiar el campo de búsqueda y el desplegable
                productoBusqueda.value = '';
                productoLista.innerHTML = '';
                return; // Salir de la función
            }
        }

        // Calcular precio y PV con descuento
        const precioBase = parseFloat(producto.precio_afiliado);
        const pvBase = parseFloat(producto.pv_afiliado) || 0;
        const precioFinal = redondearHaciaArriba(precioBase - (precioBase * (descuento / 100)));
        const pvFinal = redondearHaciaArriba(pvBase - (pvBase * (descuento / 100)));

        // Crear la fila del producto
        const row = `
            <tr data-id="${producto.id}" data-precio-afiliado="${precioBase}" data-pv-afiliado="${pvBase}">
                <td>${producto.nombre}</td>
                <td>${producto.codigo}</td>
                <td class="precio">S/${precioFinal.toFixed(2)}</td>
                <td class="pv">${pvFinal.toFixed(2)}</td>
                <td>
                    <div class="input-group">
                        <button class="btn btn-outline-secondary btn-sm disminuir-cantidad cantidad-btn" type="button">-</button>
                        <input type="text" value="1" min="1" class="form-control cantidad text-center" style="max-width: 60px;" readonly>
                        <button class="btn btn-outline-secondary btn-sm aumentar-cantidad cantidad-btn" type="button">+</button>
                    </div>
                </td>
                <td class="subtotal">S/${precioFinal.toFixed(2)}</td>
                <td class="subtotal-pv">${pvFinal.toFixed(2)}</td>
                <td><button class="btn btn-danger btn-sm eliminar-producto">Eliminar</button></td>
            </tr>
        `;

        // Agregar la fila a la tabla
        productosLista.insertAdjacentHTML('beforeend', row);

        // Verificar si hay productos en la lista
        verificarProductosEnLista();

        // Actualizar los totales
        actualizarTotales();

        // Manejar la eliminación de productos
        const eliminarBotones = document.querySelectorAll('.eliminar-producto');
        eliminarBotones.forEach(boton => {
            boton.addEventListener('click', function () {
                this.closest('tr').remove();
                verificarProductosEnLista(); // Verificar nuevamente después de eliminar
                actualizarTotales();
            });
        });
    }

  

    // Manejar el botón "Comenzar de nuevo"
    comenzarNuevo.addEventListener('click', function () {
        location.reload(); // Recargar la página
    });

    // Manejar la actualización del descuento
    actualizarDescuento.addEventListener('click', function () {
        if (!personaSeleccionada) {
            alert('Por favor, seleccione una persona primero.');
            return;
        }

        const nuevoDescuento = parseInt(descuentoPersona.value);
        if (isNaN(nuevoDescuento) || nuevoDescuento < 0 || nuevoDescuento > 100) {
            alert('Por favor, seleccione un descuento válido.');
            return;
        }

        // Actualizar el descuento en la base de datos
        fetch('actualizar_descuento.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: personaSeleccionada.id,
                descuento: nuevoDescuento,
            }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    personaSeleccionada.descuento = `${nuevoDescuento}%`; // Actualizar el descuento localmente
                    modalConfirmacion.show(); // Mostrar el modal de confirmación

                    // Cerrar el modal automáticamente después de 1 segundo
                    setTimeout(() => {
                        modalConfirmacion.hide();
                    }, 1000); // 1000 ms = 1 segundo
                } else {
                    alert('Error al actualizar el descuento.');
                }
            })
            .catch(error => console.error('Error:', error));
    });

    // Manejar el cambio de descuento en el dropdown
    descuentoPersona.addEventListener('change', function () {
        const nuevoDescuento = parseInt(descuentoPersona.value) || 0;

        // Si hay productos en el carrito, actualizar precios y PV
        const filas = productosLista.querySelectorAll('tr');
        if (filas.length > 0) {
            filas.forEach(fila => {
                const precioBase = parseFloat(fila.dataset.precioAfiliado);
                const pvBase = parseFloat(fila.dataset.pvAfiliado);

                // Recalcular precio y PV con el nuevo descuento
                const precioFinal = redondearHaciaArriba(precioBase - (precioBase * (nuevoDescuento / 100)));
                const pvFinal = redondearHaciaArriba(pvBase - (pvBase * (nuevoDescuento / 100)));

                // Actualizar los valores en la fila
                fila.querySelector('.precio').textContent = `S/${precioFinal.toFixed(2)}`;
                fila.querySelector('.pv').textContent = pvFinal.toFixed(2);

                // Actualizar subtotales
                const cantidad = parseInt(fila.querySelector('.cantidad').value) || 0;
                const nuevoSubtotal = precioFinal * cantidad;
                const nuevoSubtotalPV = pvFinal * cantidad;

                fila.querySelector('.subtotal').textContent = `S/${nuevoSubtotal.toFixed(2)}`;
                fila.querySelector('.subtotal-pv').textContent = nuevoSubtotalPV.toFixed(2);
            });

            // Actualizar los totales
            actualizarTotales();
        }
    });

    // Manejar el botón "Guardar Compra"
    guardarCompra.addEventListener('click', function () {
        // Mostrar el modal para ingresar los detalles del pago
        const modalPago = new bootstrap.Modal(document.getElementById('modalPago'));
        modalPago.show();
    });

   
    // Función para procesar la compra
    function procesarCompra(estado, liquidacionNota) {
        const filas = document.querySelectorAll('#productos-lista tr');
        const productos = [];

        if (!personaSeleccionada) {
            alert('Por favor, seleccione una persona antes de guardar la compra.');
            return;
        }

        if (filas.length === 0) {
            alert('Por favor, agregue productos antes de guardar la compra.');
            return;
        }

        // Iterar sobre las filas de productos
        filas.forEach(fila => {
            const id = fila.getAttribute('data-id');
            const codigo = fila.querySelector('td:nth-child(2)').textContent.trim();
            const precio = parseFloat(fila.querySelector('.precio').textContent.replace('S/', '').trim());
            const pv = parseFloat(fila.querySelector('.pv').textContent.replace('S/', '').trim());
            const cantidad = parseInt(fila.querySelector('.cantidad').value) || 0;

            // Crear un registro por cada unidad del producto
            for (let i = 0; i < cantidad; i++) {
                productos.push({ id, codigo, precio, pv });
            }
        });

        if (productos.length === 0) {
            alert('No hay productos válidos para guardar.');
            return;
        }

        // Agregar la fecha actual a la nota
        const fechaActual = new Date().toLocaleString(); // Formato: DD/MM/YYYY HH:MM:SS
        const notaConFecha = `${fechaActual}: ${liquidacionNota}`;

        // Enviar los datos al backend
        fetch('procesar_compra.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                persona: personaSeleccionada,
                productos,
                estado,
                liquidacion_nota: notaConFecha, // Enviar la nota con la fecha
            }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar el modal de confirmación de compra
                    const modalConfirmacionCompra = new bootstrap.Modal(document.getElementById('modalConfirmacionCompra'));
                    modalConfirmacionCompra.show();

                    // Redirigir a compras.php al cerrar el modal
                    const cerrarConfirmacionBtn = document.getElementById('cerrar-confirmacion');
                    cerrarConfirmacionBtn.addEventListener('click', function () {
                        window.location.href = 'compras.php';
                    });

                    // También redirigir automáticamente después de 2 segundos
                    setTimeout(() => {
                        modalConfirmacionCompra.hide();
                        window.location.href = 'compras.php';
                    }, 2000);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Llamar a verificarProductosEnLista al cargar la página
    verificarProductosEnLista();

 
    // Confirmar el pago y procesar la compra
    confirmarPagoBtn.addEventListener('click', function () {
        const pagoNotaInput = document.getElementById('pago-nota');
        liquidacionNota = `Info Pago: ${pagoNotaInput.value.trim()}`; // Obtener el valor ingresado en el campo de pago

        if (!liquidacionNota) {
            // Mostrar el modal de alerta si no se ingresaron detalles del pago
            modalAlertaPago.show();
            return;
        }

        modalPago.hide(); // Ocultar el modal de pago
        procesarCompra('pendiente', liquidacionNota); // Llamar a la función para procesar la compra
    });

    // Delegación de eventos para manejar los botones de cantidad
    productosLista.addEventListener('click', function (event) {
        const target = event.target;

        // Verificar si se hizo clic en un botón de disminuir cantidad
        if (target.classList.contains('disminuir-cantidad')) {
            const fila = target.closest('tr');
            const cantidadInput = fila.querySelector('.cantidad');
            let cantidad = parseInt(cantidadInput.value) || 1;

            if (cantidad > 1) {
                cantidad--;
                cantidadInput.value = cantidad;
                actualizarSubtotal(fila);

            }
        }

        // Verificar si se hizo clic en un botón de aumentar cantidad
        if (target.classList.contains('aumentar-cantidad')) {
            const fila = target.closest('tr');
            const cantidadInput = fila.querySelector('.cantidad');
            let cantidad = parseInt(cantidadInput.value) || 1;

            cantidad++;
            cantidadInput.value = cantidad;
            actualizarSubtotal(fila);
        }


    });
});

function actualizarSubtotal(fila) {
    const precio = parseFloat(fila.querySelector('.precio').textContent.replace('S/', ''));
    const pv = parseFloat(fila.querySelector('.pv').textContent);
    const cantidad = parseInt(fila.querySelector('.cantidad').value) || 0;

    const nuevoSubtotal = precio * cantidad;
    const nuevoSubtotalPV = pv * cantidad;

    fila.querySelector('.subtotal').textContent = `S/${nuevoSubtotal.toFixed(2)}`;
    fila.querySelector('.subtotal-pv').textContent = nuevoSubtotalPV.toFixed(2);

    // Llamar a actualizarTotales después de actualizar los subtotales
    actualizarTotales();
}

  // Función para actualizar los totales
  function actualizarTotales() {
    let total = 0;
    let totalPV = 0;

    // Seleccionar todas las filas de productos en la tabla
    const filas = document.querySelectorAll('#productos-lista tr');

    // Iterar sobre cada fila para sumar los subtotales
    filas.forEach(fila => {
        const subtotal = parseFloat(fila.querySelector('.subtotal').textContent.replace('S/', '')) || 0;
        const subtotalPV = parseFloat(fila.querySelector('.subtotal-pv').textContent) || 0;

        total += subtotal;
        totalPV += subtotalPV;
    });

    // Actualizar los totales en el DOM
    const totalPagar = document.getElementById('total-pagar');
    const totalPVDisplay = document.getElementById('total-pv-display');

    totalPagar.textContent = total.toFixed(2); // Mostrar el total a pagar con 2 decimales
    totalPVDisplay.textContent = totalPV.toFixed(2); // Mostrar el total PV con 2 decimales
}
