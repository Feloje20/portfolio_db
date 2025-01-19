<?php
namespace App\Models;
require_once "DBAbstractModel.php";

class Portfolios extends DBAbstractModel
{
    private static $instancia;
    // Patron singleton, no puedo tener dos objetos de la clase usuario
    public static function getInstancia()
    {
        if (!isset(self::$instancia)) {
            $miClase = __CLASS__;
            self::$instancia = new $miClase;
        }
        return self::$instancia;
    }

    public function __clone()
    {
        trigger_error('La clonación no esta permitida!.', E_USER_ERROR);
    }

    function set() {
    }

    function get() {
    }

    function edit() {
    }

    function delete() {
    }

    // Devuelve los portfolios que son públicos (visible = 1)
    function getPublic() {
        $this->query = "SELECT * FROM usuarios WHERE visible = 1";
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return $this->rows;
        }
        return [];
    }

    function checkVisibility($id) {
        $this->query = "SELECT visible FROM usuarios WHERE id = '$id'";
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return $this->rows[0]['visible'];
        }
        return [];
    }

    // Función de búsqueda en la base de datos coincidencias con el parametro pasado
    function searchPortfolios($search) {
        // Se revisan los campos de nombre, apellidos y resumen de perfil
        $this->query = "SELECT * FROM usuarios WHERE (nombre LIKE '%$search%' OR apellidos LIKE '%$search%') AND visible = 1";
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return $this->rows;
        }
        return [];
    }

    // Función que determina si el usuario logeado es el dueño del portfolio
    function isOwner($id) {
        $this->query = "SELECT id FROM usuarios WHERE id = '$id' AND email = '$_SESSION[email]'";
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return true;
        }
        return false;
    }

    // Función para comprobar si el usuario ya ha pasado por la creación de su portfolio
    // Solo necesitamos comprobar si ya hay un trabajo creado
    function isPortfolioCreated($id) {
        $this->query = "SELECT id FROM trabajos WHERE usuarios_id = '$id'";
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return true;
        }
        return false;
    }

}