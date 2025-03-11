<?php

// Conexión a la base de datos
$servername = "localhost";
$username = "tienslima_shopu";
$password = "Mar11ine!shop";
$dbname = "tienslima_shop";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}

// Ruta del archivo CSV (corregida)
$csvFile = 'importar.csv'; 

// Verificar si el archivo existe
if (file_exists($csvFile)) {
  // Abrir el archivo CSV
  $file = fopen($csvFile, 'r');

  // Leer la primera línea (encabezados)
  fgetcsv($file);

  // Recorrer el archivo CSV línea por línea
  while (($row = fgetcsv($file)) !== false) {
    // Obtener los datos de cada columna
    $codigo = $row[0];
    $nombre = $row[1];
    $precio_publico = $row[2];
    $pv_publico = $row[3];
    $precio_afiliado = $row[4];
    $pv_afiliado = $row[5];
    $precio_junior = $row[6];
    $pv_junior = $row[7];
    $precio_senior = $row[8];
    $pv_senior = $row[9];
    $precio_master = $row[10];
    $pv_master = $row[11];

    // Insertar los datos en la base de datos, incluyendo la columna "imagen" con un valor vacío
    $sql = "INSERT INTO productos (codigo, nombre, precio_publico, pv_publico, precio_afiliado, pv_afiliado, precio_junior, pv_junior, precio_senior, pv_senior, precio_master, pv_master, imagen) 
            VALUES ('$codigo', '$nombre', '$precio_publico', '$pv_publico', '$precio_afiliado', '$pv_afiliado', '$precio_junior', '$pv_junior', '$precio_senior', '$pv_senior', '$precio_master', '$pv_master', '')";

    if ($conn->query($sql) === TRUE) {
      echo "Producto $nombre importado correctamente.<br>";
    } else {
      echo "Error al importar el producto $nombre: " . $conn->error . "<br>";
    }
  }

  // Cerrar el archivo CSV
  fclose($file);

  echo "Importación completada.";
} else {
  echo "El archivo CSV no existe.";
}

$conn->close();

?>