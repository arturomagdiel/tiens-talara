<?php
require_once __DIR__ . '/_app.php';

if ($_SERVER['REQUEST_METHOD']==='POST') {
  if (($_POST['pass'] ?? '') === APP_PASSWORD) {
    $_SESSION['ok'] = true;
    header("Location: ./index.php"); exit;
  } else {
    $err = "Clave incorrecta";
  }
}
$title = "Acceso";
include __DIR__ . '/_header.php';
?>
<div class="row justify-content-center">
  <div class="col-12 col-md-6 col-lg-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="mb-3">Acceso</h5>
        <?php if (!empty($err)): ?><div class="alert alert-danger"><?=h($err)?></div><?php endif; ?>
        <form method="post">
          <?php csrf_field(); ?>
          <input class="form-control mb-2" type="password" name="pass" placeholder="Clave" autofocus>
          <button class="btn btn-dark w-100">Entrar</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/_footer.php'; ?>
