<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ort hinzufügen</title>
</head>
<body>

<form action="" method="post">
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
if (isset($_POST['submit'])) { // Wenn das Formular abgeschickt wurde
    $ort_name = $_POST['ort_name']; // Den eingegebenen Ortsnamen erhalten
    $plz = $_POST['plz']; // Die ausgewählte Postleitzahl erhalten

    $checkStmt = $con->prepare("SELECT * FROM ort WHERE ort_name = :ort_name");
    $checkStmt->bindParam(':ort_name', $ort_name);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        echo '<p>Der Ort/Die Stadt ' . htmlspecialchars($ort_name) . ' ist bereits vorhanden.</p>';
        // Überprüfen, ob der Ort bereits in der Datenbank existiert und eine entsprechende Meldung ausgeben
    } else {
        $insertStmt = $con->prepare("INSERT INTO ort (ort_name) VALUES (:ort_name)");
        $insertStmt->bindParam(':ort_name', $ort_name);
        $insertStmt->execute();

        $ort_id = $con->lastInsertId();

        $relationStmt = $con->prepare("INSERT INTO ort_plz (ort_id, plz_id) VALUES (:ort_id, :plz)");
        $relationStmt->bindParam(':ort_id', $ort_id);
        $relationStmt->bindParam(':plz', $plz);
        $relationStmt->execute();

        echo '<p>Die neue Stadt/der neue Ort ' . htmlspecialchars($ort_name) . ' wurde eingefügt.</p>';
        // Wenn der Ort nicht existiert, wird er zur Datenbank hinzugefügt und eine Erfolgsmeldung wird angezeigt
    }

    $retrieveStmt = $con->prepare("
        SELECT plz.plz_nr, ort.ort_name
        FROM ort_plz
        INNER JOIN ort ON ort_plz.ort_id = ort.ort_id
        INNER JOIN plz ON ort_plz.plz_id = plz.plz_id
    ");
    $retrieveStmt->execute();

    echo '<table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>PLZ</th>
            <th>Ort</th>
        </tr>';

    while ($row = $retrieveStmt->fetch(PDO::FETCH_ASSOC)) {
        $style = '';
        if ($row['ort_name'] == $ort_name && $checkStmt->rowCount() > 0) {
            $style = ' style="color: red;"';
        }
        echo '<tr>';
        echo '<td' . $style . '>' . $row['plz_nr'] . '</td>';
        echo '<td' . $style . '>' . $row['ort_name'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    // Eine Tabelle wird erstellt, um alle Orte und Postleitzahlen darzustellen. Der neu hinzugefügte Ort wird hervorgehoben.
}
?>

</body>
</html>
