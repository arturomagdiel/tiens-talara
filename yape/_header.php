<?php require_once __DIR__ . '/_app.php'; ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h($title ?? 'Yapeos') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{background:#f5f7fb}
    .card{border-radius:14px}
    .table thead th{position:sticky;top:0;background:#f8f9fb;z-index:1}
    .pastezone{border:2px dashed #c6ccda;border-radius:12px;min-height:90px;background:#fcfdff;padding:10px}
    #preview img{max-width:220px;border:1px solid #e6e8ef;border-radius:10px;margin-top:8px}
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
  <div class="container-fluid">
    <span class="navbar-brand fw-semibold">Yapeos</span>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-secondary btn-sm" href="init.php" title="Crear tablas">Init</a>
      <a class="btn btn-dark btn-sm" href="logout.php">Salir</a>
    </div>
  </div>
</nav>
<main class="container my-3">
