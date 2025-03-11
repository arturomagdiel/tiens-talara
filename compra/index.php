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
<script src="scripts.js"></script>


</body>
</html>