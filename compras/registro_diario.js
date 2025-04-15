document.addEventListener('DOMContentLoaded', function () {
    const pageTitle = document.getElementById('page-title');
    if (pageTitle) {
        pageTitle.textContent = 'Registro Diario'; // Cambia este texto según la página
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const fechaInicio = document.getElementById('fechaInicio');
    const fechaFin = document.getElementById('fechaFin');
    const registroDiario = document.getElementById('registroDiario');
    const btnImprimir = document.getElementById('btnImprimir');

    // Función para cargar las compras
    function cargarCompras() {
        const inicio = fechaInicio.value;
        const fin = fechaFin.value;

        fetch(`obtener_compras.php?fecha_inicio=${inicio}&fecha_fin=${fin}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error en la respuesta del servidor: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                registroDiario.innerHTML = ''; // Limpiar el contenido actual

                if (data.error) {
                    registroDiario.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                    return;
                }

                if (data.length === 0) {
                    registroDiario.innerHTML = '<div class="alert alert-warning text-center">No hay compras en este rango de fechas.</div>';
                    return;
                }

                // Formatear los datos: Encabezado con el nombre de la persona y sus compras en una tabla
                data.forEach(persona => {
                    // Crear el encabezado con el nombre de la persona
                    const encabezado = document.createElement('h3');
                    encabezado.textContent = persona.nombre_completo;
                    encabezado.className = 'mt-4';

                    // Crear la tabla
                    const tabla = document.createElement('table');
                    tabla.className = 'table table-striped mt-3';

                    // Crear el encabezado de la tabla
                    const thead = document.createElement('thead');
                    thead.innerHTML = `
                        <tr>
                            <th style="border-bottom: 2px solid #000;">Fecha</th>
                            <th style="border-bottom: 2px solid #000;">ID</th>
                            <th style="border-bottom: 2px solid #000;">Código</th>
                            <th style="border-bottom: 2px solid #000;">Producto</th>
                            <th style="border-bottom: 2px solid #000;">PV</th>
                            <th style="border-bottom: 2px solid #000;">Precio</th>
                            <th class="no-print" style="border-bottom: 2px solid #000;">Notas</th>
                            <th style="border-bottom: 2px solid #000;">Estado</th>
                        </tr>
                    `;
                    tabla.appendChild(thead);

                    // Crear el cuerpo de la tabla
                    const tbody = document.createElement('tbody');
                    persona.compras.forEach(compra => {
                        const fila = document.createElement('tr');
                        fila.innerHTML = `
                            <td>${compra.fecha_compra}</td>
                            <td>${compra.compra_id}</td>
                            <td>${compra.productos_codigo}</td>
                            <td>${compra.producto_nombre}</td>
                            <td>S/${compra.producto_pv}</td>
                            <td>S/${compra.productos_precio}</td>
                            <td class="no-print">${compra.compra_notas || 'Sin notas'}</td>
                            <td>${compra.estado}</td>
                        `;
                        tbody.appendChild(fila);
                    });
                    tabla.appendChild(tbody);

                    // Agregar el encabezado y la tabla al contenedor
                    registroDiario.appendChild(encabezado);
                    registroDiario.appendChild(tabla);
                });
            })
            .catch(error => {
                registroDiario.innerHTML = `<div class="alert alert-danger">Error al cargar las compras: ${error.message}</div>`;
            });
    }

    // Función para manejar la impresión
    btnImprimir.addEventListener('click', function () {
        const inicio = fechaInicio.value;
        const fin = fechaFin.value;
        const contenido = document.getElementById('registroDiario').innerHTML; // Obtener el contenido a imprimir
        const ventanaImpresion = window.open('', '_blank'); // Abrir una nueva ventana
        ventanaImpresion.document.write(`
            <html>
                <head>
                    <title>Vista de Impresión</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        @media print {
                            body {
                                margin: 10mm;
                                font-size: 12px;
                            }
                            h1 {
                                font-size: 16px;
                                text-align: center;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                            }
                            th {
                                text-align: left;
                                padding: 5px;
                                border-bottom: 2px solid #000; /* Solo separación en el encabezado */
                            }
                            td {
                                padding: 3px; /* Reducir espacio entre celdas */
                                border: none; /* Eliminar bordes de las celdas */
                            }
                            .no-print {
                                display: none; /* Ocultar elementos con la clase no-print */
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="container mt-5">
                        <h1 class="text-center mb-4">Registro Diario (del ${inicio} al ${fin})</h1>
                        ${contenido} <!-- Insertar el contenido -->
                    </div>
                </body>
            </html>
        `);
        ventanaImpresion.document.close(); // Cerrar el documento para que se cargue
        ventanaImpresion.focus(); // Enfocar la ventana
        ventanaImpresion.print(); // Lanzar la impresión
        ventanaImpresion.close(); // Cerrar la ventana después de imprimir
    });

    // Escuchar cambios en los campos de fecha
    fechaInicio.addEventListener('change', cargarCompras);
    fechaFin.addEventListener('change', cargarCompras);

    // Cargar las compras al cargar la página
    cargarCompras();
});