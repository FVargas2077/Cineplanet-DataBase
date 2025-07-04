<?php
require_once '../../includes/header.php';
require_once '../../includes/funciones.php';

if (!isset($_GET['pelicula'])) {
    header("Location: ../peliculas/listar.php");
    exit();
}

$id_pelicula = $_GET['pelicula'];
$funciones = obtenerFuncionesPorPelicula($id_pelicula);

// Obtener datos de la película
$sql_pelicula = "SELECT titulo FROM Pelicula WHERE ID_pelicula = ?";
$stmt = $conexion->prepare($sql_pelicula);
$stmt->bind_param("i", $id_pelicula);
$stmt->execute();
$pelicula = $stmt->get_result()->fetch_assoc();
?>

<h2>Funciones para: <?php echo htmlspecialchars($pelicula['titulo']); ?></h2>

<table>
    <thead>
        <tr>
            <th>Sede</th>
            <th>Sala</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($funciones as $funcion): ?>
        <tr>
            <td><?php echo htmlspecialchars($funcion['nombre_sede']); ?></td>
            <td><?php echo htmlspecialchars($funcion['numero_sala']); ?></td>
            <td><?php echo date('d/m/Y', strtotime($funcion['fecha'])); ?></td>
            <td><?php echo date('H:i', strtotime($funcion['hora'])); ?></td>
            <td>S/ <?php echo number_format($funcion['precio_base'], 2); ?></td>
            <td>
                <a href="comprar.php?funcion=<?php echo $funcion['ID_funcion']; ?>" class="btn">Comprar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../../includes/footer.php'; ?>