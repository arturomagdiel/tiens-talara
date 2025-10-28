<?php
// Proteger página con autenticación
require_once __DIR__ . '/../shared/auth.php';
requireAuth();

// Conexión a la base de datos
include '../shared/conexion.php';

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tiens Talara - Gestión de Afiliados</title>
  
  <!-- Bootstrap 5.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  
  <!-- DataTables con Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
  
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      --card-glass: rgba(255, 255, 255, 0.25);
      --card-border: rgba(255, 255, 255, 0.18);
      --text-primary: #2d3748;
      --text-secondary: #4a5568;
      --shadow-soft: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }

    body {
      background: var(--primary-gradient);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Contenedor principal con glass morphism */
    .main-container {
      background: var(--card-glass);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-radius: 20px;
      border: 1px solid var(--card-border);
      box-shadow: var(--shadow-soft);
      margin: 1rem auto;
      padding: 1.5rem;
      max-width: 95%;
    }

    /* Botón moderno */
    .btn-modern {
      background: var(--success-gradient);
      border: none;
      border-radius: 15px;
      padding: 0.75rem 1.5rem;
      color: white;
      font-weight: 500;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px 0 rgba(116, 79, 168, 0.3);
    }

    .btn-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px 0 rgba(116, 79, 168, 0.4);
      color: white;
    }

    /* DataTable personalizada */
    .table-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      padding: 1.5rem;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      margin-top: 0;
    }

    #tablaPersonas {
      border-radius: 10px;
      overflow: hidden;
    }

    #tablaPersonas thead th {
      background: var(--primary-gradient);
      color: white;
      border: none;
      font-weight: 500;
      padding: 1rem 0.75rem;
    }

    #tablaPersonas tbody tr {
      transition: all 0.3s ease;
    }

    #tablaPersonas tbody tr:hover {
      background-color: rgba(102, 126, 234, 0.1);
      transform: scale(1.01);
    }

    #tablaPersonas tbody td {
      padding: 0.75rem;
      vertical-align: middle;
      border-color: rgba(0, 0, 0, 0.05);
    }

    /* Botones de acción */
    .btn-action {
      padding: 0.375rem 0.75rem;
      border-radius: 8px;
      border: none;
      margin: 0 0.125rem;
      transition: all 0.3s ease;
      font-size: 0.875rem;
    }

    .btn-edit {
      background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
      color: white;
    }

    .btn-edit:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(245, 87, 108, 0.3);
      color: white;
    }

    .btn-delete {
      background: linear-gradient(45deg, #ff9a9e 0%, #fecfef 100%);
      color: #721c24;
    }

    .btn-delete:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(255, 154, 158, 0.3);
      color: #721c24;
    }

    /* Modal moderno */
    .modal-content {
      background: var(--card-glass);
      backdrop-filter: blur(20px);
      border: 1px solid var(--card-border);
      border-radius: 20px;
      box-shadow: var(--shadow-soft);
    }

    .modal-header {
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 20px 20px 0 0;
    }

    .modal-title {
      color: var(--text-primary);
      font-weight: 600;
    }

    .form-label {
      color: var(--text-primary);
      font-weight: 500;
      margin-bottom: 0.5rem;
    }

    .form-control {
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.9);
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
      background: white;
    }

    /* Responsive design */
    @media (max-width: 768px) {
      .main-container {
        margin: 0.5rem;
        padding: 0.75rem;
      }
      
      .page-title {
        font-size: 2rem;
      }
      
      .table-container {
        padding: 0.75rem;
      }
      
      .btn-modern {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
      }
      
      /* Ocultar columnas en móvil - SOLO mostrar nombre */
      #tablaPersonas th:nth-child(2), /* Código */
      #tablaPersonas td:nth-child(2),
      #tablaPersonas th:nth-child(3), /* Descuento */
      #tablaPersonas td:nth-child(3),
      #tablaPersonas th:nth-child(5), /* Apellido */
      #tablaPersonas td:nth-child(5),
      #tablaPersonas th:nth-child(6), /* Teléfono */
      #tablaPersonas td:nth-child(6),
      #tablaPersonas th:nth-child(7), /* RUC */
      #tablaPersonas td:nth-child(7),
      #tablaPersonas th:nth-child(8), /* Patrocinador */
      #tablaPersonas td:nth-child(8),
      #tablaPersonas th:nth-child(9), /* Acciones */
      #tablaPersonas td:nth-child(9) {
        display: none;
      }
      
      /* Solo mostrar columna nombre en móvil */
      #tablaPersonas th:nth-child(4),
      #tablaPersonas td:nth-child(4) {
        width: 100%;
        text-align: center;
        padding: 1rem 0.5rem;
      }
      
      /* Hacer la fila clickeable */
      #tablaPersonas tbody tr {
        cursor: pointer;
      }
      
      #tablaPersonas tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.15);
      }
      
      /* Estilo para la fila expandida */
      .details-row {
        background-color: rgba(102, 126, 234, 0.05) !important;
      }
      
      .details-content {
        padding: 1rem;
        border-left: 3px solid #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        border-radius: 8px;
        margin: 0.5rem 0;
      }
      
      .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 0.25rem 0;
        border-bottom: 1px solid rgba(102, 126, 234, 0.1);
      }
      
      .detail-item:last-child {
        border-bottom: none;
      }
      
      .detail-label {
        font-weight: 600;
        color: #667eea;
      }
      
      .detail-value {
        color: #2d3748;
      }
      
      /* Indicador visual para filas expandibles */
      #tablaPersonas tbody tr:not(.details-row)::after {
        content: '👆';
        position: absolute;
        right: 10px;
        opacity: 0.3;
        font-size: 0.8rem;
      }
      
      #tablaPersonas tbody tr.expanded::after {
        content: '👇';
      }
      
      /* Mejorar botones en móvil */
      .btn-action {
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        font-size: 0.9rem;
        min-width: 80px;
      }
      
      /* Estilo especial para iconos pequeños en detalles */
      .details-content .btn-action {
        padding: 0.5rem;
        font-weight: 600;
        margin: 0 0.5rem;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
      }
      
      .details-content .btn-edit {
        background: linear-gradient(45deg, #28a745, #20c997);
        border: none;
        color: white;
      }
      
      .details-content .btn-edit:hover {
        background: linear-gradient(45deg, #218838, #1e9b8a);
        transform: translateY(-2px) scale(1.1);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
        color: white;
      }
      
      .details-content .btn-delete {
        background: linear-gradient(45deg, #dc3545, #c82333);
        border: none;
        color: white;
      }
      
      .details-content .btn-delete:hover {
        background: linear-gradient(45deg, #c82333, #bd2130);
        transform: translateY(-2px) scale(1.1);
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        color: white;
      }
    }

    /* DataTables responsive styling */
    .dataTables_wrapper {
      padding: 0;
    }

    .dataTables_filter input {
      border: 1px solid rgba(102, 126, 234, 0.3);
      border-radius: 10px;
      padding: 0.5rem 1rem;
    }

    .dataTables_length select {
      border: 1px solid rgba(102, 126, 234, 0.3);
      border-radius: 8px;
      padding: 0.375rem;
    }

    .page-link {
      border-radius: 8px;
      margin: 0 2px;
      border: 1px solid rgba(102, 126, 234, 0.3);
    }

    .page-item.active .page-link {
      background: var(--primary-gradient);
      border-color: transparent;
    }
  </style>
</head>

<body>

<?php include '../shared/header_afiliados.php'; ?>

  <div class="container-fluid">
    <div class="main-container">

      <!-- Botón Agregar Afiliado (oculto, será activado desde el header) -->
      <button class="btn btn-modern d-none" id="btnNuevaPersona">
        <i class="bi bi-person-plus me-2"></i>
        Agregar Afiliado
      </button>

      <!-- Contenedor de la tabla -->
      <div class="table-container">
        <div class="table-responsive">
          <table id="tablaPersonas" class="table table-hover">
            <thead>
              <tr>
                <th class="d-none">ID</th>
                <th class="d-none d-md-table-cell"><i class="bi bi-hash me-1"></i>Código</th>
                <th class="d-none d-md-table-cell"><i class="bi bi-percent me-1"></i>Descuento</th>
                <th><i class="bi bi-person me-1"></i><span class="d-none d-md-inline">Nombre</span><span class="d-md-none">Afiliados</span></th>
                <th class="d-none d-md-table-cell"><i class="bi bi-person-badge me-1"></i>Apellido</th>
                <th class="d-none d-md-table-cell"><i class="bi bi-telephone me-1"></i>Teléfono</th>
                <th class="d-none d-md-table-cell"><i class="bi bi-building me-1"></i>RUC</th>
                <th class="d-none d-md-table-cell"><i class="bi bi-person-check me-1"></i>Patrocinador</th>
                <th class="d-none d-md-table-cell"><i class="bi bi-gear me-1"></i>Acciones</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

  <!-- Modal para Crear/Editar Afiliado -->
  <div class="modal fade" id="personaPopup" tabindex="-1" aria-labelledby="personaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="personaModalLabel">
            <i class="bi bi-person-gear me-2"></i>
            Gestionar Afiliado
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="personaForm">
            <input type="hidden" id="personaId" name="id">

            <div class="row g-3">
              <!-- Nombre y Apellido -->
              <div class="col-md-6">
                <label for="nombre" class="form-label">
                  <i class="bi bi-person me-1"></i>Nombre
                </label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
              </div>
              
              <div class="col-md-6">
                <label for="apellido" class="form-label">
                  <i class="bi bi-person-badge me-1"></i>Apellido
                </label>
                <input type="text" class="form-control" id="apellido" name="apellido" required>
              </div>

              <!-- Código y Descuento -->
              <div class="col-md-6">
                <label for="codigo" class="form-label">
                  <i class="bi bi-hash me-1"></i>Código de Afiliado
                </label>
                <input type="text" class="form-control" id="codigo" name="codigo" required>
              </div>
              
              <div class="col-md-6">
                <label for="descuento" class="form-label">
                  <i class="bi bi-percent me-1"></i>Descuento (%)
                </label>
                <select class="form-control" id="descuento" name="descuento">
                  <option value="0">0%</option>
                  <option value="5">5%</option>
                  <option value="8">8%</option>
                  <option value="15">15%</option>
                </select>
              </div>

              <!-- Teléfono y RUC -->
              <div class="col-md-6">
                <label for="telefono" class="form-label">
                  <i class="bi bi-telephone me-1"></i>Teléfono
                </label>
                <input type="tel" class="form-control" id="telefono" name="telefono">
              </div>
              
              <div class="col-md-6">
                <label for="ruc" class="form-label">
                  <i class="bi bi-building me-1"></i>RUC
                </label>
                <input type="text" class="form-control" id="ruc" name="ruc">
              </div>

              <!-- Patrocinador -->
              <div class="col-12">
                <label for="patrocinador" class="form-label">
                  <i class="bi bi-person-check me-1"></i>Patrocinador
                </label>
                <input type="text" class="form-control" id="patrocinador" name="patrocinador">
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-lg me-1"></i>Cancelar
          </button>
          <button type="submit" form="personaForm" class="btn btn-modern">
            <i class="bi bi-check-lg me-1"></i>Guardar Afiliado
          </button>
          <button type="button" class="btn btn-delete" data-id="" id="btnModalEliminar">
            <i class="bi bi-trash me-1"></i>Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Confirmación de Eliminación -->
  <div class="modal fade" id="confirmarEliminarPopup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Confirmar Eliminación
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <i class="bi bi-person-x display-1 text-danger mb-3"></i>
            <p class="fs-5">¿Estás seguro de que quieres eliminar este afiliado?</p>
            <p class="text-muted">Esta acción no se puede deshacer.</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-lg me-1"></i>Cancelar
          </button>
          <button type="button" class="btn btn-delete" id="btnConfirmarEliminar">
            <i class="bi bi-trash me-1"></i>Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Éxito -->
  <div class="modal fade" id="mensajeExitoPopup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-success">
            <i class="bi bi-check-circle me-2"></i>
            ¡Éxito!
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <i class="bi bi-check-circle display-1 text-success mb-3"></i>
          <p class="fs-5">¡Los datos se han guardado correctamente!</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-modern" data-bs-dismiss="modal">
            <i class="bi bi-check-lg me-1"></i>Entendido
          </button>
        </div>
      </div>
    </div>
  </div>

  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
  <script src="index-modern.js"></script>

</body>

</html>