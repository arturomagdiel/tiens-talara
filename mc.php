<?php
/*************************************************
 * Mini DB Client + Editor SQL con Autocomplete
 * Requisitos:
 * - PHP >= 7.4
 * - /shared/conexion.php define $conn (mysqli) y set_charset
 *
 * Seguridad:
 * - Cambia AUTH_USER / AUTH_PASS
 * - ALLOW_WRITE=false por defecto (solo SELECT/SHOW/...)
 **************************************************/

/*================== AUTH ==================*/
const AUTH_USER = 'admin';
const AUTH_PASS = 'Isa220821';

if (
    !isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] !== AUTH_USER ||
    $_SERVER['PHP_AUTH_PW']   !== AUTH_PASS
) {
    header('WWW-Authenticate: Basic realm="Mini DB"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Auth required';
    exit;
}

/*================== SEGURIDAD CONSULTAS ==================*/
const ALLOW_WRITE     = false;  // true para permitir INSERT/UPDATE/DELETE/DDL
const HARD_LIMIT      = 1000;   // límite máx. filas
const DEFAULT_LIMIT   = 200;    // si el SELECT no trae LIMIT
const TIMEOUT_SECONDS = 20;     // ms para MAX_EXECUTION_TIME

/*================== CARGAR CONEXIÓN ==================*/
$conexionFile = __DIR__ . '/shared/conexion.php';
if (!is_file($conexionFile)) {
    http_response_code(500);
    exit("No se encontró /shared/conexion.php");
}
require_once $conexionFile;

if (!isset($conn) || !($conn instanceof mysqli)) {
    http_response_code(500);
    exit("La conexión \$conn no está disponible.");
}
mysqli_report(MYSQLI_REPORT_OFF);
$conn->query("SET SESSION MAX_EXECUTION_TIME=".(TIMEOUT_SECONDS*1000));
$conn->set_charset('utf8mb4');

/*================== HELPERS ==================*/
session_start();
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
function h($s){ return htmlspecialchars($s, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }

function is_readonly_sql($sql){
    $s = ltrim($sql);
    // quitar comentarios iniciales /* ... */ y -- ...
    $s = preg_replace('~^(?:/\*.*?\*/\s*|--[^\n]*\n\s*)+~s', '', $s);
    $kw = strtoupper(strtok($s, " \t\n\r("));
    return in_array($kw, ['SELECT','SHOW','DESCRIBE','EXPLAIN'], true);
}
function ensure_limit($sql){
    if (!preg_match('~\blimit\s+\d+(\s*,\s*\d+)?\b~i', $sql)) {
        $sql .= ' LIMIT ' . DEFAULT_LIMIT;
    }
    // recortar a HARD_LIMIT
    return preg_replace_callback('~\blimit\s+(\d+)(?:\s*,\s*(\d+))?\b~i', function($m){
        $a = (int)$m[1];
        $b = isset($m[2]) ? (int)$m[2] : null;
        if ($b !== null) return 'LIMIT '.min($a, HARD_LIMIT).', '.min($b, HARD_LIMIT);
        return 'LIMIT '.min($a, HARD_LIMIT);
    }, $sql, 1);
}
function single_statement($sql){
    // chequeo simple: máx. 1 ';'
    return substr_count($sql, ';') <= 1;
}
function require_csrf(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'], (string)$_POST['csrf'])) {
            http_response_code(400);
            exit('CSRF token inválido');
        }
    }
}

/*================== ENDPOINTS JSON ==================*/
$action = $_GET['a'] ?? 'home';
if ($action === 'schema') {
    // Devuelve JSON con tablas y columnas para el autocompletado
    header('Content-Type: application/json; charset=utf-8');
    $schema = ['tables'=>[]];
    if ($res = $conn->query("SHOW FULL TABLES")) {
        while ($r = $res->fetch_array(MYSQLI_NUM)) {
            $table = $r[0];
            $schema['tables'][$table] = [];
            if ($cRes = $conn->query("SHOW FULL COLUMNS FROM `".$conn->real_escape_string($table)."`")) {
                while ($c = $cRes->fetch_assoc()) {
                    $schema['tables'][$table][] = $c['Field'];
                }
                $cRes->free();
            }
        }
        $res->free();
    }
    echo json_encode($schema, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($action === 'export' && isset($_SESSION['last_csv'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=result_'.date('Ymd_His').'.csv');
    echo $_SESSION['last_csv'];
    exit;
}

/*================== LÓGICA UI ==================*/
$exec_msg   = '';
$result_html= '';
$prefill    = "SHOW TABLES;";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sql'])) {
    require_csrf();
    $sql = trim($_POST['sql']);

    if ($sql === '') {
        $exec_msg = '<div class="err">La consulta está vacía.</div>';
    } elseif (!single_statement($sql)) {
        $exec_msg = '<div class="err">Solo 1 sentencia por ejecución.</div>';
    } else {
        // Control de permisos
        if (is_readonly_sql($sql)) {
            $sql = ensure_limit($sql);
        } elseif (!ALLOW_WRITE) {
            $exec_msg = '<div class="warn">Modo solo-lectura: solo SELECT/SHOW/DESCRIBE/EXPLAIN.</div>';
            $sql = null;
        }

        if ($sql) {
            $t0 = microtime(true);
            $res = $conn->query($sql);
            $elapsed = round((microtime(true)-$t0)*1000);

            if ($res === false) {
                $exec_msg = '<div class="err">Error: '.h($conn->error).'</div>';
            } else {
                if ($res instanceof mysqli_result) {
                    $fields = $res->fetch_fields();
                    $head   = array_map(fn($f)=>$f->name, $fields);
                    $html   = '<div class="muted">Consulta: <code>'.h($sql).'</code></div>';
                    $html  .= '<div class="muted">Tiempo: '.$elapsed.' ms</div>';
                    $html  .= '<div class="toolbar"><a class="btn" href="?a=export">Descargar CSV</a></div>';
                    $html  .= '<div style="overflow:auto"><table><thead><tr>';
                    foreach ($head as $n) $html.='<th>'.h($n).'</th>';
                    $html  .= '</tr></thead><tbody>';

                    $csv = fopen('php://temp','r+');
                    fputcsv($csv, $head);

                    while ($row = $res->fetch_assoc()) {
                        $html .= '<tr>';
                        $csvRow = [];
                        foreach ($head as $n) {
                            $v = $row[$n];
                            $disp = is_null($v) ? 'NULL' : (is_scalar($v) ? (string)$v : json_encode($v, JSON_UNESCAPED_UNICODE));
                            $html .= '<td>'.h($disp).'</td>';
                            $csvRow[] = is_scalar($v) || is_null($v) ? $v : json_encode($v, JSON_UNESCAPED_UNICODE);
                        }
                        $html .= '</tr>';
                        fputcsv($csv, $csvRow);
                    }
                    $html .= '</tbody></table></div>';
                    $result_html = $html;

                    rewind($csv);
                    $_SESSION['last_csv'] = stream_get_contents($csv);
                    fclose($csv);
                    $res->free();

                    $exec_msg = '<div class="ok">OK</div>';
                } else {
                    $exec_msg = '<div class="ok">OK. Filas afectadas: '.(int)$conn->affected_rows.' ('.$elapsed.' ms)</div>';
                }
            }
        }
    }
}

/*================== CONSULTAS PARA SIDEBAR ==================*/
$tables_html = '';
if ($res = $conn->query("SHOW TABLE STATUS")) {
    $tables_html .= '<table><thead><tr><th>Tabla</th><th>Filas</th><th>Engine</th></tr></thead><tbody>';
    while ($row = $res->fetch_assoc()) {
        $name = $row['Name'];
        $tables_html .= '<tr><td><a href="?a=table&t='.urlencode($name).'">'.h($name).'</a></td>'.
                        '<td>'.h((string)($row['Rows']??'')).'</td>'.
                        '<td>'.h((string)($row['Engine']??'')).'</td></tr>';
    }
    $tables_html .= '</tbody></table>';
    $res->free();
} else {
    $tables_html = '<div class="err">No se pudieron listar tablas: '.h($conn->error).'</div>';
}

$tableView = '';
if (($action === 'table') && isset($_GET['t'])) {
    $t = $_GET['t'];
    $esct = $conn->real_escape_string($t);
    $tableView .= '<h2>Estructura: <code>'.h($t).'</code></h2>';

    // columnas
    if ($res = $conn->query("SHOW FULL COLUMNS FROM `{$esct}`")) {
        $tableView .= '<h3>Columnas</h3><table><thead><tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th><th>Collation</th><th>Comentario</th></tr></thead><tbody>';
        while ($c = $res->fetch_assoc()){
            $tableView .= '<tr>'.
                          '<td>'.h($c['Field']).'</td>'.
                          '<td>'.h($c['Type']).'</td>'.
                          '<td>'.h($c['Null']).'</td>'.
                          '<td>'.h($c['Key']).'</td>'.
                          '<td>'.h($c['Default']).'</td>'.
                          '<td>'.h($c['Extra']).'</td>'.
                          '<td>'.h($c['Collation']).'</td>'.
                          '<td>'.h($c['Comment']).'</td>'.
                          '</tr>';
        }
        $tableView .= '</tbody></table>';
        $res->free();
    } else {
        $tableView .= '<div class="err">Error columnas: '.h($conn->error).'</div>';
    }

    // índices
    if ($res = $conn->query("SHOW INDEX FROM `{$esct}`")) {
        $tableView .= '<h3>Índices</h3><table><thead><tr><th>Key_name</th><th>Column_name</th><th>Unique</th><th>Seq</th><th>Cardinality</th><th>Type</th></tr></thead><tbody>';
        while ($i = $res->fetch_assoc()){
            $tableView .= '<tr>'.
                          '<td>'.h($i['Key_name']).'</td>'.
                          '<td>'.h($i['Column_name']).'</td>'.
                          '<td>'.($i['Non_unique'] ? 'No':'Sí').'</td>'.
                          '<td>'.h($i['Seq_in_index']).'</td>'.
                          '<td>'.h($i['Cardinality']).'</td>'.
                          '<td>'.h($i['Index_type']).'</td>'.
                          '</tr>';
        }
        $tableView .= '</tbody></table>';
        $res->free();
    }

    $prefill = "SELECT * FROM `{$t}` LIMIT ".DEFAULT_LIMIT.";";
}

/*================== HTML ==================*/
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Mini DB Client</title>

<!-- CodeMirror (SQL + Hints) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/material-darker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/hint/show-hint.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/sql/sql.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/hint/show-hint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/hint/sql-hint.min.js"></script>

<style>
:root{--bg:#0f172a;--panel:#111827;--fg:#e5e7eb;--muted:#9ca3af;--accent:#22c55e;}
html,body{margin:0;background:#0b1220;color:var(--fg);font:14px/1.45 system-ui,Segoe UI,Roboto}
.wrap{max-width:1200px;margin:24px auto;padding:0 16px}
.grid{display:grid;grid-template-columns:260px 1fr;gap:16px}
aside,main{background:var(--panel);border-radius:12px;padding:16px}
h1{margin:0 0 12px 0}
a{color:#93c5fd;text-decoration:none}
a:hover{text-decoration:underline}
.badge{display:inline-block;background:#1f2937;color:#cbd5e1;border:1px solid #374151;border-radius:999px;padding:2px 8px;font-size:12px}
table{width:100%;border-collapse:collapse;margin:12px 0;border:1px solid #1f2937}
th,td{border-bottom:1px solid #1f2937;padding:8px 10px;text-align:left;vertical-align:top}
th{background:#0b1220;color:#cbd5e1;position:sticky;top:0}
.toolbar{display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin:8px 0 12px}
.btn{background:#1f2937;border:1px solid #374151;color:#d1d5db;padding:8px 12px;border-radius:8px;cursor:pointer}
.btn:hover{border-color:#4b5563}
textarea,input,select{width:100%;background:#0b1220;border:1px solid #334155;color:#e5e7eb;border-radius:8px;padding:8px}
.muted{color:var(--muted)}
.ok{color:var(--accent)}
.warn{color:#f59e0b}
.err{color:#f87171}
.CodeMirror{height:200px;border:1px solid #334155;border-radius:8px;background:#0b1220}
</style>
</head>
<body>
<div class="wrap">
  <h1>Mini DB Client <span class="badge"><?php echo h($conn->host_info ?? ''); ?></span></h1>
  <div class="grid">
    <aside>
      <h3>Tablas</h3>
      <div style="max-height:65vh;overflow:auto"><?php echo $tables_html; ?></div>
      <hr style="border-color:#1f2937">
      <div class="muted" style="font-size:12px">
        Escritura: <?php echo ALLOW_WRITE?'<span class="ok">permitida</span>':'<span class="warn">bloqueada</span>'; ?>
      </div>
    </aside>

    <main>
      <?php if (!empty($tableView)) echo $tableView; ?>

      <h2>Editor SQL (Ctrl+Espacio = Autocompletar)</h2>
      <?php echo $exec_msg; ?>
      <form method="post" action="">
        <input type="hidden" name="csrf" value="<?php echo h($_SESSION['csrf']); ?>">
        <textarea id="sql" name="sql" rows="8" spellcheck="false"><?php
          echo isset($_POST['sql']) ? h($_POST['sql']) : h($prefill);
        ?></textarea>
        <div class="toolbar">
          <button class="btn" type="submit">Ejecutar</button>
          <a class="btn" href="?a=export">Descargar CSV</a>
          <button class="btn" type="button" id="fmt">Formatear</button>
          <span class="muted">Límite máx. SELECT: <?php echo HARD_LIMIT; ?> filas</span>
        </div>
      </form>

      <?php echo $result_html; ?>
    </main>
  </div>
</div>

<!-- Autocomplete: cargar esquema y configurar CodeMirror -->
<script>
(async function(){
  // Inicializar editor
  var textarea = document.getElementById('sql');
  var editor = CodeMirror.fromTextArea(textarea, {
    mode: 'text/x-mysql',
    theme: 'material-darker',
    lineNumbers: true,
    indentUnit: 2,
    smartIndent: true,
    lineWrapping: true,
    extraKeys: {
      'Ctrl-Space': 'autocomplete',
      'Ctrl-Enter': function(cm){ cm.getTextArea().form.submit(); },
      'Tab': function(cm){
        if (cm.somethingSelected()) cm.indentSelection("add");
        else cm.replaceSelection("  ", "end");
      }
    },
    hintOptions: {
      // tables se inyecta más abajo con el esquema real
      tables: {}
    }
  });

  // Cargar esquema para autocompletar tablas/columnas
  try {
    const resp = await fetch('?a=schema', {cache:'no-store'});
    if (resp.ok) {
      const data = await resp.json();
      // CodeMirror sql-hint espera { tableName: [col1, col2, ...], ... }
      editor.setOption('hintOptions', { tables: data.tables || {} });
    }
  } catch(e){ console.warn('No se pudo cargar esquema para autocomplete', e); }

  // Autocomplete mientras escribes tras un punto o identificador
  editor.on('inputRead', function(cm, change) {
    if (change.text[0] && /[\w\.\`]/.test(change.text[0])) {
      CodeMirror.commands.autocomplete(cm, null, {completeSingle:false});
    }
  });

  // Botón simple "formatear" (sangría básica)
  document.getElementById('fmt').addEventListener('click', function(){
    var total = editor.lineCount();
    editor.operation(function(){
      for (var i=0;i<total;i++){ editor.indentLine(i,'smart'); }
    });
  });
})();
</script>
</body>
</html>
