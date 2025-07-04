<?php
session_start();
require_once '../../includes/header.php';
require_once '../../includes/funciones.php';

// Verificar si es administrador
if (!isset($_SESSION['admin'])) {
    header("Location: /cineplanet/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $director = $_POST['director'];
    $genero = $_POST['genero'];
    $duracion = $_POST['duracion'];
    $clasificacion = $_POST['clasificacion'];
    $sinopsis = $_POST['sinopsis'];
    $fecha_estreno = $_POST['fecha_estreno'];
    $estado = $_POST['estado'];
    
    $sql = "INSERT INTO Pelicula (titulo, director, genero, duracion_minutos, clasificacion, sinopsis, fecha_estreno, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssissss", $titulo, $director, $genero, $duracion, $clasificacion, $sinopsis, $fecha_estreno, $estado);
    
    if ($stmt->execute()) {
        header("Location: listar.php?success=1");
        exit();
    } else {
        $error = "Error al agregar la película";
    }
}
?>

<h2>Agregar Nueva Película</h2>

<?php if (isset($error)): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<form method="post">
    <div class="form-group">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>
    </div>
    <div class="form-group">
        <label for="director">Director:</label>
        <input type="text" id="director" name="director">
    </div>
    <div class="form-group">
        <label for="genero">Género:</label>
        <input type="text" id="genero" name="genero" required>
    </div>
    <div class="form-group">
        <label for="duracion">Duración (minutos):</label>
        <input type="number" id="duracion" name="duracion">
    </div>
    <div class="form-group">
        <label for="clasificacion">Clasificación:</label>
        <select id="clasificacion" name="clasificacion">
            <option value="ATP">ATP</option>
            <option value="+13">+13</option>
            <option value="+16">+16</option>
            <option value="+18">+18</option>
        </select>
    </div>
    <div class="form-group">
        <label for="sinopsis">Sinopsis:</label>
        <textarea id="sinopsis" name="sinopsis" rows="4"></textarea>
    </div>
    <div class="form-group">
        <label for="fecha_estreno">Fecha de Estreno:</label>
        <input type="date" id="fecha_estreno" name="fecha_estreno">
    </div>
    <div class="form-group">
        <label for="estado">Estado:</label>
        <select id="estado" name="estado">
            <option value="En_Cartelera">En Cartelera</option>
            <option value="Proximamente">Próximamente</option>
            <option value="Retirada">Retirada</option>
        </select>
    </div>
    <button type="submit" class="btn">Agregar Película</button>
</form>

<?php require_once '../../includes/footer.php'; ?>