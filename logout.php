<?php
// Incluir sistema de autenticación y cerrar sesión
require_once 'shared/auth.php';

// Ejecutar logout
logout();

// Cabeceras adicionales de seguridad para evitar caché
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');

// Redirigir al usuario a la página de login
header('Location: /talara/login.php');
exit;
?>