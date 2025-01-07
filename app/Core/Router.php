<?php

namespace App\Core;

class Router
{
    // Creación de array de rutas, vacío
    private $routes = array();
    
    public function add($route )
    {
        $this->routes[] = $route; // Añadimos una ruta al array de rutas.
    }

    public function match(string $request)
    {
        $matches = array();
        foreach ($this->routes as $route) {
            $patron=$route['path'];
            if (preg_match($patron, $request)){
                $matches = $route;
            }
        }
        return $matches;
    }
}