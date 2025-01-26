<?php
namespace App\Models;
require_once "DBAbstractModel.php";

class Trabajos extends DBAbstractModel
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
    private $descripcion;
    private $fecha_inicio;
    private $fecha_final;
    private $logros;
    private $visible;
    private $created_at;
    private $updated_at;
    private $usuarios_id;

    // Creo los setters
    public function setId($id) {
        $this->id = $id;
    }
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
    public function setFechaInicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
    }
    public function setFechaFinal($fecha_final) {
        $this->fecha_final = $fecha_final;
    }
    public function setLogros($logros) {
        $this->logros = $logros;
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

    /*Método para registrar un trabajo en la base de datos*/
    public function set() {
        $fecha = new \DateTime();
        $this->query = "INSERT INTO trabajos(titulo, descripcion, fecha_inicio, fecha_final, logros, visible, created_at, usuarios_id)
        VALUES(:titulo, :descripcion, :fecha_inicio, :fecha_final, :logros, :visible, :created_at, :usuarios_id)";
        
        $this->parametros['titulo'] = $this->titulo;
        $this->parametros['descripcion'] = $this->descripcion;
        $this->parametros['fecha_inicio'] = $this->fecha_inicio;
        $this->parametros['fecha_final'] = $this->fecha_final;
        $this->parametros['logros'] = $this->logros;
        $this->parametros['visible'] = $this->visible;
        $this->parametros['created_at'] = date('Y-m-d H:i:s', $fecha->getTimestamp());
        $this->parametros['usuarios_id'] = $this->usuarios_id;
        $this->get_results_from_query();
        $this->mensaje = 'Trabajo añadido.';
    }

    // Para obtener un trabajo por id
    public function get($id = ''){
        $this->query = "SELECT * FROM trabajos WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
        if (count($this->rows) == 1) {
            foreach ($this->rows[0] as $propiedad => $valor) {
                if (!empty($valor)) {
                    $this->$propiedad = $valor;
                }
            }
            $this->mensaje = 'Trabajo encontrado';
        } else {
            $this->mensaje = 'Trabajo no encontrado';
        }
        return $this->rows[0] ?? null;
    }

    // Para editar un trabajo
    public function edit(){
        $fecha = new \DateTime();
        $this->query = "UPDATE trabajos 
                SET titulo = :titulo, descripcion = :descripcion, fecha_inicio = :fecha_inicio, fecha_final = :fecha_final, logros = :logros, visible = :visible, updated_at = :updated_at, usuarios_id = :usuarios_id
                WHERE id = :id";
        $this->parametros['titulo'] = $this->titulo;
        $this->parametros['descripcion'] = $this->descripcion;
        $this->parametros['fecha_inicio'] = $this->fecha_inicio;
        $this->parametros['fecha_final'] = $this->fecha_final;
        $this->parametros['logros'] = $this->logros;
        $this->parametros['visible'] = $this->visible;
        $this->parametros['updated_at'] = date('Y-m-d H:i:s', $fecha->getTimestamp());
        $this->parametros['usuarios_id'] = $this->usuarios_id;
        $this->parametros['id'] = $this->id;
        $this->get_results_from_query();
        $this->mensaje = 'Trabajo modificado';
    }

    // Para eliminar el trabajo que coincida con la id
    public function delete(){
        $this->query = "DELETE FROM trabajos WHERE id = :id";
        $this->parametros['id'] = $this->id;
        $this->get_results_from_query();
        $this->mensaje = 'Trabajo eliminado';
    }

    // Función para mostrar los trabajos que sean visibles
    public function getTrabajosVisibles($userId) {
        $this->query = "SELECT * FROM trabajos WHERE usuarios_id = :usuarios_id AND visible = 1";
        $this->parametros['usuarios_id'] = $userId;
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return $this->rows;
        }
        return [];
    }

    // Método para mostrar todos los trabajos
    public function getAll($userId) {
        $this->query = "SELECT * FROM trabajos WHERE usuarios_id = :usuarios_id";
        $this->parametros['usuarios_id'] = $userId;
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return $this->rows;
        }
        return [];
    }

    // Método para cambiar la visibilidad
    public function changeVisibility($id, $userId) {
        $this->query = "UPDATE trabajos SET visible = :visible WHERE id = :id AND usuarios_id = :usuarios_id";
        $this->parametros['visible'] = $this->visible;
        $this->parametros['id'] = $id;
        $this->parametros['usuarios_id'] = $userId;
        $this->get_results_from_query();
        $this->mensaje = 'Visibilidad cambiada';
    }
}
