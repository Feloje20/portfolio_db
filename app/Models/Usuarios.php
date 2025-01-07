<?php
namespace App\Models;
require_once "DBAbstractModel.php";

class Usuarios extends DBAbstractModel
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
    private $id;
    private $nombre;
    private $apellidos;
    private $email;
    private $password;
    private $resumen_perfil;
    private $token;
    private $created_at;
    private $updated_at;
    private $visible;
    private $cuenta_activa;

    // Creo los setters
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    public function setApellidos($apellidos) {
        $this->apellidos = $apellidos ;
    }
    public function setEmail($email) {
        $this->email = $email ;
    }
    public function setPassword($password) {
        $this->password = $password ;
    }
    public function setProfileSummary($resumen_perfil) {
        $this->resumen_perfil = $resumen_perfil ;
    }
    public function setToken($token) {
        $this->token = $token ;
    }

    public function getMensaje(){
        return $this->mensaje;
    }

    /*Método para insertar datos en la tabla usuario*/
    public function set() {
        $fecha = new \DateTime();
        $this->query = "INSERT INTO usuarios(nombre, apellidos, email, password, resumen_perfil, token, created_at, visible, cuenta_activa)
        VALUES(:nombre, :apellidos, :email, :password, :resumen_perfil, :token, :created_at, :visible, :cuenta_activa)";
        
        $this->parametros['nombre']= $this->nombre;
        $this->parametros['apellidos']= $this->apellidos;
        $this->parametros['email']= $this->email;
        $this->parametros['password']= $this->password;
        $this->parametros['resumen_perfil']= $this->resumen_perfil;
        $this->parametros['token']= $this->token;
        $this->parametros['created_at'] = date( 'Y-m-d H:i:s', $fecha->getTimestamp());
        $this->parametros['visible'] = 0;
        $this->parametros['cuenta_activa'] = 0;
        $this->get_results_from_query();
        $this->mensaje = 'Usuario añadido.';
    }

    // Para obtener un usuario por id
    public function get($id = ''){
        $this->query = "SELECT * FROM usuarios WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
        if (count($this->rows) == 1) {
            foreach ($this->rows[0] as $propiedad=>$valor) {
                $this->$propiedad = $valor;
            }
            $this->mensaje = 'Usuario encontrada';
        } else {
            $this->mensaje = 'Usuario no encontrada';
        }
        return $this->rows[0] ?? null;
        
    }

    // Para editar usuarios
    public function edit(){
        $fecha = new \DateTime();
        $this->query = "UPDATE usuarios 
                        SET nombre = :nombre, apellidos = :apellidos, email = :email, password = :password, resumen_perfil = :resumen_perfil, token = :token, updated_at = :update_at, visible = :visible, cuenta_activa = :cuenta_activa
                        WHERE id = :id";
        $this->parametros['nombre'] = $this->nombre;
        $this->parametros['apellidos'] = $this->apellidos;
        $this->parametros['email'] = $this->email;
        $this->parametros['password'] = $this->password;
        $this->parametros['resumen_perfil'] = $this->resumen_perfil;
        $this->parametros['token'] = $this->token;
        $this->parametros['update_at'] = date( 'Y-m-d H:i:s', $fecha->getTimestamp());
        $this->parametros['visible'] = $this->visible;
        $this->parametros['cuenta_activa'] = $this->cuenta_activa;
        $this->parametros['id'] = $this->id;
        $this->get_results_from_query();
        $this->mensaje = 'Usuario modificado';
    }

    // Para eliminar el ultimo usuario creado
    public function delete(){
        $this->query = "DELETE FROM usuarios WHERE id = :id";
        $this->parametros['id'] = $this->id;
        $this->get_results_from_query();
        $this->mensaje = 'Usuario eliminado';
    }

    // Para obtener todos los usuarios
    public function getAll(){
        $this->query = "SELECT * FROM usuarios";
        $this->get_results_from_query();
        return $this->rows;
    }

}
?>