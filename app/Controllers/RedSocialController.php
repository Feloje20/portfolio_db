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

        // Ahora que hemos validado que el usuario puede ejecutar esta acción, inicializamos variables.
        $lprocesaFormulario = false;
        $data = [];
        $data['redsocial']['msjErrorNombre'] = $data['redsocial']['msjErrorUrl'] = '';

        // Comprobamos si estamos recibiendo un post
        if (isset($_POST['modificar'])) {
            // Inicializamos variables del formulario saneando los datos
            $lprocesaFormulario = true;
            $data['redsocial']['redes_sociales'] = $this->sanearDatos($_POST['redes_sociales']['nombre']);
            $data['redsocial']['url'] = $this->sanearDatos($_POST['redes_sociales']['enlace']);

            // Comprobamos si hay campos vacíos
            if (empty($data['redsocial']['redes_sociales'])) {
                $data['redsocial']['msjErrorNombre'] = 'El nombre de la red social no puede estar vacío';
                $lprocesaFormulario = false;
            }

            if (empty($data['redsocial']['url'])) {
                $data['redsocial']['msjErrorUrl'] = 'La URL de la red social no puede estar vacía';
                $lprocesaFormulario = false;
            }
        }

        // Si se han validado los datos del formulario, creamos la red social.
        if($lprocesaFormulario) {
            $redSocial->setRedesSociales($data['redsocial']['redes_sociales']);
            $redSocial->setUrl($data['redsocial']['url']);
            $redSocial->setUsuariosId($userId);
            $redSocial->set();
            header('Location: /edit/' . $userId);
            exit();
        }

        // Si el usuario clicka el botón cancelar, lo devolvemos a la página de edición.
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

        // Ahora que hemos validado que el usuario puede ejecutar esta acción, inicializamos variables.
        $lprocesaFormulario = false;
        $data = [];
        $data['redsocial']['msjErrorNombre'] = $data['redsocial']['msjErrorUrl'] = '';

        // Comprobamos si estamos recibiendo un post
        if (isset($_POST['modificar'])) {
            // Inicializamos variables del formulario saneando los datos
            $lprocesaFormulario = true;
            $data['redsocial']['redes_sociales'] = $this->sanearDatos($_POST['redes_sociales']['nombre']);
            $data['redsocial']['url'] = $this->sanearDatos($_POST['redes_sociales']['enlace']);

            // Comprobamos si hay campos vacíos
            if (empty($data['redsocial']['redes_sociales'])) {
                $data['redsocial']['msjErrorNombre'] = 'El nombre de la red social no puede estar vacío';
                $lprocesaFormulario = false;
            }

            if (empty($data['redsocial']['url'])) {
                $data['redsocial']['msjErrorUrl'] = 'La URL de la red social no puede estar vacía';
                $lprocesaFormulario = false;
            }
        } else {
            // Si no estamos recibiendo un post, recuperamos los datos de la red social
            $data['redsocial'] += $redSocial->get($id);
        }

        // Si se han validado los datos del formulario, modificamos la red social.
        if($lprocesaFormulario) {
            $redSocial->setId($id);
            $redSocial->setRedesSociales($data['redsocial']['redes_sociales']);
            $redSocial->setUrl($data['redsocial']['url']);
            $redSocial->setUsuariosId($userId);
            $redSocial->edit();
            header('Location: /edit/' . $userId);
            exit();
        }

        // Si el usuario clicka el botón cancelar, lo devolvemos a la página de edición.
        if (isset($_POST['cancelar'])) {
            header('Location: /edit/' . $userId);
            exit();
        }

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