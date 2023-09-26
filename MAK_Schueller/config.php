<?php
/* Verbindung zur Datenbank */
$server = 'localhost:3306'; // 3307 = MariaDB, keine Angabe des Ports oder 3306 ist MySQL (Schulrechner)
$user = 'root';
$pwd = '';
$db = 'adresse';

try {
    $con = new PDO('mysql:host=' . $server . ';dbname=' . $db, $user, $pwd);
    $con->exec("SET NAMES 'utf8'"); // Encoding auf UTF-8 setzen für die Datenbankverbindung
} catch (Exception $e) {
    echo 'Error - Verbindung zur DB' . $e->getCode() . ': ' . $e->getMessage();
}

?>