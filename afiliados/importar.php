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

// Verificar si se ha subido un archivo
if (isset($_FILES['csv']['tmp_name']) && !empty($_FILES['csv']['tmp_name'])) {
    $csvFile = $_FILES['csv']['tmp_name'];

    // Abrir el archivo CSV
    if (($handle = fopen($csvFile, "r")) !== FALSE) {
        // Leer la primera línea (encabezados) para ignorarla
        fgetcsv($handle);

        // Recorrer el archivo CSV línea por línea
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Obtener los valores de nombre y código
            $nombre = $data[0]; // Asumiendo que el nombre está en la primera columna
            $codigo = $data[1]; // Asumiendo que el código está en la segunda columna

            // Asignar valores por defecto a las demás columnas
            $apellido = ""; 
            $telefono = ""; 
            $ruc = ""; 
            $patrocinador = ""; 
            $descuento = 0; 

            // Insertar los datos en la tabla personas
            $sql = "INSERT INTO personas (nombre, codigo, apellido, telefono, ruc, patrocinador, descuento) VALUES ('$nombre', '$codigo', '$apellido', '$telefono', '$ruc', '$patrocinador', '$descuento')";

            if ($conn->query($sql) === TRUE) {
                echo "Registro insertado correctamente: $nombre - $codigo<br>";
            } else {
                echo "Error al insertar registro: " . $conn->error . "<br>";
            }
        }

        // Cerrar el archivo CSV
        fclose($handle);
    } else {
        echo "Error al abrir el archivo CSV.";
    }
} else {
    echo "Por favor, selecciona un archivo CSV para importar.";
}

$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
  <title>Importar CSV</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container">

  <h1>Importar datos desde CSV</h1>

  <form method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label for="csv">Selecciona un archivo CSV:</label>
      <input type="file" class="form-control-file" id="csv" name="csv">
    </div>
    <button type="submit" class="btn btn-primary">Importar</button>
  </form>

</div>

</body>
</html>