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
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="tittle-box" onclick="location.href='/'" style="cursor: pointer;">
            <h1 class="site-title">Portfolio Manager</h1>
        </div>
        <nav>
            <ul>
            <?php if (!$isLogged): ?>
                <li><a href="usuarios/add">Registro</a></li>
                <li><a href="usuarios/login">Iniciar Sesión</a></li>
            <?php else: ?>
                <li>Hola, <?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellidos']; ?></li>
                <li><a href="usuarios/logout">Cerrar Sesión</a></li>
                <li><a href="">Crear portfolio</a></li>
                <li><a href="">Editar portfolio</a></li>
                <li><a href="">Borrar portfolio</a></li>
            <?php endif; ?>
            </ul>
        </nav>
    </header>
    <h2>PORFOLIOS INDEX</h2>
    <form action="search.php" method="POST" class="search-form">
        <input type="text" name="query" placeholder="Buscar usuarios..." class="search-input">
        <button type="submit" class="search-button">Buscar</button>
    </form>
    <div class="user-cards">
        <?php foreach ($data['usuarios'] as $user): ?>
            <div class="user-card" onclick="location.href='view/<?php echo $user['id']; ?>'">
                <h2><?php echo $user['nombre'] . ' ' . $user['apellidos']; ?></h2>
                <img src="<?php echo 'img/' . $user['foto']; ?>" alt="Imagen de <?php echo $user['nombre']; ?>">
                <p>Email: <?php echo $user['email']; ?></p>
                <p>Resumen: <?php echo $user['resumen_perfil']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>