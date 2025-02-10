<?php
// requreimos el bootstrap y el autoload para la carga automatica de clases
require_once "../boosttrap.php";
require_once "../vendor/autoload.php";
require_once "../app/Controllers/UsuarioController.php";

// Inicio sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Usamos el espacio de nombre
use App\Core\Router;
use App\Controllers\UsuarioController;
use App\Controllers\PortfolioController;
use App\Controllers\TrabajoController;
use App\Controllers\ProyectoController;
use App\Controllers\SkillController;
use App\Controllers\RedSocialController;

// Creamos una instancia de la clase Router
$router = new Router();

// Añadimos rutas al array
                // --------------------USUARIOS------------------------------
                // Ruta de registro de usuarios nuevos
$router->add([  'name' => 'añadir',
                'path' => '/^\/usuarios\/add$/',
                'action' => [UsuarioController::class, 'AddAction']]);

$router->add([  'name' => 'verificar usuario',
                'path' => '/^\/usuarios\/verificar\/(.+)$/',
                'action' => [UsuarioController::class, 'VerificarAction']]);

                // Ruta de inicio de sesión de usuarios
$router->add([  'name' => 'Iniciar sesión de usuario',
                'path' => '/^\/login$/',
                'action' => [UsuarioController::class, 'LoginAction']]);

                // Ruta de cierre de sesión de usuarios
$router->add([  'name' => 'Cerrar sesión de usuario',
                'path' => '/^\/usuarios\/logout$/',
                'action' => [UsuarioController::class, 'LogOutAction'],
                'perfil' => ['admin', 'usuario']]);

                // Ruta de edición de usuarios
$router->add([  'name' => 'Editar usuario',
                'path' => '/^\/editUser\/([a-zA-Z0-9_-]+)$/',
                'action' => [UsuarioController::class, 'ModifyAction'],
                'perfil' => ['admin', 'usuario']]);

                // --------------------PORTFOLIOS------------------------------
                // Landing page con un buscador y todos los portfolios que sean públicos
$router->add([  'name' => 'Todos los usuarios',
                'path' => '/^\/$/',
                'action' => [PortfolioController::class, 'viewPublicPortfoliosAction']]);

                // Landing page pero con los portfolios que cumplen la búsqueda del usuario.
$router->add([  'name' => 'Búsqueda de portfolios con query',
                'path' => '/^\/search$/',
                'action' => [PortfolioController::class, 'searchAction']]);

                // Ruta para crear un portfolio.
$router->add([  'name' => 'Crear un portfolio nuevo',
                'path' => '/^\/newPortfolio$/',
                'action' => [PortfolioController::class, 'newPortfolioAction'],
                'perfil' => ['usuario']]);

                // Ruta de ver portfolio individual.
$router->add([  'name' => 'Ver portfolio',
                'path' => '/^\/view\/([a-zA-Z0-9_-]+)$/',
                'action' => [PortfolioController::class, 'viewPortfolioAction']]);

                // Ruta para permitir la modificación del portfolio al dueño y al administrador.
$router->add([  'name' => 'Modificar portfolio',
                'path' => '/^\/edit\/([a-zA-Z0-9_-]+)$/',
                'action' => [PortfolioController::class, 'editPortfolioAction'],
                'perfil' => ['admin', 'usuario']]);

                // Ruta para permitir la eliminación del portfolio al dueño y al administrador.
$router->add([  'name' => 'Eliminar portfolio',
                'path' => '/^\/delete\/([a-zA-Z0-9_-]+)$/',
                'action' => [PortfolioController::class, 'deletePortfolioAction'],
                'perfil' => ['admin', 'usuario']]);

                // Rutas para cambiar las tablas trabajos, proyectos, skills, redes_sociales
                // --------------------TRABAJOS------------------------------
                // Ruta de modificación de la visibilidad de los trabajos
$router->add([  'name' => 'Cambiar visibilidad de trabajos',
                'path' => '/^\/trabajo\/(no)?visibilityRow\/([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)$/',
                'action' => [TrabajoController::class, 'changeVisibilityAction'],
                'perfil' => ['admin', 'usuario']]);

                // Ruta de modificación de los datos de los trabajos
$router->add([  'name' => 'Cambiar datos de trabajos',
                'path' => '/^\/trabajo\/editRow\/([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)$/',
                'action' => [TrabajoController::class, 'editAction'],
                'perfil' => ['admin', 'usuario']]);

                // Ruta de eliminación de trabajos
$router->add([  'name' => 'Eliminar un trabajo',
                'path' => '/^\/trabajo\/delRow\/([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)$/',
                'action' => [TrabajoController::class, 'deleteAction'],
                'perfil' => ['admin', 'usuario']]);

                // Ruta de adición de trabajo
$router->add([  'name' => 'Crear un nuevo trabajo',
                'path' => '/^\/trabajo\/new\/([a-zA-Z0-9_-]+)$/',
                'action' => [TrabajoController::class, 'createAction'],
                'perfil' => ['admin', 'usuario']]);

                // --------------------PROYECTOS------------------------------
                // Ruta de modificación de la visibilidad de los proyectos
$router->add([  'name' => 'Cambiar visibilidad de proyectos',
                'path' => '/^\/proyecto\/(no)?visibilityRow\/([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)$/',
                'action' => [ProyectoController::class, 'changeVisibilityAction'],
                'perfil' => ['admin', 'usuario']]);

                // Ruta de modificación de los datos de los proyectos
$router->add([  'name' => 'Cambiar datos de proyectos',
                'path' => '/^\/proyecto\/editRow\/([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)$/',
                'action' => [ProyectoController::class, 'editAction'],
                'perfil' => ['admin', 'usuario']]);

                // Ruta de eliminación de proyectos
$router->add([  'name' => 'Eliminar un proyecto',
                'path' => '/^\/proyecto\/delRow\/([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)$/',
                'action' => [ProyectoController::class, 'deleteAction'],
                'perfil' => ['admin', 'usuario']]);

                // Ruta de adición de proyecto
$router->add([  'name' => 'Crear un nuevo proyecto',
                'path' => '/^\/proyecto\/new\/([a-zA-Z0-9_-]+)$/',
                'action' => [ProyectoController::class, 'createAction'],
                'perfil' => ['admin', 'usuario']]);

                // --------------------SKILLS------------------------------
                // Ruta de modificación de la visibilidad de los proyectos
$router->add([  'name' => 'Cambiar visibilidad de skills',
                'path' => '/^\/skill\/(no)?visibilityRow\/([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)$/',
                'action' => [SkillController::class, 'changeVisibilityAction'],
                'perfil' => ['admin', 'usuario']]);

                // Ruta de modificación de los datos de los skills
$router->add([  'name' => 'Cambiar datos de skills',
                'path' => '/^\/skill\/editRow\/([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)$/',
                'action' => [SkillController::class, 'editAction'],
                'perfil' => ['admin', 'usuario']]);

                // Ruta de eliminación de skills
$router->add([  'name' => 'Eliminar un skill',
                'path' => '/^\/skill\/delRow\/([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)$/',
                'action' => [SkillController::class, 'deleteAction'],
                'perfil' => ['admin', 'usuario']]);

                // Ruta de adición de skill
$router->add([  'name' => 'Crear un nuevo skill',
                'path' => '/^\/skill\/new\/([a-zA-Z0-9_-]+)$/',
                'action' => [SkillController::class, 'createAction'],
                'perfil' => ['admin', 'usuario']]);

                // --------------------REDES SOCIALES------------------------------
                // Ruta de modificación de los datos de las redes sociales
$router->add([  'name' => 'Cambiar datos de redes sociales',
                'path' => '/^\/redsocial\/editRow\/([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)$/',
                'action' => [RedSocialController::class, 'editAction'],
                'perfil' => ['admin', 'usuario']]);

                // Ruta de eliminación de redes sociales
$router->add([  'name' => 'Eliminar una red social',
                'path' => '/^\/redsocial\/delRow\/([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)$/',
                'action' => [RedSocialController::class, 'deleteAction'],
                'perfil' => ['admin', 'usuario']]);

                // Ruta de adición de red social
$router->add([  'name' => 'Crear una nueva red social',
                'path' => '/^\/redsocial\/new\/([a-zA-Z0-9_-]+)$/',
                'action' => [RedSocialController::class, 'createAction'],
                'perfil' => ['admin', 'usuario']]);

//$request = $_SERVER['REQUEST_URI'];
// Esto limpia la ruta de la petición
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = $router->match($request); // Comprobamos que coincide una ruta

if($route){
    // Verifica si el perfil de la sesión coincide con algún perfil en route['perfil']
    if (isset($route['perfil']) && (!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], $route['perfil']))) {
        header('Location: /');
    } else {
        $controllerName = $route['action'][0];
        $actionName = $route['action'][1];
        $controller = new $controllerName;
        $controller->$actionName($request);
    }
}else{
    echo "No route";
}
