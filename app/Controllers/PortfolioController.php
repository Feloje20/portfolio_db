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
        $trabajo = Trabajos::getInstancia();
        $proyecto = Proyectos::getInstancia();
        $skills = Skills::getInstancia();
        $redesSociales = RedesSociales::getInstancia();
        $portfolio = Portfolios::getInstancia();

        // Iniciamos la sesión
        session_start();

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
            $data['trabajos'] = $trabajo->getTrabajosVisibles($id);
            $data['proyectos'] = $proyecto->getProyectosVisibles($id);
            $data['skills'] = $skills->getSkillsVisibles($id);
            $data['redesSociales'] = $redesSociales->getRedesSocialesById($id);

            // Llamamos a la función renderHTML
            $this->renderHTML('../app/views/portfolio.php', $data);
        } 
    }

    // Función que muestra solo los formularios con las visibilidad activa
    public function viewPublicPortfoliosAction()
    {
        // Creamos una instancia de usuarios
        $portfolio = Portfolios::getInstancia();

        // Comprobamos si se ha enviado un formulario de búsqueda
        if (isset($_POST['query'])) {
            // Almacenamos los datos en $data
            $data['usuarios'] = $portfolio->searchPortfolios($_POST['query']);
        } else {
            // Almacenamos los datos en $data
            $data['usuarios'] = $portfolio->getPublic();
        }

        // Llamamos a la función renderHTML
        $this->renderHTML('../app/views/index_test.php', $data);
    }

    // Función que muestra el formulario para crear un nuevo portfolio
    public function newPortfolioAction() {
        session_start();
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
            $proyectoLogo = $this->sanearDatos($_POST['proyectos']['logo']);
            $proyectoTecnologias = $this->sanearDatos($_POST['proyectos']['tecnologias']);
            $proyectoVisible = $_POST['proyectos']['visible'] ?? 0;

            // Skills
            $skillsHabilidades = $this->sanearDatos($_POST['skills']['habilidades']);
            $skillsVisible = $_POST['skills']['visible'] ?? 0;
            $skillsCategoria = $_POST['skills']['categoria'];

            // Redes sociales
            $redesNombre = $this->sanearDatos($_POST['redes_sociales']['nombre']);
            $redesUrl = $this->sanearDatos($_POST['redes_sociales']['enlace']);
            
            // COMPROBAR ERRORES EN LOS CAMPOS ****************************************************

            // Creamos instancias de los modelos a usar
            $trabajo = Trabajos::getInstancia();
            $proyecto = Proyectos::getInstancia();
            $skills = Skills::getInstancia();
            $redes = RedesSociales::getInstancia();

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
            $proyecto->setLogo($proyectoLogo);
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
        session_start();

        $id = explode('/', $_SERVER['REQUEST_URI'])[2];

        // Si el usuario no está logeado, inicializamos la variable $userEmail a null
        $userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : null;
        
        // Si el usuario no está logeado, inicializamos la variable $userProfile a null
        $userProfile = isset($_SESSION['perfil']) ? $_SESSION['perfil'] : null;

        $portfolio = Portfolios::getInstancia();
        $usuario = Usuarios::getInstancia();
        $trabajo = Trabajos::getInstancia();
        $proyecto = Proyectos::getInstancia();
        $skills = Skills::getInstancia();
        $redesSociales = RedesSociales::getInstancia();

        // Comprobamos si el usuario está logeado y es dueño del portfolio o es administrador
        if ($portfolio->isOwner($id, $userEmail) || $userProfile === 'admin') {
            // Alamacenamos los datos en $data
            $data['usuario'] = $usuario->get($id);
            $data['trabajos'] = $trabajo->getAll($id);
            $data['proyectos'] = $proyecto->getAll($id);
            $data['skills'] = $skills->getAll($id);
            $data['redesSociales'] = $redesSociales->getRedesSocialesById($id);

            $this->renderHTML('../app/views/editarPortfolio.php', $data);
        } else {
            // Si no se cumplen las condiciones, volvemos a la página de inicio
            header('Location: /');
        }
    }

    // Método para borrar toda la información de un portfolio
    public function deletePortfolioAction() {
        session_start();

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