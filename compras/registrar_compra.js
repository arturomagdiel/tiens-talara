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

    // Debug para móvil
    console.log('🔍 Debug elements:', {
        personaBusqueda: !!personaBusqueda,
        personaLista: !!personaLista,
        isMobile: window.innerWidth <= 576,
        viewport: { width: window.innerWidth, height: window.innerHeight }
    });

    let personaSeleccionada = null;
    let descuentoSeleccionado = 0;
    let liquidacionNota = '';
    let prevenirBusquedaProducto = false; // Flag para prevenir búsqueda después de selección

    // Deshabilitar búsqueda de productos inicialmente
    if (productoBusqueda) {
      productoBusqueda.disabled = true;
      productoBusqueda.placeholder = 'Primero selecciona una persona';
      productoBusqueda.style.backgroundColor = '#f8f9fa';
      productoBusqueda.style.color = '#6c757d';
    }

    // Helpers dropdown simplificados - ya no necesitan posicionamiento complejo
    function posicionarDropdown(dropdown, input) {
        // Los dropdowns ahora están integrados directamente debajo de cada input
        // No necesitan posicionamiento manual ya que están en el flujo del documento
        console.log('✅ Dropdown integrado en flujo del documento');
    }
    
    function mostrarDropdown(dropdown, input) {
        console.log('📱 Mostrando dropdown integrado:', dropdown.id, 'isDesktop:', window.innerWidth > 576);
        dropdown.style.display = 'block';
        dropdown.classList.add('show');
        
        // Asegurar visibilidad completa
        dropdown.style.visibility = 'visible';
        dropdown.style.opacity = '1';
        
        // Debug adicional para desktop
        if (window.innerWidth > 576) {
            console.log('🖥️ Desktop mode - dropdown info:', {
                id: dropdown.id,
                display: dropdown.style.display,
                className: dropdown.className,
                innerHTML: dropdown.innerHTML.length > 0,
                parentElement: dropdown.parentElement?.className
            });
        }
        
        console.log('✅ Dropdown mostrado correctamente');
    }
    function ocultarDropdown(dropdown) {
        console.log('🫥 Ocultando dropdown:', dropdown.id);
        dropdown.style.display = 'none';
        dropdown.classList.remove('show');
        dropdown.innerHTML = '';
        
        // Asegurar que se oculte con múltiples métodos
        dropdown.style.visibility = 'hidden';
        dropdown.style.opacity = '0';
        
        console.log('✅ Dropdown ocultado correctamente');
    }

    // Buscar/seleccionar persona
    if (personaBusqueda && personaLista) {
      personaBusqueda.addEventListener('input', function () {
          const query = this.value.toLowerCase();
          console.log('🔍 Búsqueda personas:', query, 'length:', query.length);

          if (query.length < 3) { 
              console.log('❌ Query muy corto, ocultando dropdown');
              ocultarDropdown(personaLista); 
              return; 
          }

          const resultados = personas.filter(p =>
              (p.nombre || '').toLowerCase().includes(query) ||
              (p.codigo || '').toLowerCase().includes(query)
          );

          console.log('📊 Resultados encontrados:', resultados.length);

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
      });
    }

    // Buscar/seleccionar producto
    if (productoBusqueda && productoLista) {
      // Crear handler de input y guardarlo como referencia
      const inputHandler = function () {
          // Si acabamos de seleccionar un producto, ignorar este evento
          if (prevenirBusquedaProducto) {
              console.log('🚫 Búsqueda producto prevenida por selección reciente');
              return;
          }
          
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

              item.addEventListener('click', function (event) {
                  // Prevenir propagación del evento
                  event.preventDefault();
                  event.stopPropagation();
                  
                  if (!personaSeleccionada) {
                      const modalSeleccionarPersona = new bootstrap.Modal(document.getElementById('modalSeleccionarPersona'));
                      modalSeleccionarPersona.show();
                      return;
                  }
                  
                  // PRIMERO: Ocultar dropdown inmediatamente
                  ocultarDropdown(productoLista);
                  
                  // SEGUNDO: Activar flag para prevenir búsqueda
                  prevenirBusquedaProducto = true;
                  
                  // TERCERO: Limpiar búsqueda sin disparar eventos
                  productoBusqueda.removeEventListener('input', productoBusqueda._inputHandler);
                  productoBusqueda.value = '';
                  productoBusqueda.blur();
                  
                  // CUARTO: Agregar producto a la lista
                  agregarProductoALista(producto, descuentoSeleccionado);
                  
                  // Reactivar eventos después de un delay
                  setTimeout(() => {
                      if (productoBusqueda._inputHandler) {
                          productoBusqueda.addEventListener('input', productoBusqueda._inputHandler);
                      }
                      prevenirBusquedaProducto = false;
                  }, 300);
                  
                  // Verificar que se haya limpiado correctamente
                  setTimeout(() => {
                      console.log('✅ Dropdown y búsqueda limpiados correctamente');
                  }, 100);
              });

              productoLista.appendChild(item);
          });

          mostrarDropdown(productoLista, productoBusqueda);
      };
      
      // Guardar referencia del handler y agregar el event listener
      productoBusqueda._inputHandler = inputHandler;
      productoBusqueda.addEventListener('input', inputHandler);
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
        // Verificar si el producto ya existe (tanto en desktop como móvil)
        const filasDesktop = document.querySelectorAll('#productos-lista tr');
        const tarjetasMobile = document.querySelectorAll('.mobile-product-card');
        
        for (let fila of filasDesktop) {
            if (fila.dataset.id === String(producto.id)) {
                const modalProductoExistente = new bootstrap.Modal(document.getElementById('modalProductoExistente'));
                modalProductoExistente.show();
                productoBusqueda.value = '';
                productoLista.innerHTML = '';
                return;
            }
        }
        
        for (let tarjeta of tarjetasMobile) {
            if (tarjeta.dataset.id === String(producto.id)) {
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

        // Vista Desktop: Tabla
        const rowDesktop = `
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
        
        // Vista Mobile: Tarjeta moderna
        const cardMobile = `
            <div class="mobile-product-card" data-id="${producto.id}" data-precio-afiliado="${precioBase}" data-pv-afiliado="${pvBase}">
                <div class="mobile-product-header">
                    <div class="mobile-product-name">${producto.nombre}</div>
                    <div class="mobile-product-code">${producto.codigo}</div>
                </div>
                
                <div class="mobile-product-details">
                    <div class="mobile-detail-item">
                        <i class="bi bi-currency-dollar"></i>
                        <span class="mobile-detail-label">Precio:</span>
                        <span class="mobile-detail-value precio">S/${precioFinal.toFixed(2)}</span>
                    </div>
                    <div class="mobile-detail-item">
                        <i class="bi bi-star"></i>
                        <span class="mobile-detail-label">PV:</span>
                        <span class="mobile-detail-value pv">${pvFinal.toFixed(2)}</span>
                    </div>
                </div>
                
                <div class="mobile-product-actions">
                    <div class="mobile-quantity-controls">
                        <button class="mobile-qty-btn disminuir-cantidad" type="button">−</button>
                        <input type="text" value="1" min="1" class="mobile-qty-input cantidad text-center" readonly>
                        <button class="mobile-qty-btn aumentar-cantidad" type="button">+</button>
                    </div>
                    
                    <div class="mobile-subtotal">
                        <div class="mobile-subtotal-label">Subtotal</div>
                        <div class="mobile-subtotal-value subtotal">S/${precioFinal.toFixed(2)}</div>
                    </div>
                    
                    <button class="mobile-delete-btn eliminar-producto" type="button">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;

        // Agregar a ambas vistas
        const productosListaDesktop = document.getElementById('productos-lista');
        const productosListaMobile = document.getElementById('productos-lista-mobile');
        
        if (productosListaDesktop) {
            productosListaDesktop.insertAdjacentHTML('beforeend', rowDesktop);
        }
        
        if (productosListaMobile) {
            productosListaMobile.insertAdjacentHTML('beforeend', cardMobile);
        }

        verificarProductosEnLista();
        actualizarTotales();
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
          
          // Actualizar productos en vista desktop (tabla)
          const filasDesktop = document.querySelectorAll('#productos-lista tr');
          filasDesktop.forEach(fila => {
              const precioBase = parseFloat(fila.dataset.precioAfiliado);
              const pvBase = parseFloat(fila.dataset.pvAfiliado);
              const precioFinal = redondearHaciaArriba(precioBase * (1 - (nuevoDescuento / 100)));
              const pvFinal = redondearHaciaArriba(pvBase * (1 - (nuevoDescuento / 100)));

              fila.querySelector('.precio').textContent = `S/${precioFinal.toFixed(2)}`;
              fila.querySelector('.pv').textContent = pvFinal.toFixed(2);

              const cantidad = parseInt(fila.querySelector('.cantidad').value, 10) || 0;
              fila.querySelector('.subtotal').textContent = `S/${(precioFinal * cantidad).toFixed(2)}`;
              const subtotalPvElement = fila.querySelector('.subtotal-pv');
              if (subtotalPvElement) {
                  subtotalPvElement.textContent = (pvFinal * cantidad).toFixed(2);
              }
          });
          
          // Actualizar productos en vista móvil (tarjetas)
          const tarjetasMobile = document.querySelectorAll('.mobile-product-card');
          tarjetasMobile.forEach(tarjeta => {
              const precioBase = parseFloat(tarjeta.dataset.precioAfiliado);
              const pvBase = parseFloat(tarjeta.dataset.pvAfiliado);
              const precioFinal = redondearHaciaArriba(precioBase * (1 - (nuevoDescuento / 100)));
              const pvFinal = redondearHaciaArriba(pvBase * (1 - (nuevoDescuento / 100)));

              tarjeta.querySelector('.precio').textContent = `S/${precioFinal.toFixed(2)}`;
              tarjeta.querySelector('.pv').textContent = pvFinal.toFixed(2);

              const cantidad = parseInt(tarjeta.querySelector('.cantidad').value, 10) || 0;
              tarjeta.querySelector('.subtotal').textContent = `S/${(precioFinal * cantidad).toFixed(2)}`;
          });
          
          actualizarTotales();
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

    // Delegación de cantidad para vista Desktop (tabla)
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
          if (target.classList.contains('eliminar-producto')) {
              console.log('🗑️ ELIMINANDO producto desde tabla desktop');
              const fila = target.closest('tr');
              if (fila) {
                  const nombreProducto = fila.querySelector('td:first-child')?.textContent;
                  console.log('🗑️ Producto a eliminar:', nombreProducto);
                  fila.remove();
                  console.log('✅ Fila eliminada, actualizando totales...');
                  verificarProductosEnLista();
                  actualizarTotales();
              }
          }
      });
    }

    // Delegación de cantidad para vista Móvil (tarjetas)
    const productosListaMobile = document.getElementById('productos-lista-mobile');
    if (productosListaMobile) {
      productosListaMobile.addEventListener('click', function (event) {
          const target = event.target;
          if (target.classList.contains('disminuir-cantidad')) {
              const tarjeta = target.closest('.mobile-product-card');
              const cantidadInput = tarjeta.querySelector('.cantidad');
              let cantidad = parseInt(cantidadInput.value, 10) || 1;
              if (cantidad > 1) { cantidad--; cantidadInput.value = cantidad; actualizarSubtotal(tarjeta); }
          }
          if (target.classList.contains('aumentar-cantidad')) {
              const tarjeta = target.closest('.mobile-product-card');
              const cantidadInput = tarjeta.querySelector('.cantidad');
              let cantidad = parseInt(cantidadInput.value, 10) || 1;
              cantidad++; cantidadInput.value = cantidad; actualizarSubtotal(tarjeta);
          }
          if (target.classList.contains('eliminar-producto') || target.closest('.eliminar-producto')) {
              console.log('🗑️ ELIMINANDO producto desde tarjeta móvil');
              const tarjeta = target.closest('.mobile-product-card');
              if (tarjeta) {
                  const nombreProducto = tarjeta.querySelector('.mobile-product-name')?.textContent;
                  console.log('🗑️ Producto a eliminar:', nombreProducto);
                  tarjeta.remove();
                  console.log('✅ Tarjeta eliminada, actualizando totales...');
                  verificarProductosEnLista();
                  actualizarTotales();
              }
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
    function actualizarSubtotal(elemento) {
        // Detectar si es fila de tabla (desktop) o tarjeta móvil
        const esTabla = elemento.tagName === 'TR';
        
        let precio, pv, cantidad, subtotalElement;
        
        if (esTabla) {
            // Vista Desktop (tabla)
            precio = parseFloat(elemento.querySelector('.precio').textContent.replace('S/', ''));
            pv = parseFloat(elemento.querySelector('.pv').textContent);
            cantidad = parseInt(elemento.querySelector('.cantidad').value, 10) || 0;
            subtotalElement = elemento.querySelector('.subtotal');
        } else {
            // Vista Mobile (tarjeta)
            precio = parseFloat(elemento.querySelector('.precio').textContent.replace('S/', ''));
            pv = parseFloat(elemento.querySelector('.pv').textContent);
            cantidad = parseInt(elemento.querySelector('.cantidad').value, 10) || 0;
            subtotalElement = elemento.querySelector('.subtotal');
        }

        const nuevoSubtotal = precio * cantidad;

        if (subtotalElement) {
            subtotalElement.textContent = `S/${nuevoSubtotal.toFixed(2)}`;
        }

        // En vista desktop también actualizar PV
        if (esTabla) {
            const nuevoSubtotalPV = pv * cantidad;
            const subtotalPVElement = elemento.querySelector('.subtotal-pv');
            if (subtotalPVElement) {
                subtotalPVElement.textContent = nuevoSubtotalPV.toFixed(2);
            }
        }

        actualizarTotales();
    }

    function actualizarTotales() {
        console.log('💰 INICIANDO actualizarTotales()');
        let total = 0;
        let totalPV = 0;
        
        // Determinar si estamos en móvil o desktop
        const esMobile = window.innerWidth <= 768;
        console.log('📱 Modo detectado:', esMobile ? 'MOBILE' : 'DESKTOP');
        
        if (esMobile) {
            // En móvil: solo calcular desde tarjetas mobile
            const tarjetasMobile = document.querySelectorAll('.mobile-product-card');
            console.log('� Calculando desde tarjetas mobile únicamente:', tarjetasMobile.length);
            tarjetasMobile.forEach((tarjeta, index) => {
                const subtotal = parseFloat(tarjeta.querySelector('.subtotal').textContent.replace('S/', '')) || 0;
                const pv = parseFloat(tarjeta.querySelector('.pv').textContent) || 0;
                const cantidad = parseInt(tarjeta.querySelector('.cantidad').value) || 1;
                console.log(`   Mobile tarjeta ${index + 1}:`, { subtotal, pv, cantidad, pvTotal: pv * cantidad });
                total += subtotal;
                totalPV += (pv * cantidad);
            });
        } else {
            // En desktop: solo calcular desde tabla desktop
            const filasDesktop = document.querySelectorAll('#productos-lista tr');
            console.log('�️ Calculando desde tabla desktop únicamente:', filasDesktop.length);
            filasDesktop.forEach((fila, index) => {
                const subtotal = parseFloat(fila.querySelector('.subtotal').textContent.replace('S/', '')) || 0;
                const subtotalPV = parseFloat(fila.querySelector('.subtotal-pv').textContent) || 0;
                console.log(`   Desktop fila ${index + 1}:`, { subtotal, subtotalPV });
                total += subtotal;
                totalPV += subtotalPV;
            });
        }
        
        console.log('💰 Totales calculados SIN DUPLICACIÓN:', { total, totalPV, modo: esMobile ? 'mobile' : 'desktop' });
        
        console.log('🎯 Elementos de total encontrados:', {
            totalPagar: !!totalPagar,
            totalPVDisplay: !!totalPVDisplay,
            totalPagarId: totalPagar?.id,
            totalPVId: totalPVDisplay?.id
        });
        
        if (totalPagar) {
            totalPagar.textContent = total.toFixed(2);
            console.log('✅ Total a pagar actualizado:', total.toFixed(2));
        } else {
            console.log('❌ Elemento totalPagar no encontrado');
        }
        
        if (totalPVDisplay) {
            totalPVDisplay.textContent = totalPV.toFixed(2);
            console.log('✅ Total PV actualizado:', totalPV.toFixed(2));
        } else {
            console.log('❌ Elemento totalPVDisplay no encontrado');
        }
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
