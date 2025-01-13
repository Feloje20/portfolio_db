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
        $portfolio = Portfolios::getInstancia();

        // Tomamos la id del usuario de la URL
        $id = explode('/', $_SERVER['REQUEST_URI'])[2];

        // Comprobamos si el portfolio del usuario tiene la visibilidad activa
        if ($portfolio->checkVisibility($id) == 0) {
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
}