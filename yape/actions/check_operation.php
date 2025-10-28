<?php
require_once __DIR__ . '/../_app.php';  // sesión + $conn
require_login();

header('Content-Type: application/json; charset=utf-8');

$op = trim($_GET['operation_no'] ?? '');
if ($op === '') {
  echo json_encode(['ok'=>true, 'exists'=>false]); exit;
}

$stmt = $conn->prepare("SELECT deposit_date, deposit_time, chat, origin, amount FROM yapeos WHERE operation_no=? LIMIT 1");
$stmt->bind_param('s', $op);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if ($res) {
  echo json_encode([
    'ok'=>true,
    'exists'=>true,
    'deposit_date'=>$res['deposit_date'],
    'deposit_time'=>substr($res['deposit_time'],0,5),
    'chat'=>$res['chat'],
    'origin'=>$res['origin'],
    'amount'=>number_format((float)$res['amount'], 2, '.', '')
  ]);
} else {
  echo json_encode(['ok'=>true, 'exists'=>false]);
}
