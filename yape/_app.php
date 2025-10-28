<?php
// Bootstrap de la app (sesión, conexión, helpers, seguridad)
session_start();

require_once __DIR__ . '/../shared/conexion.php'; // mysqli $conn

// Config
const APP_PASSWORD = 'arnomamisaca';
const DIR_UPLOADS  = 'yape-uploads';
const DIR_REPORTS  = 'yape-reports';

$ORIGENES = [
  'yape' => 'Yape',
  'yape desde plin' => 'Yape desde Plin',
  'yape desde bn' => 'Yape desde BN',
  'yape desde bim' => 'Yape desde BIM',
  'yape desde scotia' => 'Yape desde Scotia',
];

// Asegurar carpetas
foreach ([DIR_UPLOADS, DIR_REPORTS] as $d) {
  $path = __DIR__ . "/$d";
  if (!is_dir($path)) @mkdir($path, 0775, true);
}

// Helpers
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money($n){ return number_format((float)$n, 2, '.', ''); }

// CSRF
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
function csrf_field(){ echo '<input type="hidden" name="csrf" value="'.h($_SESSION['csrf']).'">'; }
function csrf_check(){
  if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (empty($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
      http_response_code(400);
      exit('CSRF inválido');
    }
  }
}

// Auth
function require_login(){
  if (empty($_SESSION['ok'])) {
    header("Location: ./login.php"); exit;
  }
}

// Flash
function flash_out(){
  if (!empty($_SESSION['flash_ok'])) {
    echo '<div class="alert alert-success">'.h($_SESSION['flash_ok']).'</div>';
    unset($_SESSION['flash_ok']);
  }
  if (!empty($_SESSION['flash_err'])) {
    echo '<div class="alert alert-danger">'.h($_SESSION['flash_err']).'</div>';
    unset($_SESSION['flash_err']);
  }
}
