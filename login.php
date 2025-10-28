<?php
// Incluir el sistema de autenticación
require_once __DIR__ . '/shared/auth.php';

$error = '';

// Si ya está autenticado, redirigir
if (isAuthenticated()) {
    $redirect = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : '/talara/';
    unset($_SESSION['redirect_after_login']);
    header('Location: ' . $redirect);
    exit;
}

// Procesar formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clave_acceso'])) {
    $password = $_POST['clave_acceso'];
    
    if (processLogin($password)) {
        // Login exitoso - redirigir a la página original o index por defecto
        $redirect = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : '/talara/';
        
        // Debug: Log para verificar redirección (quitar en producción)
        error_log("LOGIN DEBUG: Redirigiendo a: " . $redirect);
        
        unset($_SESSION['redirect_after_login']);
        header('Location: ' . $redirect);
        exit;
    } else {
        $error = 'Clave de acceso incorrecta';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Tiens Talara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        .btn-login {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            backdrop-filter: blur(5px);
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            color: white;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .logo-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-card p-4 text-center text-white">
                    <!-- Logo y título -->
                    <div class="mb-4">
                        <i class="bi bi-leaf logo-icon"></i>
                        <h3 class="fw-bold mb-2">Tiens Talara</h3>
                        <p class="mb-0 opacity-75">Sistema de Gestión</p>
                    </div>

                    <!-- Mostrar errores si existen -->
                    <?php if (isset($error) && !empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Formulario de login -->
                    <form method="POST" action="">
                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-white-50">
                                    <i class="bi bi-key"></i>
                                </span>
                                <input type="password" 
                                       class="form-control border-start-0" 
                                       name="clave_acceso" 
                                       placeholder="Clave de acceso" 
                                       required
                                       autocomplete="current-password"
                                       autofocus>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-login text-white">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Ingresar
                            </button>
                        </div>
                    </form>

                    <!-- Link de regreso -->
                    <div class="mt-4">
                        <a href="/talara/index.php" class="text-white-50 text-decoration-none small">
                            <i class="bi bi-arrow-left me-1"></i>
                            Volver al inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animación de entrada suave
        document.addEventListener('DOMContentLoaded', function() {
            const loginCard = document.querySelector('.login-card');
            loginCard.style.opacity = '0';
            loginCard.style.transform = 'translateY(30px) scale(0.9)';
            
            setTimeout(() => {
                loginCard.style.transition = 'all 0.6s ease';
                loginCard.style.opacity = '1';
                loginCard.style.transform = 'translateY(0) scale(1)';
            }, 200);
        });
        
        // Efecto de typing en el placeholder
        const passwordInput = document.querySelector('input[name="clave_acceso"]');
        if (passwordInput) {
            passwordInput.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
                this.parentElement.style.transition = 'transform 0.2s ease';
            });
            
            passwordInput.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        }
    </script>
</body>
</html>