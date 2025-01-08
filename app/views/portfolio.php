<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
</head>
<body>
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