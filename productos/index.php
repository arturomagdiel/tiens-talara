<!DOCTYPE html>
<html>
<head>
  <title>Calcula tu compra Tiens</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <style>
    .product-button {
      width: 100%; 
      margin-bottom: 10px;
      text-align: center;
    }
    .product-button img {
      max-width: 100%;
      height: auto;
      margin-bottom: 5px;
    }
    #carrito-lista li {
      list-style: none; 
    }
    @media (max-width: 768px) { 
      #carrito {
        margin-top: 20px;
      }
    }
  </style>
</head>
<body>
    <audio id="clickSound" src="sonidos/click1.mp3" preload="auto"></audio>


<div class="container">
  <div class="row">
    <div class="col-md-6"> 
    <h2>Calcula tu Compra</h2> 
    
    <div class="row mb-3">
    <div class="col-md-3">
      <select class="form-select" id="tipo-precio">
        <option value="publico">Publico</option>
        <option value="afiliado">Afiliado</option>
        <option value="junior">5%</option>
        <option value="senior">8%</option>
        <option value="master">15%</option>
      </select>
    </div>
  </div>
  
    </div>
    <div class="col-md-6 text-end"> 
    <h2>Carrito</h2> 
      <div id="carrito">
        <p>Productos: <span id="carrito-cantidad">0</span></p>
        <p>S/<span id="carrito-precio">0</span> - <span id="carrito-pv">0</span>PV</p>
      </div>
    </div>
  </div>

  

  <div class="row">
    <div class="col-md-9">
      <div id="product-list" class="row row-cols-1 row-cols-md-4 g-4">
        </div>
    </div>
    <div class="col-md-3">
      <ul id="carrito-lista"></ul>
      <button id="limpiar-carrito" class="btn btn-secondary btn-sm">Limpiar Carrito</button> 
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script> 
$(document).ready(function() {
  function cargarProductos() {
    var tipoPrecio = $('#tipo-precio').val();
    console.log("Tipo de precio:", tipoPrecio);

    $.ajax({
      url: 'obtener_productos.php',
      type: 'GET',
      data: { tipo: tipoPrecio },
      success: function(response) {
        console.log("Respuesta AJAX:", response); // Depuración: verificar la respuesta AJAX
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

  cargarProductos(); // Cargar productos al inicio

  $('#tipo-precio').change(function() {
    cargarProductos();
    actualizarCarrito();
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
      // Asegurarse de que los valores de precio y pv sean numéricos
      precio = parseFloat(precio);
      pv = parseFloat(pv); // Corregido: usar parseFloat para PV

      let nuevoProducto = { 
        id: id, 
        nombre: nombre, 
        imagen: imagen, 
        cantidad: 1,
        precio_publico: parseFloat(precio_publico),
        pv_publico: parseFloat(pv_publico), // Guardar como número de punto flotante
        precio_afiliado: parseFloat(precio_afiliado),
        pv_afiliado: parseFloat(pv_afiliado), // Guardar como número de punto flotante
        precio_junior: parseFloat(precio_junior),
        pv_junior: parseFloat(pv_junior), // Guardar como número de punto flotante
        precio_senior: parseFloat(precio_senior),
        pv_senior: parseFloat(pv_senior), // Guardar como número de punto flotante
        precio_master: parseFloat(precio_master),
        pv_master: parseFloat(pv_master) // Guardar como número de punto flotante
      };
      carrito.push(nuevoProducto);
    }

    localStorage.setItem('carrito', JSON.stringify(carrito));
    actualizarCarrito();
  }

  function actualizarCarrito() {

    const clickSound = document.getElementById("clickSound");
    clickSound.play();

    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    console.log("Carrito:", carrito);

    var cantidad = 0;
    var precioTotal = 0;
    var pvTotal = 0;
    var tipoPrecio = $('#tipo-precio').val();

    carrito.forEach(function(item) {
      cantidad += item.cantidad;

      // Formatear precio y PV con dos decimales antes de sumarlos
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
        <div class="card mb-2">
          <div class="card-body">
            <h6 class="card-title">${item.cantidad} ${item.nombre}</h6>
            <p class="card-text">S/${parseFloat(item["precio_" + tipoPrecio]).toFixed(2)} (${parseFloat(item["pv_" + tipoPrecio]).toFixed(2)})PV</p>  
            <button class="btn btn-danger btn-sm eliminar-producto" data-id="${item.id}">Eliminar</button>
          </div>
        </div>
      `);
      //listItem.html(`
      //  <div class="card mb-2">
      //      <div class="card-body">
      //      <h6 class="card-title">${item.nombre}</h6>
      //      <p class="card-text">Precio: ${parseFloat(item["precio_" + tipoPrecio]).toFixed(2)}</p> 
      //      <p class="card-text">PV: ${parseFloat(item["pv_" + tipoPrecio]).toFixed(2)}</p>  
      //      <p class="card-text">Cantidad: ${item.cantidad}</p>
      //      <button class="btn btn-danger btn-sm eliminar-producto" data-id="${item.id}">Eliminar</button>
      //    </div>
      //  </div>
      //`);
      carritoLista.append(listItem);
    });

    $('.eliminar-producto').click(function() {
      var id = $(this).data('id');
      eliminarDelCarrito(id);
    });
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

      localStorage.setItem('carrito', JSON.stringify(carrito));
      actualizarCarrito();
    }
  }

  $('#limpiar-carrito').click(function() {
    localStorage.removeItem('carrito');
    actualizarCarrito();
  });

  actualizarCarrito();
});



</script>

</body>
</html>