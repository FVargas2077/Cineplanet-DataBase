<?php
session_start();
require_once '../includes/header.php';
require_once '../includes/funciones.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $fecha_nac = $_POST['fecha_nac'];
    
    if (registrarCliente($dni, $nombre, $apellidos, $email, $telefono, $fecha_nac)) {
        $_SESSION['cliente_dni'] = $dni;
        header("Location: ../index.php");
        exit();
    } else {
        $error = "Error al registrar el cliente";
    }
}
?>

<h2>Registro de Cliente</h2>

<?php if (isset($error)): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<form method="post">
    <div class="form-group">
        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" required>
    </div>
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
    </div>
    <div class="form-group">
        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email">
    </div>
    <div class="form-group">
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono">
    </div>
    <div class="form-group">
        <label for="fecha_nac">Fecha de Nacimiento:</label>
        <input type="date" id="fecha_nac" name="fecha_nac">
    </div>
    <button type="submit" class="btn">Registrarse</button>
</form>

<?php require_once '../includes/footer.php'; ?>