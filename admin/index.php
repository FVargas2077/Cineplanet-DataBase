<?php
session_start();
require_once '../includes/header.php';

// Verificar si es administrador
if (!isset($_SESSION['admin'])) {
    header("Location: /cineplanet/login.php");
    exit();
}
?>

<h2>Panel de Administración</h2>

<div class="admin-menu">
    <a href="../peliculas/listar.php" class="btn">Gestionar Películas</a>
    <a href="../funciones/listar.php" class="btn">Gestionar Funciones</a>
    <a href="../clientes/listar.php" class="btn">Gestionar Clientes</a>
    <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
</div>

<?php require_once '../includes/footer.php'; ?>