<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
    <link rel="stylesheet" href="<?php echo BASE_URL . "/" ?>css/styles.css">
</head>
<body>
    <div class="tittle-box" onclick="location.href='/'" style="cursor: pointer;">
        <h1 class="site-title">Portfolio Manager</h1>
    </div>
    <?php
    if (isset($data['usuario'])) {
        $user = $data['usuario'];
        echo '<h1>' . $user['nombre'] . '</h1>';
        echo '<h2>' . $user['apellidos'] . '</h2>';
        echo '<img src="' . BASE_URL . 'img/' . $user['foto'] . '" alt="Foto de perfil">';
        echo '<p>Email: ' . $user['email'] . '</p>';
        echo '<p>Bio: ' . $user['resumen_perfil'] . '</p>';
    } else {
        echo '<p>User not found.</p>';
    }
    ?>
</body>
</html>