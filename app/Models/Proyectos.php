<?php
namespace App\Models;
require_once "DBAbstractModel.php";

class Proyectos extends DBAbstractModel
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
    private $titulo;
    private $logo;
    private $tecnologias;
    private $visible;
    private $created_at;
    private $updated_at;
    private $usuarios_id;

    // Creo los setters
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }
    public function setLogo($logo) {
        $this->logo = $logo;
    }
    public function setTecnologias($tecnologias) {
        $this->tecnologias = $tecnologias;
    }
    public function setVisible($visible) {
        $this->visible = $visible;
    }
    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }
    public function setUsuariosId($usuarios_id) {
        $this->usuarios_id = $usuarios_id;
    }

    /*Método para registrar un proyecto en la base de datos*/
    public function set() {
        $fecha = new \DateTime();
        $this->query = "INSERT INTO proyectos(titulo, logo, tecnologias, visible, created_at, usuarios_id)
        VALUES(:titulo, :logo, :tecnologias, :visible, :created_at, :usuarios_id)";
        
        $this->parametros['titulo'] = $this->titulo;
        $this->parametros['logo'] = $this->logo;
        $this->parametros['tecnologias'] = $this->tecnologias;
        $this->parametros['visible'] = $this->visible;
        $this->parametros['created_at'] = date('Y-m-d H:i:s', $fecha->getTimestamp());
        $this->parametros['usuarios_id'] = $this->usuarios_id;
        $this->get_results_from_query();
        $this->mensaje = 'Proyecto añadido.';
    }

    // Para obtener un proyecto por id
    public function get($id = ''){
        $this->query = "SELECT * FROM proyectos WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
        if (count($this->rows) == 1) {
            foreach ($this->rows[0] as $propiedad => $valor) {
                if (!empty($valor)) {
                    $this->$propiedad = $valor;
                }
            }
            $this->mensaje = 'Proyecto encontrado';
        } else {
            $this->mensaje = 'Proyecto no encontrado';
        }
        return $this->rows[0] ?? null;
    }

    // Para editar un proyecto
    public function edit(){
        $fecha = new \DateTime();
        $this->query = "UPDATE proyectos 
                SET titulo = :titulo, logo = :logo, tecnologias = :tecnologias, visible = :visible, updated_at = :updated_at, usuarios_id = :usuarios_id
                WHERE id = :id";
        $this->parametros['titulo'] = $this->titulo;
        $this->parametros['logo'] = $this->logo;
        $this->parametros['tecnologias'] = $this->tecnologias;
        $this->parametros['visible'] = $this->visible;
        $this->parametros['updated_at'] = date('Y-m-d H:i:s', $fecha->getTimestamp());
        $this->parametros['usuarios_id'] = $this->usuarios_id;
        $this->parametros['id'] = $this->id;
        $this->get_results_from_query();
        $this->mensaje = 'Proyecto modificado';
    }

    // Para eliminar el proyecto que coincida con la id
    public function delete(){
        $this->query = "DELETE FROM proyectos WHERE id = :id";
        $this->parametros['id'] = $this->id;
        $this->get_results_from_query();
        $this->mensaje = 'Proyecto eliminado';
    }
}
