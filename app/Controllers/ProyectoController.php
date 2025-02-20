<?php

namespace App\Controllers;
use App\Models\Proyectos;
use App\Models\Portfolios;

class ProyectoController extends BaseController
{
    // Método para crear un trabajo
    public function createAction() {
        $proyecto = Proyectos::getInstancia();
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
        $data['proyecto']['msjErrorTitulo'] = $data['proyecto']['msjErrorTecnologias'] = $data['proyecto']['msjErrorImagen'] = '';

        // Comprobamos si estamos recibiendo un post
        if (isset($_POST['modificar'])) {
            $lprocesaFormulario = true;

            $data['picture'] = $_FILES['proyecto_logo'];
            $data['proyecto']['titulo'] = $this->sanearDatos($_POST['proyectos']['titulo']);
            $data['proyecto']['tecnologias'] = $this->sanearDatos($_POST['proyectos']['tecnologias']);
            $data['proyecto']['visible'] = isset($_POST['proyectos']['visible']) ? 1 : 0;

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

            // Comprobamos los campos en los que el usuario puede introducir información libremente.
            if ($data['proyecto']['titulo'] == '') {
                $lprocesaFormulario = false;
                $data['proyecto']['msjErrorTitulo'] = "* El título no puede estar vacío";
            }

            if ($data['proyecto']['tecnologias'] == '') {
                $lprocesaFormulario = false;
                $data['proyecto']['msjErrorTecnologias'] = "* Las tecnologías no pueden estar vacías";
            }
        }

        // En el caso de que no haya habido errores en el formulario, guardamos los datos en la base de datos.
        if ($lprocesaFormulario) {
            $proyecto->setTitulo($data['proyecto']['titulo']);
            $proyecto->setLogo($data['picture']['name']);
            $proyecto->setTecnologias($data['proyecto']['tecnologias']);
            $proyecto->setVisible($data['proyecto']['visible']);
            $proyecto->setUsuariosId($userId);
            $proyecto->set();

            // Movemos el archivo a la carpeta de imágenes
            move_uploaded_file($data['picture']['tmp_name'], dirname(__DIR__, 2) . '/public/img/' . $data['picture']['name']);

            header('Location: /edit/' . $userId);
            exit();
        }

        if (isset($_POST['cancelar'])) {
            header('Location: /edit/' . $userId);
            exit();
        }

        $data['tipo'] = 'proyecto';
        $data['accion'] = 'crear';
        $this->renderHTML('../app/views/editarFormulario.php', $data);
    }

    // Método que cambia la visibilidad de un trabajo
    public function changeVisibilityAction() {
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

        if (!($portfolio->isOwner($userId, $userEmail)) && !($userProfile === 'admin')) {
            header('Location: /');
            exit();
        }

        $proyecto->setId($id);
        $proyecto->get($id);

        // Ahora que hemos validado que el usuario puede ejecutar esta acción, inicializamos variables.
        $lprocesaFormulario = false;
        $data = [];
        $data['proyecto']['msjErrorTitulo'] = $data['proyecto']['msjErrorTecnologias'] = $data['proyecto']['msjErrorImagen'] = '';

        // Comprobamos si estamos recibiendo un post
        if (isset($_POST['modificar'])) {
            // Declaración de variables. 
            $lprocesaFormulario = true;
            $data['picture'] = $_FILES['proyecto_logo'];
            $data['proyecto']['titulo'] = $this->sanearDatos($_POST['proyectos']['titulo']);
            $data['proyecto']['tecnologias'] = $this->sanearDatos($_POST['proyectos']['tecnologias']);
            $data['proyecto']['visible'] = isset($_POST['proyectos']['visible']) ? 1 : 0;

            if ($data['picture']['error'] == 0 && isset($data['picture']['name']) && $data['picture']['name'] != '') {
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

            // Comprobamos los campos en los que el usuario puede introducir información libremente.
            if ($data['proyecto']['titulo'] == '') {
                $lprocesaFormulario = false;
                $data['proyecto']['msjErrorTitulo'] = "* El título no puede estar vacío";
            }

            if ($data['proyecto']['tecnologias'] == '') {
                $lprocesaFormulario = false;
                $data['proyecto']['msjErrorTecnologias'] = "* Las tecnologías no pueden estar vacías";
            }
        } else {
            // Si no estamos recibiendo un post, rellenamos los campos con los datos del proyecto.
            $data['proyecto'] += $proyecto->get($id);
        }

        // En el caso de que no haya habido errores en el formulario, guardamos los datos en la base de datos.
        if ($lprocesaFormulario) {
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

            $proyecto->get($id);
            $proyecto->setTitulo($data['proyecto']['titulo']);
            if (isset($data['picture']['name']) && $data['picture']['name'] != '') {
                $proyecto->setLogo($data['picture']['name']);
            }
            $proyecto->setTecnologias($data['proyecto']['tecnologias']);
            $proyecto->setVisible($data['proyecto']['visible']);
            $proyecto->edit();
            header('Location: /edit/' . $userId);
            exit();
        }

        if (isset($_POST['cancelar'])) {
            header('Location: /edit/' . $userId);
            exit();
        }

        $data['tipo'] = 'proyecto';
        $data['accion'] = 'editar';
        $this->renderHTML('../app/views/editarFormulario.php', $data);
    }

    // Método para eliminar un trabajo
    public function deleteAction() {
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