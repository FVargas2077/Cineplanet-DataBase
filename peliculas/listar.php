<?php
require_once '../../includes/header.php';
require_once '../../includes/funciones.php';

$peliculas = obtenerPeliculas();
?>

<h2>Películas en Cartelera</h2>

<div class="peliculas-grid">
    <?php foreach ($peliculas as $pelicula): ?>
    <div class="pelicula-card">
        <h3><?php echo htmlspecialchars($pelicula['titulo']); ?></h3>
        <p><strong>Género:</strong> <?php echo htmlspecialchars($pelicula['genero']); ?></p>
        <p><strong>Duración:</strong> <?php echo htmlspecialchars($pelicula['duracion_minutos']); ?> min</p>
        <p><strong>Clasificación:</strong> <?php echo htmlspecialchars($pelicula['clasificacion']); ?></p>
        <a href="../funciones/listar.php?pelicula=<?php echo $pelicula['ID_pelicula']; ?>" class="btn">Ver funciones</a>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once '../../includes/footer.php'; ?>