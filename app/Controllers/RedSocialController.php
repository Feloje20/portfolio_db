<?php

namespace App\Controllers;
use App\Models\RedesSociales;
use App\Models\Portfolios;

class RedSocialController extends BaseController
{
    // Método para crear un trabajo
    public function createAction() {
        $redSocial = RedesSociales::getInstancia();
        $portfolio = Portfolios::getInstancia();

        // Recuperamos la información mediante la ruta
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode('/', $url);

        // Extraemos la información de la ruta
        $userId = $urlParts[3];

        // Si el usuario que accede a la ruta no es el dueño del portfolio o es administrador, redirigimos a la página principal
        $userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : null;
        $userProfile = isset($_SESSION['perfil']) ? $_SESSION['perfil'] : null;

        if (!($portfolio->isOwner($userId, $userEmail)) && !($userProfile === 'admin')) {
            header('Location: /');
            exit();
        }

        // Comprobamos si estamos recibiendo un post
        if (isset($_POST['modificar'])) {
            // COMPROBAR SI SE HA DEJADO ALGÚN CAMPO VACÍO************************************************
            $redSocial->setRedesSociales($_POST['redes_sociales']['nombre']);
            $redSocial->setUrl($_POST['redes_sociales']['enlace']);
            $redSocial->setUsuariosId($userId);
            $redSocial->set();
            header('Location: /edit/' . $userId);
            exit();
        }

        if (isset($_POST['cancelar'])) {
            header('Location: /edit/' . $userId);
            exit();
        }

        $data['tipo'] = 'red social';
        $data['accion'] = 'crear';
        $this->renderHTML('../app/views/editarFormulario.php', $data);
    }

    // Método para editar un trabajo
    public function editAction() {
        $redSocial = RedesSociales::getInstancia();
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

        if (!($portfolio->isOwner($userId, $userEmail)) && !($userProfile === 'admin')) {
            header('Location: /');
            exit();
        }

        // Comprobamos si estamos recibiendo un post
        if (isset($_POST['modificar'])) {
            // COMPROBAR SI SE HA DEJADO ALGÚN CAMPO VACÍO************************************************
            $redSocial->setId($id);
            $redSocial->setRedesSociales($_POST['redes_sociales']['nombre']);
            $redSocial->setUrl($_POST['redes_sociales']['enlace']);
            $redSocial->setUsuariosId($userId);
            $redSocial->edit();
            header('Location: /edit/' . $userId);
            exit();
        }

        if (isset($_POST['cancelar'])) {
            header('Location: /edit/' . $userId);
            exit();
        }

        $data = $redSocial->get($id);
        $data['tipo'] = 'red social';
        $data['accion'] = 'editar';
        $this->renderHTML('../app/views/editarFormulario.php', $data);
    }

    // Método para eliminar un trabajo
    public function deleteAction() {
        $redSocial = RedesSociales::getInstancia();
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
            $redSocial->setId($id);
            $redSocial->delete();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            header('Location: /');
            exit();
        }
    }
}