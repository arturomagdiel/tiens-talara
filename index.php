<!-- filepath: c:\Users\artur\Documents\GitHub\tiens-talara\index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio - Sistema</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <style>
    .option-card {
      text-align: center;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 10px;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .option-card:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .option-icon {
      font-size: 4rem;
      color: #007bff;
    }
    .option-title {
      margin-top: 10px;
      font-size: 1.5rem;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <h1 class="text-center mb-4">Bienvenido al Sistema</h1>
    <div class="row g-4">
      <!-- Pedidos -->
      <div class="col-md-3">
        <a href="pedido/" class="text-decoration-none">
          <div class="option-card">
            <i class="fas fa-shopping-cart option-icon"></i>
            <div class="option-title">Pedidos</div>
          </div>
        </a>
      </div>
      <!-- Compras -->
      <div class="col-md-3">
        <a href="compras/" class="text-decoration-none">
          <div class="option-card">
            <i class="fas fa-box option-icon"></i>
            <div class="option-title">Compras</div>
          </div>
        </a>
      </div>
      <!-- Mantenimiento de Productos -->
      <div class="col-md-3">
        <a href="productos/" class="text-decoration-none">
          <div class="option-card">
            <i class="fas fa-tags option-icon"></i>
            <div class="option-title">Mant. Productos</div>
          </div>
        </a>
      </div>
      <!-- Mantenimiento de Afiliados -->
      <div class="col-md-3">
        <a href="afiliados/" class="text-decoration-none">
          <div class="option-card">
            <i class="fas fa-users option-icon"></i>
            <div class="option-title">Mant. Afiliados</div>
          </div>
        </a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>
</html>