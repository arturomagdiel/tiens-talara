<?php
// filepath: c:\Users\artur\Documents\GitHub\tiens-talara\compras\registro_diario.php
// Configurar la zona horaria
date_default_timezone_set('America/Lima');
// Obtener la fecha actual
$fecha_actual = date('Y-m-d');

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Diario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<?php
// Incluir sistema de autenticación y requerir login
require_once '../shared/auth.php';
requireAuth();
?>

<?php include '../shared/header_compras.php'; ?>

<script>
// Establecer el título específico para esta página
setPageTitle('Registro Diario');
</script>

<div class="container mt-5">
 
<!-- Selección de rango de fechas -->
<div class="row mb-4">
        <div class="col-md-4">
            <label for="fechaInicio" class="form-label">Fecha Inicio</label>
            <input type="date" id="fechaInicio" class="form-control" value="<?= $fecha_actual; ?>">
        </div>
        <div class="col-md-4">
            <label for="fechaFin" class="form-label">Fecha Fin</label>
            <input type="date" id="fechaFin" class="form-control" value="<?= $fecha_actual; ?>">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button id="btnImprimir" class="btn btn-primary w-100">Imprimir</button>
        </div>
    </div>

    <!-- Listado de personas y compras -->
    <div id="registroDiario" class="mt-4">
        <div class="alert alert-info text-center">Seleccione un rango de fechas para ver las compras.</div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="registro_diario.js"></script>
</body>
</html>