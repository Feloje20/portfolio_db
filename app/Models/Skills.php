<?php
namespace App\Models;
require_once "DBAbstractModel.php";

class Skills extends DBAbstractModel
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
        trigger_error('La clonación no es permitida!.', E_USER_ERROR);
    }

    // Declaramos las variables
    private $id;
    private $habilidades;
    private $visible;
    private $created_at;
    private $updated_at;
    private $categorias_skills_categoria;
    private $usuarios_id;

    // Creo los setters
    public function setId($id) {
        $this->id = $id;
    }
    public function setHabilidades($habilidades) {
        $this->habilidades = $habilidades;
    }
    public function setVisible($visible) {
        $this->visible = $visible;
    }
    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }
    public function setCategoriasSkillsCategoria($categorias_skills_categoria) {
        $this->categorias_skills_categoria = $categorias_skills_categoria;
    }
    public function setUsuariosId($usuarios_id) {
        $this->usuarios_id = $usuarios_id;
    }

    /*Método para registrar un grupo de habilidades en la base de datos*/
    public function set() {
        $fecha = new \DateTime();
        $this->query = "INSERT INTO skills(habilidades, visible, created_at, categorias_skills_categoria, usuarios_id)
        VALUES(:habilidades, :visible, :created_at, :categorias_skills_categoria, :usuarios_id)";
        
        $this->parametros['habilidades'] = $this->habilidades;
        $this->parametros['visible'] = $this->visible;
        $this->parametros['created_at'] = date('Y-m-d H:i:s', $fecha->getTimestamp());
        $this->parametros['categorias_skills_categoria'] = $this->categorias_skills_categoria;
        $this->parametros['usuarios_id'] = $this->usuarios_id;
        $this->get_results_from_query();
        $this->mensaje = 'Habilidades añadidas.';
    }

    // Para obtener un grupo de skills por id
    public function get($id = ''){
        $this->query = "SELECT * FROM skills WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
        if (count($this->rows) == 1) {
            foreach ($this->rows[0] as $propiedad => $valor) {
                if (!empty($valor)) {
                    $this->$propiedad = $valor;
                }
            }
            $this->mensaje = 'Skills no encontradas';
        } else {
            $this->mensaje = 'Skills no encontradas';
        }
        return $this->rows[0] ?? null;
    }

    // Para editar grupos de skills
    public function edit(){
        $fecha = new \DateTime();
        $this->query = "UPDATE skills 
                SET habilidades = :habilidades, visible = :visible, updated_at = :updated_at, categorias_skills_categoria = :categorias_skills_categoria, usuarios_id = :usuarios_id
                WHERE id = :id";
        $this->parametros['habilidades'] = $this->habilidades;
        $this->parametros['visible'] = $this->visible;
        $this->parametros['updated_at'] = date('Y-m-d H:i:s', $fecha->getTimestamp());
        $this->parametros['categorias_skills_categoria'] = $this->categorias_skills_categoria;
        $this->parametros['usuarios_id'] = $this->usuarios_id;
        $this->parametros['id'] = $this->id;
        $this->get_results_from_query();
        $this->mensaje = 'Skills modificadas';
    }

    // Para eliminar el grupo de skills que coincida con la id
    public function delete(){
        $this->query = "DELETE FROM skills WHERE id = :id";
        $this->parametros['id'] = $this->id;
        $this->get_results_from_query();
        $this->mensaje = 'Skills eliminadas';
    }

    // Función que devuelve un array con las categorias de skills
    public function getSkillsCategories() {
        $this->query = "SELECT * FROM categorias_skills";
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return $this->rows;
        }
        return [];
    }

    public function getSkillsVisibles($userId) {
        $this->query = "SELECT * FROM skills WHERE usuarios_id = :usuarios_id AND visible = 1";
        $this->parametros['usuarios_id'] = $userId;
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return $this->rows;
        }
        return [];
    }

    // Método para mostrar todas las skills
    public function getAll($userId) {
        $this->query = "SELECT * FROM skills WHERE usuarios_id = :usuarios_id";
        $this->parametros['usuarios_id'] = $userId;
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return $this->rows;
        }
        return [];
    }
}