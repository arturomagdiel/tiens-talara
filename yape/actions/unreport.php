<?php
require_once __DIR__ . '/../_app.php'; require_login(); csrf_check();

$id = (int)($_POST['id'] ?? 0);
$back = $_POST['back'] ?? '../index.php';
if (!$id){ header("Location: $back"); exit; }

// obtener
$q = $conn->prepare("SELECT id, amount, report_id FROM yapeos WHERE id=? AND reported=1");
$q->bind_param('i',$id); $q->execute(); $row = $q->get_result()->fetch_assoc();
if (!$row){ $_SESSION['flash_err']='No existe o no está reportado.'; header("Location: $back"); exit; }

$rep_id = (int)$row['report_id']; $amt = (float)$row['amount'];

// desmarcar
$u = $conn->prepare("UPDATE yapeos SET reported=0, report_id=NULL, reported_at=NULL WHERE id=?");
$u->bind_param('i',$id); $u->execute();

// ajustar totales
$adj = $conn->prepare("UPDATE yapeo_reports SET item_count=GREATEST(item_count-1,0), total_amount=GREATEST(total_amount-?,0) WHERE id=?");
$adj->bind_param('di',$amt,$rep_id); $adj->execute();

$_SESSION['flash_ok']='Depósito desmarcado del reporte.';
header("Location: $back");
