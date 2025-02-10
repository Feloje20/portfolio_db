<?php 
require 'vendor/autoload.php';

use Dotenv\Dotenv;

// Estas variables están declaradas en el archivo .env, el cual no se sube al repositorio
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_PORT', $_ENV['DB_PORT']);
define('BASE_URL', $_ENV['BASE_URL']);

ini_set("display_errors", 1);
ini_Set("display_startup_errors", 1);
error_reporting(E_ALL);