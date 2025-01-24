<?php

namespace App\Controllers;
use App\Models\Trabajos;
use App\Models\Portfolios;

class TrabajoController extends BaseController
{
    // Método que cambia la visibilidad de un trabajo
    public function changeVisibility() {
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
        // NO FUNCIONA ********************************************
        $userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : null;
        $userProfile = isset($_SESSION['perfil']) ? $_SESSION['perfil'] : null;
        if ($portfolio->isOwner($id, $userEmail) || $userProfile === 'admin') {
            $trabajo->setVisible($visibility ? 0 : 1);
            $trabajo->changeVisibility($id, $userId);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            header('Location: /');
            exit();
        }
    }
}