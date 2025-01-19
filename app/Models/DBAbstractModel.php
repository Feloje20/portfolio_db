<?php
namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';


/* ES NECESARIO PONER BIEN TODO EL TEMA DEL .ENV PARA QUE LAS VARIABLES ESTÉN PROTEGIDAS 
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../..'); // Ajusta la ruta si es necesario
$dotenv->load(); */

abstract class DBAbstractModel
{
    // Estas variables globales están definidas en el archivo boosttrap.php
    private static $db_host = DB_HOST;
    private static $db_user = DB_USER;
    private static $db_pass = DB_PASS;
    private static $db_name = DB_NAME;
    private static $db_port = DB_PORT;
    protected $mensaje = '';
    protected $conn; // Manejador de la BD
    // Manejo básico para consultas.
    protected $query; // consulta
    protected $parametros = array(); // parámetros de entrada
    protected $rows = array(); // array con los datos de salida

    // Métodos abstractos para implementar en los diferentes módulos.
    abstract protected function get();
    abstract protected function set();
    abstract protected function edit();
    abstract protected function delete();

    # Crear conexión a la base de datos.
    protected function open_connection()
    {
        $dsn = 'mysql:host=' . self::$db_host . ';' . 'dbname=' . self::$db_name . ';' . 'port=' . self::$db_port;
        try {
            $this->conn = new \PDO($dsn, self::$db_user, self::$db_pass, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            return $this->conn;
        } catch (\PDOException $e) {
            printf("Conexión fallida: %s\n", $e->getMessage());
            exit();
        }
    }

    # Método que devuelve el último id introducido.
    public function lastInsert()
    {
        return $this->conn->lastInsertId();
    }

    # Desconectar la base de datos
    private function close_connection()
    {
        $this->conn = null;
    }

    # Ejecutar un query simple del tipo INSERT, DELETE, UPDATE
    # Consulta que no devuelve tuplas de la tabla
    protected function execute_single_query()
    {
        if ($_POST) {
            $this->open_connection();
            $this->conn->query($this->query);
            $this->close_connection();
        } else {
            $this->mensaje = 'Metodo no permitido';
        }
    }

    # Traer resultados de una consulta en un Array
    # Consulta que devuelve tuplas de la tabla.
    protected function get_results_from_query()
    {
        $this->open_connection();
        if (($_stmt = $this->conn->prepare($this->query))) {
            # PREG_PATTERN_ORDER flag para especificar como se cargan los resultados en $named.
            if (preg_match_all('/(:\w+)/', $this->query, $_named, PREG_PATTERN_ORDER)) {
                $_named = array_pop($_named);
                foreach ($_named as $_param) {
                    $_stmt->bindValue($_param, $this->parametros[substr($_param, 1)]);
                }
            }
            try {
                if (!$_stmt->execute()) {
                    printf("Error de consulta: %s\n", $_stmt->errorInfo()[2]);
                }
                $this->rows = $_stmt->fetchAll(\PDO::FETCH_ASSOC);
                $_stmt->closeCursor();
            } catch (\PDOException $e) {
                printf("Error en consulta: %s\n", $e->getMessage());
            }
        }
    }
}
