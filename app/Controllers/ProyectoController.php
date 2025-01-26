<?php

namespace App\Controllers;
use App\Models\Proyectos;
use App\Models\Portfolios;

class ProyectoController extends BaseController
{
    // Método para crear un trabajo
    public function create() {
        session_start();
        $proyecto = Proyectos::getInstancia();
        $portfolio = Portfolios::getInstancia();

        // Recuperamos la información mediante la ruta
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode('/', $url);

        // Extraemos la información de la ruta
        $userId = $urlParts[3];

        // Comprobamos si estamos recibiendo un post
        if (isset($_POST['modificar'])) {
            // COMPROBAR SI SE HA DEJADO ALGÚN CAMPO VACÍO************************************************
            // CAMBIAR EL LOGO POR UNA IMAGEN DE VERDAD***************************************************
            $proyecto->setTitulo($_POST['proyectos']['titulo']);
            $proyecto->setLogo($_POST['proyectos']['logo']);
            $proyecto->setTecnologias($_POST['proyectos']['tecnologias']);
            $proyecto->setVisible(isset($_POST['proyectos']['visible']) ? 1 : 0);
            $proyecto->setUsuariosId($userId);
            $proyecto->set();
            header('Location: /edit/' . $userId);
            exit();
        }

        if (isset($_POST['cancelar'])) {
            header('Location: /edit/' . $userId);
            exit();
        }

        // Si el usuario que accede a la ruta no es el dueño del portfolio o es administrador, redirigimos a la página principal
        $userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : null;
        $userProfile = isset($_SESSION['perfil']) ? $_SESSION['perfil'] : null;

        if ($portfolio->isOwner($userId, $userEmail) || $userProfile === 'admin') {
            $data['tipo'] = 'proyecto';
            $this->renderHTML('../app/views/editarFormulario.php', $data);
        } else {
            header('Location: /');
            exit();
        }
    }

    // Método que cambia la visibilidad de un trabajo
    public function changeVisibility() {
        session_start();
        $proyecto = Proyectos::getInstancia();
        $portfolio = Portfolios::getInstancia();

        // Recuperamos la información mediante la ruta
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode('/', $url);

        // Extraemos la información de la ruta
        $visibility = $urlParts[2] == 'visibilityRow' ? 1 : 0;
        $userId = $urlParts[3];
        $id = $urlParts[4];

        // Si el usuario que accede a la ruta no es el dueño del portfolio o es administrador, redirigimos a la página principal
        $userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : null;
        $userProfile = isset($_SESSION['perfil']) ? $_SESSION['perfil'] : null;

        if ($portfolio->isOwner($userId, $userEmail) || $userProfile === 'admin') {
            $proyecto->setVisible($visibility ? 0 : 1);
            $proyecto->changeVisibility($id, $userId);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            header('Location: /');
            exit();
        }
    }

    // Método para editar un trabajo
    public function edit() {
        session_start();
        $proyecto = Proyectos::getInstancia();
        $portfolio = Portfolios::getInstancia();

        // Recuperamos la información mediante la ruta
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode('/', $url);

        // Extraemos la información de la ruta
        $userId = $urlParts[3];
        $id = $urlParts[4];

        // Comprobamos si estamos recibiendo un post
        if (isset($_POST['modificar'])) {
            // COMPROBAR SI SE HA DEJADO ALGÚN CAMPO VACÍO************************************************
            $proyecto->setId($id);
            $proyecto->setTitulo($_POST['proyectos']['titulo']);
            $proyecto->setLogo($_POST['proyectos']['logo']);
            $proyecto->setTecnologias($_POST['proyectos']['tecnologias']);
            $proyecto->setVisible(isset($_POST['proyectos']['visible']) ? 1 : 0);
            $proyecto->setUsuariosId($userId);
            $proyecto->edit();
            header('Location: /edit/' . $userId);
            exit();
        }

        if (isset($_POST['cancelar'])) {
            header('Location: /edit/' . $userId);
            exit();
        }

        // Si el usuario que accede a la ruta no es el dueño del portfolio o es administrador, redirigimos a la página principal
        $userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : null;
        $userProfile = isset($_SESSION['perfil']) ? $_SESSION['perfil'] : null;

        if ($portfolio->isOwner($userId, $userEmail) || $userProfile === 'admin') {
            $data = $proyecto->get($id);
            $data['tipo'] = 'proyecto';
            $this->renderHTML('../app/views/editarFormulario.php', $data);
        } else {
            header('Location: /');
            exit();
        }
    }

    // Método para eliminar un trabajo
    public function delete() {
        session_start();
        $proyecto = Proyectos::getInstancia();
        $portfolio = Portfolios::getInstancia();

        // Recuperamos la información mediante la ruta
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode('/', $url);

        // Extraemos la información de la ruta
        $userId = $urlParts[3];
        $id = $urlParts[4];

        // Si el usuario que accede a la ruta no es el dueño del portfolio o es administrador, redirigimos a la página principal
        $userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : null;
        $userProfile = isset($_SESSION['perfil']) ? $_SESSION['perfil'] : null;

        if ($portfolio->isOwner($userId, $userEmail) || $userProfile === 'admin') {
            $proyecto->setId($id);
            $proyecto->delete();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            header('Location: /');
            exit();
        }
    }
}