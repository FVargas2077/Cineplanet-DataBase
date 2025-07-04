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

// Verificar si la película existe
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
    if (isset($_POST['confirmar'])) {
        $sql = "DELETE FROM Pelicula WHERE ID_pelicula = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_pelicula);
        
        if ($stmt->execute()) {
            header("Location: listar.php?success=1");
            exit();
        } else {
            $error = "Error al eliminar la película";
        }
    } else {
        header("Location: listar.php");
        exit();
    }
}
?>

<h2>Eliminar Película</h2>

<?php if (isset($error)): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<p>¿Estás seguro que deseas eliminar la película "<?php echo htmlspecialchars($pelicula['titulo']); ?>"?</p>

<form method="post">
    <button type="submit" name="confirmar" class="btn btn-danger">Sí, eliminar</button>
    <a href="listar.php" class="btn">Cancelar</a>
</form>

<?php require_once '../../includes/footer.php'; ?>