<?php
require_once 'includes/header.php';
require_once 'includes/funciones.php';

// Simulación del caso de negocio:
// Cliente socio compra 2 boletos para "Cómo entrenar tu dragón" en 3D en Tacna a las 20:40

// 1. Buscar la película
$sql_pelicula = "SELECT ID_pelicula FROM Pelicula WHERE titulo LIKE '%Cómo entrenar tu dragón%' LIMIT 1";
$resultado = $conexion->query($sql_pelicula);
$pelicula = $resultado->fetch_assoc();
$id_pelicula = $pelicula['ID_pelicula'];

// 2. Buscar la sede de Tacna
$sql_sede = "SELECT ID_sede FROM Sede WHERE ciudad = 'Tacna' LIMIT 1";
$resultado = $conexion->query($sql_sede);
$sede = $resultado->fetch_assoc();
$id_sede = $sede['ID_sede'];

// 3. Buscar la función específica
$sql_funcion = "SELECT ID_funcion FROM Funcion 
               WHERE ID_pelicula = ? 
               AND ID_sala IN (SELECT ID_sala FROM Sala WHERE tipo_sala = '3D' AND ID_sede = ?)
               AND hora = '20:40:00' LIMIT 1";
$stmt = $conexion->prepare($sql_funcion);
$stmt->bind_param("ii", $id_pelicula, $id_sede);
$stmt->execute();
$funcion = $stmt->get_result()->fetch_assoc();
$id_funcion = $funcion['ID_funcion'];

// 4. Obtener 2 asientos disponibles
$asientos_disponibles = obtenerAsientosDisponibles($id_funcion);
$asientos_seleccionados = array_slice(array_column($asientos_disponibles, 'ID_asiento'), 0, 2);

// 5. Registrar cliente (si no existe)
$dni_cliente = '12345678'; // DNI de ejemplo
$es_socio = true;

// 6. Realizar la compra
if (count($asientos_seleccionados) == 2) {
    $id_compra = realizarCompra($dni_cliente, $id_funcion, $asientos_seleccionados, $es_socio);
    
    if ($id_compra) {
        echo "<div class='success'>Transacción completada exitosamente. ID de compra: $id_compra</div>";
        
        // Mostrar detalles de la compra
        $sql_detalle = "SELECT c.*, b.numero_serie, a.fila, a.numero as numero_asiento
                       FROM Compra c
                       JOIN Boleto b ON c.ID_compra = b.ID_compra
                       JOIN Asiento a ON b.ID_asiento = a.ID_asiento
                       WHERE c.ID_compra = ?";
        $stmt = $conexion->prepare($sql_detalle);
        $stmt->bind_param("i", $id_compra);
        $stmt->execute();
        $detalles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        echo "<h3>Detalles de la compra:</h3>";
        echo "<p>Subtotal: S/ " . number_format($detalles[0]['subtotal'], 2) . "</p>";
        echo "<p>Descuento: S/ " . number_format($detalles[0]['descuento'], 2) . "</p>";
        echo "<p>Total: S/ " . number_format($detalles[0]['total'], 2) . "</p>";
        
        echo "<h4>Boletos:</h4>";
        foreach ($detalles as $boleto) {
            echo "<p>Asiento: " . $boleto['fila'] . $boleto['numero_asiento'] . " - N° Serie: " . $boleto['numero_serie'] . "</p>";
        }
    } else {
        echo "<div class='error'>Error al procesar la transacción</div>";
    }
} else {
    echo "<div class='error'>No hay suficientes asientos disponibles</div>";
}

require_once 'includes/footer.php';
?>