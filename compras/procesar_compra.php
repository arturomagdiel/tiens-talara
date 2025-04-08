<?php
include '../shared/conexion.php';

header('Content-Type: application/json'); // Asegurarse de que la respuesta sea JSON

// Leer los datos enviados desde el frontend
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No se recibieron datos.']);
    exit;
}

$persona = $data['persona'] ?? null;
$productos = $data['productos'] ?? [];
$estado = $data['estado'] ?? 'pendiente';
$liquidacionNota = $data['liquidacion_nota'] ?? null;

if (!$persona || empty($productos) || !$estado) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}

if (!isset($persona['id']) || !isset($persona['codigo']) || !isset($persona['descuento'])) {
    echo json_encode(['success' => false, 'message' => 'Datos de la persona incompletos.']);
    exit;
}

// Procesar los datos y almacenarlos en la base de datos
foreach ($productos as $producto) {
    if (!isset($producto['id']) || !isset($producto['codigo']) || !isset($producto['precio']) || !isset($producto['pv'])) {
        echo json_encode(['success' => false, 'message' => 'Datos del producto incompletos.']);
        exit;
    }

    $query = "INSERT INTO compras (
                fecha_compra, 
                personas_id, 
                personas_codigo, 
                personas_descuento, 
                productos_id, 
                productos_codigo, 
                productos_precio, 
                productos_pv, 
                estado, 
                liquidacion_nota
            ) VALUES (
                NOW(), 
                '{$persona['id']}', 
                '{$persona['codigo']}', 
                '{$persona['descuento']}', 
                '{$producto['id']}', 
                '{$producto['codigo']}', 
                '{$producto['precio']}', 
                '{$producto['pv']}', 
                '$estado', 
                '$liquidacionNota'
            )";

    if (!$conn->query($query)) {
        echo json_encode(['success' => false, 'message' => 'Error al registrar la compra: ' . $conn->error]);
        exit;
    }
}

// Respuesta de éxito
echo json_encode(['success' => true, 'message' => 'Compra registrada correctamente.']);
exit;
?>

<script>
function procesarCompra(estado) {
    const productos = [];
    const filas = document.querySelectorAll('#productos-lista tr');

    // Recopilar los datos de los productos
    filas.forEach(fila => {
        const codigo = fila.querySelector('td:nth-child(2)').textContent;
        const nombre = fila.querySelector('td:nth-child(1)').textContent;
        const precio = parseFloat(fila.querySelector('.precio').textContent.replace('S/', ''));
        const cantidad = parseInt(fila.querySelector('.cantidad').value) || 0;

        // Agregar el producto tantas veces como la cantidad
        for (let i = 0; i < cantidad; i++) {
            productos.push({ codigo, nombre, precio });
        }
    });

    // Generar número de liquidación si el estado es "liquidado"
    const numeroLiquidacion = estado === 'liquidado'
        ? new Date().toISOString().replace(/[-:.TZ]/g, '') // Formato: YYYYMMDDHHMMSS
        : null;

    // Enviar los datos al backend
    fetch('compras/procesar_compra.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            persona: personaSeleccionada,
            productos,
            estado,
            numeroLiquidacion,
        }),
    })
        .then(response => {
            console.log('Estado de la respuesta:', response.status); // Verificar el estado HTTP
            return response.json();
        })
        .then(data => {
            console.log('Respuesta del servidor:', data); // Mostrar la respuesta del servidor
            if (data.success) {
                alert(data.message);
                location.reload(); // Recargar la página después de guardar
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}
</script>
