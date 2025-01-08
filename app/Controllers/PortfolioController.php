<?php

namespace App\Controllers;
use App\Models\Usuarios;

class PortfolioController extends BaseController
{
    public function viewPortfolio()
    {
        // Creamos una instancia de usuarios
        $usuario = Usuarios::getInstancia();

        // Tomamos la id del usuario de la URL
        $id = explode('/', $_SERVER['REQUEST_URI'])[2];

        // Alamacenamos los datos en $data
        $data['usuario'] = $usuario->get($id);

        // Llamamos a la función renderHTML
        $this->renderHTML('../app/views/portfolio.php', $data);
    }
}