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
            $data['query'] = $_GET['query'];

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

        // Inicializamos los errores a vacíos.
        $data['trabajo']['msjErrorTitulo'] = $data['trabajo']['msjErrorDescripcion'] = $data['trabajo']['msjErrorFechaInicial'] = $data['trabajo']['msjErrorFechaFinal'] = $data['trabajo']['msjErrorLogros'] = '';
        $data['proyecto']['msjErrorTitulo'] = $data['proyecto']['msjErrorTecnologias'] = $data['proyecto']['msjErrorImagen'] = '';
        $data['skill']['msjErrorHabilidades'] = '';
        $data['redsocial']['msjErrorNombre'] = $data['redsocial']['msjErrorUrl'] = '';

        $lprocesaFormulario = false;

        // Si el usuario clicka el botón cancelar, lo devolvemos a la página de edición.
        if (isset($_POST['cancelar'])) {
            header('Location: /edit/' . $userId);
            exit();
        }

        // Comprobamos si se ha enviado un formulario
        if (isset($_POST['crear'])) {
            $data['picture'] = $_FILES['proyecto_logo'];
            $lprocesaFormulario = true;

            // Recogemos los datos mientras los saneamos usando sanearDatos

            // Trabajo
            $data['trabajo']['titulo'] = $this->sanearDatos($_POST['trabajos']['titulo']);
            $data['trabajo']['descripcion'] = $this->sanearDatos($_POST['trabajos']['descripcion']);
            $data['trabajo']['fecha_inicio'] = $_POST['trabajos']['fecha_inicio'];
            $data['trabajo']['fecha_final'] = $_POST['trabajos']['fecha_final'];
            $data['trabajo']['logros'] = $this->sanearDatos($_POST['trabajos']['logros']);
            $data['trabajo']['visible'] = isset($_POST['trabajos']['visible']) ? 1 : 0;

            // Proyectos
            $data['picture'] = $_FILES['proyecto_logo'];
            $data['proyecto']['titulo'] = $this->sanearDatos($_POST['proyectos']['titulo']);
            $data['proyecto']['tecnologias'] = $this->sanearDatos($_POST['proyectos']['tecnologias']);
            $data['proyecto']['visible'] = isset($_POST['proyectos']['visible']) ? 1 : 0;

            // Skills
            $data['skill']['habilidades'] = $this->sanearDatos($_POST['skills']['habilidades']);
            $data['skill']['categorias_skills_categoria'] = $_POST['skills']['categoria'];
            $data['skill']['visible'] = isset($_POST['skills']['visible']) ? 1 : 0;

            // Redes sociales
            $data['redsocial']['redes_sociales'] = $this->sanearDatos($_POST['redes_sociales']['nombre']);
            $data['redsocial']['url'] = $this->sanearDatos($_POST['redes_sociales']['enlace']);

            // Visibilidad del portfolio
            $data['isVisible'] = isset($_POST['isVisible']) ?? 0;

            // Validamos los campos TRABAJO
            if ($data['trabajo']['titulo'] == '') {
                $data['trabajo']['msjErrorTitulo'] = 'El título no puede estar vacío';
                $lprocesaFormulario = false;
            }

            if ($data['trabajo']['descripcion'] == '') {
                $data['trabajo']['msjErrorDescripcion'] = 'La descripción no puede estar vacía';
                $lprocesaFormulario = false;
            }

            if ($data['trabajo']['fecha_inicio'] == '') {
                $data['trabajo']['msjErrorFechaInicial'] = 'La fecha de inicio no puede estar vacía';
                $lprocesaFormulario = false;
            }

            if ($data['trabajo']['fecha_final'] == '') {
                $data['trabajo']['msjErrorFechaFinal'] = 'La fecha final no puede estar vacía';
                $lprocesaFormulario = false;
            }

            if ($data['trabajo']['logros'] == '') {
                $data['trabajo']['msjErrorLogros'] = 'Los logros no pueden estar vacíos';
                $lprocesaFormulario = false;
            }

            // Validamos los campos PROYECTOS

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
                    } else {
                        $lprocesaFormulario = false;
                        $data['msjErrorImagen'] = "* La imagen no puede superar los 2MB";
                    }
                } else {
                    $lprocesaFormulario = false;
                    $data['msjErrorImagen'] = "* El archivo subido no es una imagen";
                }
            }

            if ($data['proyecto']['titulo'] == '') {
                $lprocesaFormulario = false;
                $data['proyecto']['msjErrorTitulo'] = "* El título no puede estar vacío";
            }

            if ($data['proyecto']['tecnologias'] == '') {
                $lprocesaFormulario = false;
                $data['proyecto']['msjErrorTecnologias'] = "* Las tecnologías no pueden estar vacías";
            }

            // Validación de campos SKILLS

            if (empty($data['skill']['habilidades'])) {
                $data['skill']['msjErrorHabilidades'] = 'Debes introducir al menos una habilidad';
                $lprocesaFormulario = false;
            } 

            // Validación de campos redes sociales

            if ($data['redsocial']['redes_sociales'] == '') {
                $data['redsocial']['msjErrorNombre'] = 'El nombre no puede estar vacío';
                $lprocesaFormulario = false;
            }

            if ($data['redsocial']['url'] == '') {
                $data['redsocial']['msjErrorUrl'] = 'La URL no puede estar vacía';
                $lprocesaFormulario = false;
            }
        }

        if($lprocesaFormulario) {
            // Creamos instancias de los modelos a usar
            $trabajo = Trabajos::getInstancia();
            $proyecto = Proyectos::getInstancia();
            $skills = Skills::getInstancia();
            $redes = RedesSociales::getInstancia();
            $portfolio = Portfolios::getInstancia();

            // Añadimos los datos a la base de datos
            // Añadimos los datos a la base de datos usando setters individuales
            $trabajo->setTitulo($data['trabajo']['titulo']);
            $trabajo->setDescripcion($data['trabajo']['descripcion']);
            $trabajo->setFechaInicio($data['trabajo']['fecha_inicio']);
            $trabajo->setFechaFinal($data['trabajo']['fecha_final']);
            $trabajo->setLogros($data['trabajo']['logros']);
            $trabajo->setVisible($data['trabajo']['visible']);
            $trabajo->setUsuariosId($userId);
            $trabajo->set();

            $proyecto->setTitulo($data['proyecto']['titulo']);
            $proyecto->setLogo($data['picture']['name']);
            $proyecto->setTecnologias($data['proyecto']['tecnologias']);
            $proyecto->setVisible($data['proyecto']['visible']);
            $proyecto ->setUsuariosId($userId);
            $proyecto->set();

            $skills->setHabilidades($data['skill']['habilidades']);
            $skills->setVisible($data['skill']['visible']);
            $skills->setCategoriasSkillsCategoria($data['skill']['categorias_skills_categoria']);
            $skills->setUsuariosId($userId);
            $skills->set();

            $redes->setRedesSociales($data['redsocial']['redes_sociales']);
            $redes->setUrl($data['redsocial']['url']);
            $redes->setUsuariosId($userId);
            $redes->set();

            $portfolio->changeVisibility($userId, $data['isVisible']);

            // Movemos el archivo a la carpeta de imágenes
            move_uploaded_file($data['picture']['tmp_name'], dirname(__DIR__, 2) . '/public/img/' . $data['picture']['name']);

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