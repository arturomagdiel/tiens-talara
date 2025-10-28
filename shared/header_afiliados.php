<!-- Header moderno para el módulo de afiliados v1.3.9 -->
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

.header-btn.btn-success {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
}

.header-btn.btn-success:hover {
    background: linear-gradient(45deg, #218838, #1e9b8a);
    transform: translateY(-2px) scale(1.05);
}

.header-btn.btn-secondary {
    background: linear-gradient(45deg, #6c757d, #5a6268);
    border: none;
}

.header-btn.btn-secondary:hover {
    background: linear-gradient(45deg, #5a6268, #495057);
    transform: translateY(-2px) scale(1.05);
}

.page-title {
    font-weight: 600;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    color: white;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-title i {
    font-size: 1.8rem;
}

@media (max-width: 768px) {
    .header-btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        margin: 0.125rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .page-title i {
        font-size: 1.4rem;
    }
    
    .btn-group-mobile {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        width: 100%;
    }
    
    .btn-group-mobile .header-btn {
        width: 100%;
        margin: 0;
    }
}
</style>

<nav class="navbar navbar-expand-lg modern-header">
    <div class="container-fluid">
        <!-- Título del módulo -->
        <h1 class="page-title">
            <i class="bi bi-people-fill"></i>
            Gestión de Afiliados
        </h1>
        
        <!-- Botones de acción -->
        <div class="d-flex align-items-center gap-2">
            <div class="d-none d-md-flex gap-2">
                <!-- Botón Agregar Afiliado -->
                <button class="btn header-btn btn-success" id="btnNuevaPersonaHeader">
                    <i class="bi bi-person-plus me-1"></i>
                    Agregar Afiliado
                </button>
                
                <!-- Botón Salir -->
                <a href="../index.php" class="btn header-btn btn-secondary">
                    <i class="bi bi-house me-1"></i>
                    Salir
                </a>
            </div>
            
            <!-- Menú móvil -->
            <div class="dropdown d-md-none">
                <button class="btn header-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <button class="dropdown-item" id="btnNuevaPersonaMobile">
                            <i class="bi bi-person-plus me-2"></i>
                            Agregar Afiliado
                        </button>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="../index.php">
                            <i class="bi bi-house me-2"></i>
                            Salir
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<script>
// Script para sincronizar botones de agregar afiliado
document.addEventListener('DOMContentLoaded', function() {
    // Botón desktop
    const btnHeaderDesktop = document.getElementById('btnNuevaPersonaHeader');
    if (btnHeaderDesktop) {
        btnHeaderDesktop.addEventListener('click', function() {
            const btnNuevaPersona = document.getElementById('btnNuevaPersona');
            if (btnNuevaPersona) {
                btnNuevaPersona.click();
            }
        });
    }
    
    // Botón móvil
    const btnHeaderMobile = document.getElementById('btnNuevaPersonaMobile');
    if (btnHeaderMobile) {
        btnHeaderMobile.addEventListener('click', function() {
            const btnNuevaPersona = document.getElementById('btnNuevaPersona');
            if (btnNuevaPersona) {
                btnNuevaPersona.click();
            }
        });
    }
});
</script>