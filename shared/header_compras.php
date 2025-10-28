<!-- Header moderno para el módulo de compras v1.3.3 -->
<style>
.modern-header {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.header-btn {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    backdrop-filter: blur(5px);
    border-radius: 10px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.header-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    color: white;
    border-color: rgba(255, 255, 255, 0.5);
}

.header-btn.btn-primary {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
}

.header-btn.btn-primary:hover {
    background: linear-gradient(45deg, #218838, #1e9b8a);
    transform: translateY(-2px) scale(1.05);
}

.header-btn.btn-danger {
    background: linear-gradient(45deg, #dc3545, #c82333);
    border: none;
}

.header-btn.btn-danger:hover {
    background: linear-gradient(45deg, #c82333, #bd2130);
    transform: translateY(-2px) scale(1.05);
}

.page-title {
    font-weight: 600;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .header-btn {
        padding: 8px 12px;
        font-size: 0.9rem;
    }
    
    .page-title {
        font-size: 1rem;
    }
}
</style>

<div class="container-fluid modern-header text-white py-3 sticky-top mb-3">
    <div class="row align-items-center">
        <!-- Título de la página -->
        <div class="col-md-6">
            <h6 class="text-center text-md-start mb-0 page-title" id="page-title">
                <i class="bi bi-box-seam me-2"></i>Gestión de Compras
            </h6>
        </div>

        <!-- Botones modernos -->
        <div class="col-md-6 d-flex justify-content-center justify-content-md-end gap-2 mt-2 mt-md-0">
            <!-- Botón Inicio -->
            <button class="btn header-btn btn-sm d-flex align-items-center justify-content-center" 
                onclick="window.location.href='/talara/index.php'" 
                title="Volver al menú principal">
                <i class="bi bi-house-door me-1"></i> Inicio
            </button>

            <!-- Botón Registrar Compra -->
            <button class="btn header-btn btn-primary btn-sm d-flex align-items-center justify-content-center" 
                onclick="window.location.href='/talara/compras/registrar_compra.php'" 
                title="Registrar Compra">
                <i class="bi bi-cart-plus-fill"></i>
            </button>

            <!-- Botón Ver Compras -->
            <button class="btn header-btn btn-sm d-flex align-items-center justify-content-center" 
                onclick="window.location.href='/talara/compras/compras.php'" 
                title="Ver Compras">
                <i class="bi bi-list-ul"></i>
            </button>

            <!-- Botón Registro Diario -->
            <button class="btn header-btn btn-sm d-flex align-items-center justify-content-center" 
                onclick="window.location.href='/talara/compras/registro_diario.php'" 
                title="Registro Diario">
                <i class="bi bi-calendar-check-fill"></i>
            </button>

            <!-- Botón Salir -->
            <button class="btn header-btn btn-danger btn-sm d-flex align-items-center justify-content-center" 
                onclick="window.location.href='/talara/logout.php'" 
                title="Cerrar Sesión">
                <i class="bi bi-box-arrow-right me-1"></i> Salir
            </button>
        </div>
    </div>
</div>

<script>
// Función para establecer el título de la página en el header
function setPageTitle(title) {
    const titleElement = document.getElementById('page-title');
    if (titleElement) {
        titleElement.innerHTML = '<i class="bi bi-box-seam me-2"></i>' + title;
    }
}
</script>