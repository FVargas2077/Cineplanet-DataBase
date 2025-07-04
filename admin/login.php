<?php
session_start();
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    
    if (loginAdmin($usuario, $contrasena)) {
        $_SESSION['admin'] = true;
        header("Location: index.php");
        exit();
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>

<h2>Inicio de Sesión - Administrador</h2>

<?php if (isset($error)): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<form method="post">
    <div class="form-group">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>
    </div>
    <div class="form-group">
        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>
    </div>
    <button type="submit" class="btn">Ingresar</button>
</form>

<?php require_once '../includes/footer.php'; ?>