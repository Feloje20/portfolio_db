<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?php echo BASE_URL ?>css/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=login" />
</head>
<body>
    <?php include 'header.php'; ?>
    <h2>PORTFOLIOS</h2>
    <form action="<?php echo BASE_URL?>search" method="get" class="search-form">
        <input type="text" name="query" placeholder="Buscar portfolios..." class="search-input">
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
                <img src="<?php echo BASE_URL . 'img/' . $user['foto']; ?>" alt="Imagen de <?php echo $user['nombre']; ?>">
                <p><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z"/></svg><?php echo $user['email']; ?></p>
                <p><?php echo $user['resumen_perfil']; ?></p>
                <?php 
                    $tecnologias = explode(',', $user['tecnologias']);
                    echo '<p>' . implode(', ', $tecnologias) . '</p>';
                ?>
            </div>
        <?php endforeach; } ?>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>