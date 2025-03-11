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

// Obtener los datos del formulario
$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$codigo = $_POST["codigo"];
$telefono = $_POST["telefono"];
$ruc = $_POST["ruc"];
$patrocinador = $_POST["patrocinador"];
$descuento = $_POST["descuento"];

// Insertar nueva persona
$sql = "INSERT INTO personas (nombre, apellido, codigo, telefono, ruc, patrocinador, descuento) 
        VALUES ('$nombre', '$apellido', '$codigo', '$telefono', '$ruc', '$patrocinador', '$descuento')";

// Imprimir la consulta SQL (para depurar)
echo $sql;

if ($conn->query($sql) === TRUE) {
    echo "Datos guardados correctamente";
} else {
    echo "Error al guardar datos: " . $conn->error;
}

$conn->close();

?>