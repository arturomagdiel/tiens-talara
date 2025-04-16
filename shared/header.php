<?php
session_start(); // Iniciar la sesión

include '../shared/conexion.php';

$error = ''; // Variable para almacenar el mensaje de error

// Verificar si el usuario ya está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    // Verificar si se ha enviado una clave de acceso
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clave_acceso'])) {
        $clave_acceso = $_POST['clave_acceso'];

        // Consultar las claves de acceso desde la tabla configuraciones
        $stmt = $conn->prepare("SELECT valor FROM configuraciones WHERE clave IN ('pass_admin', 'pass_noemi')");
        $stmt->execute();
        $result = $stmt->get_result();

        $claves_validas = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $claves_validas[] = $row['valor'];
            }
        }

        // Verificar si la clave ingresada es válida
        if (in_array($clave_acceso, $claves_validas)) {
            // Marcar al usuario como autenticado
            $_SESSION['autenticado'] = true;

            // Redirigir al usuario a la misma página para evitar que el formulario se muestre nuevamente
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            // Mostrar un mensaje de error en el formulario
            $error = 'Clave incorrecta. Inténtelo nuevamente.';
        }
    }

    // Mostrar el formulario de clave de acceso si no se ha enviado una clave o si hay un error
    echo '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Acceso Restringido</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Acceso Restringido</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="clave_acceso" class="form-label">Ingrese la clave de acceso</label>
                            <input type="password" id="clave_acceso" name="clave_acceso" class="form-control" required>
                        </div>
                        ' . (!empty($error) ? '<div class="alert alert-danger">' . $error . '</div>' : '') . '
                        <button type="submit" class="btn btn-primary">Acceder</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
    </html>
    ';
    exit;
}
?>

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



            <!-- Botón Logout -->
            <button class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" 
                onclick="window.location.href='../logout.php'" 
                title="Cerrar Sesión">
                <i class="bi bi-box-arrow-right m-1"></i> Salir
            </button>
        </div>
    </div>
</div>