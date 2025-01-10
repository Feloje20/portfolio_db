<?php

namespace App\Controllers;
use App\Models\Usuarios;
use App\Models\Portfolios;

class PortfolioController extends BaseController
{
    // Función que muestra el portfolio de un usuario
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

    // Función que muestra solo los formularios con las visibilidad activa
    public function viewPublicPortfoliosAction()
    {
        // Creamos una instancia de usuarios
        $portfolio = Portfolios::getInstancia();

        // Alamacenamos los datos en $data
        $data['usuarios'] = $portfolio->getPublic();

        // Llamamos a la función renderHTML
        $this->renderHTML('../app/views/index_test.php', $data);
    }
}