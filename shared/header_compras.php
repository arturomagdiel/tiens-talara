<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: ../index.php');
    exit;
}
?>

<!-- Header simplificado para el módulo de compras -->
<div class="container-fluid bg-dark text-white py-2 sticky-top mb-3">
    <div class="row align-items-center">
        <!-- Título de la página -->
        <div class="col-md-6">
            <h6 class="text-center text-md-start mb-0" id="page-title">Gestión de Compras</h6>
        </div>

        <!-- Botones simplificados -->
        <div class="col-md-6 d-flex justify-content-center justify-content-md-end gap-2">
            <!-- Botón Inicio -->
            <button class="btn btn-light btn-sm d-flex align-items-center justify-content-center" 
                onclick="window.location.href='../index.php'" 
                title="Volver al menú principal">
                <i class="bi bi-house-door text-dark me-1"></i> Inicio
            </button>

            <!-- Botón Registrar Compra - Solo icono -->
            <button class="btn btn-primary btn-sm d-flex align-items-center justify-content-center" 
                onclick="window.location.href='registrar_compra.php'" 
                title="Registrar Compra">
                <i class="bi bi-cart-plus-fill"></i>
            </button>

            <!-- Botón Ver Compras - Solo icono -->
            <button class="btn btn-success btn-sm d-flex align-items-center justify-content-center" 
                onclick="window.location.href='compras.php'" 
                title="Ver Compras">
                <i class="bi bi-list-ul"></i>
            </button>

            <!-- Botón Registro Diario - Solo icono -->
            <button class="btn btn-info btn-sm d-flex align-items-center justify-content-center" 
                onclick="window.location.href='registro_diario.php'" 
                title="Registro Diario">
                <i class="bi bi-calendar-check-fill"></i>
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

<script>
// Función para establecer el título de la página en el header
function setPageTitle(title) {
    document.getElementById('page-title').textContent = title;
}
</script>