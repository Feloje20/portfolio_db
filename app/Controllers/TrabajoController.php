<?php

namespace App\Controllers;
use App\Models\Trabajos;
use App\Models\Portfolios;

class TrabajoController extends BaseController
{
    // Método para crear un trabajo
    public function createAction() {
        session_start();
        $trabajo = Trabajos::getInstancia();
        $portfolio = Portfolios::getInstancia();

        // Recuperamos la información mediante la ruta
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode('/', $url);

        // Extraemos la información de la ruta
        $userId = $urlParts[3];

        // Comprobamos si estamos recibiendo un post
        if (isset($_POST['modificar'])) {
            // COMPROBAR SI SE HA DEJADO ALGÚN CAMPO VACÍO************************************************
            $trabajo->setTitulo($_POST['trabajos']['titulo']);
            $trabajo->setDescripcion($_POST['trabajos']['descripcion']);
            $trabajo->setFechaInicio($_POST['trabajos']['fecha_inicio']);
            $trabajo->setFechaFinal($_POST['trabajos']['fecha_final']);
            $trabajo->setLogros($_POST['trabajos']['logros']);
            $trabajo->setVisible(isset($_POST['trabajos']['visible']) ? 1 : 0);
            $trabajo->setUsuariosId($userId);
            $trabajo->set();
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
            $data['tipo'] = 'trabajo';
            $this->renderHTML('../app/views/editarFormulario.php', $data);
        } else {
            header('Location: /');
            exit();
        }
    }

    // Método que cambia la visibilidad de un trabajo
    public function changeVisibilityAction() {
        session_start();
        $trabajo = Trabajos::getInstancia();
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
            $trabajo->setVisible($visibility ? 0 : 1);
            $trabajo->changeVisibility($id, $userId);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            header('Location: /');
            exit();
        }
    }

    // Método para editar un trabajo
    public function editAction() {
        session_start();
        $trabajo = Trabajos::getInstancia();
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
            $trabajo->setId($id);
            $trabajo->setTitulo($_POST['trabajos']['titulo']);
            $trabajo->setDescripcion($_POST['trabajos']['descripcion']);
            $trabajo->setFechaInicio($_POST['trabajos']['fecha_inicio']);
            $trabajo->setFechaFinal($_POST['trabajos']['fecha_final']);
            $trabajo->setLogros($_POST['trabajos']['logros']);
            $trabajo->setVisible(isset($_POST['trabajos']['visible']) ? 1 : 0);
            $trabajo->setUsuariosId($userId);
            $trabajo->edit();
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
            $data = $trabajo->get($id);
            $data['tipo'] = 'trabajo';
            $this->renderHTML('../app/views/editarFormulario.php', $data);
        } else {
            header('Location: /');
            exit();
        }
    }

    // Método para eliminar un trabajo
    public function deleteAction() {
        session_start();
        $trabajo = Trabajos::getInstancia();
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
            $trabajo->setId($id);
            $trabajo->delete();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            header('Location: /');
            exit();
        }
    }
}