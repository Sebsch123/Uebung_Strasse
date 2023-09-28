<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ort hinzufügen</title>
</head>
<body>

<form action="" method="post">
    <!-- Ein Formular zum Hinzufügen eines Ortes mit Ortsnamen und Postleitzahl -->
    <label for="ort_name">Ortsname:</label>
    <input type="text" name="ort_name" required>
    <br>
    <label for="plz">PLZ:</label>
    <select name="plz" style="width: 200px;">
        <?php
        require_once 'config.php'; // Einbindung der Konfigurationsdatei für die Datenbankverbindung

        $stmt = $con->prepare("SELECT * FROM plz"); // Vorbereitung einer SQL-Abfrage, um Postleitzahlen abzurufen
        $stmt->execute(); // Ausführen der SQL-Abfrage

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' . $row['plz_id'] . '">' . $row['plz_nr'] . '</option>';
            // Erzeugen von Dropdown-Optionen basierend auf den abgerufenen Postleitzahlen
        }
        ?>
    </select>
    <br>
    <input type="submit" name="submit" value="Speichern">
</form>

<?php
if (isset($_POST['submit'])) { // Überprüft, ob das Formular abgeschickt wurde
    $ort_name = $_POST['ort_name']; // Den eingegebenen Ortsnamen erhalten
    $plz = $_POST['plz']; // Die ausgewählte Postleitzahl erhalten

    // Überprüfung, ob der Ort bereits in der Datenbank existiert
    $checkStmt = $con->prepare("SELECT * FROM ort WHERE ort_name = :ort_name");
    $checkStmt->bindParam(':ort_name', $ort_name); // Den Ortsnamen an den Platzhalter in der SQL-Anweisung binden
    $checkStmt->execute(); // Die SQL-Anweisung ausführen

    if ($checkStmt->rowCount() > 0) {
        // Wenn der Ort bereits existiert, eine Meldung ausgeben
        echo '<p>Der Ort/Die Stadt ' . htmlspecialchars($ort_name) . ' ist bereits vorhanden.</p>';
    } else {
        // Wenn der Ort nicht existiert, füge ihn zur Datenbank hinzu
        $insertStmt = $con->prepare("INSERT INTO ort (ort_name) VALUES (:ort_name)");
        $insertStmt->bindParam(':ort_name', $ort_name); // Den Ortsnamen an den Platzhalter in der SQL-Anweisung binden
        $insertStmt->execute(); // Die SQL-Anweisung ausführen

        // Die zuletzt eingefügte ID abrufen (falls benötigt)
        $ort_id = $con->lastInsertId();

        // Eine Beziehung zwischen Ort und Postleitzahl herstellen
        $relationStmt = $con->prepare("INSERT INTO ort_plz (ort_id, plz_id) VALUES (:ort_id, :plz)");
        $relationStmt->bindParam(':ort_id', $ort_id); // Ort-ID an den Platzhalter binden
        $relationStmt->bindParam(':plz', $plz); // Postleitzahl an den Platzhalter binden
        $relationStmt->execute(); // Die SQL-Anweisung ausführen

        // Eine Erfolgsmeldung ausgeben
        echo '<p>Die neue Stadt/der neue Ort ' . htmlspecialchars($ort_name) . ' wurde eingefügt.</p>';
    }

    // Daten aus der Datenbank abrufen, um sie in einer Tabelle darzustellen
    $retrieveStmt = $con->prepare("
        SELECT plz.plz_nr, ort.ort_name
        FROM ort_plz
        INNER JOIN ort ON ort_plz.ort_id = ort.ort_id
        INNER JOIN plz ON ort_plz.plz_id = plz.plz_id
    ");
    $retrieveStmt->execute(); // Die SQL-Anweisung ausführen

    // Eine HTML-Tabelle erstellen, um Orte und Postleitzahlen darzustellen
    echo '<table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>PLZ</th>
            <th>Ort</th>
        </tr>';

    // Daten aus der Abfrage in die Tabelle einfügen
    while ($row = $retrieveStmt->fetch(PDO::FETCH_ASSOC)) {
        $style = '';
        // Wenn der Ort neu hinzugefügt wurde, hervorheben
        if ($row['ort_name'] == $ort_name && $checkStmt->rowCount() > 0) {
            $style = ' style="color: red;"';
        }
        echo '<tr>';
        echo '<td' . $style . '>' . $row['plz_nr'] . '</td>';
        echo '<td' . $style . '>' . $row['ort_name'] . '</td>';
        echo '</tr>';
    }
    echo '</table>'; // Die Tabelle schließen
}
?>
