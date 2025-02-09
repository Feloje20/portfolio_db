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

    // Devuelve las IDs de los usuarios con el perfil visible.
    function getPublic() {
        $this->query = "SELECT id FROM usuarios WHERE visible = :visible";
        $this->parametros['visible'] = 1;
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
        $this->query = "SELECT id FROM usuarios WHERE (nombre LIKE :search OR apellidos LIKE :search) AND visible = 1";
        $this->parametros['search'] = '%' . $search . '%';

        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return $this->rows;
        }
        return [];
    }

    // Función que determina si el usuario logeado es el dueño del portfolio
    function isOwner($id, $email = null) {
        $this->query = "SELECT id FROM usuarios WHERE id = :id AND email = :email";
        $this->parametros['id'] = $id;
        $this->parametros['email'] = $email;
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return true;
        }
        return false;
    }

    // Método para cambiar la visibilidad de un portfolio
    function changeVisibility($id, $visible) {
        $this->query = "UPDATE usuarios SET visible = :visible WHERE id = :id";
        $this->parametros['visible'] = $visible;
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
    }

    // Función para comprobar si el usuario ya ha pasado por la creación de su portfolio
    function isPortfolioCreated($id) {
        // Comprobamos si hay algún trabajo creado.
        $this->query = "SELECT id FROM trabajos WHERE usuarios_id = '$id'";
        $this->get_results_from_query();
        $trabajos = $this->rows;

        // Comprobamos si hay algún proyecto creado.
        $this->query = "SELECT id FROM proyectos WHERE usuarios_id = '$id'";
        $this->get_results_from_query();
        $proyectos = $this->rows;

        // Comprobamos si hay alguna habilidad creada.
        $this->query = "SELECT id FROM skills WHERE usuarios_id = '$id'";
        $this->get_results_from_query();
        $skills = $this->rows;

        // Comprobamos si hay alguna red social creada
        $this->query = "SELECT id FROM redes_sociales WHERE usuarios_id = '$id'";
        $this->get_results_from_query();
        $redes_sociales = $this->rows;

        // Si hay algún trabajo, proyecto, habilidad o red social creada, devolvemos true
        if (count($trabajos) > 0 || count($proyectos) > 0 || count($skills) > 0 || count($redes_sociales) > 0) {
            return true;
        }
        return false;
    }
}