<?php

namespace App\Controllers;
use App\Models\RedesSociales;
use App\Models\Portfolios;

class RedSocialController extends BaseController
{
    // Método para crear un trabajo
    public function createAction() {
        session_start();
        $redSocial = RedesSociales::getInstancia();
        $portfolio = Portfolios::getInstancia();

        // Recuperamos la información mediante la ruta
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode('/', $url);

        // Extraemos la información de la ruta
        $userId = $urlParts[3];

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

        // Si el usuario que accede a la ruta no es el dueño del portfolio o es administrador, redirigimos a la página principal
        $userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : null;
        $userProfile = isset($_SESSION['perfil']) ? $_SESSION['perfil'] : null;

        if ($portfolio->isOwner($userId, $userEmail) || $userProfile === 'admin') {
            $data['tipo'] = 'red_social';
            $this->renderHTML('../app/views/editarFormulario.php', $data);
        } else {
            header('Location: /');
            exit();
        }
    }

    // Método para editar un trabajo
    public function editAction() {
        session_start();
        $redSocial = RedesSociales::getInstancia();
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

        // Si el usuario que accede a la ruta no es el dueño del portfolio o es administrador, redirigimos a la página principal
        $userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : null;
        $userProfile = isset($_SESSION['perfil']) ? $_SESSION['perfil'] : null;

        if ($portfolio->isOwner($userId, $userEmail) || $userProfile === 'admin') {
            $data = $redSocial->get($id);
            $data['tipo'] = 'red_social';
            $this->renderHTML('../app/views/editarFormulario.php', $data);
        } else {
            header('Location: /');
            exit();
        }
    }

    // Método para eliminar un trabajo
    public function deleteAction() {
        session_start();
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