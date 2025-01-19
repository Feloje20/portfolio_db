<?php
    $user = $data['usuario'];
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
    <?php include 'header.php'; ?>
    <h2><?php echo $user['nombre'] . " " . $user['apellidos']?></h2>
    <?php
        echo '<img src="' . BASE_URL . 'img/' . $user['foto'] . '" alt="Foto de perfil">';
        echo '<p>Email: ' . $user['email'] . '</p>';
        echo '<p>Bio: ' . $user['resumen_perfil'] . '</p>';
    ?>
</body>
</html>