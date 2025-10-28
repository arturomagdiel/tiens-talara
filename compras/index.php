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

<!-- Header simplificado para compras -->
<div class="container-fluid bg-dark text-white py-2 sticky-top mb-3">
    <div class="row align-items-center">
        <!-- Título de la página -->
        <div class="col-md-6">
            <h6 class="text-center text-md-start mb-0">Gestión de Compras</h6>
        </div>

        <!-- Botones simplificados -->
        <div class="col-md-6 d-flex justify-content-center justify-content-md-end gap-2">
            <!-- Botón Inicio -->
            <button class="btn btn-light btn-sm d-flex align-items-center justify-content-center" 
                onclick="window.location.href='../index.php'" 
                title="Volver al menú principal">
                <i class="bi bi-house-door text-dark me-1"></i> Inicio
            </button>

            <!-- Botón Salir -->
            <button class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" 
                onclick="window.location.href='../logout.php'" 
                title="Cerrar Sesión">
                <i class="bi bi-box-arrow-right me-1"></i> Salir
            </button>
        </div>
    </div>
</div>

<?php
// Verificación de autenticación simplificada
session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: ../index.php');
    exit;
}
?>
    <div class="container mt-4">
        <div class="row g-4 row-cols-2 row-cols-md-3">
            <!-- Botón Registrar Compra -->
            <div class="col">
                <a href="registrar_compra.php" class="card text-center text-decoration-none bg-primary text-white h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <i class="bi bi-cart-plus-fill mb-3" style="font-size: 3rem;"></i>
                        <h5 class="card-title">Registrar Compra</h5>
                    </div>
                </a>
            </div>
            <!-- Botón Ver Compras -->
            <div class="col">
                <a href="compras.php" class="card text-center text-decoration-none bg-success text-white h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <i class="bi bi-list-ul mb-3" style="font-size: 3rem;"></i>
                        <h5 class="card-title">Ver Compras</h5>
                    </div>
                </a>
            </div>
            <!-- Botón Registro Diario -->
            <div class="col">
                <a href="registro_diario.php" class="card text-center text-decoration-none bg-danger text-white h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <i class="bi bi-calendar-check-fill mb-3" style="font-size: 3rem;"></i>
                        <h5 class="card-title">Registro Diario</h5>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="index.js"></script>
</body>
</html>