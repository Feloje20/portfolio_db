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
            justify-content: center;
        }

        .user-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            box-sizing: border-box;
            text-align: center;
            flex: 1 1 20%;
            min-width: 100px; /* Set the minimum width of the card */
        }

        .user-card:hover {
            background-color: #f0f0f0;
        }

        .user-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
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
            <div class="user-card" onclick="location.href='view/<?php echo $user['id']; ?>'">
                <h2><?php echo $user['nombre'] . ' ' . $user['apellidos']; ?></h2>
                <p>Email: <?php echo $user['email']; ?></p>
                <p>Resumen: <?php echo $user['resumen_perfil']; ?></p>
                <img src="<?php echo 'img/' . $user['foto']; ?>" alt="Imagen de <?php echo $user['nombre']; ?>">
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>