<?php
namespace App\Models;
require_once "DBAbstractModel.php";

class RedesSociales extends DBAbstractModel
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
    private $redes_sociales;
    private $url;
    private $created_at;
    private $updated_at;
    private $usuarios_id;

    // Creo los setters
    public function setRedesSociales($redes_sociales) {
        $this->redes_sociales = $redes_sociales;
    }
    public function setUrl($url) {
        $this->url = $url;
    }
    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }
    public function setUsuariosId($usuarios_id) {
        $this->usuarios_id = $usuarios_id;
    }

    /*Método para registrar una red social en la base de datos*/
    public function set() {
        $fecha = new \DateTime();
        $this->query = "INSERT INTO redes_sociales(redes_sociales, url, created_at, usuarios_id)
        VALUES(:redes_sociales, :url, :created_at, :usuarios_id)";
        
        $this->parametros['redes_sociales'] = $this->redes_sociales;
        $this->parametros['url'] = $this->url;
        $this->parametros['created_at'] = date('Y-m-d H:i:s', $fecha->getTimestamp());
        $this->parametros['usuarios_id'] = $this->usuarios_id;
        $this->get_results_from_query();
        $this->mensaje = 'Red social añadida.';
    }

    // Para obtener una red social por id
    public function get($id = ''){
        $this->query = "SELECT * FROM redes_sociales WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
        if (count($this->rows) == 1) {
            foreach ($this->rows[0] as $propiedad => $valor) {
                if (!empty($valor)) {
                    $this->$propiedad = $valor;
                }
            }
            $this->mensaje = 'Red social encontrada';
        } else {
            $this->mensaje = 'Red social no encontrada';
        }
        return $this->rows[0] ?? null;
    }

    // Para editar una red social
    public function edit(){
        $fecha = new \DateTime();
        $this->query = "UPDATE redes_sociales 
                SET redes_sociales = :redes_sociales, url = :url, updated_at = :updated_at, usuarios_id = :usuarios_id
                WHERE id = :id";
        $this->parametros['redes_sociales'] = $this->redes_sociales;
        $this->parametros['url'] = $this->url;
        $this->parametros['updated_at'] = date('Y-m-d H:i:s', $fecha->getTimestamp());
        $this->parametros['usuarios_id'] = $this->usuarios_id;
        $this->parametros['id'] = $this->id;
        $this->get_results_from_query();
        $this->mensaje = 'Red social modificada';
    }

    // Para eliminar la red social que coincida con la id
    public function delete(){
        $this->query = "DELETE FROM redes_sociales WHERE id = :id";
        $this->parametros['id'] = $this->id;
        $this->get_results_from_query();
        $this->mensaje = 'Red social eliminada';
    }

    // Para obtener todas las redes sociales de un usuario
    public function getRedesSocialesById($userId){
        $this->query = "SELECT * FROM redes_sociales WHERE usuarios_id = :usuarios_id";
        $this->parametros['usuarios_id'] = $userId;
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return $this->rows;
        }
        return [];
    }
}
