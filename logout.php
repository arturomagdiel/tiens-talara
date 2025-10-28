<?php
// Incluir sistema de autenticación y cerrar sesión
require_once 'shared/auth.php';
logout();

// Redirigir al usuario a la página de login
header('Location: /talara/login.php');
exit;
?>