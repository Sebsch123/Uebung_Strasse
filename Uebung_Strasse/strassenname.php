<?php
include 'funktionen.php'; // Einbinden der Datei 'funktionen.php', die Funktionen und Konfigurationen enthält.

if(isset($_POST['save'])) // Überprüfen, ob das Formular abgeschickt wurde.
{
    // to do ... Daten speichern
    $strasse = $_POST['strasse']; // Den Wert des 'strasse'-Feldes aus dem Formular in die Variable $strasse speichern.
    try
    {
        $insertQuery = 'insert into strasse (str_name) values(?)'; // SQL-Anweisung zum Einfügen eines neuen Straßennamens in die Datenbanktabelle 'strasse'.
        $stmt = $arrayV = array($strasse); // Das SQL-Statement vorbereiten, allerdings ist $arrayV scheinbar nicht in Verwendung und sollte überprüft werden.
        makeStatement($insertQuery, $arrayV); // Die vorbereitete SQL-Anweisung mit der Funktion 'makeStatement' ausführen, um die Daten in die Datenbank einzufügen.

        // Die neue ID der Straße auch ausgeben.
        $strID = $con->lastInsertId(); // Die zuletzt eingefügte ID (Auto-Increment-Wert) aus der Datenbankverbindung ($con) abrufen und in $strID speichern.
        echo "<h3>Die neue Straße '".$strasse."' wurde mit der Nr. '".$strID."' eingefügt.</h3>"; // Erfolgsmeldung ausgeben.

        $query = 'select * from strasse order by str_name'; // SQL-Anweisung zum Abrufen aller Straßennamen aus der Datenbank, sortiert nach 'str_name'.
        makeTable($query); // Die Funktion 'makeTable' aufrufen, um die Ergebnisse in einer Tabelle darzustellen.
    } 
    catch (Exception $e)
    {
        echo 'Error - Straße einfügen - '.$e->getCode().': '.$e->getMessage(); // Fehler behandeln und eine entsprechende Fehlermeldung ausgeben.
    }
} 
else
{
    // Formular anzeigen
    echo '<h2>Neuen Straßennamen eingeben </h2>';

    ?>
    <form method="post">
        <label for="str">Straßenname:</label>
        <input type="text" id="str" name="strasse" required placeholder="z.B. Wiener Straße">
        <br>
        <input type="submit" name="save" value="speichern">
    </form>
    <?php
}
?>
