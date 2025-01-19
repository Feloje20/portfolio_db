<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?php echo BASE_URL . "/" ?>css/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=login" />
</head>
<body>
    <?php include 'header.php'; ?>
    <h2>PORTFOLIOS</h2>
    <form action="" method="POST" class="search-form">
        <input type="text" name="query" placeholder="Buscar usuarios..." class="search-input">
        <button type="submit" class="search-button" name="search">Buscar</button>
    </form>
    <div class="user-cards">
        <?php 
        if (empty($data['usuarios'])) {
            echo '<p>No se han encontrado usuarios</p>';
        } else {
            foreach ($data['usuarios'] as $user): ?>
            <div class="user-card" onclick="location.href='view/<?php echo $user['id']; ?>'">
                <h2><?php echo $user['nombre'] . ' ' . $user['apellidos']; ?></h2>
                <img src="<?php echo 'img/' . $user['foto']; ?>" alt="Imagen de <?php echo $user['nombre']; ?>">
                <p>Email: <?php echo $user['email']; ?></p>
                <p>Resumen: <?php echo $user['resumen_perfil']; ?></p>
            </div>
        <?php endforeach; } ?>
    </div>
</body>
</html>