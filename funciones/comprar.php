<?php
require_once '../../includes/header.php';
require_once '../../includes/funciones.php';

if (!isset($_GET['funcion'])) {
    header("Location: ../peliculas/listar.php");
    exit();
}

$id_funcion = $_GET['funcion'];
$asientos = obtenerAsientosDisponibles($id_funcion);

// Obtener datos de la función
$sql_funcion = "SELECT f.*, p.titulo, s.numero_sala, sa.nombre as nombre_sede 
               FROM Funcion f
               JOIN Pelicula p ON f.ID_pelicula = p.ID_pelicula
               JOIN Sala s ON f.ID_sala = s.ID_sala
               JOIN Sede sa ON s.ID_sede = sa.ID_sede
               WHERE f.ID_funcion = ?";
$stmt = $conexion->prepare($sql_funcion);
$stmt->bind_param("i", $id_funcion);
$stmt->execute();
$funcion = $stmt->get_result()->fetch_assoc();
?>

<h2>Compra de boletos</h2>

<div class="funcion-info">
    <h3><?php echo htmlspecialchars($funcion['titulo']); ?></h3>
    <p><strong>Sede:</strong> <?php echo htmlspecialchars($funcion['nombre_sede']); ?></p>
    <p><strong>Sala:</strong> <?php echo htmlspecialchars($funcion['numero_sala']); ?></p>
    <p><strong>Fecha y hora:</strong> <?php echo date('d/m/Y H:i', strtotime($funcion['fecha'] . ' ' . $funcion['hora'])); ?></p>
    <p><strong>Precio:</strong> S/ <?php echo number_format($funcion['precio_base'], 2); ?></p>
</div>

<?php if (isset($_SESSION['cliente_dni'])): ?>
    <form action="procesar_compra.php" method="post">
        <input type="hidden" name="funcion_id" value="<?php echo $id_funcion; ?>">
        
        <h3>Seleccione sus asientos</h3>
        <div class="asientos-container">
            <?php foreach ($asientos as $asiento): ?>
            <div class="asiento">
                <input type="checkbox" name="asientos[]" id="asiento-<?php echo $asiento['ID_asiento']; ?>" 
                       value="<?php echo $asiento['ID_asiento']; ?>">
                <label for="asiento-<?php echo $asiento['ID_asiento']; ?>">
                    <?php echo $asiento['fila'] . $asiento['numero']; ?>
                </label>
            </div>
            <?php endforeach; ?>
        </div>
        
        <button type="submit" class="btn">Confirmar compra</button>
    </form>
<?php else: ?>
    <div class="alert">
        <p>Debe iniciar sesión o registrarse para comprar boletos.</p>
        <a href="../clientes/login.php" class="btn">Iniciar sesión</a>
        <a href="../clientes/registrar.php" class="btn">Registrarse</a>
    </div>
<?php endif; ?>

<?php require_once '../../includes/footer.php'; ?>