<?php
// requreimos el bootstrap y el autoload para la carga automatica de clases
require_once "../boosttrap.php";
require_once "../vendor/autoload.php";
require_once "../app/Controllers/UsuarioController.php";

// Usamos el espacio de nombre
use App\Core\Router;
use App\Controllers\UsuarioController;

// Creamos una instancia de la clase Router
$router = new Router();

// Añadimos rutas al array
$router->add([  'name' => 'Todos los usuarios',
                'path' => '/^\/$/',
                'action' => [UsuarioController::class, 'IndexAction']]);

$router->add([  'name' => 'añadir',
                'path' => '/^\/usuarios\/add$/',
                'action' => [UsuarioController::class, 'AddAction']]);

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
