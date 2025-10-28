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
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
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
            Afiliados
        </h1>
        
        <!-- Botones de acción -->
        <div class="d-flex align-items-center gap-2">
            <!-- Botón Agregar Afiliado -->
            <button class="btn header-btn btn-success" id="btnNuevaPersonaHeader" title="Agregar Afiliado">
                <i class="bi bi-person-plus"></i>
            </button>
            
            <!-- Botón Salir -->
            <a href="../index.php" class="btn header-btn btn-secondary" title="Salir">
                <i class="bi bi-house"></i>
            </a>
        </div>
    </div>
</nav>

<script>
// Script para sincronizar botón de agregar afiliado
document.addEventListener('DOMContentLoaded', function() {
    // Botón header
    const btnHeader = document.getElementById('btnNuevaPersonaHeader');
    if (btnHeader) {
        btnHeader.addEventListener('click', function() {
            const btnNuevaPersona = document.getElementById('btnNuevaPersona');
            if (btnNuevaPersona) {
                btnNuevaPersona.click();
            }
        });
    }
});
</script>