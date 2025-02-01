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
    <h2>Modificar <?php echo $data['tipo']?></h2>
    <form action="" method="POST" class="newPortfolioForm" enctype="multipart/form-data">
        <?php
        switch ($data['tipo']) {
            case 'trabajo':
                include 'crearTrabajo.php';
                break;
            case 'proyecto':
                include 'crearProyecto.php';
                break;
            case 'skill':
                include 'crearSkills.php';
                break;
            case 'red_social':
                include 'crearRedSocial.php';
                break;
            default:
                echo "Tipo no válido.";
                break;
        }
        ?>
        <input type="submit" class="" name="modificar" value="Guardar cambios">
        <button type="submit" class="btnCancelarEdit" name="cancelar" formnovalidate>Cancelar</button>
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>