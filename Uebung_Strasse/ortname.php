<?php
include 'funktionen.php'; // Einbinden der Datei 'funktionen.php', die Funktionen enthält, die im Code verwendet werden

if (isset($_POST['save'])) { // Prüfen, ob das Formular abgeschickt wurde und die 'save'-Schaltfläche gedrückt wurde
    // to do ... Daten speichern
    $ort = $_POST['ort']; // Den Wert des 'ort'-Felds aus dem Formular in die Variable $ort speichern

    try {
        // Überprüfen, ob der Ort bereits in der Datenbank vorhanden ist
        $checkQuery = 'SELECT COUNT(*) FROM ort WHERE ort_name = ?'; // SQL-Abfrage, um die Anzahl der Datensätze mit dem gegebenen Ort zu ermitteln
        $checkStmt = makeStatement($checkQuery, array($ort)); // Verwendung einer benutzerdefinierten Funktion 'makeStatement', um die Abfrage vorzubereiten
        $count = $checkStmt->fetchColumn(); // Ausführen der Abfrage und Speichern des Ergebnisses (Anzahl der übereinstimmenden Datensätze) in $count

        if ($count > 0) { // Wenn bereits Datensätze mit diesem Ort vorhanden sind
            echo "<h3>Der Ort '".$ort."' ist bereits vorhanden.</h3>"; // Eine Meldung anzeigen, dass der Ort bereits in der Datenbank existiert
        } else {
            // JavaScript-Bestätigungsabfrage anzeigen, um sicherzustellen, dass der Benutzer den Ort hinzufügen möchte
            echo '<script>
                    if (confirm("Möchten Sie den Ort \''.$ort.'\' wirklich in die Datenbank einfügen?")) {
                        document.getElementById("confirmForm").ort.value = "'.$ort.'"; // Den Wert an das versteckte Feld im Formular übergeben
                        document.getElementById("confirmForm").submit(); // Das Formular automatisch abschicken
                    }
                </script>';
        }

        // Aktuelle Liste der Orte anzeigen, unabhängig davon, ob der Ort bereits vorhanden ist oder nicht
        $query = 'SELECT * FROM ort ORDER BY ort_id'; // SQL-Abfrage, um die Liste der Orte aus der Datenbank abzurufen und nach Ort-ID zu sortieren
        makeTable($query); // Verwendung der Funktion 'makeTable', um eine Tabelle mit den Orten anzuzeigen
    } catch (Exception $e) {
        echo 'Fehler - Ort einfügen - '.$e->getCode().': '.$e->getMessage(); // Fehlermeldung anzeigen, falls ein Fehler auftritt
    }
} elseif (isset($_POST['confirm'])) { // Prüfen, ob das Formular nach Bestätigung abgeschickt wurde
    // Daten speichern, nachdem der Benutzer die Bestätigung abgegeben hat
    $ort = $_POST['ort']; // Den Wert des 'ort'-Felds aus dem Formular in die Variable $ort speichern

    try {
        // Ort in die Datenbank einfügen
        $insertQuery = 'INSERT INTO ort (ort_name) VALUES (?)'; // SQL-Abfrage, um den neuen Ort in die Datenbank einzufügen
        $arrayV = array($ort); // Ein Array erstellen, das den zu speichernden Ort enthält
        $stmt = makeStatement($insertQuery, $arrayV); // Verwendung der Funktion 'makeStatement', um die Abfrage vorzubereiten

        if ($stmt) { // Überprüfen, ob das Einfügen erfolgreich war
            // Die neue ID des Orts ausgeben
            $ortID = $con->lastInsertId(); // Die zuletzt eingefügte ID in der Datenbank abrufen und in $ortID speichern
            echo "<h3>Der neue Ortsname '".$ort."' wurde mit der Nummer '".$ortID."' eingefügt.</h3>"; // Erfolgsmeldung anzeigen

            // Aktuelle Liste der Orte anzeigen, einschließlich des neuen Ortsnamens
            $query = 'SELECT * FROM ort ORDER BY ort_id'; // SQL-Abfrage, um die Liste der Orte aus der Datenbank abzurufen und nach Ort-ID zu sortieren
            makeTable($query); // Verwendung der Funktion 'makeTable', um eine Tabelle mit den Orten anzuzeigen
        } else {
            echo "<h3>Fehler beim Einfügen des Orts '".$ort."' in die Datenbank.</h3>"; // Fehlermeldung anzeigen, wenn das Einfügen fehlschlägt
        }
    } catch (Exception $e) {
        echo 'Fehler - Ort einfügen - '.$e->getCode().': '.$e->getMessage(); // Fehlermeldung anzeigen, falls ein Fehler auftritt
    }
} else {
    // Formular anzeigen, wenn keine der oben genannten Bedingungen erfüllt ist (normaler Anfangszustand)
    echo '<h2>Neuen Ortsnamen eingeben </h2>'; // Eine Überschrift für das Formular anzeigen

    // Aktuelle Liste der Orte anzeigen
    $query = 'SELECT * FROM ort ORDER BY ort_id'; // SQL-Abfrage, um die Liste der Orte aus der Datenbank abzurufen und nach Ort-ID zu sortieren
    makeTable($query); // Verwendung der Funktion 'makeTable', um eine Tabelle mit den Orten anzuzeigen

    ?>
    <form method="post" id="confirmForm"> <!-- Ein Formular erstellen, das die Eingabe des neuen Ortsnamens ermöglicht -->
        <label for="ort">Ortsname:</label> <!-- Ein Label für das 'ort'-Feld anzeigen -->
        <input type="text" id="ort" name="ort" required placeholder="z.B. Linz"> <!-- Ein Texteingabefeld für den Ortsnamen anzeigen -->
        <br>
        <input type="hidden" name="confirm" value="true"> <!-- Ein verstecktes Feld hinzufügen, um die Bestätigung zu überprüfen -->
        <input type="submit" name="save" value="speichern"> <!-- Eine Schaltfläche anzeigen, um das Formular abzuschicken -->
    </form>
    <?php
}
?>
