<?php
/* Verbindung zur Datenbank */
$server = 'localhost:3306'; // Der Server und Port, auf dem die Datenbank läuft (in diesem Fall localhost auf Port 3306).
$user = 'root'; // Der Benutzername für die Datenbankverbindung.
$pwd = ''; // Das Passwort für die Datenbankverbindung (in diesem Fall leer).
$db = 'adresse'; // Der Name der Datenbank, zu der eine Verbindung hergestellt werden soll.

// Eine Verbindung zur Datenbank herstellen und eventuelle Fehler abfangen.
try {
    $con = new PDO('mysql:host=' . $server . ';dbname=' . $db, $user, $pwd);
    $con->exec("SET NAMES 'utf8'"); // Das Encoding für die Datenbankverbindung auf UTF-8 setzen.
} catch (Exception $e) {
    echo 'Error - Verbindung zur DB' . $e->getCode() . ': ' . $e->getMessage(); // Fehlermeldung ausgeben, falls die Verbindung fehlschlägt.
}
?>
