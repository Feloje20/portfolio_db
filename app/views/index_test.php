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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header>
        <nav>
            <ul>
            <?php if (!$isLogged): ?>
                <li><a href="usuarios/add">Registro</a></li>
                <li><a href="usuarios/login">Iniciar Sesión</a></li>
            <?php else: ?>
                <li>Hola, <?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellidos']; ?></li>
                <li><a href="usuarios/logout">Cerrar Sesión</a></li>
            <?php endif; ?>
            </ul>
        </nav>
    </header>
    <h1>PORFOLIOS INDEX</h1>
</body>
</html>