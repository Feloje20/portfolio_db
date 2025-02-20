<?php

namespace App\Controllers;
use App\Models\Skills;
use App\Models\Portfolios;

class SkillController extends BaseController
{
    // Método para crear un trabajo
    public function createAction() {
        $skill = Skills::getInstancia();
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
        $data['skill']['msjErrorHabilidades'] = '';

        // Comprobamos si estamos recibiendo un post
        if (isset($_POST['modificar'])) {
            $lprocesaFormulario = true;
            $data['skill']['habilidades'] = $this->sanearDatos($_POST['skills']['habilidades']);
            $data['skill']['categorias_skills_categoria'] = $_POST['skills']['categoria'];
            $data['skill']['visible'] = isset($_POST['skills']['visible']) ? 1 : 0;

            if (empty($data['skill']['habilidades'])) {
                $data['skill']['msjErrorHabilidades'] = 'Debes introducir al menos una habilidad';
                $lprocesaFormulario = false;
            } 
        }

        // En el caso de que no haya habido errores en el formulario, guardamos los datos en la base de datos.
        if ($lprocesaFormulario) {
            $skill->sethabilidades($data['skill']['habilidades']);
            $skill->setCategoriasSkillsCategoria($data['skill']['categorias_skills_categoria']);
            $skill->setVisible($data['skill']['visible']);
            $skill->setUsuariosId($userId);
            $skill->set();
            header('Location: /edit/' . $userId);
            exit();
        }

        if (isset($_POST['cancelar'])) {
            header('Location: /edit/' . $userId);
            exit();
        }

        $data['tipo'] = 'skill';
        $data['accion'] = 'crear';
        $data['categorias'] = $skill->getSkillsCategories();
        $this->renderHTML('../app/views/editarFormulario.php', $data);
    }

    // Método que cambia la visibilidad de un trabajo
    public function changeVisibilityAction() {
        $skill = Skills::getInstancia();
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
            $skill->setVisible($visibility ? 0 : 1);
            $skill->changeVisibility($id, $userId);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            header('Location: /');
            exit();
        }
    }

    // Método para editar un trabajo
    public function editAction() {
        $skill = Skills::getInstancia();
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
        $data['skill']['msjErrorHabilidades'] = '';

        // Comprobamos si estamos recibiendo un post
        if (isset($_POST['modificar'])) {
            $lprocesaFormulario = true;
            $data['skill']['habilidades'] = $this->sanearDatos($_POST['skills']['habilidades']);
            $data['skill']['categorias_skills_categoria'] = $_POST['skills']['categoria'];
            $data['skill']['visible'] = isset($_POST['skills']['visible']) ? 1 : 0;

            if (empty($data['skill']['habilidades'])) {
                $data['skill']['msjErrorHabilidades'] = 'Debes introducir al menos una habilidad';
                $lprocesaFormulario = false;
            } 
        } else {
            // Si no estamos recibiendo un post, recuperamos los datos de la base de datos
            $data['skill'] += $skill->get($id);
        }

        // En el caso de que no haya habido errores en el formulario, guardamos los datos en la base de datos.
        if ($lprocesaFormulario) {
            $skill->get($id);
            $skill->sethabilidades( $data['skill']['habilidades']);
            $skill->setCategoriasSkillsCategoria($data['skill']['categorias_skills_categoria']);
            $skill->setVisible($data['skill']['visible'] ? 1 : 0);
            $skill->setUsuariosId($userId);
            $skill->edit();
            header('Location: /edit/' . $userId);
            exit();
        }

        if (isset($_POST['cancelar'])) {
            header('Location: /edit/' . $userId);
            exit();
        }

        $data['tipo'] = 'skill';
        $data['accion'] = 'editar';
        $data['categorias'] = $skill->getSkillsCategories();
        $this->renderHTML('../app/views/editarFormulario.php', $data);
    }

    // Método para eliminar un trabajo
    public function deleteAction() {
        $skill = Skills::getInstancia();
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
            $skill->setId($id);
            $skill->delete();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            header('Location: /');
            exit();
        }
    }
}