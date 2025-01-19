<?php

namespace App\Controllers;

class BaseController
{
    public function renderHTML($fileName, $data=[])
    {
        include($fileName);
    }

    public function sanearDatos($datos)
    {
        $datos = trim($datos);
        $datos = stripslashes($datos);
        $datos = htmlspecialchars($datos);
        return $datos;
    }
}