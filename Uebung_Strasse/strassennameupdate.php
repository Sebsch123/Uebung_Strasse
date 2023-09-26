<?php
include 'funktionen.php'; // Einbinden der Datei 'funktionen.php', die Funktionen und Konfigurationen enthält.

if(isset($_POST['save'])) // Überprüfen, ob das Formular abgeschickt wurde.
{
    // to do ... Daten speichern
    $strID = $_POST['strID']; // Den Wert des 'strID'-Feldes aus dem Formular in die Variable $strID speichern.
    $neuerName = $_POST['neuerName']; // Den Wert des 'neuerName'-Feldes aus dem Formular in die Variable $neuerName speichern.
    try
    {
        // Straßenname aktualisieren
        $updateQuery = 'UPDATE strasse SET str_name = ? WHERE str_id = ?'; // SQL-Anweisung zum Aktualisieren eines Straßennamens in der Datenbanktabelle 'strasse'.
        $arrayV = array($neuerName, $strID); // Ein Array erstellen, das die Werte für das Update enthält.
        makeStatement($updateQuery, $arrayV); // Die vorbereitete SQL-Anweisung mit der Funktion 'makeStatement' ausführen, um den Straßennamen zu aktualisieren.

        echo "<h3>Der Straßenname wurde erfolgreich geändert.</h3>"; // Erfolgsmeldung ausgeben.

        // Aktuelle Liste der Straßennamen anzeigen
        $query = 'SELECT * FROM strasse ORDER BY str_name'; // SQL-Anweisung zum Abrufen aller Straßennamen aus der Datenbank, sortiert nach 'str_name'.
        makeTable($query); // Die Funktion 'makeTable' aufrufen, um die Ergebnisse in einer Tabelle darzustellen.
    } 
    catch (Exception $e)
    {
        echo 'Error - Straßenname ändern - '.$e->getCode().': '.$e->getMessage(); // Fehler behandeln und eine entsprechende Fehlermeldung ausgeben.
    }
} 
else
{
    // Formular anzeigen
    echo '<h2>Straßennamen bearbeiten</h2>'; // Überschrift ausgeben, um anzuzeigen, dass Straßennamen bearbeitet werden können.

    // Dropdown-Feld mit Straßennamen anzeigen
    $query = 'SELECT * FROM strasse ORDER BY str_id'; // SQL-Anweisung zum Abrufen aller Straßennamen aus der Datenbank, sortiert nach 'str_id'.
    $stmt = makeStatement($query); // Die Funktion 'makeStatement' aufrufen, um die Daten abzurufen und das Ergebnis in $stmt zu speichern.
    $strassen = $stmt->fetchAll(PDO::FETCH_ASSOC); // Alle abgerufenen Straßennamen als assoziatives Array in $strassen speichern.

    // Aktuelle Liste der Straßennamen anzeigen
    $query = 'SELECT * FROM strasse ORDER BY str_id'; // Erneute SQL-Anweisung zum Abrufen aller Straßennamen aus der Datenbank, sortiert nach 'str_id'.
    makeTable($query); // Die Funktion 'makeTable' aufrufen, um die Ergebnisse in einer Tabelle darzustellen.

    echo '<form method="post">
            <label for="strID">Straßenname auswählen:</label>
            <select id="strID" name="strID" required>
                <option value="">Bitte wählen</option>'; // Ein Dropdown-Menü erstellen, um einen Straßennamen auszuwählen.

    foreach ($strassen as $strasse) { // Durch das Array $strassen iterieren und Optionen für das Dropdown-Menü erstellen.
        echo '<option value="'.$strasse['str_id'].'">'.$strasse['str_name'].'</option>';
    }

    echo '</select>
            <br>
            <label for="neuerName">Neuer Name:</label>
            <input type="text" id="neuerName" name="neuerName" required>
            <br>
            <input type="submit" name="save" value="Ändern">
        </form>'; // Das Formular erstellen, um den neuen Straßennamen einzugeben und die Änderungen zu speichern.
}
?>
