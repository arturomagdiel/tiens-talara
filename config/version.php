<?php
// Single Source of Truth para la versión de Tiens Talara
define('APP_VERSION', '1.6.5');
define('APP_NAME', 'Tiens Talara');
define('APP_DESCRIPTION', 'Sistema de gestión de compras, productos y afiliados para Tiens Talara');

// Función para obtener la versión completa
function getFullVersion() {
    return APP_NAME . ' v' . APP_VERSION;
}

// Función para obtener solo la versión
function getVersion() {
    return APP_VERSION;
}

// Función para obtener el cache name para Service Worker
function getCacheName() {
    return 'tiens-talara-v' . APP_VERSION;
}
?>