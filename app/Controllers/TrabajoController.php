<?php

namespace App\Controllers;
use App\Models\Trabajos;
use App\Models\Portfolios;

class TrabajoController extends BaseController
{
    // Método para crear un trabajo
    public function createAction() {
        $trabajo = Trabajos::getInstancia();
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
        $data['msjErrorTitulo'] = $data['msjErrorDescripcion'] = $data['msjErrorFechaInicial'] = $data['msjErrorFechaFinal'] = $data['msjErrorLogros'] = '';

        // Comprobamos si estamos recibiendo un post
        if (isset($_POST['modificar'])) {
            $lprocesaFormulario = true;
            $data['titulo'] = $this->sanearDatos($_POST['trabajos']['titulo']);
            $data['descripcion'] = $this->sanearDatos($_POST['trabajos']['descripcion']);
            $data['fecha_inicio'] = $_POST['trabajos']['fecha_inicio'];
            $data['fecha_final'] = $_POST['trabajos']['fecha_final'];
            $data['logros'] = $this->sanearDatos($_POST['trabajos']['logros']);

            // Validamos los campos
            if ($data['titulo'] == '') {
                $data['msjErrorTitulo'] = 'El título no puede estar vacío';
                $lprocesaFormulario = false;
            }

            if ($data['descripcion'] == '') {
                $data['msjErrorDescripcion'] = 'La descripción no puede estar vacía';
                $lprocesaFormulario = false;
            }

            if ($data['fecha_inicio'] == '') {
                $data['msjErrorFechaInicial'] = 'La fecha de inicio no puede estar vacía';
                $lprocesaFormulario = false;
            }

            if ($data['fecha_final'] == '') {
                $data['msjErrorFechaFinal'] = 'La fecha final no puede estar vacía';
                $lprocesaFormulario = false;
            }

            if ($data['logros'] == '') {
                $data['msjErrorLogros'] = 'Los logros no pueden estar vacíos';
                $lprocesaFormulario = false;
            }
        }

         // En el caso de que no haya habido errores en el formulario, guardamos los datos en la base de datos.
        if ($lprocesaFormulario) {
            $trabajo->setTitulo($data['titulo']);
            $trabajo->setDescripcion($data['descripcion']);
            $trabajo->setFechaInicio($data['fecha_inicio']);
            $trabajo->setFechaFinal($data['fecha_final']);
            $trabajo->setLogros($data['logros']);
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

        $data['tipo'] = 'trabajo';
        $data['accion'] = 'crear';
        $this->renderHTML('../app/views/editarFormulario.php', $data);
    }

    // Método que cambia la visibilidad de un trabajo
    public function changeVisibilityAction() {
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

        if (!($portfolio->isOwner($userId, $userEmail)) && !($userProfile === 'admin')) {
            header('Location: /');
            exit();
        }

        // Ahora que hemos validado que el usuario puede ejecutar esta acción, inicializamos variables.
        $lprocesaFormulario = false;
        $data = [];
        $data['msjErrorTitulo'] = $data['msjErrorDescripcion'] = $data['msjErrorFechaInicial'] = $data['msjErrorFechaFinal'] = $data['msjErrorLogros'] = '';

        // Comprobamos si estamos recibiendo un post
        if (isset($_POST['modificar'])) {
            $lprocesaFormulario = true;
            $data['titulo'] = $this->sanearDatos($_POST['trabajos']['titulo']);
            $data['descripcion'] = $this->sanearDatos($_POST['trabajos']['descripcion']);
            $data['fecha_inicio'] = $_POST['trabajos']['fecha_inicio'];
            $data['fecha_final'] = $_POST['trabajos']['fecha_final'];
            $data['logros'] = $this->sanearDatos($_POST['trabajos']['logros']);

            // Validamos los campos
            if ($data['titulo'] == '') {
                $data['msjErrorTitulo'] = 'El título no puede estar vacío';
                $lprocesaFormulario = false;
            }

            if ($data['descripcion'] == '') {
                $data['msjErrorDescripcion'] = 'La descripción no puede estar vacía';
                $lprocesaFormulario = false;
            }

            if ($data['fecha_inicio'] == '') {
                $data['msjErrorFechaInicial'] = 'La fecha de inicio no puede estar vacía';
                $lprocesaFormulario = false;
            }

            if ($data['fecha_final'] == '') {
                $data['msjErrorFechaFinal'] = 'La fecha final no puede estar vacía';
                $lprocesaFormulario = false;
            }

            if ($data['logros'] == '') {
                $data['msjErrorLogros'] = 'Los logros no pueden estar vacíos';
                $lprocesaFormulario = false;
            }
        }

        if ($lprocesaFormulario) {
            $trabajo->setId($id);
            $trabajo->setTitulo($data['titulo']);
            $trabajo->setDescripcion($data['descripcion']);
            $trabajo->setFechaInicio($data['fecha_inicio']);
            $trabajo->setFechaFinal($data['fecha_final']);
            $trabajo->setLogros($data['logros']);
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

        $data += $trabajo->get($id);
        $data['tipo'] = 'trabajo';
        $data['accion'] = 'editar';
        $this->renderHTML('../app/views/editarFormulario.php', $data);
    }

    // Método para eliminar un trabajo
    public function deleteAction() {
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