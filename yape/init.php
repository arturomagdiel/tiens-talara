<?php
require_once __DIR__ . '/_app.php'; require_login();
$title="Init"; include __DIR__.'/_header.php';

$sql1 = "CREATE TABLE IF NOT EXISTS yapeo_reports (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  from_date DATE NULL,
  to_date DATE NULL,
  filter_origin VARCHAR(32) NULL,
  note TEXT NULL,
  total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  item_count INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$sql2 = "CREATE TABLE IF NOT EXISTS yapeos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  deposit_date DATE NOT NULL,
  deposit_time TIME NOT NULL,
  origin ENUM('yape','yape desde plin','yape desde bn','yape desde bim','yape desde scotia') NOT NULL,
  operation_no VARCHAR(40) NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  chat VARCHAR(160) NOT NULL,
  note TEXT NULL,
  image_path VARCHAR(255) NULL,
  reported TINYINT(1) NOT NULL DEFAULT 0,
  report_id INT NULL,
  reported_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_operation (operation_no),
  CONSTRAINT fk_report FOREIGN KEY (report_id) REFERENCES yapeo_reports(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$ok = $conn->query($sql1) && $conn->query($sql2);
?>
<div class="alert <?= $ok?'alert-success':'alert-danger' ?>">
  <?= $ok ? '✔️ Tablas creadas/actualizadas.' : 'Error: '.h($conn->error) ?>
</div>
<a class="btn btn-dark" href="./index.php">Volver</a>
<?php include __DIR__ . '/_footer.php'; ?>
