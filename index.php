<?php
require_once 'includes/header.php';
require_once 'includes/funciones.php';

// Obtener películas en cartelera
$peliculas = obtenerPeliculas();
?>

<div class="banner">
    <h2>Bienvenido a Cineplanet Perú</h2>
    <p>Las mejores películas en las salas más modernas</p>
</div>

<h2>Películas Destacadas</h2>

<div class="peliculas-grid">
    <?php foreach ($peliculas as $pelicula): ?>
    <div class="pelicula-card">
        <h3><?php echo htmlspecialchars($pelicula['titulo']); ?></h3>
        <p><strong>Género:</strong> <?php echo htmlspecialchars($pelicula['genero']); ?></p>
        <p><strong>Clasificación:</strong> <?php echo htmlspecialchars($pelicula['clasificacion']); ?></p>
        <a href="peliculas/listar.php?pelicula=<?php echo $pelicula['ID_pelicula']; ?>" class="btn">Ver funciones</a>
    </div>
    <?php endforeach; ?>
</div>

<div class="promociones">
    <h2>Promociones Especiales</h2>
    <div class="promo-card">
        <h3>Martes de Descuento</h3>
        <p>Todos los martes 2x1 en entradas para socios</p>
    </div>
    <div class="promo-card">
        <h3>Combo Familiar</h3>
        <p>Ahorra 15% en combos los fines de semana</p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>