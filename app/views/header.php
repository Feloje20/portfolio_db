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

$isRegisterPage = ($_SERVER['REQUEST_URI'] === '/usuarios/add');

?>
<header>
    <h1><a href="<?php echo BASE_URL; ?>">Portfolio manager</a></h1>
    <ul class="header-list">
    <?php if (!$isLogged): ?>
            <li><?php include "header_login.php" ?></li>
        <?php if (!$isRegisterPage): ?>
            <li><a class="loginButton" href="<?php echo BASE_URL?>usuarios/add"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="M720-400v-120H600v-80h120v-120h80v120h120v80H800v120h-80Zm-360-80q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm80-80h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0-80Zm0 400Z"/></svg></a></li>
        <?php endif; ?>
    <?php else: ?>
        <li>Hola, <?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellidos']; ?></li>
        <li><a href="usuarios/logout">Cerrar Sesión</a></li>
        <li><a href="">Crear portfolio</a></li>
        <li><a href="">Editar portfolio</a></li>
        <li><a href="">Borrar portfolio</a></li>
    <?php endif; ?>
    </ul>
</header>
