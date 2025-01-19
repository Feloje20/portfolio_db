<?php
define('DBHOST', 'localhost');
define('DBPASS', 'root');
define('DBUSER', 'root');
define('DBNAME', 'portolio_db');
define('DBPORT', '3306');

// Función de limpieza de datots de entrada
function cleanInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}