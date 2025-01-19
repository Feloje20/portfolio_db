<?php

namespace App\Controllers;
use App\Models\Usuarios;
use App\Models\Portfolios;

class UsuarioController extends BaseController
{
    public function IndexAction()
    {
        // Creamos una instancia de usuarios
        $usuario = Usuarios::getInstancia();

        // Alamacenamos los datos en $data
        $data['usuarios'] = $usuario->getAll();

        // Llamamos a la función renderHTML
        $this->renderHTML('../app/views/index_test.php', $data);
    }

    // Manejo de creaciónn de usuarios en la base de datos.
    public function AddAction()
    {
        // Si el usuario está logeado, no puede registrarse. 
        if (isset($_SESSION['email'])) {
            header('Location: ..');
            exit();
        }

        $lprocesaFormulario = false;
        $data = array();
        $data['nombre'] = $data['apellidos'] = $data['email'] = $data['password'] = $data['password_confirmation'] = $data['profile_summary'] = $data['picture'] = '';
        $data['msjErrorNombre'] = $data['msjErrorApellidos'] = $data['msjErrorEmail'] = $data['msjErrorPassword'] = $data['msjErrorPassword2'] = $data['msjErrorImagen'] = '';

        if(!empty($_POST)){
            // Saneamos las entradas antes de utilizarlas
            // HAY QUE SANEAR LOS DATOS ****************************************************
            $data['nombre'] = $this->sanearDatos($_POST['first_name']);
            $data['apellidos'] = $this->sanearDatos($_POST['last_name']);
            $data['email'] = $this->sanearDatos($_POST['email']);
            $data['password'] = $this->sanearDatos($_POST['password']);
            $data['password_confirmation'] = $this->sanearDatos($_POST['password_confirmation']);
            $data['profile_summary'] = $this->sanearDatos($_POST['profile_summary']);
            $data['picture'] = $_FILES['profile_picture'];

            // Creamos una instancia de usuarios
            $objUsuario = Usuarios::getInstancia();

            $lprocesaFormulario = true;

            // Validamos que el campo nombre no esté vacío
            if (empty($data['nombre'])) {
                $lprocesaFormulario = false;
                $data['msjErrorNombre'] = "* El nombre no puede estar vacío";
            }

            // Validamos que el campo apellidos no esté vacío
            if (empty($data['apellidos'])) {
                $lprocesaFormulario = false;
                $data['msjErrorApellidos'] = "* Los apellidos no pueden estar vacíos";
            }

            // Validamos que el campo email no esté vacío
            if (empty($data['email'])) {
                $lprocesaFormulario = false;
                $data['msjErrorEmail'] = "* El email no puede estar vacío";
            }

            // Validamos que el email no se encuentre ya en la base de datos
            if ($objUsuario->emailExists($data['email'])) {
                $lprocesaFormulario = false;
                $data['msjErrorEmail'] = "* El email ya está en uso";
            }

            // Validamos que el campo password no esté vacío
            if (empty($data['password'])) {
                $lprocesaFormulario = false;
                $data['msjErrorPassword'] = "* La contraseña no puede estar vacía";
            }

            // valida que el campo password_confirmation sea igual que password
            if ($data['password'] !== $data['password_confirmation']) {
                $lprocesaFormulario = false;
                $data['msjErrorPassword2'] = "* Las contraseñas no coinciden";
            }

            // Comprobamos si se ha subido una imagen
            if ($data['picture']['error'] == 0) {
                // Comprobamos si el archivo subido es una imagen
                if ($data['picture']['type'] == 'image/jpeg' || $data['picture']['type'] == 'image/png' || $data['picture']['type'] == 'image/PNG') {
                    // Comprobamos si el archivo subido no supera los 2MB
                    if ($data['picture']['size'] <= 2000000) {
                        // Generamos un nombre para la imagen al azar
                        // OPCIONAL GUARDAR SOLO EL UNIQID Y LA EXTENSIÓN ******************************
                        $data['picture']['name'] = uniqid() . $data['picture']['name'];
                    } else {
                        $lprocesaFormulario = false;
                        $data['msjErrorImagen'] = "* La imagen no puede superar los 2MB";
                    }
                } else {
                    $lprocesaFormulario = false;
                    $data['msjErrorImagen'] = "* El archivo subido no es una imagen";
                }
            }

        }

        if ($lprocesaFormulario) {
            // Generación de token
            $rb = random_bytes(32);
            $token = base64_encode($rb);
            $secureToken = uniqid("",true) . $token;

            // Movemos el archivo a la carpeta de imágenes
            move_uploaded_file($data['picture']['tmp_name'], dirname(__DIR__, 2) . '/public/img/' . $data['picture']['name']);

            // Guardar el usuario en la base de datos
            $objUsuario->setNombre($data['nombre']);
            $objUsuario->setApellidos($data['apellidos']);
            $objUsuario->setFoto($data['picture']['name']);
            $objUsuario->setEmail($data['email']);
            $objUsuario->setPassword($data['password']);
            $objUsuario->setProfileSummary($data['profile_summary']);
            $objUsuario->setToken($secureToken);
            $objUsuario->set();
            header('Location: ..');
        } else {
            // Mostrar la vista de agregar usuario con los datos y errores
            $this->renderHTML('../app/views/register.php', $data);
        }
    }

    // Manejo del login de usuarios en la base de datos
    public function LoginAction()
    {
        // Si el usuario está logeado, no puede registrarse.
        if (isset($_SESSION['email'])) {
            header('Location: ..');
            exit();
        }
        
        $data = array();
        $data['email'] = $data['password'] = '';
        $data['msjErrorEmail'] = $data['msjErrorPassword'] = $data['msjErrorMissmatch'] = '';
        if(!empty($_POST)){
            // Saneamos las entradas antes de utilizarlas
            // HAY QUE SANEAR LOS DATOS ****************************************************
            $data['email'] = $_POST['email'];
            $data['password'] = $_POST['password'];

            // Creamos una instancia de usuarios
            $objUsuario = Usuarios::getInstancia();

            // Validamos que el campo email no esté vacío
            if (empty($data['email'])) {
                $data['msjErrorEmail'] = "* El email no puede estar vacío";
            }

            // Validamos si el correo existe en la base de datos
            if (!$objUsuario->emailExists($data['email'])) {
                $data['msjErrorEmail'] = "* El email no está registrado";
            }

            // Validamos que el campo password no esté vacío
            if (empty($data['password'])) {
                $data['msjErrorPassword'] = "* La contraseña no puede estar vacía";
            }

            // Validamos que el email y la contraseña coincidan, además de comprobar si la cuenta está activa
            if ($objUsuario->emailPasswordExists($data['email'], $data['password']) && $objUsuario->isActive($data['email'])) {
                // Iniciar sesión
                session_start();

                // Guardamos el email y el nombre y apellidos del usuario en la sesión
                $_SESSION['email'] = $data['email'];
                $_SESSION['nombre'] = $objUsuario->getNameByEmail($data['email']);
                $_SESSION['apellidos'] = $objUsuario->getLastNameByEmail($data['email']);

                header('Location: ..');
            } 

            // Validamos que la cuenta se encuentra activa
            if (!$objUsuario->emailPasswordExists($data['email'], $data['password'])) {
                $data['msjErrorMissmatch'] = "El email o la contraseña no son correctos";
            } else if (!$objUsuario->isActive($data['email'])) {
                $data['msjErrorMissmatch'] = "* La cuenta no está activa";
            }
        }

        // Se envía al index la información de los usuarios públicos de nuevo.
        $portfolio = Portfolios::getInstancia();
        $data['usuarios'] = $portfolio->getPublic();

        // Mostrar la vista de login con los datos y errores
        $this->renderHTML('../app/views/index_test.php', $data);
    }

    // Manejo del logout de usuarios en la base de datos
    public function LogoutAction()
    {
        // Iniciar sesión
        session_start();

        // Destruir la sesión
        session_destroy();

        header('Location: ' . BASE_URL);
    }
}