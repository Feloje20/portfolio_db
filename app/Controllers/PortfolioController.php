<?php

namespace App\Controllers;
use App\Models\Usuarios;
use App\Models\Portfolios;
use App\Models\Trabajos;
use App\Models\Proyectos;
use App\Models\Skills;
use App\Models\RedesSociales;

class PortfolioController extends BaseController
{
    // Función que muestra el portfolio de un usuario
    public function viewPortfolioAction()
    {
        // Creamos una instancia de usuarios
        $usuario = Usuarios::getInstancia();
        $portfolio = Portfolios::getInstancia();

        // Si no se ha iniciado sesión, inicializamos la variable $sessionId a null
        if (isset($_SESSION['id'])) {
            $sessionId = $_SESSION['id'];
        } else {
            $sessionId = null;
        }

        // Tomamos la id del usuario de la URL
        $id = explode('/', $_SERVER['REQUEST_URI'])[2];

        // Comprobamos si el portfolio del usuario tiene la visibilidad activa o sí el usuario logeado es el dueño del portfolio
        if ($portfolio->checkVisibility($id) == 0 && $id != $sessionId) {
            $this->renderHTML('../app/views/error.php', ['error' => 'Error: Este portfolio es privado']);
        } 
        else 
        {
            // Alamacenamos los datos en $data
            $data['usuario'] = $usuario->get($id);

            // Llamamos a la función renderHTML
            $this->renderHTML('../app/views/portfolio.php', $data);
        } 
    }

    // Función que muestra solo los formularios con las visibilidad activa
    public function viewPublicPortfoliosAction()
    {
        // Creamos una instancia de usuarios
        $portfolio = Portfolios::getInstancia();
        $usuario = Usuarios::getInstancia();

        // Recorremos las IDs y recuperamos los datos de los usuarios.
        $data['ids'] = $portfolio->getPublic();
        $data['usuarios'] = [];
        foreach ($data['ids'] as $id) {
            $data['usuario'] = $usuario->get($id['id']);
            $tecnologias = '';

            // Recorremos la información del usuario y extraemos las tecnologías no repetidas de sus proyectos.
            foreach ($data['usuario']['proyectos'] as $proyecto) {
                $tecnologiasArray = explode(',', $proyecto['tecnologias']);
                $tecnologiasArray = array_unique($tecnologiasArray);
                $tecnologias .= implode(', ', $tecnologiasArray) . ', ';
            }
            $data['usuario']['tecnologias'] = substr($tecnologias, 0, -2);
            $data['usuarios'][] = $data['usuario'];
        }

        $data['query'] = '';

        // Llamamos a la función renderHTML
        $this->renderHTML('../app/views/index_test.php', $data);
    }

    // Método que muestra el index_test.php con la información de búsqueda del usuario recibida por get
    public function searchAction() {
        // Creamos una instancia de usuarios
        $portfolio = Portfolios::getInstancia();
        $proyecto = Proyectos::getInstancia();
        $usuario = Usuarios::getInstancia();
        $data['query'] = '';

        if (isset($_GET['query'])) {
            // Almacenamos los las IDs de los usuarios
            $data['ids'] = $portfolio->getPublic();

            // Almacenamos las IDs válidas por el nombre o el apellido
            $validIds = $portfolio->searchPortfolios($_GET['query']);

            // Recorremos todas las ids filtrando los usuarios que cumplan con la búsqueda.
            if($data['ids']) {
                $data['usuarios'] = [];
                foreach ($data['ids'] as $id) {
                    if (in_array($id, $validIds)) {
                        $data['usuario'] = $usuario->get($id['id']);
                        $tecnologias = '';

                        // Recorremos la información del usuario y extraemos las tecnologías no repetidas de sus proyectos.
                        foreach ($data['usuario']['proyectos'] as $proyecto) {
                            $tecnologiasArray = explode(',', $proyecto['tecnologias']);
                            $tecnologiasArray = array_unique($tecnologiasArray);
                            $tecnologias .= implode(', ', $tecnologiasArray) . ', ';
                        }

                        $data['usuario']['tecnologias'] = substr($tecnologias, 0, -2);
                        $data['usuarios'][] = $data['usuario'];
                    } else {
                        // Comprobamos si alguna de las tecnologías del usuario cumple con la búsqueda
                        $data['usuario'] = $usuario->get($id['id']);
                        $tecnologias = '';

                        foreach ($data['usuario']['proyectos'] as $proyecto) {
                            $tecnologiasArray = explode(',', $proyecto['tecnologias']);
                            $tecnologiasArray = array_unique($tecnologiasArray);
                            $tecnologias .= implode(', ', $tecnologiasArray) . ', ';
                            foreach ($tecnologiasArray as $tecnologia) {
                                // En el caso de que se cumpla con la búsqueda, añadimos los datos para mostrar en la vista.
                                if (stripos($tecnologia, $_GET['query']) !== false) {
                                    $data['usuario']['tecnologias'] = substr($tecnologias, 0, -2);
                                    $data['usuarios'][] = $data['usuario'];
                                    break 2;
                                }
                            }
                        }
                    }
                }
            }

        } else {
            // Volvemos a la página inicial.
            header('Location: /');
        }

        // Llamamos a la función renderHTML
        $this->renderHTML('../app/views/index_test.php', $data);
    }

    // Función que muestra el formulario para crear un nuevo portfolio
    public function newPortfolioAction() {
        $data = [];
        $data['categorias'] = [];

        // Recuperamos la id del usuario logeado
        $usuario = Usuarios::getInstancia();
        $userId = $_SESSION['id'];

        // Comprobamos si el usuario ha iniciado sesión o si ya ha creado su portfolio, si no, lo redirigimos a la página de inicio
        $portfolio = Portfolios::getInstancia();

        if (!isset($_SESSION['email']) || $portfolio->isPortfolioCreated($userId)) {
            header('Location: /');
        }

        // Comprobamos si se ha enviado un formulario
        if (isset($_POST['crear'])) {
            $data['picture'] = $_FILES['proyecto_logo'];
            // FALTA MODIFICAR LA VISIBILIDAD EL PORTFOLIO *******************************************************

            // Recogemos los datos mientras los saneamos usando sanearDatos

            // Trabajo
            $trabajoTitulo = $this->sanearDatos($_POST['trabajos']['titulo']);
            $trabajoDescripcion = $this->sanearDatos($_POST['trabajos']['descripcion']);
            $trabajoFechaInicio = $_POST['trabajos']['fecha_inicio'];
            $trabajoFechaFinal = $_POST['trabajos']['fecha_final'];
            $trabajoLogros = $this->sanearDatos($_POST['trabajos']['logros']);
            $trabajoVisible = $_POST['trabajos']['visible'] ?? 0;

            // Proyectos
            $proyectoTitulo = $this->sanearDatos($_POST['proyectos']['titulo']);

            // Si no se ha subido una imagen, la imagen será la que hay por defecto "defaultLogo.jpg".
            // En caso contrario procesamos la subida.
            if ($data['picture']['name'] == '') {
                // $data['picture']['name'] = 'defaultLogo.png';
                $data['picture']['name'] = 'defaultLogo.png';
            } else if ($data['picture']['error'] == 0) {
                // Comprobamos si el archivo subido es una imagen
                if ($data['picture']['type'] == 'image/jpeg' || $data['picture']['type'] == 'image/png' || $data['picture']['type'] == 'image/PNG') {
                    // Comprobamos si el archivo subido no supera los 2MB
                    if ($data['picture']['size'] <= 2000000) {
                        // Generamos un nombre para la imagen al azar
                        //$data['picture']['name'] = 'logo_' . uniqid() . $data['picture']['name'];
                        $data['picture']['name'] = 'logo_' . uniqid() . $data['picture']['name'];
                        // Movemos el archivo a la carpeta de imágenes
                        move_uploaded_file($data['picture']['tmp_name'], dirname(__DIR__, 2) . '/public/img/' . $data['picture']['name']);
                    } else {
                        $lprocesaFormulario = false;
                        $data['msjErrorImagen'] = "* La imagen no puede superar los 2MB";
                    }
                } else {
                    $lprocesaFormulario = false;
                    $data['msjErrorImagen'] = "* El archivo subido no es una imagen";
                }
            }

            $proyectoTecnologias = $this->sanearDatos($_POST['proyectos']['tecnologias']);
            $proyectoVisible = $_POST['proyectos']['visible'] ?? 0;

            // Skills
            $skillsHabilidades = $this->sanearDatos($_POST['skills']['habilidades']);
            $skillsVisible = $_POST['skills']['visible'] ?? 0;
            $skillsCategoria = $_POST['skills']['categoria'];

            // Redes sociales
            $redesNombre = $this->sanearDatos($_POST['redes_sociales']['nombre']);
            $redesUrl = $this->sanearDatos($_POST['redes_sociales']['enlace']);

            // Visibilidad del portfolio
            $data['isVisible'] = isset($_POST['isVisible']) ?? 0;
            
            // COMPROBAR ERRORES EN LOS CAMPOS ****************************************************

            // Creamos instancias de los modelos a usar
            $trabajo = Trabajos::getInstancia();
            $proyecto = Proyectos::getInstancia();
            $skills = Skills::getInstancia();
            $redes = RedesSociales::getInstancia();
            $portfolio = Portfolios::getInstancia();

            // Añadimos los datos a la base de datos
            // Añadimos los datos a la base de datos usando setters individuales
            $trabajo->setTitulo($trabajoTitulo);
            $trabajo->setDescripcion($trabajoDescripcion);
            $trabajo->setFechaInicio($trabajoFechaInicio);
            $trabajo->setFechaFinal($trabajoFechaFinal);
            $trabajo->setLogros($trabajoLogros);
            $trabajo->setVisible($trabajoVisible);
            $trabajo->setUsuariosId($userId);
            $trabajo->set();

            $proyecto->setTitulo($proyectoTitulo);
            $proyecto->setLogo($data['picture']['name']);
            $proyecto->setTecnologias($proyectoTecnologias);
            $proyecto->setVisible($proyectoVisible);
            $proyecto ->setUsuariosId($userId);
            $proyecto->set();

            $skills->setHabilidades($skillsHabilidades);
            $skills->setVisible($skillsVisible);
            $skills->setCategoriasSkillsCategoria($skillsCategoria);
            $skills->setUsuariosId($userId);
            $skills->set();

            $redes->setRedesSociales($redesNombre);
            $redes->setUrl($redesUrl);
            $redes->setUsuariosId($userId);
            $redes->set();

            $portfolio->changeVisibility($userId, $data['isVisible']);

            // Marcamos en la sesión actual que el portfolio ha sido creado
            $_SESSION['isPorfolioCreated'] = true;

            // Redirigimos al usuario a la página de inicio
            header('Location: /');
        }

        // Recogemos las categorías de skills disponibles en la base de datos
        $skills = Skills::getInstancia();
        $data['categorias'] = $skills->getSkillsCategories();
        // Llamamos a la función renderHTML
        $this->renderHTML('../app/views/crearPortfolio.php', $data);
    }

    // Función que permite modificar un portfolio, solo el dueño o el administrador pueden hacerlo
    public function editPortfolioAction() {

        $id = explode('/', $_SERVER['REQUEST_URI'])[2];

        // Si el usuario no está logeado, inicializamos la variable $userEmail a null
        $userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : null;
        
        // Si el usuario no está logeado, inicializamos la variable $userProfile a null
        $userProfile = isset($_SESSION['perfil']) ? $_SESSION['perfil'] : null;

        $portfolio = Portfolios::getInstancia();
        $usuario = Usuarios::getInstancia();

        // Comprobamos si el usuario está logeado y es dueño del portfolio o es administrador
        if ($portfolio->isOwner($id, $userEmail) || $userProfile === 'admin') {
            // Alamacenamos los datos en $data
            $data['usuario'] = $usuario->get($id);

            $this->renderHTML('../app/views/editarPortfolio.php', $data);
        } else {
            // Si no se cumplen las condiciones, volvemos a la página de inicio
            header('Location: /');
        }
    }

    // Método para borrar toda la información de un portfolio
    public function deletePortfolioAction() {
        $id = explode('/', $_SERVER['REQUEST_URI'])[2];

        // Si el usuario no está logeado, inicializamos la variable $userEmail a null
        $userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : null;
        
        // Si el usuario no está logeado, inicializamos la variable $userProfile a null
        $userProfile = isset($_SESSION['perfil']) ? $_SESSION['perfil'] : null;

        $portfolio = Portfolios::getInstancia();
        $trabajo = Trabajos::getInstancia();
        $proyecto = Proyectos::getInstancia();
        $skills = Skills::getInstancia();
        $redesSociales = RedesSociales::getInstancia();

        // Comprobamos si el usuario está logeado y es dueño del portfolio o es administrador
        if ($portfolio->isOwner($id, $userEmail) || $userProfile === 'admin') {
            // Borramos toda la información del portfolio
            $trabajo->deleteAll($id);
            $proyecto->deleteAll($id);
            $skills->deleteAll($id);
            $redesSociales->deleteAll($id);

            // Ponemos la variable de sesión isPortfolioCreated a false
            $_SESSION['isPorfolioCreated'] = false;

            // Cambiamos la visibilidad del portfolio a 0
            $portfolio->changeVisibility($id, 0);

            // Redirigimos al usuario a la página de inicio
            header('Location: /');
        } else {
            // Si no se cumplen las condiciones, volvemos a la página de inicio
            header('Location: /');
        }
    }
}