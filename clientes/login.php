<?php
session_start();
require_once '../includes/header.php';
require_once '../includes/funciones.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'];
    
    // Verificar si el cliente existe
    $sql = "SELECT * FROM Cliente WHERE DNI = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $_SESSION['cliente_dni'] = $dni;
        header("Location: ../index.php");
        exit();
    } else {
        $error = "Cliente no registrado";
    }
}
?>

<h2>Iniciar Sesión</h2>

<?php if (isset($error)): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<form method="post">
    <div class="form-group">
        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" required>
    </div>
    <button type="submit" class="btn">Ingresar</button>
</form>

<p>¿No tienes cuenta? <a href="registrar.php">Regístrate aquí</a></p>

<?php require_once '../includes/footer.php'; ?>