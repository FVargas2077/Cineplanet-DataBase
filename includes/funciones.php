// Archivo: cineplanet/includes/funciones.php
<?php
include 'conexion.php';

// Función para verificar credenciales de administrador
function loginAdmin($usuario, $contrasena) {
    global $conexion;
    // Esto es solo un ejemplo básico - en producción usa password_hash()
    $sql = "SELECT * FROM Administrador WHERE usuario = ? AND contrasena = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $usuario, $contrasena);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->num_rows > 0;
}

// Función para obtener todas las películas (para administrador)
function obtenerTodasPeliculas() {
    global $conexion;
    $sql = "SELECT * FROM Pelicula ORDER BY estado, titulo";
    $resultado = $conexion->query($sql);
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

// Función para eliminar una película
function eliminarPelicula($id_pelicula) {
    global $conexion;
    $sql = "DELETE FROM Pelicula WHERE ID_pelicula = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_pelicula);
    return $stmt->execute();
}

function obtenerPeliculas() {
    global $conexion;
    $sql = "SELECT * FROM Pelicula WHERE estado = 'En_Cartelera'";
    $resultado = $conexion->query($sql);
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

function obtenerFuncionesPorPelicula($id_pelicula) {
    global $conexion;
    $sql = "SELECT f.*, s.numero_sala, sa.nombre as nombre_sede 
            FROM Funcion f 
            JOIN Sala s ON f.ID_sala = s.ID_sala 
            JOIN Sede sa ON s.ID_sede = sa.ID_sede 
            WHERE f.ID_pelicula = ? AND f.estado = 'Programada'";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_pelicula);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

function obtenerAsientosDisponibles($id_funcion) {
    global $conexion;
    $sql = "SELECT a.* 
            FROM Asiento a
            JOIN Sala s ON a.ID_sala = s.ID_sala
            JOIN Funcion f ON f.ID_sala = s.ID_sala
            WHERE f.ID_funcion = ? AND a.estado = 'Disponible'
            AND a.ID_asiento NOT IN (
                SELECT b.ID_asiento FROM Boleto b 
                WHERE b.ID_funcion = ? AND b.estado = 'Activo'
            )";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $id_funcion, $id_funcion);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

function registrarCliente($dni, $nombre, $apellidos, $email, $telefono, $fecha_nac) {
    global $conexion;
    $sql = "INSERT INTO Cliente (DNI, nombre, apellidos, email, telefono, fecha_nac) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssss", $dni, $nombre, $apellidos, $email, $telefono, $fecha_nac);
    return $stmt->execute();
}

function realizarCompra($dni_cliente, $id_funcion, $asientos, $es_socio = false) {
    global $conexion;
    
    // Iniciar transacción
    $conexion->begin_transaction();
    
    try {
        // 1. Obtener precio base de la función
        $sql_funcion = "SELECT precio_base FROM Funcion WHERE ID_funcion = ?";
        $stmt = $conexion->prepare($sql_funcion);
        $stmt->bind_param("i", $id_funcion);
        $stmt->execute();
        $funcion = $stmt->get_result()->fetch_assoc();
        $precio_base = $funcion['precio_base'];
        
        // 2. Calcular totales
        $subtotal = $precio_base * count($asientos);
        $descuento = $es_socio ? $subtotal * 0.1 : 0; // 10% descuento para socios
        $total = $subtotal - $descuento;
        
        // 3. Crear registro de compra
        $sql_compra = "INSERT INTO Compra (subtotal, descuento, total, DNI) 
                      VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql_compra);
        $stmt->bind_param("ddds", $subtotal, $descuento, $total, $dni_cliente);
        $stmt->execute();
        $id_compra = $conexion->insert_id;
        
        // 4. Crear boletos
        foreach ($asientos as $id_asiento) {
            $numero_serie = uniqid('BOL');
            $sql_boleto = "INSERT INTO Boleto (numero_serie, precio, ID_compra, ID_funcion, ID_asiento)
                           VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql_boleto);
            $stmt->bind_param("sdiii", $numero_serie, $precio_base, $id_compra, $id_funcion, $id_asiento);
            $stmt->execute();
            
            // Marcar asiento como ocupado
            $sql_asiento = "UPDATE Asiento SET estado = 'Ocupado' WHERE ID_asiento = ?";
            $stmt = $conexion->prepare($sql_asiento);
            $stmt->bind_param("i", $id_asiento);
            $stmt->execute();
        }
        
        // Si todo sale bien, confirmar transacción
        $conexion->commit();
        return $id_compra;
        
    } catch (Exception $e) {
        // Si hay error, revertir cambios
        $conexion->rollback();
        return false;
    }
}
?>
