<?php
// requreimos el bootstrap y el autoload para la carga automatica de clases
require_once "../boosttrap.php";
require_once "../vendor/autoload.php";
require_once "../app/Controllers/UsuarioController.php";

// Usamos el espacio de nombre
use App\Core\Router;
use App\Controllers\UsuarioController;
use App\Controllers\PortfolioController;
use App\Controllers\TrabajoController;
use App\Controllers\ProyectoController;

// Creamos una instancia de la clase Router
$router = new Router();

// Añadimos rutas al array
// Landing page con un buscador y todos los portfolios que sean públicos
$router->add([  'name' => 'Todos los usuarios',
                'path' => '/^\/$/',
                'action' => [PortfolioController::class, 'viewPublicPortfoliosAction']]);

                // Ruta de registro de usuarios nuevos
$router->add([  'name' => 'añadir',
                'path' => '/^\/usuarios\/add$/',
                'action' => [UsuarioController::class, 'AddAction']]);

                // Ruta de inicio de sesión de usuarios
$router->add([  'name' => 'Iniciar sesión de usuario',
                'path' => '/^\/login$/',
                'action' => [UsuarioController::class, 'LoginAction']]);

                // Ruta de cierre de sesión de usuarios
$router->add([  'name' => 'Cerrar sesión de usuario',
                'path' => '/^\/usuarios\/logout$/',
                'action' => [UsuarioController::class, 'LogoutAction']]);

                // Ruta para crear un portfolio.
$router->add([  'name' => 'Crear un portfolio nuevo',
                'path' => '/^\/newPortfolio$/',
                'action' => [PortfolioController::class, 'newPortfolio']]);

                // Ruta de ver portfolio individual.
$router->add([  'name' => 'Ver portfolio',
                'path' => '/^\/view\/([a-zA-Z0-9_-]+)$/',
                'action' => [PortfolioController::class, 'viewPortfolio']]);

                // Ruta para permitir la modificación del portfolio al dueño y al administrador.
$router->add([  'name' => 'Modificar portfolio',
                'path' => '/^\/edit\/([a-zA-Z0-9_-]+)$/',
                'action' => [PortfolioController::class, 'editPortfolio']]);

                // Ruta para permitir la eliminación del portfolio al dueño y al administrador.

                // Rutas para cambiar las tablas trabajos, proyectos, skills, redes_sociales
                // --------------------TRABAJOS------------------------------
                // Ruta de modificación de la visibilidad de los trabajos
$router->add([  'name' => 'Cambiar visibilidad de trabajos',
                'path' => '/^\/trabajo\/(no)?visibilityRow\/([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)$/',
                'action' => [TrabajoController::class, 'changeVisibility']]);

                // Ruta de modificación de los datos de los trabajos
$router->add([  'name' => 'Cambiar datos de trabajos',
                'path' => '/^\/trabajo\/editRow\/([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)$/',
                'action' => [TrabajoController::class, 'edit']]);

                // Ruta de eliminación de trabajos
$router->add([  'name' => 'Eliminar un trabajo',
                'path' => '/^\/trabajo\/delRow\/([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)$/',
                'action' => [TrabajoController::class, 'delete']]);

                // Ruta de adición de trabajo
$router->add([  'name' => 'Crear un nuevo trabajo',
                'path' => '/^\/trabajo\/new\/([a-zA-Z0-9_-]+)$/',
                'action' => [TrabajoController::class, 'create']]);

                // --------------------PROYECTOS------------------------------
                // Ruta de modificación de la visibilidad de los proyectos
$router->add([  'name' => 'Cambiar visibilidad de proyectos',
                'path' => '/^\/proyecto\/(no)?visibilityRow\/([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)$/',
                'action' => [ProyectoController::class, 'changeVisibility']]);

                // Ruta de modificación de los datos de los proyectos
$router->add([  'name' => 'Cambiar datos de proyectos',
                'path' => '/^\/proyecto\/editRow\/([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)$/',
                'action' => [ProyectoController::class, 'edit']]);

                // Ruta de eliminación de proyectos
$router->add([  'name' => 'Eliminar un proyecto',
                'path' => '/^\/proyecto\/delRow\/([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)$/',
                'action' => [ProyectoController::class, 'delete']]);

                // Ruta de adición de proyecto
$router->add([  'name' => 'Crear un nuevo proyecto',
                'path' => '/^\/proyecto\/new\/([a-zA-Z0-9_-]+)$/',
                'action' => [ProyectoController::class, 'create']]);

//$request = $_SERVER['REQUEST_URI'];
// Esto limpia la ruta de la petición
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = $router->match($request); // Comprobamos que coincide una ruta

if($route){
    $controllerName = $route['action'][0];
    $actionName = $route['action'][1];
    $controller = new $controllerName;
    $controller->$actionName($request);
}else{
    echo "No route";
}
