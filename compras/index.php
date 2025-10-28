<?php
// Proteger la página con autenticación
require_once __DIR__ . '/../shared/auth.php';
requireAuth();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Compras - Tiens Talara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../pwa-styles.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .main-container {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        
        .header-section {
            text-align: center;
            margin-bottom: 2rem;
            color: white;
        }
        
        .module-icon {
            font-size: 3.5rem;
            color: #28a745;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        
        .module-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .module-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 1rem;
        }
        
        .option-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 2rem 1rem;
            text-align: center;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none !important;
            display: block;
            height: 100%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            min-height: 200px;
        }
        
        .option-card:hover {
            transform: translateY(-8px) scale(1.02);
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
        }
        
        .option-card.card-primary {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.3), rgba(32, 201, 151, 0.3));
        }
        
        .option-card.card-primary:hover {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.5), rgba(32, 201, 151, 0.5));
        }
        
        .option-card.card-success {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.3), rgba(102, 126, 234, 0.3));
        }
        
        .option-card.card-success:hover {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.5), rgba(102, 126, 234, 0.5));
        }
        
        .option-card.card-info {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.3), rgba(255, 143, 0, 0.3));
        }
        
        .option-card.card-info:hover {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.5), rgba(255, 143, 0, 0.5));
        }
        
        .option-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .option-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }
        
        .option-description {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-top: 0.5rem;
        }
        
        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .option-card {
            animation: fadeInUp 0.6s ease forwards;
        }
        
        .option-card:nth-child(1) { animation-delay: 0.1s; }
        .option-card:nth-child(2) { animation-delay: 0.2s; }
        .option-card:nth-child(3) { animation-delay: 0.3s; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                margin: 1rem;
                padding: 1.5rem;
            }
            
            .module-title {
                font-size: 1.8rem;
            }
            
            .option-icon {
                font-size: 2.5rem;
            }
            
            .option-title {
                font-size: 1.1rem;
            }
            
            .option-card {
                min-height: 180px;
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>
<body>

<?php include '../shared/header_compras.php'; ?>

<script>
// Establecer el título específico para esta página
setPageTitle('Gestión de Compras');
</script>

<div class="container">
    <div class="main-container">
        <!-- Header Section -->
        <div class="header-section">
            <i class="bi bi-box-seam module-icon"></i>
            <h1 class="module-title">Gestión de Compras</h1>
            <p class="module-subtitle">Sistema integral para el registro y control de compras</p>
        </div>
        
        <!-- Options Grid -->
        <div class="row g-4">
            <!-- Registrar Compra -->
            <div class="col-12 col-md-4">
                <a href="registrar_compra.php" class="option-card card-primary">
                    <i class="bi bi-cart-plus-fill option-icon"></i>
                    <div class="option-title">Registrar Compra</div>
                    <div class="option-description">Crear nueva compra con productos y descuentos</div>
                </a>
            </div>
            
            <!-- Ver Compras -->
            <div class="col-12 col-md-4">
                <a href="compras.php" class="option-card card-success">
                    <i class="bi bi-list-ul option-icon"></i>
                    <div class="option-title">Ver Compras</div>
                    <div class="option-description">Consultar y gestionar compras registradas</div>
                </a>
            </div>
            
            <!-- Registro Diario -->
            <div class="col-12 col-md-4">
                <a href="registro_diario.php" class="option-card card-info">
                    <i class="bi bi-calendar-check-fill option-icon"></i>
                    <div class="option-title">Registro Diario</div>
                    <div class="option-description">Reporte diario de ventas y liquidaciones</div>
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Animación de entrada suave
    document.addEventListener('DOMContentLoaded', function() {
        const mainContainer = document.querySelector('.main-container');
        mainContainer.style.opacity = '0';
        mainContainer.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            mainContainer.style.transition = 'all 0.6s ease';
            mainContainer.style.opacity = '1';
            mainContainer.style.transform = 'translateY(0)';
        }, 100);
    });
</script>
<script src="index.js"></script>
</body>
</html>