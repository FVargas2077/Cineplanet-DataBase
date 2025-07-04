<?php
session_start();
require_once '../../includes/header.php';
require_once '../../includes/funciones.php';

// Verificar si es administrador
if (!isset($_SESSION['admin'])) {
    header("Location: /cineplanet/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit();
}

$id_pelicula = $_GET['id'];

// Obtener datos de la película
$sql = "SELECT * FROM Pelicula WHERE ID_pelicula = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_pelicula);
$stmt->execute();
$pelicula = $stmt->get_result()->fetch_assoc();

if (!$pelicula) {
    header("Location: listar.php");
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
    
    $sql = "UPDATE Pelicula SET 
            titulo = ?, 
            director = ?, 
            genero = ?, 
            duracion_minutos = ?, 
            clasificacion = ?, 
            sinopsis = ?, 
            fecha_estreno = ?, 
            estado = ?
            WHERE ID_pelicula = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssissssi", $titulo, $director, $genero, $duracion, $clasificacion, $sinopsis, $fecha_estreno, $estado, $id_pelicula);
    
    if ($stmt->execute()) {
        header("Location: listar.php?success=1");
        exit();
    } else {
        $error = "Error al actualizar la película";
    }
}
?>

<h2>Editar Película</h2>

<?php if (isset($error)): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="id" value="<?php echo $pelicula['ID_pelicula']; ?>">
    <div class="form-group">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($pelicula['titulo']); ?>" required>
    </div>
    <div class="form-group">
        <label for="director">Director:</label>
        <input type="text" id="director" name="director" value="<?php echo htmlspecialchars($pelicula['director']); ?>">
    </div>
    <div class="form-group">
        <label for="genero">Género:</label>
        <input type="text" id="genero" name="genero" value="<?php echo htmlspecialchars($pelicula['genero']); ?>" required>
    </div>
    <div class="form-group">
        <label for="duracion">Duración (minutos):</label>
        <input type="number" id="duracion" name="duracion" value="<?php echo htmlspecialchars($pelicula['duracion_minutos']); ?>">
    </div>
    <div class="form-group">
        <label for="clasificacion">Clasificación:</label>
        <select id="clasificacion" name="clasificacion">
            <option value="ATP" <?php echo $pelicula['clasificacion'] == 'ATP' ? 'selected' : ''; ?>>ATP</option>
            <option value="+13" <?php echo $pelicula['clasificacion'] == '+13' ? 'selected' : ''; ?>>+13</option>
            <option value="+16" <?php echo $pelicula['clasificacion'] == '+16' ? 'selected' : ''; ?>>+16</option>
            <option value="+18" <?php echo $pelicula['clasificacion'] == '+18' ? 'selected' : ''; ?>>+18</option>
        </select>
    </div>
    <div class="form-group">
        <label for="sinopsis">Sinopsis:</label>
        <textarea id="sinopsis" name="sinopsis" rows="4"><?php echo htmlspecialchars($pelicula['sinopsis']); ?></textarea>
    </div>
    <div class="form-group">
        <label for="fecha_estreno">Fecha de Estreno:</label>
        <input type="date" id="fecha_estreno" name="fecha_estreno" value="<?php echo htmlspecialchars($pelicula['fecha_estreno']); ?>">
    </div>
    <div class="form-group">
        <label for="estado">Estado:</label>
        <select id="estado" name="estado">
            <option value="En_Cartelera" <?php echo $pelicula['estado'] == 'En_Cartelera' ? 'selected' : ''; ?>>En Cartelera</option>
            <option value="Proximamente" <?php echo $pelicula['estado'] == 'Proximamente' ? 'selected' : ''; ?>>Próximamente</option>
            <option value="Retirada" <?php echo $pelicula['estado'] == 'Retirada' ? 'selected' : ''; ?>>Retirada</option>
        </select>
    </div>
    <button type="submit" class="btn">Actualizar Película</button>
</form>

<?php require_once '../../includes/footer.php'; ?>