<?php
/**
 * Sistema de Autenticación Global
 * Archivo: shared/auth.php
 * 
 * Este archivo maneja toda la lógica de autenticación de forma centralizada.
 * Se puede incluir en cualquier página que requiera autenticación.
 */

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir conexión a base de datos
require_once __DIR__ . '/conexion.php';

/**
 * Verificar si el usuario está autenticado
 * @return bool True si está autenticado, False si no
 */
function isAuthenticated() {
    return isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === true;
}

/**
 * Requerir autenticación - redirige si no está autenticado
 * @param string $redirectTo URL donde redirigir si no está autenticado (opcional)
 */
function requireAuth($redirectTo = '/talara/login.php') {
    if (!isAuthenticated()) {
        // Guardar la URL actual para redirigir después del login
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: ' . $redirectTo);
        exit;
    }
}

/**
 * Procesar intento de login
 * @param string $password Contraseña ingresada
 * @return bool True si login exitoso, False si falla
 */
function processLogin($password) {
    global $conn;
    
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
    if (in_array($password, $claves_validas)) {
        $_SESSION['autenticado'] = true;
        return true;
    }
    
    return false;
}

/**
 * Cerrar sesión
 */
function logout() {
    session_start();
    session_unset();
    session_destroy();
}

/**
 * Obtener el estado de autenticación para JavaScript
 * @return string JSON con el estado
 */
function getAuthStatus() {
    return json_encode([
        'authenticated' => isAuthenticated(),
        'timestamp' => time()
    ]);
}
?>