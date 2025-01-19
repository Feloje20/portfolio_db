<?php
// requreimos el bootstrap y el autoload para la carga automatica de clases
require_once "../boosttrap.php";
require_once "../vendor/autoload.php";
require_once "../app/Controllers/UsuarioController.php";

// Usamos el espacio de nombre
use App\Core\Router;
use App\Controllers\UsuarioController;
use App\Controllers\PortfolioController;

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
