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
    <style>
        .user-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .user-card {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .user-card:hover {
            background-color: #f0f0f0;
        }
    </style>
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
    <div class="user-cards">
        <?php foreach ($data['usuarios'] as $user): ?>
            <div class="user-card" onclick="location.href='usuarios/view/<?php echo $user['id']; ?>'">
                <h2><?php echo $user['nombre'] . ' ' . $user['apellidos']; ?></h2>
                <p>Email: <?php echo $user['email']; ?></p>
                <p>Resumen: <?php echo $user['resumen_perfil']; ?></p>
                <img src="<?php echo $user['foto']; ?>" alt="Imagen de <?php echo $user['nombre']; ?>" style="width:100px;height:auto;">
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>