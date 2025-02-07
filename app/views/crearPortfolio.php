<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear portfolio</title>
    <link rel="stylesheet" href="<?php echo BASE_URL . "/" ?>css/styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <h2>Crear portfolio</h2>
    <form action="" method="POST" class="newPortfolioForm" enctype="multipart/form-data">
        <!---- HAY QUE PONER SI QUIERE CAMBIAR LA VISIBILIDAD A PUBLICO O PRIVADO ---->
        <h3>Primer trabajo</h3>
        <?php include 'crearTrabajo.php'; ?>
        
        <h3>Primer proyecto</h3>
        <?php include 'crearProyecto.php'; ?>
        
        <h3>Primera skill</h3>
        <?php include 'crearSkills.php'; ?>

        <h3>Primera red social</h3>
        <?php include 'crearRedSocial.php'; ?>

        <label for="isVisible">portfolio visible al público:
            <input type="checkbox" id="isVisible" name="isVisible" value="1">
        </label>

        <input type="submit" class="" name="crear" value="Crear">
        <button type="submit" class="btnCancelarEdit" name="cancelar" formnovalidate>Cancelar</button>
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>