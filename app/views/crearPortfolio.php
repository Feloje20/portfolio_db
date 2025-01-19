<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?php echo BASE_URL . "/" ?>css/styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <h2>Crear portfolio</h2>
    <form action="" method="POST" class="">
        <h3>Trabajos</h3>
        <?php include 'crearTrabajo.php'; ?>
        
        <h3>Proyectos</h3>
        <?php include 'crearProyecto.php'; ?>
        
        <h3>Skills</h3>
        <?php include 'crearSkills.php'; ?>

        <h3>Redes Sociales</h3>
        <?php include 'crearRedSocial.php'; ?>

        <button type="submit" class="" name="crear">Crear</button>
    </form>
</body>
</html>