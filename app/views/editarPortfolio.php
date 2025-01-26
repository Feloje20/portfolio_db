<?php
    $user = $data['usuario'];
    $userId = $user['id'];
    $trabajos = $data['trabajos'];

    // Función para mostrar los botones de edición.
    function botones($campo, $campoId, $userId, $visibilidad = 2) {
        echo '<div class="botonesEdicion">';
        if ($visibilidad == 1) {
            echo '<a class="btn-visibility" href="/' . $campo . '/visibilityRow/' . $userId . '/' . $campoId . '"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/></svg></a>';
        } else if ($visibilidad == 0) {
            echo '<a class="btn-novisibility" href="/' . $campo . '/novisibilityRow/' . $userId . '/' . $campoId . '"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="m644-428-58-58q9-47-27-88t-93-32l-58-58q17-8 34.5-12t37.5-4q75 0 127.5 52.5T660-500q0 20-4 37.5T644-428Zm128 126-58-56q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-62-62q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302Zm20 246L624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM222-624q-29 26-53 57t-41 67q50 101 143.5 160.5T480-280q20 0 39-2.5t39-5.5l-36-38q-11 3-21 4.5t-21 1.5q-75 0-127.5-52.5T300-500q0-11 1.5-21t4.5-21l-84-82Zm319 93Zm-151 75Z"/></svg></a>';
        }
        echo '<a class="btn-edit" href="/' . $campo . '/editRow/' . $userId . '/' . $campoId . '">Editar</a>';
        echo '<a class="btn-delete" href="/' . $campo . '/delRow/' . $userId . '/' . $campoId . '">Eliminar</a>';
        echo '</div>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
    <link rel="stylesheet" href="<?php echo BASE_URL?>css/styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="portfolioMain">
        <?php
            echo '<div class="portfolioUserData">';
            echo '<h2>' . $user['nombre'] . ' ' . $user['apellidos'] . '</h2>';
            echo '<img src="' . BASE_URL . 'img/' . $user['foto'] . '" alt="Foto de perfil" class="portfolioImg">';
            echo '<p><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z"/></svg> ' . $user['email'] . '</p>';
            echo '<p>' . $user['resumen_perfil'] . '</p>';
            echo '</div>';
        ?>
        <div class='tituloConBoton'>
            <h2>Trabajos</h2>
            <?php echo '<a class="btn-new" href="/trabajo' . '/new/' . $userId . '"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/></svg></a>';?>
        </div>
        <?php
            if (empty($trabajos)) {
                echo '<p  class="parrafoVacio">No hay trabajos para mostrar</p>';
            } else {
                echo '<div class="trabajos">';
                foreach ($trabajos as $trabajo) {
                    $fechaInicio = date('d-m-Y', strtotime($trabajo['fecha_inicio']));
                    $fechaFinal = date('d-m-Y', strtotime($trabajo['fecha_final']));
                    if ($trabajo['visible'] == 1) {
                        echo '<div class="trabajo">';
                    } else {
                        echo '<div class="trabajo trabajoOculto">';
                    }
                    echo '<h4>' . $trabajo['titulo'] . '</h4>';
                    echo '<p>' . $trabajo['descripcion'] . '</p>';
                    echo '<p><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="M180-380q-42 0-71-29t-29-71q0-42 29-71t71-29q31 0 56 17t36 43h608v80H272q-11 26-36 43t-56 17Z"/></svg> ' . $fechaInicio . '</p>';
                    echo '<p><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="M780-380q-31 0-56-17t-36-43H80v-80h608q11-26 36-43t56-17q42 0 71 29t29 71q0 42-29 71t-71 29Z"/></svg> ' . $fechaFinal . '</p>';
                    echo '<p>' . $trabajo['logros'] . '</p>';
                    botones('trabajo', $trabajo['id'], $user['id'], $trabajo['visible']);
                    echo '</div>';
                }
                echo '</div>';
            }
        ?>
        <div class='tituloConBoton'>
            <h2>Proyectos</h2>
            <?php echo '<a class="btn-new" href="/proyecto' . '/new/' . $userId . '"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/></svg></a>';?>
        </div>
        <?php
            if (empty($data['proyectos'])) {
                echo '<p  class="parrafoVacio">No hay proyectos para mostrar</p>';
            } else {
                echo '<div class="trabajos">';
                foreach ($data['proyectos'] as $proyecto) {
                    if ($proyecto['visible'] == 1) {
                        echo '<div class="trabajo">';
                    } else {
                        echo '<div class="trabajo trabajoOculto">';
                    }
                    echo '<h4>' . $proyecto['titulo'] . '</h4>';
                    // EL LOGO HAY QUE CAMBIARLO A UNA IMAGEN DE VERDAD ****************************************************
                    echo '<p>' . $proyecto['logo'] . '</p>';
                    echo '<p>' . $proyecto['tecnologias'] . '</p>';
                    botones('proyecto', $proyecto['id'], $user['id'], $proyecto['visible']);
                    echo '</div>';
                }
                echo '</div>';
            }
        ?>
        <div class='tituloConBoton'>
            <h2>Skills</h2>
            <?php echo '<a class="btn-new" href="/skill' . '/new/' . $userId . '"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/></svg></a>';?>
        </div>
        <?php
            if (empty($data['skills'])) {
                echo '<p  class="parrafoVacio">No hay skills para mostrar</p>';
            } else {
                echo '<div class="trabajos">';
                foreach ($data['skills'] as $skill) {
                    echo '<div class="trabajo">';
                    echo '<p>' . $skill['categorias_skills_categoria'] . '</p>';
                    echo '<h4>' . $skill['habilidades'] . '</h4>';
                    botones('skill', $skill['id'], $user['id'], $skill['visible']);
                    echo '</div>';
                }
                echo '</div>';
            }
        ?>
        <div class='tituloConBoton'>        
            <h2>Redes sociales</h2>
            <?php echo '<a class="btn-new" href="/redes_sociales' . '/new/' . $userId . '"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/></svg></a>';?>
        </div>
        <?php
            if (empty($data['redesSociales'])) {
                echo '<p class="parrafoVacio">No hay redes sociales para mostrar</p>';
            } else {
                echo '<div class="trabajos">';
                foreach ($data['redesSociales'] as $red_social) {
                    echo '<div class="trabajo">';
                    echo '<h4>' . $red_social['redes_sociales'] . '</h4>';
                    echo '<p>' . $red_social['url'] . '</p>';
                    botones('redes_sociales', $red_social['id'], $user['id']);
                    echo '</div>';
                }
                echo '</div>';
            }
        ?>
    </main>
    <script>
    document.querySelectorAll(".btn-delete").forEach(function (element) {
      element.addEventListener("click", function (e) {
        e.preventDefault(); // Evita la redirección automática
        const confirmacion = confirm("¿Estás seguro de que deseas eliminar este elemento?");
        if (confirmacion) {
          window.location.href = this.href; // Redirige a la URL del enlace
        }
      });
    });
  </script>
</body>
</html>