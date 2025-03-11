<!DOCTYPE html>
<html>
<head>
  <title>Actualizar Producto</title>
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
    $id = $_POST["id"];
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

    // Verificar si se ha subido una nueva imagen
    if ($_FILES["imagen"]["name"] != "") {
        // Subir la imagen
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verificar si la imagen es real o falsa
        $check = getimagesize($_FILES["imagen"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "El archivo no es una imagen.";
            $uploadOk = 0;
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
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.";
            $uploadOk = 0;
        }

        // Verificar si $uploadOk está establecido en 0 por un error
        if ($uploadOk == 0) {
            echo "Lo siento, tu archivo no fue subido.";
        } else {
            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                // Actualizar la información del producto en la base de datos, incluyendo la nueva imagen
                $sql = "UPDATE productos SET 
                        codigo = '$codigo', 
                        nombre = '$nombre', 
                        imagen = '$target_file', 
                        precio_publico = '$precio_publico', 
                        pv_publico = '$pv_publico', 
                        precio_afiliado = '$precio_afiliado', 
                        pv_afiliado = '$pv_afiliado', 
                        precio_junior = '$precio_junior', 
                        pv_junior = '$pv_junior', 
                        precio_senior = '$precio_senior', 
                        pv_senior = '$pv_senior', 
                        precio_master = '$precio_master', 
                        pv_master = '$pv_master' 
                        WHERE id = $id";
            } else {
                echo "Lo siento, hubo un error al subir tu archivo.";
            }
        }
    } else {
        // Actualizar la información del producto en la base de datos sin cambiar la imagen
        $sql = "UPDATE productos SET 
                codigo = '$codigo', 
                nombre = '$nombre', 
                precio_publico = '$precio_publico', 
                pv_publico = '$pv_publico', 
                precio_afiliado = '$precio_afiliado', 
                pv_afiliado = '$pv_afiliado', 
                precio_junior = '$precio_junior', 
                pv_junior = '$pv_junior', 
                precio_senior = '$precio_senior', 
                pv_senior = '$pv_senior', 
                precio_master = '$precio_master', 
                pv_master = '$pv_master' 
                WHERE id = $id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                $(document).ready(function(){
                    $('#modalExito').modal('show');
                    setTimeout(function() {
                        window.location.href='lista_productos.php'; 
                    }, 1000); // 1000 milisegundos = 1 segundo
                });
              </script>";
    } else {
        echo "Error al actualizar el producto: " . $conn->error;
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
        Producto actualizado con éxito.
      </div>
    </div>
  </div>
</div>

</body>
</html>