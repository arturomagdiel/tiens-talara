
<!-- Contenido del header -->
<div class="container-fluid bg-dark text-white py-1 sticky-top mb-2">
    <div class="row align-items-center">
        <!-- Título de la página -->
        <div class="col-md-6">
            <h6 class="text-center text-md-start mb-0" id="page-title">Título de la Página</h6>
        </div>

        <!-- Botones a la derecha -->
        <div class="col-md-6 d-flex justify-content-center justify-content-md-end gap-2">
        
                    <!-- Botón Menú Principal -->
                    <button class="btn btn-light btn-sm d-flex align-items-center justify-content-center" 
                onclick="window.location.href='../index.php'" 
                title="Menú Principal">
                <i class="bi bi-house-door text-dark m-1"></i> Inicio
            </button>

            <!-- Botón Registrar Compra -->
            <button class="btn btn-primary btn-sm d-flex align-items-center justify-content-center" 
                onclick="window.location.href='../compras/registrar_compra.php'" 
                title="Registrar Compra">
                <i class="bi bi-cart-plus m-1"></i>Comprar
            </button>


            <!-- Botón Ver Compras -->
            <button class="btn btn-info btn-sm d-flex align-items-center justify-content-center" 
                onclick="window.location.href='../compras/compras.php'" 
                title="Ver Compras">
                <i class="bi bi-list-check m-1"></i>Ver Compras
            </button>

            <!-- Botón Buscar Producto -->
            <button class="btn btn-secondary btn-sm d-flex align-items-center justify-content-center" 
                onclick="window.location.href='../compras/registro_diario.php'" 
                title="Registro Diario">
                <i class="bi bi-search m-1"></i> Registro Diario
            </button>



           
        </div>
    </div>
</div>