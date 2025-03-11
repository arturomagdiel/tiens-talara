<html>
<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>


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

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $codigo = $_POST["codigo"];
  $nombre = $_POST["nombre"];
  $precio_publico = $_POST["precio_publico"];
  $pv_publico = $_POST["pv_publico"];
  $precio_afiliado = $_POST["precio_afiliado"];
  $pv_afiliado = $_POST["pv_afiliado"];
  $precio_junior = $_POST["precio_junior"];
  $pv_junior = $_POST["pv_junior"];
  $precio_senior = $_POST["precio_senior"];
  $pv_senior = $_POST["pv_senior"];
  $precio_master = $_POST["precio_master"];
  $pv_master = $_POST["pv_master"];

  // Subir la imagen
  $target_dir = "uploads/"; // Carpeta donde se guardarán las imágenes
  $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // Verificar si la imagen es real o falsa
  if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["imagen"]["tmp_name"]);
    if($check !== false) {
      echo "El archivo es una imagen - " . $check["mime"] . ".";
      $uploadOk = 1;
    } else {
      echo "El archivo no es una imagen.";
      $uploadOk = 0;
    }
  }

  // Verificar si el archivo ya existe
  if (file_exists($target_file)) {
    echo "<script>
            alert('Lo siento, el archivo ya existe. Por favor, selecciona otra imagen.');
            window.history.back(); // Volver al formulario
          </script>";
    $uploadOk = 0; 
  }

  // Verificar el tamaño del archivo
  if ($_FILES["imagen"]["size"] > 500000) {
    echo "Lo siento, el archivo es demasiado grande.";
    $uploadOk = 0;
  }

  // Permitir ciertos formatos de archivo
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
    echo "Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.";
    $uploadOk = 0;
  }

  // Verificar si $uploadOk está establecido en 0 por un error
  if ($uploadOk == 0) {
    echo "Lo siento, tu archivo no fue subido.";
  // Si todo está bien, intenta subir el archivo
  } else {
    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
      echo "El archivo ". htmlspecialchars( basename( $_FILES["imagen"]["name"])). " ha sido subido.";

      // Insertar datos en la base de datos
      $sql = "INSERT INTO productos (codigo, nombre, imagen, precio_publico, pv_publico, precio_afiliado, pv_afiliado, precio_junior, pv_junior, precio_senior, pv_senior, precio_master, pv_master) 
              VALUES ('$codigo', '$nombre', '$target_file', '$precio_publico', '$pv_publico', '$precio_afiliado', '$pv_afiliado', '$precio_junior', '$pv_junior', '$precio_senior', '$pv_senior', '$precio_master', '$pv_master')";

      if ($conn->query($sql) === TRUE) {
        echo "<script>
                $(document).ready(function(){
                  $('#modalExito').modal('show');
                });
              </script>";
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }

    } else {
      echo "Lo siento, hubo un error al subir tu archivo.";
    }
  }
}

$conn->close();
?>


<div class="modal fade" id="modalExito" tabindex="-1" aria-labelledby="modalExitoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalExitoLabel">Éxito</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Nuevo producto creado con éxito.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <a href="formulario_productos.php" class="btn btn-primary">Agregar otro producto</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>