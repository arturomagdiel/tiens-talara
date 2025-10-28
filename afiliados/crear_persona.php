<?php
// Proteger endpoint con autenticación
require_once '../shared/auth.php';
requireAuth();

// Conexión a la base de datos
include '../shared/conexion.php';

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