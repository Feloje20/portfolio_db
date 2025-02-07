<?php
    $user = $data['usuario'];
    $trabajos = $data['trabajos'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio de <?php echo $user['nombre'] ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL?>css/styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="portfolioMain">
        <?php
            echo '<div class="portfolioUserData">';
            echo '<img src="' . BASE_URL . 'img/' . $user['foto'] . '" alt="Foto de perfil" class="portfolioImg">';
            echo '<div>';
            echo '<h2>' . $user['nombre'] . ' ' . $user['apellidos'] . '</h2>';
            echo '<p><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z"/></svg> ' . $user['email'] . '</p>';
            echo '<p>' . $user['resumen_perfil'] . '</p>';
            echo '</div>';
            echo '</div>';
        ?>
        <?php
            if (!empty($trabajos)) {
                echo "<h2>Trabajos</h2>";
                echo '<div class="trabajos">';
                foreach ($data['trabajos'] as $trabajo) {
                    $fechaInicio = date('d-m-Y', strtotime($trabajo['fecha_inicio']));
                    $fechaFinal = date('d-m-Y', strtotime($trabajo['fecha_final']));
                    echo '<div class="trabajo">';
                    echo '<h4>' . $trabajo['titulo'] . '</h4>';
                    echo '<p>' . $trabajo['descripcion'] . '</p>';
                    echo '<p><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="M180-380q-42 0-71-29t-29-71q0-42 29-71t71-29q31 0 56 17t36 43h608v80H272q-11 26-36 43t-56 17Z"/></svg> ' . $fechaInicio . '</p>';
                    echo '<p><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="M780-380q-31 0-56-17t-36-43H80v-80h608q11-26 36-43t56-17q42 0 71 29t29 71q0 42-29 71t-71 29Z"/></svg> ' . $fechaFinal . '</p>';
                    echo '<p>' . $trabajo['logros'] . '</p>';
                    echo '</div>';
                }
                echo '</div>';
            }
        ?>
        <?php
            if (!empty($data['proyectos'])) {
                echo "<h2>Proyectos</h2>";
                echo '<div class="trabajos">';
                foreach ($data['proyectos'] as $proyecto) {
                    echo '<div class="proyecto">';
                    echo '<img src="' . BASE_URL . 'img/' . $proyecto['logo'] . '" alt="Logo de proyecto" class="portfolioImg">';
                    echo '<div>';
                    echo '<h4>' . $proyecto['titulo'] . '</h4>';
                    echo '<p>' . $proyecto['tecnologias'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
            }
        ?>
        <?php
            if (!empty($data['skills'])) {
                echo "<h2>Skills</h2>";
                echo '<div class="trabajos">';
                foreach ($data['skills'] as $skill) {
                    echo '<div class="trabajo">';
                    echo '<h4>' . $skill['categorias_skills_categoria'] . '</h4>';
                    echo '<p>' . $skill['habilidades'] . '</p>';
                    echo '</div>';
                }
                echo '</div>';
            }
        ?>
        <?php
            if (!empty($data['redesSociales'])) {
                echo "<h2>Redes sociales</h2>";
                echo '<div class="trabajos">';
                foreach ($data['redesSociales'] as $red_social) {
                    echo '<div class="trabajo">';
                    echo '<h4>' . $red_social['redes_sociales'] . '</h4>';
                    echo '<p>' . $red_social['url'] . '</p>';
                    echo '</div>';
                }
                echo '</div>';
            }
        ?>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>