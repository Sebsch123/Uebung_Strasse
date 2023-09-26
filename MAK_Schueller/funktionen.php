<?php

// Überprüfung, ob die Funktion 'makeStatement' bereits existiert; wenn nicht, wird sie definiert
if (!function_exists('makeStatement')) {
    function makeStatement($query, $arrayValues = NULL)
    {
        global $con; // Hier wird die globale Verbindung zur Datenbank verwendet, die an anderer Stelle im Code erstellt wurde
        $stmt = $con->prepare($query); // Vorbereiten einer SQL-Abfrage unter Verwendung der globalen Datenbankverbindung
        $stmt->execute($arrayValues); // Ausführen der vorbereiteten Abfrage mit optionalen Werten in einem Array
        return $stmt; // Rückgabe des vorbereiteten Statements
    }
}

// Überprüfung, ob die Funktion 'makeTable' bereits existiert; wenn nicht, wird sie definiert
if (!function_exists('makeTable')) {
    function makeTable($query)
    {
        global $con; // Hier wird die globale Verbindung zur Datenbank verwendet
        $stmt = $con->prepare($query); // Vorbereiten einer SQL-Abfrage unter Verwendung der globalen Datenbankverbindung
        $stmt->execute(); // Ausführen der vorbereiteten Abfrage, ohne optionale Werte
        $meta = array(); // Ein Array zum Speichern von Metadaten (Attributeigenschaften) der Abfrageergebnisse

        echo '<table class="table">
            <tr>';
        // Spaltenbezeichner ausgeben
        for ($i = 0; $i < $stmt->columnCount(); $i++) {
            $meta[] = $stmt->getColumnMeta($i); // Metadaten für jede Spalte abrufen und im $meta-Array speichern
            echo '<td>' . $meta[$i]['name'] . '</td>'; // Den Spaltennamen in einer Tabellenzelle ausgeben
        }
        echo '</tr>';
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            echo '<tr>';
            foreach ($row as $r) {
                echo '<td>' . $r . '</td>'; // Datenzeilen in Tabellenzellen ausgeben
            }
            echo '</tr>';
        }
        echo '</table>';
    }
}
?>
