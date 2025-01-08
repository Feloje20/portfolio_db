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
    private $foto;
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
    public function setFoto($foto) {
        $this->foto = $foto;
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

    /*Método para registrar un usuario en la base de datos*/
    public function set() {
        $fecha = new \DateTime();
        $this->query = "INSERT INTO usuarios(nombre, apellidos, foto, email, password, resumen_perfil, token, created_at, visible, cuenta_activa)
        VALUES(:nombre, :apellidos, :foto, :email, :password, :resumen_perfil, :token, :created_at, :visible, :cuenta_activa)";
        
        $this->parametros['nombre']= $this->nombre;
        $this->parametros['apellidos']= $this->apellidos;
        $this->parametros['email']= $this->email;
        $this->parametros['foto'] = $this->foto;
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
            foreach ($this->rows[0] as $propiedad => $valor) {
                if (!empty($valor)) {
                    $this->$propiedad = $valor;
                }
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
                        SET nombre = :nombre, apellidos = :apellidos, foto = :foto, email = :email, password = :password, resumen_perfil = :resumen_perfil, token = :token, updated_at = :update_at, visible = :visible, cuenta_activa = :cuenta_activa
                        WHERE id = :id";
        $this->parametros['nombre'] = $this->nombre;
        $this->parametros['apellidos'] = $this->apellidos;
        $this->parametros['foto'] = $this->foto;
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

    // Función para comprobar si un email que se le pasa ya se encuentra en la base de datos
    public function emailExists($email){
        $this->query = "SELECT * FROM usuarios WHERE email = :email";
        $this->parametros['email'] = $email;
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Función parar comprobar que un email y una contraseña coinciden
    public function emailPasswordExists($email, $password){
        $this->query = "SELECT * FROM usuarios WHERE email = :email AND password = :password";
        $this->parametros['email'] = $email;
        $this->parametros['password'] = $password;
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Función para obtener el nombre usando el email
    public function getNameByEmail($email){
        $this->query = "SELECT nombre FROM usuarios WHERE email = :email";
        $this->parametros['email'] = $email;
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return $this->rows[0]['nombre'];
        } else {
            return null;
        }
    }

    // Función para obtener el apellido usando el email
    public function getLastNameByEmail($email){
        $this->query = "SELECT apellidos FROM usuarios WHERE email = :email";
        $this->parametros['email'] = $email;
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return $this->rows[0]['apellidos'];
        } else {
            return null;
        }
    }

    // Función que determina si la cuenta se encuentra activa
    public function isActive($email){
        $this->query = "SELECT cuenta_activa FROM usuarios WHERE email = :email";
        $this->parametros['email'] = $email;
        $this->get_results_from_query();
        // Hay que determinar si es un 0 o un 1
        if ($this->rows[0]['cuenta_activa'] == 1) {
            return true;
        } else {
            return false;
        }
    }

}
?>