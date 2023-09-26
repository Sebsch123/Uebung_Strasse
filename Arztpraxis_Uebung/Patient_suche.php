<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Patientensuche in Arztpraxis</title>
</head>
<body>
    <?php require_once 'config.php'; // Einbinden der Konfigurationsdatei für die Datenbankverbindung ?>

    <form method="post">
        <h2>Suche nach SV-Nr. und Geburtsdatum</h2>
        <label for="svnr">SV-Nr.:</label>
        <input type="text" name="svnr" id="svnr">
        <label for="gebdatum">Geburtsdatum:</label>
        <input type="date" name="gebdatum" id="gebdatum">
        <hr>
        <h2>Filter nach Behandlungszeitraum (Optional)</h2>
        <label for="start">Startdatum:</label>
        <input type="date" name="start" id="start">
        <label for="ende">Enddatum:</label>
        <input type="date" name="ende" id="ende">
        <hr>
        <input type="submit" value="Suchen">
    </form>

    <?php
    // Die Eingabevariablen aus dem Formular erfassen
    $svnr = $_POST['svnr'] ?? null;
    $gebdatum = $_POST['gebdatum'] ?? null;
    $start = $_POST['start'] ?? null;
    $ende = $_POST['ende'] ?? null;

    // Wenn das Formular abgeschickt wurde
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Überprüfen, ob die SV-Nr. 4-stellig ist
        if (strlen($svnr) != 4) {
            echo "Bitte die richtige 4-stellige SV-Nr. eingeben.";
            exit;
        }

        // SQL-Abfrage vorbereiten, um Daten aus der Datenbank abzurufen
        $query = "SELECT p.per_vname, p.per_nname, p.per_svnr, p.per_geburt, b.ter_beginn, b.ter_ende, d.dia_name
                  FROM Person p
                  INNER JOIN Behandlungszeitraum b ON p.id_Person = b.id_Person
                  INNER JOIN Diagnose d ON b.id_Diagnose = d.id_Diagnose
                  WHERE p.per_svnr = :svnr AND p.per_geburt = :gebdatum";

        // Parameter für die SQL-Abfrage festlegen
        $params = ['svnr' => $svnr, 'gebdatum' => $gebdatum];

        // Wenn ein Enddatum angegeben wurde, wird es zur SQL-Abfrage hinzugefügt
        if ($ende) {
            $query .= " AND b.ter_ende <= :ende";
            $params['ende'] = $ende;
        }

        // Wenn sowohl Start- als auch Enddatum angegeben wurden, werden sie zur SQL-Abfrage hinzugefügt
        if ($start && $ende) {
            $query .= " AND b.ter_beginn >= :start";
            $params['start'] = $start;
        }

        // SQL-Abfrage vorbereiten und mit den Parametern ausführen
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        // Ergebnisse der SQL-Abfrage abrufen
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Wenn keine Ergebnisse gefunden wurden, wird eine Meldung angezeigt
        if (empty($results)) {
            echo "<p>Keine Ergebnisse gefunden.</p>";
            exit;
        }

        // Ergebnisse in einer Tabelle darstellen
        echo "<table border='1'>";
        echo "<tr><th>Vorname</th><th>Nachname</th><th>SV-Nr.</th><th>Geburtsdatum</th><th>Behandlungsbeginn</th><th>Behandlungsende</th><th>Diagnose</th></tr>";
        foreach ($results as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['per_vname']) . "</td>";
            echo "<td>" . htmlspecialchars($row['per_nname']) . "</td>";
            echo "<td>" . htmlspecialchars($row['per_svnr']) . "</td>";
            echo "<td>" . htmlspecialchars($row['per_geburt']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ter_beginn']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ter_ende']) . "</td>";
            echo "<td>" . htmlspecialchars($row['dia_name']) . "</td>";
            echo "</tr>";
        }
    
        echo "</table>";
    }
    ?>
</body>
</html>
