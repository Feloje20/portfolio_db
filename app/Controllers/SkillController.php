<?php

namespace App\Controllers;
use App\Models\Skills;
use App\Models\Portfolios;

class SkillController extends BaseController
{
    // Método para crear un trabajo
    public function create() {
        session_start();
        $skill = Skills::getInstancia();
        $portfolio = Portfolios::getInstancia();

        // Recuperamos la información mediante la ruta
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode('/', $url);

        // Extraemos la información de la ruta
        $userId = $urlParts[3];

        // Comprobamos si estamos recibiendo un post
        if (isset($_POST['modificar'])) {
            // COMPROBAR SI SE HA DEJADO ALGÚN CAMPO VACÍO************************************************
            $skill->sethabilidades($_POST['skills']['habilidades']);
            $skill->setCategoriasSkillsCategoria($_POST['skills']['categoria']);
            $skill->setVisible(isset($_POST['skills']['visible']) ? 1 : 0);
            $skill->setUsuariosId($userId);
            $skill->set();
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
            $data['tipo'] = 'skill';
            $data['categorias'] = $skill->getSkillsCategories();
            $this->renderHTML('../app/views/editarFormulario.php', $data);
        } else {
            header('Location: /');
            exit();
        }
    }

    // Método que cambia la visibilidad de un trabajo
    public function changeVisibility() {
        session_start();
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
    public function edit() {
        session_start();
        $skill = Skills::getInstancia();
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
            $skill->sethabilidades($_POST['skills']['habilidades']);
            $skill->setcategorias_skills_categoria($_POST['skills']['categoria']);
            $skill->setVisible(isset($_POST['skills']['visible']) ? 1 : 0);
            $skill->setUsuariosId($userId);
            $skill->edit();
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
            $data = $skill->get($id);
            $data['tipo'] = 'skill';
            $data['categorias'] = $skill->getSkillsCategories();
            $this->renderHTML('../app/views/editarFormulario.php', $data);
        } else {
            header('Location: /');
            exit();
        }
    }

    // Método para eliminar un trabajo
    public function delete() {
        session_start();
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