<?php
require_once __DIR__ . '/../_app.php'; require_login(); csrf_check();

$from = $_POST['from'] ?: null;
$to   = $_POST['to'] ?: null;
$only_unreported = !empty($_POST['only_unreported']);
$origin_filter = $_POST['origin_filter'] ?? '';
$report_note = trim($_POST['report_note'] ?? '');
global $ORIGENES, $conn;
if ($origin_filter!=='' && !isset($ORIGENES[strtolower($origin_filter)])) $origin_filter='';

$where=[]; $params=[]; $types='';
if ($from){ $where[]="deposit_date>=?"; $params[]=$from; $types.='s'; }
if ($to){   $where[]="deposit_date<=?"; $params[]=$to; $types.='s'; }
if ($only_unreported){ $where[]="reported=0"; }
if ($origin_filter!==''){ $where[]="origin=?"; $params[]=strtolower($origin_filter); $types.='s'; }
$wsql = $where ? "WHERE ".implode(' AND ',$where) : '';

$sql = "SELECT * FROM yapeos $wsql ORDER BY deposit_date, deposit_time, id";
$stmt = $conn->prepare($sql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute(); $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (!$data){ $_SESSION['flash_err']='No hay yapeos para ese filtro.'; header("Location: ../index.php"); exit; }

$total = array_sum(array_map(fn($r)=>(float)$r['amount'],$data));
$sqlr = "INSERT INTO yapeo_reports (from_date,to_date,filter_origin,note,total_amount,item_count) VALUES (?,?,?,?,?,?)";
$stmtr = $conn->prepare($sqlr);
$fo = ($origin_filter?:NULL); $ta = money($total); $cnt = count($data);
$stmtr->bind_param('ssssdi', $from,$to,$fo,$report_note,$ta,$cnt);
$stmtr->execute(); $report_id = $conn->insert_id;

// marcar
$now = date('Y-m-d H:i:s');
$ids = array_column($data,'id');
$in = implode(',', array_fill(0, count($ids), '?'));
$types2 = str_repeat('i', count($ids));
$sqlUp = "UPDATE yapeos SET reported=1, report_id=?, reported_at=? WHERE id IN ($in)";
$stmtUp = $conn->prepare($sqlUp);
$typesAll = 'is'.$types2;
$bind = array_merge([$typesAll, $report_id, $now], $ids);
$tmp = []; foreach ($bind as $k=>$v){ $tmp[$k]=&$bind[$k]; }
call_user_func_array([$stmtUp,'bind_param'],$tmp);
$stmtUp->execute();

// CSV
$csv = __DIR__."/../".DIR_REPORTS."/reporte_$report_id.csv";
$fh = fopen($csv,'w');
fputcsv($fh, ['Report ID',$report_id]);
fputcsv($fh, ['Generado',$now]);
fputcsv($fh, ['Desde',$from,'Hasta',$to,'Origen',$origin_filter?:'Todos']);
fputcsv($fh, ['Nota',$report_note]); fputcsv($fh, []);
fputcsv($fh, ['Fecha','Hora','Origen','Operacion','Monto','Chat','Nota','Imagen','Reportado','Reportado en']);
foreach ($data as $r) {
  fputcsv($fh, [$r['deposit_date'],$r['deposit_time'],$r['origin'],$r['operation_no'],money($r['amount']),$r['chat'],$r['note'],$r['image_path'],'Sí',$now]);
}
fputcsv($fh, []); fputcsv($fh, ['Total', money($total)]); fclose($fh);

$_SESSION['flash_ok'] = "Reporte #$report_id generado.";
header("Location: ../report.php?id=".$report_id);
