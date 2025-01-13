<?php
// requerimos el boostrap y autoload
// require_once "../../boosttrap.php";
// require_once "../vendor/autoload.php";

// Inicialización de variables
$isLogged = false;

// Iniciamos sesión si existe
session_start();
if (isset($_SESSION['email'])) {
    $isLogged = true;
}

?>
<header>
    <h1><a href="<?php echo BASE_URL; ?>">Portfolio manager</a></h1>
    <ul class="header-list">
    <?php if (!$isLogged): ?>
        <li><a href="<?php echo BASE_URL?>usuarios/add">Registro</a></li>
        <li><?php include "header_login.php" ?></li>
    <?php else: ?>
        <li>Hola, <?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellidos']; ?></li>
        <li><a href="usuarios/logout">Cerrar Sesión</a></li>
        <li><a href="">Crear portfolio</a></li>
        <li><a href="">Editar portfolio</a></li>
        <li><a href="">Borrar portfolio</a></li>
    <?php endif; ?>
    </ul>
</header>
