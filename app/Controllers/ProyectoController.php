<?php

namespace App\Controllers;
use App\Models\Proyectos;
use App\Models\Portfolios;

class ProyectoController extends BaseController
{
    // Método para crear un trabajo
    public function createAction() {
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
            $data['picture'] = $_FILES['proyecto_logo'];

            // Si no se ha subido una imagen, la imagen será la que hay por defecto "defaultPic.jpg".
            // En caso contrario procesamos la subida.
            if ($data['picture']['name'] == '') {
                $data['picture']['name'] = 'defaultLogo.png';
            } else if ($data['picture']['error'] == 0) {
                // Comprobamos si el archivo subido es una imagen
                if ($data['picture']['type'] == 'image/jpeg' || $data['picture']['type'] == 'image/png' || $data['picture']['type'] == 'image/PNG') {
                    // Comprobamos si el archivo subido no supera los 2MB
                    if ($data['picture']['size'] <= 2000000) {
                        // Generamos un nombre para la imagen al azar
                        // OPCIONAL GUARDAR SOLO EL UNIQID Y LA EXTENSIÓN ******************************
                        $data['picture']['name'] = 'logo_' . uniqid() . $data['picture']['name'];
                    } else {
                        $lprocesaFormulario = false;
                        $data['msjErrorImagen'] = "* La imagen no puede superar los 2MB";
                    }
                } else {
                    $lprocesaFormulario = false;
                    $data['msjErrorImagen'] = "* El archivo subido no es una imagen";
                }
            }

            // Movemos el archivo a la carpeta de imágenes
            move_uploaded_file($data['picture']['tmp_name'], dirname(__DIR__, 2) . '/public/img/' . $data['picture']['name']);

            // COMPROBAR SI SE HA DEJADO ALGÚN CAMPO VACÍO************************************************
            $proyecto->setTitulo($_POST['proyectos']['titulo']);
            $proyecto->setLogo($data['picture']['name']);
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
    public function changeVisibilityAction() {
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
    public function editAction() {
        session_start();
        $proyecto = Proyectos::getInstancia();
        $portfolio = Portfolios::getInstancia();

        // Recuperamos la información mediante la ruta
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode('/', $url);

        // Extraemos la información de la ruta
        $userId = $urlParts[3];
        $id = $urlParts[4];

        $proyecto->setId($id);
        $proyecto->get($id);

        // Comprobamos si estamos recibiendo un post
        if (isset($_POST['modificar'])) {
            // COMPROBAR SI SE HA DEJADO ALGÚN CAMPO VACÍO************************************************
            $data['picture'] = $_FILES['proyecto_logo'];

            if ($data['picture']['error'] == 0) {
                // Comprobamos si el archivo subido es una imagen
                if ($data['picture']['type'] == 'image/jpeg' || $data['picture']['type'] == 'image/png' || $data['picture']['type'] == 'image/PNG') {
                    // Comprobamos si el archivo subido no supera los 2MB
                    if ($data['picture']['size'] <= 2000000) {
                        // Generamos un nombre para la imagen al azar
                        // OPCIONAL GUARDAR SOLO EL UNIQID Y LA EXTENSIÓN ******************************
                        $data['picture']['name'] = 'logo_' . uniqid() . $data['picture']['name'];
                    } else {
                        $lprocesaFormulario = false;
                        $data['msjErrorImagen'] = "* La imagen no puede superar los 2MB";
                    }
                } else {
                    $lprocesaFormulario = false;
                    $data['msjErrorImagen'] = "* El archivo subido no es una imagen";
                }
            }

            // Si el usuario ha subido una imagen, borramos la antigua, subimos la nueva y la modificamos en la base de datos.
            if ($data['picture']['name'] != '') {
                
                // Si la imagen anterior es la imagen por defecto, no la borramos
                if ($proyecto->getLogo() != 'defaultLogo.png') {
                    unlink(dirname(__DIR__, 2) . '/public/img/' . $proyecto->getLogo());
                }
                move_uploaded_file($data['picture']['tmp_name'], dirname(__DIR__, 2) . '/public/img/' . $data['picture']['name']);
                $proyecto->setLogo($data['picture']['name']);
            } else {
                // En caso contrario, mantenemos la imagen que ya teníamos.
                $proyecto->setLogo($proyecto->getLogo());
            }

            $proyecto->setTitulo($_POST['proyectos']['titulo']);
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
    public function deleteAction() {
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
            $proyecto->get($id);

            // Borramos la imagen del proyecto del almacenamiento local si no era la imagen por defecto.
            if ($proyecto->getLogo() != 'defaultLogo.png') {
                unlink(dirname(__DIR__, 2) . '/public/img/' . $proyecto->getLogo());
            }

            // Borramos el proyecto
            $proyecto->delete();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            header('Location: /');
            exit();
        }
    }
}