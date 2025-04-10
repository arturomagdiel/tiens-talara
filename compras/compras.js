document.addEventListener('DOMContentLoaded', function () {
    const buscarPersona = document.getElementById('buscarPersona');
    const listaPersonas = document.getElementById('listaPersonas');
    const personaId = document.getElementById('personaId');
    const tablaCompras = document.querySelector('.table tbody');
    const filtroPendientes = document.getElementById('filtroPendientes');
    const filtroLiquidadas = document.getElementById('filtroLiquidadas');
    const confirmarLiquidacionBtn = document.getElementById('confirmarLiquidacion');
    const confirmarPasarCompraBtn = document.getElementById('confirmarPasarCompra');
    const tituloCompras = document.getElementById('tituloCompras'); // Referencia al título
    let personaSeleccionada = null; // Variable para almacenar la persona seleccionada

    let personas = []; // Aquí se cargarán las personas desde el backend

    // Cargar las personas desde el backend
    fetch('../afiliados/obtener_personas.php')
        .then(response => response.json())
        .then(data => {
            personas = data.data; // Acceder a la clave "data" del JSON
            console.log('Personas cargadas:', personas); // Verificar el contenido del arreglo
        })
        .catch(error => console.error('Error al cargar las personas:', error));

    // Filtrar personas al escribir en el textbox
    buscarPersona.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        listaPersonas.innerHTML = ''; // Limpiar resultados anteriores

        const resultados = personas.filter(persona =>
            persona.nombre.toLowerCase().includes(query) || persona.codigo.toLowerCase().includes(query)
        );

        if (resultados.length === 0) {
            listaPersonas.innerHTML = '<li class="list-group-item text-muted">No se encontraron resultados.</li>';
            return;
        }

        resultados.forEach(persona => {
            const item = document.createElement('li');
            item.className = 'list-group-item list-group-item-action';
            item.textContent = `${persona.nombre} (Código: ${persona.codigo})`;
            item.dataset.id = persona.id;

            item.addEventListener('click', function () {
                personaSeleccionada = persona; // Asignar la persona seleccionada
                personaId.value = persona.id;
                buscarPersona.value = persona.nombre;
                listaPersonas.innerHTML = ''; // Limpiar la lista

                // Actualizar el título con el nombre y el código de la persona seleccionada
                tituloCompras.textContent = `Compras de ${persona.nombre} (Código: ${persona.codigo})`;

                // Cargar las compras automáticamente con el filtro por defecto (pendientes)
                cargarCompras(persona.id, 'pendiente');

                // Limpiar la lista de personas
                listaPersonas.innerHTML = '';
            });

            listaPersonas.appendChild(item);
        });
    });

    // Función para cargar las compras de la persona seleccionada
    function cargarCompras(personaId, estado) {
        fetch('cargar_compras.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ persona_id: personaId, estado: estado }),
        })
            .then(response => response.json())
            .then(data => {
                const tablaCompras = document.querySelector('table tbody');
                tablaCompras.innerHTML = ''; // Limpiar la tabla

                if (data.length === 0) {
                    tablaCompras.innerHTML = '<tr><td colspan="11" class="text-center">No se encontraron compras para la persona seleccionada.</td></tr>';
                    return;
                }

                // Generar las filas de la tabla
                data.forEach(compra => {
                    const estadoClase = compra.estado === 'liquidado' ? 'bg-success text-white' : 'bg-warning';

                    const botonLiquidar = compra.estado === 'liquidado'
                        ? 'Liquidado'
                        : `<button class="btn btn-primary btn-sm liquidar-btn" data-id="${compra.id}">Liquidar</button>`;

                    const botonPasarCompra = compra.estado === 'liquidado'
                        ? '' // No mostrar el botón si está liquidado
                        : `<button class="btn btn-secondary btn-sm pasar-compra-btn" data-id="${compra.id}">Pasar Compra</button>`;

                    const columnasLiquidacion = compra.estado === 'liquidado'
                        ? `
                            <td>${compra.liquidacion_numero || 'N/A'}</td>
                            <td>${compra.liquidacion_fecha || 'N/A'}</td>
                        `
                        : '';

                    // Reemplazar saltos de línea en la nota por una línea discontinua
                    const notaConSeparacion = (compra.liquidacion_nota || 'Sin notas')
                        .split('\n') // Dividir las notas por saltos de línea
                        .map(nota => `<div class="nota-item">${nota}</div>`) // Envolver cada nota en un contenedor
                        .join('<hr class="nota-separador">'); // Agregar una línea discontinua entre las notas

                    const row = `
                        <tr class="${estadoClase}">
                            <td>${compra.id}</td>
                            <td>${compra.fecha_compra}</td>
                            <td>${compra.productos_codigo}</td>
                            <td>${compra.producto_nombre}</td>
                            <td>${parseFloat(compra.productos_precio).toFixed(2)}</td>
                            <td>${parseFloat(compra.productos_pv).toFixed(2)}</td>
                            <td>${compra.descuento}%</td>
                            <td>${notaConSeparacion}</td>
                            ${columnasLiquidacion}
                            <td>${botonLiquidar} ${botonPasarCompra}</td>
                        </tr>
                    `;
                    tablaCompras.insertAdjacentHTML('beforeend', row);
                });
            })
            .catch(error => console.error('Error al cargar las compras:', error));
    }

    // Cambiar el filtro de estado
    filtroPendientes.addEventListener('change', function () {
        if (filtroPendientes.checked) {
            document.body.classList.remove('estado-liquidado');
            const personaIdValue = personaId.value;
            if (personaIdValue) {
                cargarCompras(personaIdValue, 'pendiente');
            }
        }
    });

    filtroLiquidadas.addEventListener('change', function () {
        if (filtroLiquidadas.checked) {
            document.body.classList.add('estado-liquidado');
            const personaIdValue = personaId.value;
            if (personaIdValue) {
                cargarCompras(personaIdValue, 'liquidado');
            }
        }
    });

    // Delegación de eventos para los botones "Liquidar"
    tablaCompras.addEventListener('click', function (event) {
        if (event.target.classList.contains('liquidar-btn')) {
            const compraId = event.target.dataset.id; // Obtener el ID de la compra desde el atributo data-id
            abrirModalLiquidar(compraId); // Llamar a la función para abrir el modal de liquidación
        }
    });

    // Delegación de eventos para los botones "Pasar Compra"
    tablaCompras.addEventListener('click', function (event) {
        if (event.target.classList.contains('pasar-compra-btn')) {
            const compraId = event.target.dataset.id; // Obtener el ID de la compra desde el atributo data-id
            abrirModalPasarCompra(compraId); // Llamar a la función para abrir el modal de pasar compra
        }
    });

    // Función para abrir el modal de liquidación
    function abrirModalLiquidar(compraId) {
        const modal = new bootstrap.Modal(document.getElementById('modalLiquidar'));
        const numeroLiquidacion = document.getElementById('numeroLiquidacion');
        const notaLiquidacion = document.getElementById('notaLiquidacion');
        const compraIdLiquidar = document.getElementById('compraIdLiquidar');

        // Generar el número de liquidación basado en la fecha y hora actual
        const fechaActual = new Date();
        const numeroDefault = fechaActual.toISOString().replace(/[-:.TZ]/g, '').slice(0, 14); // Formato: YYYYMMDDHHMMSS
        numeroLiquidacion.value = numeroDefault;

        // Limpiar la nota y asignar el ID de la compra
        notaLiquidacion.value = '';
        compraIdLiquidar.value = compraId;

        // Mostrar el modal
        modal.show();
    }

    // Función para confirmar la liquidación
    confirmarLiquidacionBtn.addEventListener('click', function () {
        const compraId = document.getElementById('compraIdLiquidar').value;
        const numeroLiquidacion = document.getElementById('numeroLiquidacion').value;
        const notaLiquidacion = `LIQUIDADO > ${document.getElementById('notaLiquidacion').value}`;

        if (!compraId) {
            alert('Debe seleccionar una compra para liquidar.');
            return;
        }

        if (!numeroLiquidacion.trim()) {
            alert('El número de liquidación no puede estar vacío.');
            return;
        }

        // Concatenar la fecha con la nota
        const notaConFecha = concatenarFechaConNota(notaLiquidacion);

        // Generar la fecha en formato MySQL
        const fechaLiquidacion = generarFechaFormatoMySQL();

        // Enviar los datos al backend
        fetch('liquidar_compra.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                compra_id: compraId,
                numero_liquidacion: numeroLiquidacion,
                fecha_liquidacion: fechaLiquidacion, // Enviar la fecha en formato MySQL
                nota: notaConFecha, // Enviar la nota con la fecha
            }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar el modal de éxito
                    mostrarModalExito('Compra liquidada con éxito.');

                    // Recargar las compras de la persona seleccionada
                    if (personaSeleccionada) {
                        cargarCompras(personaSeleccionada.id, 'pendiente');
                    }
                } else {
                    alert('Error al liquidar la compra: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    // Función para abrir el modal de pasar compra
    function abrirModalPasarCompra(compraId) {
        const modal = new bootstrap.Modal(document.getElementById('modalPasarCompra'));
        const buscarPersonaPasar = document.getElementById('buscarPersonaPasar');
        const listaPersonasPasar = document.getElementById('listaPersonasPasar');
        const compraIdPasar = document.getElementById('compraIdPasar');
        const personaIdPasar = document.getElementById('personaIdPasar');

        // Limpiar campos y lista
        buscarPersonaPasar.value = '';
        listaPersonasPasar.innerHTML = '';
        compraIdPasar.value = compraId;
        personaIdPasar.value = '';

        // Mostrar el modal
        modal.show();

        // Buscar personas dinámicamente
        buscarPersonaPasar.addEventListener('input', function () {
            const query = this.value.toLowerCase();
            listaPersonasPasar.innerHTML = ''; // Limpiar resultados anteriores

            const resultados = personas.filter(persona =>
                persona.nombre.toLowerCase().includes(query) || persona.codigo.toLowerCase().includes(query)
            );

            if (resultados.length === 0) {
                listaPersonasPasar.innerHTML = '<li class="list-group-item text-muted">No se encontraron resultados.</li>';
                return;
            }

            resultados.forEach(persona => {
                const item = document.createElement('li');
                item.className = 'list-group-item list-group-item-action';
                item.textContent = `${persona.nombre} (Código: ${persona.codigo})`;
                item.dataset.id = persona.id;

                item.addEventListener('click', function () {
                    personaIdPasar.value = persona.id;
                    buscarPersonaPasar.value = persona.nombre;
                    listaPersonasPasar.innerHTML = ''; // Limpiar la lista
                });

                listaPersonasPasar.appendChild(item);
            });
        });
    }

    // Confirmar pasar compra
    confirmarPasarCompraBtn.addEventListener('click', function () {
        const compraId = document.getElementById('compraIdPasar').value;
        const personaIdPasar = document.getElementById('personaIdPasar').value;
        const notaPasarCompra = `TRANSFERIDO DE ${personaSeleccionada.nombre} > ${document.getElementById('notaPasarCompra').value}`;

        if (!compraId || !personaIdPasar) {
            alert('Debe seleccionar una compra y una persona.');
            return;
        }

        if (!personaSeleccionada) {
            alert('Debe seleccionar una persona actual.');
            return;
        }

        // Buscar la nueva persona seleccionada en el arreglo de personas
        const personaNueva = personas.find(persona => parseInt(persona.id) === parseInt(personaIdPasar));
        if (!personaNueva) {
            alert('No se pudo encontrar la nueva persona seleccionada.');
            return;
        }

        // Validar si los descuentos son diferentes
        if (personaSeleccionada.descuento !== personaNueva.descuento) {
            const modalErrorDescuento = new bootstrap.Modal(document.getElementById('modalErrorDescuento'));
            const mensajeErrorDescuento = document.getElementById('mensajeErrorDescuento');
            mensajeErrorDescuento.textContent = `No es posible pasar la compra porque "${personaNueva.nombre} (${personaNueva.descuento}%)" no tiene el mismo descuento que "${personaSeleccionada.nombre} (${personaSeleccionada.descuento}%)".`;
            modalErrorDescuento.show();
            return;
        }

        // Concatenar la fecha con la nota
        const notaConFecha = concatenarFechaConNota(notaPasarCompra);

        // Enviar los datos al backend
        fetch('pasar_compra.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                compra_id: compraId,
                persona_id: personaIdPasar,
                nota: notaConFecha, // Enviar la nota con la fecha
            }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar el modal de éxito
                    mostrarModalExito('Compra pasada con éxito.');

                    // Recargar las compras de la persona seleccionada
                    if (personaSeleccionada) {
                        cargarCompras(personaSeleccionada.id, 'pendiente');
                    }
                } else {
                    alert('Error al pasar la compra: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    function generarFechaFormato() {
        const fechaActual = new Date();
        let horas = fechaActual.getHours();
        const minutos = fechaActual.getMinutes().toString().padStart(2, '0');
        const segundos = fechaActual.getSeconds().toString().padStart(2, '0');
        const ampm = horas >= 12 ? 'PM' : 'AM';
        horas = horas % 12 || 12; // Convertir a formato de 12 horas (0 se convierte en 12)

        return `${fechaActual.getDate().toString().padStart(2, '0')}/${(fechaActual.getMonth() + 1)
            .toString()
            .padStart(2, '0')}/${fechaActual.getFullYear()} ${horas.toString().padStart(2, '0')}:${minutos}:${segundos} ${ampm}`;
    }

    function concatenarFechaConNota(nota) {
        const fecha = generarFechaFormato();
        return `${fecha}: ${nota}`;
    }

    function mostrarModalExito(mensaje) {
        const modalExito = new bootstrap.Modal(document.getElementById('modalExito'));
        const modalExitoBody = document.querySelector('#modalExito .modal-body p');
        modalExitoBody.textContent = mensaje; // Cambiar el mensaje del modal
        modalExito.show();

        // Ocultar el modal automáticamente después de 1 segundo
        setTimeout(() => {
            modalExito.hide();
            location.reload(); // Recargar la página
        }, 1000); // 1000 ms = 1 segundo
    }

    function generarFechaFormatoMySQL() {
        const fechaActual = new Date();
        const año = fechaActual.getFullYear();
        const mes = (fechaActual.getMonth() + 1).toString().padStart(2, '0'); // Mes en formato 2 dígitos
        const dia = fechaActual.getDate().toString().padStart(2, '0'); // Día en formato 2 dígitos
        const horas = fechaActual.getHours().toString().padStart(2, '0');
        const minutos = fechaActual.getMinutes().toString().padStart(2, '0');
        const segundos = fechaActual.getSeconds().toString().padStart(2, '0');

        return `${año}-${mes}-${dia} ${horas}:${minutos}:${segundos}`;
    }
});