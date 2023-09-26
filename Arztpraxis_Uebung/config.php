<?php
// Datenbank-Konfiguration
$host = 'localhost:3306';
$db   = 'Arztpraxis';
$user = 'root';
$pass = '';

try {
    // Verbindung zur Datenbank herstellen
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
} catch (PDOException $e) {
    echo "Datenbankverbindung fehlgeschlagen: " . $e->getMessage();
}
?>
