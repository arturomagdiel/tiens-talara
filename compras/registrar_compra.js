document.addEventListener('DOMContentLoaded', function () {
    const pageTitle = document.getElementById('page-title');
    if (pageTitle) pageTitle.textContent = 'Registrar Compras';

    const personaBusqueda = document.getElementById('persona-busqueda');
    const personaLista = document.getElementById('persona-lista');
    const productoBusqueda = document.getElementById('producto-busqueda');
    const productoLista = document.getElementById('producto-lista');
    const productosLista = document.getElementById('productos-lista');
    const totalPagar = document.getElementById('total-pagar');
    const totalPVDisplay = document.getElementById('total-pv-display');
    const comenzarNuevo = document.getElementById('comenzar-nuevo');
    const descuentoPersona = document.getElementById('descuento-persona');
    const actualizarDescuento = document.getElementById('actualizar-descuento');
    const modalConfirmacion = new bootstrap.Modal(document.getElementById('modalConfirmacion'));
    const guardarCompra = document.getElementById('guardar-compra');
    const modalPago = new bootstrap.Modal(document.getElementById('modalPago')); // reutilizar esta
    const confirmarPagoBtn = document.getElementById('confirmar-pago');
    const modalConfirmacionCompra = new bootstrap.Modal(document.getElementById('modalConfirmacionCompra'));
    const modalAlertaPago = new bootstrap.Modal(document.getElementById('modalAlertaPago'));

    let personaSeleccionada = null;
    let descuentoSeleccionado = 0;
    let liquidacionNota = '';

    // Deshabilitar búsqueda de productos inicialmente
    if (productoBusqueda) {
      productoBusqueda.disabled = true;
      productoBusqueda.placeholder = 'Primero selecciona una persona';
      productoBusqueda.style.backgroundColor = '#f8f9fa';
      productoBusqueda.style.color = '#6c757d';
    }

    // Helpers dropdown
    function posicionarDropdown(dropdown, input) {
        const rect = input.getBoundingClientRect();
        dropdown.style.position = 'fixed';
        dropdown.style.top = (rect.bottom + 5) + 'px';
        dropdown.style.left = rect.left + 'px';
        dropdown.style.width = rect.width + 'px';
        dropdown.style.zIndex = '999999';

        const dropdownRect = dropdown.getBoundingClientRect();
        if (dropdownRect.right > window.innerWidth) {
            dropdown.style.left = (window.innerWidth - dropdownRect.width - 15) + 'px';
        }
        if (dropdownRect.left < 0) {
            dropdown.style.left = '15px';
            dropdown.style.width = (window.innerWidth - 30) + 'px';
        }
    }
    function mostrarDropdown(dropdown, input) {
        posicionarDropdown(dropdown, input);
        dropdown.style.display = 'block';
        dropdown.classList.add('show');
    }
    function ocultarDropdown(dropdown) {
        dropdown.style.display = 'none';
        dropdown.classList.remove('show');
        dropdown.innerHTML = '';
    }
    // Implementación opcional para móvil (si quieres conservar la llamada)
    function posicionarDropdownMovil(dropdown, input) {
        if (window.innerWidth <= 576) posicionarDropdown(dropdown, input);
    }

    // Buscar/seleccionar persona
    if (personaBusqueda && personaLista) {
      personaBusqueda.addEventListener('input', function () {
          const query = this.value.toLowerCase();

          if (query.length < 3) { ocultarDropdown(personaLista); return; }

          const resultados = personas.filter(p =>
              (p.nombre || '').toLowerCase().includes(query) ||
              (p.codigo || '').toLowerCase().includes(query)
          );

          if (resultados.length === 0) {
              personaLista.innerHTML = '<p class="text-muted p-3">No se encontraron resultados.</p>';
              mostrarDropdown(personaLista, personaBusqueda);
              return;
          }

          personaLista.innerHTML = '';
          resultados.forEach(persona => {
              const item = document.createElement('button');
              item.type = 'button';
              item.className = 'list-group-item list-group-item-action';
              item.textContent = `${persona.nombre} (${persona.codigo}) - Descuento: ${persona.descuento}`;
              item.dataset.id = persona.id;
              item.dataset.codigo = persona.codigo;
              item.dataset.nombre = persona.nombre;
              item.dataset.descuento = persona.descuento;

              item.addEventListener('click', function () {
                  personaBusqueda.value = `${persona.nombre} (${persona.codigo})`;
                  personaSeleccionada = persona;

                  descuentoSeleccionado = parseInt(String(persona.descuento).replace('%', ''), 10) || 0;
                  if (descuentoPersona) descuentoPersona.value = descuentoSeleccionado;

                  personaLista.innerHTML = '';
                  personaBusqueda.disabled = true;
                  personaBusqueda.style.backgroundColor = '#e9ecef';
                  ocultarDropdown(personaLista);

                  if (productoBusqueda) {
                    productoBusqueda.disabled = false;
                    productoBusqueda.placeholder = 'Buscar producto por nombre o código';
                    productoBusqueda.style.backgroundColor = '';
                    productoBusqueda.style.color = '';
                    productoBusqueda.focus();
                  }

                  if (comenzarNuevo) comenzarNuevo.style.display = 'inline-block';
              });

              personaLista.appendChild(item);
          });

          mostrarDropdown(personaLista, personaBusqueda);
          // Si quieres mantenerlo:
          posicionarDropdownMovil(personaLista, personaBusqueda);
      });
    }

    // Buscar/seleccionar producto
    if (productoBusqueda && productoLista) {
      productoBusqueda.addEventListener('input', function () {
          const query = this.value.toLowerCase();
          if (!query) { ocultarDropdown(productoLista); return; }

          const resultados = productos.filter(prod =>
              (prod.nombre || '').toLowerCase().includes(query) ||
              (prod.codigo || '').toLowerCase().includes(query)
          );

          if (resultados.length === 0) {
              productoLista.innerHTML = '<p class="text-muted p-3">No se encontraron productos.</p>';
              mostrarDropdown(productoLista, productoBusqueda);
              return;
          }

          productoLista.innerHTML = '';
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

              item.addEventListener('click', function () {
                  if (!personaSeleccionada) {
                      const modalSeleccionarPersona = new bootstrap.Modal(document.getElementById('modalSeleccionarPersona'));
                      modalSeleccionarPersona.show();
                      return;
                  }
                  agregarProductoALista(producto, descuentoSeleccionado);
                  ocultarDropdown(productoLista);
                  productoBusqueda.value = '';
              });

              productoLista.appendChild(item);
          });

          mostrarDropdown(productoLista, productoBusqueda);
      });
    }

    function redondearHaciaArriba(v) { return Math.ceil(v * 100) / 100; }

    function verificarProductosEnLista() {
        const filas = productosLista ? productosLista.querySelectorAll('tr') : [];
        if (!guardarCompra || !comenzarNuevo) return;
        if (filas.length > 0) {
          guardarCompra.style.display = 'inline-block';
          comenzarNuevo.style.display = 'inline-block';
        } else {
          guardarCompra.style.display = 'none';
          comenzarNuevo.style.display = 'none';
        }
    }

    function agregarProductoALista(producto, descuento) {
        const filas = document.querySelectorAll('#productos-lista tr');
        for (let fila of filas) {
            if (fila.dataset.id === String(producto.id)) {
                const modalProductoExistente = new bootstrap.Modal(document.getElementById('modalProductoExistente'));
                modalProductoExistente.show();
                productoBusqueda.value = '';
                productoLista.innerHTML = '';
                return;
            }
        }

        const precioBase = parseFloat(producto.precio_afiliado);
        const pvBase = parseFloat(producto.pv_afiliado) || 0;
        const precioFinal = redondearHaciaArriba(precioBase * (1 - (descuento / 100)));
        const pvFinal = redondearHaciaArriba(pvBase * (1 - (descuento / 100)));

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
        productosLista.insertAdjacentHTML('beforeend', row);

        verificarProductosEnLista();
        actualizarTotales();

        document.querySelectorAll('.eliminar-producto').forEach(btn => {
            btn.addEventListener('click', function () {
                this.closest('tr').remove();
                verificarProductosEnLista();
                actualizarTotales();
            });
        });
    }

    if (comenzarNuevo) {
      comenzarNuevo.addEventListener('click', () => location.reload());
    }

    if (actualizarDescuento) {
      actualizarDescuento.addEventListener('click', function () {
          if (!personaSeleccionada) { alert('Por favor, seleccione una persona primero.'); return; }

          const nuevoDescuento = parseInt(descuentoPersona.value, 10);
          if (isNaN(nuevoDescuento) || nuevoDescuento < 0 || nuevoDescuento > 100) {
              alert('Por favor, seleccione un descuento válido.');
              return;
          }

          fetch('actualizar_descuento.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ id: personaSeleccionada.id, descuento: nuevoDescuento }),
          })
          .then(r => r.json())
          .then(data => {
              if (data.success) {
                  personaSeleccionada.descuento = `${nuevoDescuento}%`;
                  modalConfirmacion.show();
                  setTimeout(() => modalConfirmacion.hide(), 1000);
              } else {
                  alert('Error al actualizar el descuento.');
              }
          })
          .catch(console.error);
      });
    }

    if (descuentoPersona) {
      descuentoPersona.addEventListener('change', function () {
          const nuevoDescuento = parseInt(descuentoPersona.value, 10) || 0;
          const filas = productosLista.querySelectorAll('tr');
          if (filas.length > 0) {
              filas.forEach(fila => {
                  const precioBase = parseFloat(fila.dataset.precioAfiliado);
                  const pvBase = parseFloat(fila.dataset.pvAfiliado);
                  const precioFinal = redondearHaciaArriba(precioBase * (1 - (nuevoDescuento / 100)));
                  const pvFinal = redondearHaciaArriba(pvBase * (1 - (nuevoDescuento / 100)));

                  fila.querySelector('.precio').textContent = `S/${precioFinal.toFixed(2)}`;
                  fila.querySelector('.pv').textContent = pvFinal.toFixed(2);

                  const cantidad = parseInt(fila.querySelector('.cantidad').value, 10) || 0;
                  fila.querySelector('.subtotal').textContent = `S/${(precioFinal * cantidad).toFixed(2)}`;
                  fila.querySelector('.subtotal-pv').textContent = (pvFinal * cantidad).toFixed(2);
              });
              actualizarTotales();
          }
      });
    }

    if (guardarCompra) {
      guardarCompra.addEventListener('click', function () {
          modalPago.show(); // reusar la instancia superior
      });
    }

    if (confirmarPagoBtn) {
      confirmarPagoBtn.addEventListener('click', function () {
          const pagoNotaInput = document.getElementById('pago-nota');
          liquidacionNota = `Info Pago: ${pagoNotaInput.value.trim()}`;
          if (!pagoNotaInput.value.trim()) { modalAlertaPago.show(); return; }
          modalPago.hide();
          procesarCompra('pendiente', liquidacionNota);
      });
    }

    // Delegación de cantidad
    if (productosLista) {
      productosLista.addEventListener('click', function (event) {
          const target = event.target;
          if (target.classList.contains('disminuir-cantidad')) {
              const fila = target.closest('tr');
              const cantidadInput = fila.querySelector('.cantidad');
              let cantidad = parseInt(cantidadInput.value, 10) || 1;
              if (cantidad > 1) { cantidad--; cantidadInput.value = cantidad; actualizarSubtotal(fila); }
          }
          if (target.classList.contains('aumentar-cantidad')) {
              const fila = target.closest('tr');
              const cantidadInput = fila.querySelector('.cantidad');
              let cantidad = parseInt(cantidadInput.value, 10) || 1;
              cantidad++; cantidadInput.value = cantidad; actualizarSubtotal(fila);
          }
      });
    }

    // Clic fuera para ocultar dropdowns
    document.addEventListener('click', function(e) {
        if (personaBusqueda && personaLista &&
            !personaBusqueda.contains(e.target) && !personaLista.contains(e.target)) {
            ocultarDropdown(personaLista);
        }
        if (productoBusqueda && productoLista &&
            !productoBusqueda.contains(e.target) && !productoLista.contains(e.target)) {
            ocultarDropdown(productoLista);
        }
    });

    // Reposicionar en resize/scroll
    window.addEventListener('resize', function() {
        if (personaLista && personaLista.classList.contains('show')) posicionarDropdown(personaLista, personaBusqueda);
        if (productoLista && productoLista.classList.contains('show')) posicionarDropdown(productoLista, productoBusqueda);
    });
    window.addEventListener('scroll', function() {
        if (personaLista && personaLista.classList.contains('show')) posicionarDropdown(personaLista, personaBusqueda);
        if (productoLista && productoLista.classList.contains('show')) posicionarDropdown(productoLista, productoBusqueda);
    });

    // Arranque
    verificarProductosEnLista();

    // ------- funciones que usan variables de arriba permanecen dentro del DOMContentLoaded -------
    function actualizarSubtotal(fila) {
        const precio = parseFloat(fila.querySelector('.precio').textContent.replace('S/', ''));
        const pv = parseFloat(fila.querySelector('.pv').textContent);
        const cantidad = parseInt(fila.querySelector('.cantidad').value, 10) || 0;

        const nuevoSubtotal = precio * cantidad;
        const nuevoSubtotalPV = pv * cantidad;

        fila.querySelector('.subtotal').textContent = `S/${nuevoSubtotal.toFixed(2)}`;
        fila.querySelector('.subtotal-pv').textContent = nuevoSubtotalPV.toFixed(2);

        actualizarTotales();
    }

    function actualizarTotales() {
        let total = 0;
        let totalPV = 0;
        const filas = document.querySelectorAll('#productos-lista tr');
        filas.forEach(fila => {
            const subtotal = parseFloat(fila.querySelector('.subtotal').textContent.replace('S/', '')) || 0;
            const subtotalPV = parseFloat(fila.querySelector('.subtotal-pv').textContent) || 0;
            total += subtotal;
            totalPV += subtotalPV;
        });
        if (totalPagar) totalPagar.textContent = total.toFixed(2);
        if (totalPVDisplay) totalPVDisplay.textContent = totalPV.toFixed(2);
    }

    function procesarCompra(estado, liquidacionNota) {
        const filas = document.querySelectorAll('#productos-lista tr');
        const productos = [];

        if (!personaSeleccionada) { alert('Por favor, seleccione una persona antes de guardar la compra.'); return; }
        if (filas.length === 0) { alert('Por favor, agregue productos antes de guardar la compra.'); return; }

        filas.forEach(fila => {
            const id = fila.getAttribute('data-id');
            const codigo = fila.querySelector('td:nth-child(2)').textContent.trim();
            const precio = parseFloat(fila.querySelector('.precio').textContent.replace('S/', '').trim());
            const pv = parseFloat(fila.querySelector('.pv').textContent.trim()); // .pv no lleva "S/"
            const cantidad = parseInt(fila.querySelector('.cantidad').value, 10) || 0;
            for (let i = 0; i < cantidad; i++) productos.push({ id, codigo, precio, pv });
        });

        if (productos.length === 0) { alert('No hay productos válidos para guardar.'); return; }

        const fechaActual = new Date().toLocaleString();
        const notaConFecha = `${fechaActual}: ${liquidacionNota}`;

        fetch('procesar_compra.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ persona: personaSeleccionada, productos, estado, liquidacion_nota: notaConFecha }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                modalConfirmacionCompra.show();
                const cerrarConfirmacionBtn = document.getElementById('cerrar-confirmacion');
                if (cerrarConfirmacionBtn) {
                    cerrarConfirmacionBtn.addEventListener('click', function () {
                        window.location.href = 'compras.php';
                    }, { once: true });
                }
                setTimeout(() => {
                    modalConfirmacionCompra.hide();
                    window.location.href = 'compras.php';
                }, 2000);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(console.error);
    }
});
