<?php
require_once __DIR__ . '/_app.php'; require_login();
$id = (int)($_GET['id'] ?? 0);
if (!$id) { header("Location: ./index.php"); exit; }

$rpt = $conn->prepare("SELECT * FROM yapeo_reports WHERE id=?");
$rpt->bind_param('i',$id); $rpt->execute();
$report = $rpt->get_result()->fetch_assoc();
if (!$report){ die('Reporte no encontrado'); }

$q = $conn->prepare("SELECT * FROM yapeos WHERE report_id=? ORDER BY deposit_date, deposit_time, id");
$q->bind_param('i',$id); $q->execute();
$data = $q->get_result()->fetch_all(MYSQLI_ASSOC);

$title = "Reporte #$id";
include __DIR__ . '/_header.php';
?>
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="mb-2">Reporte #<?= (int)$id ?></h5>
    <p class="mb-1"><b>Rango:</b> <?=h($report['from_date'] ?: '—')?> a <?=h($report['to_date'] ?: '—')?> ·
      <b>Origen:</b> <?=h($report['filter_origin'] ?: 'Todos')?></p>
    <?php if ($report['note']): ?><p class="mb-1"><b>Nota:</b> <?= nl2br(h($report['note'])) ?></p><?php endif; ?>
    <p class="mb-1"><b>Items:</b> <?= (int)$report['item_count'] ?> · <b>Total:</b> S/ <?= money($report['total_amount']) ?></p>
    <?php $csv = DIR_REPORTS . "/reporte_{$id}.csv"; if (is_file(__DIR__."/$csv")): ?>
      <a class="btn btn-dark btn-sm" href="<?=h($csv)?>" download>⬇️ Descargar CSV</a>
    <?php endif; ?>
    <a class="btn btn-outline-dark btn-sm" href="./index.php">← Volver</a>
  </div>
</div>

<div class="card shadow-sm mt-3">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead><tr>
          <th>#</th><th>Fecha</th><th>Hora</th><th>Origen</th><th>Operación</th>
          <th class="text-end">Monto</th><th>Chat</th><th>Nota</th><th>Imagen</th><th></th>
        </tr></thead>
        <tbody>
        <?php foreach($data as $r): ?>
          <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= h($r['deposit_date']) ?></td>
            <td><?= h(substr($r['deposit_time'],0,5)) ?></td>
            <td><?= h($ORIGENES[$r['origin']] ?? $r['origin']) ?></td>
            <td><?= h($r['operation_no']) ?></td>
            <td class="text-end">S/ <?= money($r['amount']) ?></td>
            <td><?= h($r['chat']) ?></td>
            <td><?= h($r['note']) ?></td>
            <td><?= $r['image_path']?'<a target="_blank" href="'.h($r['image_path']).'">Ver</a>':'—' ?></td>
            <td class="text-end">
              <form class="d-inline" method="post" action="./actions/unreport.php" onsubmit="return confirm('¿Desmarcar este depósito del reporte?')">
                <?php csrf_field(); ?>
                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                <input type="hidden" name="back" value="<?= h($_SERVER['REQUEST_URI']) ?>">
                <button class="btn btn-outline-danger btn-sm">Desmarcar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; if(!$data): ?>
          <tr><td colspan="10" class="text-center text-muted">Sin depósitos</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/_footer.php'; ?>
