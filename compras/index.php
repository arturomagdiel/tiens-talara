<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Efecto de escala y sombra al pasar el mouse */
        .card:hover {
            transform: scale(1.05); /* Aumentar ligeramente el tamaño */
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3); /* Agregar sombra */
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Transición suave */
        }

        /* Efecto de parpadeo en el contenido de la tarjeta */
        .card:hover .card-body {
            animation: parpadeo 1s infinite; /* Animación de parpadeo */
        }

        /* Animación de parpadeo */
        @keyframes parpadeo {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
    </style>
</head>
<body>
<?php include '../shared/header.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Gestión de Compras</h1>
        <div class="row">
            <!-- Botón Registrar Compra -->
            <div class="col-md-4">
                <a href="registrar_compra.php" class="card text-center text-decoration-none bg-primary text-white">
                    <div class="card-body">
                        <i class="bi bi-cart-plus-fill" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-2">Registrar Compra</h5>
                    </div>
                </a>
            </div>
            <!-- Botón Ver Compras -->
            <div class="col-md-4">
                <a href="compras.php" class="card text-center text-decoration-none bg-success text-white">
                    <div class="card-body">
                        <i class="bi bi-list-ul" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-2">Ver Compras</h5>
                    </div>
                </a>
            </div>
            <!-- Botón Registro Diario -->
            <div class="col-md-4">
                <a href="registro_diario.php" class="card text-center text-decoration-none bg-danger text-white">
                    <div class="card-body">
                        <i class="bi bi-calendar-check-fill" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-2">Registro Diario</h5>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="index.js"></script>
</body>
</html>