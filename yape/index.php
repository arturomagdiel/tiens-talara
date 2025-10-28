<?php
require_once __DIR__ . '/_app.php'; require_login();
$title="Registro y Reportes";
include __DIR__ . '/_header.php';

// Filtros
$ORIGENES = $ORIGENES; // usar global
$flt_from = $_GET['f_from'] ?? '';
$flt_to   = $_GET['f_to'] ?? '';
$flt_origin = $_GET['f_origin'] ?? '';
$flt_rep  = $_GET['f_rep'] ?? '';

$where=[]; $types=''; $params=[];
if ($flt_from){ $where[]='deposit_date>=?'; $types.='s'; $params[]=$flt_from; }
if ($flt_to){   $where[]='deposit_date<=?'; $types.='s'; $params[]=$flt_to; }
if ($flt_origin!=='' && isset($ORIGENES[strtolower($flt_origin)])){ $where[]='origin=?'; $types.='s'; $params[]=strtolower($flt_origin); }
if ($flt_rep==='0'){ $where[]='reported=0'; }
elseif ($flt_rep==='1'){ $where[]='reported=1'; }
$wsql = $where ? "WHERE ".implode(' AND ',$where) : '';

$sql = "SELECT * FROM yapeos $wsql ORDER BY deposit_date DESC, deposit_time DESC, id DESC LIMIT 1000";
$stmt = $conn->prepare($sql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute(); $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Totales
$tot_all = (float)($conn->query("SELECT IFNULL(SUM(amount),0) t FROM yapeos")->fetch_assoc()['t'] ?? 0);
$tot_unrep = (float)($conn->query("SELECT IFNULL(SUM(amount),0) t FROM yapeos WHERE reported=0")->fetch_assoc()['t'] ?? 0);
$tot_list = array_sum(array_map(fn($r)=>(float)$r['amount'],$rows));
?>

<div class="row g-3">
  <!-- Alta -->
  <div class="col-12 col-lg-6">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h5 class="mb-3">Nuevo yapeo</h5>
        <?php flash_out(); ?>
        <form method="post" enctype="multipart/form-data" action="./actions/save.php" id="form-alta">
          <?php csrf_field(); ?>
          <div class="row g-2">
            <div class="col-6"><label class="form-label">Fecha</label><input class="form-control" type="date" name="deposit_date" required value="<?=h(date('Y-m-d'))?>"></div>
            <div class="col-6"><label class="form-label">Hora</label><input class="form-control" type="time" name="deposit_time" required value="<?=h(date('H:i'))?>"></div>
            <div class="col-12"><label class="form-label">Origen</label>
              <select class="form-select" name="origin" required>
                <?php foreach($ORIGENES as $k=>$v): ?><option value="<?=h($k)?>"><?=$v?></option><?php endforeach; ?>
              </select>
            </div>

<div class="col-6">
  <label class="form-label">N° Operación</label>
  <input class="form-control" type="text" name="operation_no" id="operation_no" maxlength="40" required>
  <div id="op-feedback" class="form-text"></div>
  <div class="invalid-feedback">Este número ya existe. Revisa el detalle debajo.</div>
</div>


            <div class="col-6"><label class="form-label">Monto (S/)</label><input class="form-control" type="number" name="amount" step="0.01" min="0.01" required></div>
            <div class="col-12"><label class="form-label">Chat</label><input class="form-control" type="text" name="chat" maxlength="160" required></div>
            <div class="col-12"><label class="form-label">Nota</label><input class="form-control" type="text" name="note" maxlength="255" placeholder="Opcional"></div>
            <div class="col-12"><label class="form-label">Voucher (archivo)</label><input class="form-control" type="file" name="voucher_file" accept="image/*"></div>
            <div class="col-12">
              <label class="form-label">O pega la imagen aquí</label>
              <div id="pastezone" class="pastezone" contenteditable="true" placeholder="Toca/click y pega (Ctrl/Cmd+V)"></div>
              <input type="hidden" name="image_base64" id="image_base64">
              <div id="preview" class="mt-2"></div>
            </div>

<div class="col-12">
  <button id="btn-save" class="btn btn-dark w-100">Guardar</button>
</div>            

          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Reporte -->
  <div class="col-12 col-lg-6">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h5 class="mb-3">Crear reporte para Piura</h5>
        <form method="post" action="./actions/make_report.php">
          <?php csrf_field(); ?>
          <div class="row g-2">
            <div class="col-6"><label class="form-label">Desde</label><input class="form-control" type="date" name="from"></div>
            <div class="col-6"><label class="form-label">Hasta</label><input class="form-control" type="date" name="to"></div>
            <div class="col-12"><label class="form-label">Origen</label>
              <select class="form-select" name="origin_filter">
                <option value="">Todos</option>
                <?php foreach($ORIGENES as $k=>$v): ?><option value="<?=h($k)?>"><?=$v?></option><?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="only_unreported" id="onlyUnrep" checked>
                <label class="form-check-label" for="onlyUnrep">Solo no reportados</label>
              </div>
            </div>
            <div class="col-12">
              <label class="form-label">Nota del reporte</label>
              <textarea class="form-control" name="report_note" rows="2" placeholder="Ej: Enviado a Piura el 10/08, envío parcial..."></textarea>
            </div>
            <div class="col-12"><button class="btn btn-dark w-100">Generar reporte</button></div>
          </div>
        </form>

        <hr>
        <form class="row g-2 align-items-end" method="get" action="./report.php">
          <div class="col-auto"><label class="form-label">Ver reporte #</label></div>
          <div class="col"><input class="form-control" type="number" name="id" min="1" required></div>
          <div class="col-auto"><button class="btn btn-outline-dark">Abrir</button></div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Filtros + Totales + Lista -->
<div class="card shadow-sm mt-3">
  <div class="card-body">
    <form class="row g-2 align-items-end">
      <input type="hidden" name="action" value="home">
      <div class="col-6 col-md-3"><label class="form-label">Desde</label><input class="form-control" type="date" name="f_from" value="<?=h($flt_from)?>"></div>
      <div class="col-6 col-md-3"><label class="form-label">Hasta</label><input class="form-control" type="date" name="f_to" value="<?=h($flt_to)?>"></div>
      <div class="col-6 col-md-3"><label class="form-label">Origen</label>
        <select class="form-select" name="f_origin">
          <option value="">Todos</option>
          <?php foreach($ORIGENES as $k=>$v): ?><option value="<?=h($k)?>" <?=($flt_origin===$k?'selected':'')?>><?=$v?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-6 col-md-2"><label class="form-label">Estado</label>
        <select class="form-select" name="f_rep">
          <option value="">Todos</option>
          <option value="0" <?=($flt_rep==='0'?'selected':'')?>>No reportados</option>
          <option value="1" <?=($flt_rep==='1'?'selected':'')?>>Reportados</option>
        </select>
      </div>
      <div class="col-12 col-md-1"><button class="btn btn-outline-dark w-100">Filtrar</button></div>
    </form>

    <div class="row g-2 mt-2">
      <div class="col-12 col-md-auto"><span class="badge text-bg-light p-2">Total base: <b>S/ <?=money($tot_all)?></b></span></div>
      <div class="col-12 col-md-auto"><span class="badge text-bg-light p-2">No reportado: <b>S/ <?=money($tot_unrep)?></b></span></div>
      <div class="col-12 col-md-auto"><span class="badge text-bg-light p-2">Total listado: <b>S/ <?=money($tot_list)?></b></span></div>
    </div>

    <div class="table-responsive mt-3">
      <table class="table table-sm align-middle">
        <thead><tr>
          <th>#</th><th>Fecha</th><th>Hora</th><th>Origen</th><th>Operación</th>
          <th class="text-end">Monto</th><th>Chat</th><th>Nota</th><th>Imagen</th><th>Estado</th><th></th>
        </tr></thead>
        <tbody>
        <?php if (!$rows): ?>
          <tr><td colspan="11" class="text-center text-muted">Sin resultados</td></tr>
        <?php else: foreach($rows as $r): ?>
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
            <td><?= $r['reported']?'Reportado':'No' ?></td>
            <td class="text-end">
              <?php if ((int)$r['reported']===1): ?>
                <form class="d-inline" method="post" action="./actions/unreport.php" onsubmit="return confirm('¿Desmarcar este depósito del reporte?')">
                  <?php csrf_field(); ?>
                  <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                  <input type="hidden" name="back" value="<?= h($_SERVER['REQUEST_URI']) ?>">
                  <button class="btn btn-outline-danger btn-sm">Desmarcar</button>
                </form>
              <?php else: ?>—<?php endif; ?>
            </td>
          </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/_footer.php'; ?>
