<?php

// Conexión a la base de datos
include '../shared/conexion.php';

// Obtener los datos del formulario
$id = $_POST["id"];
$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$codigo = $_POST["codigo"];
$telefono = $_POST["telefono"];
$ruc = $_POST["ruc"];
$patrocinador = $_POST["patrocinador"];
$descuento = $_POST["descuento"];

// Actualizar persona existente
$sql = "UPDATE personas SET 
        nombre = '$nombre', 
        apellido = '$apellido', 
        codigo = '$codigo', 
        telefono = '$telefono', 
        ruc = '$ruc', 
        patrocinador = '$patrocinador', 
        descuento = '$descuento' 
        WHERE id = $id";

// Imprimir la consulta SQL (para depurar)
echo $sql;

if ($conn->query($sql) === TRUE) {
    echo "Datos guardados correctamente";
} else {
    echo "Error al guardar datos: " . $conn->error;
}

$conn->close();

?>