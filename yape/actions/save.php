<?php
require_once __DIR__ . '/../_app.php'; require_login(); csrf_check();

$origin = strtolower(trim($_POST['origin'] ?? ''));
$deposit_date = $_POST['deposit_date'] ?? '';
$deposit_time = $_POST['deposit_time'] ?? '';
$operation_no = trim($_POST['operation_no'] ?? '');
$amount = (float)($_POST['amount'] ?? 0);
$chat = trim($_POST['chat'] ?? '');
$note = trim($_POST['note'] ?? '');
$image_b64 = $_POST['image_base64'] ?? '';
$errs=[];

global $ORIGENES, $conn;

if (!$deposit_date) $errs[]='Fecha requerida';
if (!$deposit_time) $errs[]='Hora requerida';
if (!isset($ORIGENES[$origin])) $errs[]='Origen inválido';
if ($operation_no==='') $errs[]='N° operación requerido';
if ($amount<=0) $errs[]='Monto > 0';
if ($chat==='') $errs[]='Chat requerido';

// Imagen
$image_path = null;
try {
  if ($image_b64) {
    if (preg_match('#^data:image/(png|jpe?g|webp);base64,#i',$image_b64,$m)) {
      $ext = strtolower($m[1]); if ($ext==='jpeg') $ext='jpg';
      $raw = base64_decode(preg_replace('#^data:image/\w+;base64,#','',$image_b64), true);
      if ($raw===false) throw new Exception('Base64 inválido');
      $fname = 'up_'.date('Ymd_His').'_'.bin2hex(random_bytes(4)).'.'.$ext;
      file_put_contents(__DIR__."/../".DIR_UPLOADS."/$fname", $raw);
      $image_path = DIR_UPLOADS."/$fname";
    }
  } elseif (!empty($_FILES['voucher_file']['tmp_name'])) {
    $f = $_FILES['voucher_file'];
    if ($f['error']===UPLOAD_ERR_OK) {
      $mime = mime_content_type($f['tmp_name']);
      $ext = 'jpg';
      if ($mime==='image/png') $ext='png';
      elseif ($mime==='image/webp') $ext='webp';
      elseif (in_array($mime,['image/jpeg','image/pjpeg'])) $ext='jpg';
      else throw new Exception('Formato no permitido');
      $fname = 'up_'.date('Ymd_His').'_'.bin2hex(random_bytes(4)).'.'.$ext;
      move_uploaded_file($f['tmp_name'], __DIR__."/../".DIR_UPLOADS."/$fname");
      $image_path = DIR_UPLOADS."/$fname";
    }
  }
} catch (Throwable $e) { $errs[]='Error imagen: '.$e->getMessage(); }

if ($errs){ $_SESSION['flash_err']=implode(' · ',$errs); header("Location: ../index.php"); exit; }

$sql = "INSERT INTO yapeos (deposit_date,deposit_time,origin,operation_no,amount,chat,note,image_path)
        VALUES (?,?,?,?,?,?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssssssss',$deposit_date,$deposit_time,$origin,$operation_no,$amount,$chat,$note,$image_path);
try {
  $stmt->execute(); $_SESSION['flash_ok']='Yapeo guardado.';
} catch (mysqli_sql_exception $e) {
  $_SESSION['flash_err'] = ($e->getCode()==1062) ? 'El N° de operación ya existe.' : ('Error: '.$e->getMessage());
}
header("Location: ../index.php");
