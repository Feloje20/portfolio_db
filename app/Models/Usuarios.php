<?php
namespace App\Models;
require_once "DBAbstractModel.php";

use App\Models\Trabajos;
use App\Models\Proyectos;
use App\Models\Skills;
use App\Models\RedesSociales;

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
    private $perfil;
    private $foto;
    private $email;
    private $password;
    private $resumen_perfil;
    private $token;
    private $created_at;
    private $updated_at;
    private $fecha_creacion_token;
    private $visible;
    private $cuenta_activa;
    private $trabajos;
    private $proyectos;
    private $skills;
    private $redes_sociales;

    // Creo los setters
    public function setId($id) {
        $this->id = $id;
    }
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
    public function setVisible($visible) {
        $this->visible = $visible ;
    }
    public function setToken($token) {
        $this->token = $token ;
    }
    public function setCuentaActiva($cuenta_activa) {
        $this->cuenta_activa = $cuenta_activa ;
    }

    // Creo los getters
    public function getNombre() {
        return $this->nombre;
    }
    public function getApellidos() {
        return $this->apellidos;
    }
    public function getPerfil() {
        return $this->perfil;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getFoto() {
        return $this->foto;
    }
    public function getPassword() {
        return $this->password;
    }
    public function getProfileSummary() {
        return $this->resumen_perfil;
    }
    public function getVisible() {
        return $this->visible;
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
            // Ahora rellenamos la información de los trabajos, proyectos, redes sociales y skills.
            $trabajos = Trabajos::getInstancia();
            $proyectos = Proyectos::getInstancia();
            $skills = Skills::getInstancia();
            $redes_sociales = RedesSociales::getInstancia();

            $this->trabajos = $trabajos->getAll($this->id);
            $this->proyectos = $proyectos->getAll($this->id);
            $this->skills = $skills->getAll($this->id);
            $this->redes_sociales = $redes_sociales->getRedesSocialesById($this->id);

            // Luego lo añadimos a lo que se da a devolver en el getter.
            $this->rows[0]['trabajos'] = $this->trabajos;
            $this->rows[0]['proyectos'] = $this->proyectos;
            $this->rows[0]['skills'] = $this->skills;
            $this->rows[0]['redes_sociales'] = $this->redes_sociales;
            $this->mensaje = 'Usuario encontrado';
        } else {
            $this->mensaje = 'Usuario no encontrado';
        }
        return $this->rows[0] ?? null;
    }

    // Para editar usuarios
    public function edit(){
        $fecha = new \DateTime();
        $this->query = "UPDATE usuarios 
                        SET nombre = :nombre, apellidos = :apellidos, foto = :foto, email = :email, password = :password, resumen_perfil = :resumen_perfil, updated_at = :update_at, visible = :visible
                        WHERE id = :id";
        $this->parametros['nombre'] = $this->nombre;
        $this->parametros['apellidos'] = $this->apellidos;
        $this->parametros['foto'] = $this->foto;
        $this->parametros['email'] = $this->email;
        $this->parametros['password'] = $this->password;
        $this->parametros['resumen_perfil'] = $this->resumen_perfil;
        $this->parametros['update_at'] = date( 'Y-m-d H:i:s', $fecha->getTimestamp());
        $this->parametros['visible'] = $this->visible;
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

    // Función para obtener el id usando el email
    public function getIdByEmail($email){
        $this->query = "SELECT id FROM usuarios WHERE email = :email";
        $this->parametros['email'] = $email;
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return $this->rows[0]['id'];
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

    // Método que comprueba que el id pertenece al email
    public function checkIdEmail($id, $email){
        $this->query = "SELECT * FROM usuarios WHERE id = :id AND email = :email";
        $this->parametros['id'] = $id;
        $this->parametros['email'] = $email;
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Método para verificar que un token existe y que la cuenta no está ya activa.
    public function verifyToken($token) {
        $this->query = "SELECT * FROM usuarios WHERE token = :token AND cuenta_activa = 0";
        $this->parametros['token'] = $token;
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Método para activar la cuenta
    public function activateAccount($token) {
        $this->query = "UPDATE usuarios SET cuenta_activa = 1 WHERE token = :token";
        $this->parametros['token'] = $token;
        $this->get_results_from_query();
        $this->mensaje = 'Cuenta activada';
    }
}
?>