<?php

if (!function_exists('makeStatement')) {
    // Überprüfen, ob die Funktion 'makeStatement' bereits existiert. Falls nicht, wird sie definiert.
    function makeStatement($query, $arrayValues = NULL)
    {
        global $con; // Die globale Datenbankverbindung wird verwendet.
        $stmt = $con->prepare($query); // Vorbereiten der SQL-Anweisung mit der Datenbankverbindung.
        $stmt->execute($arrayValues); // Ausführen der vorbereiteten Anweisung mit optionalen Array-Werten.
        return $stmt; // Das vorbereitete Statement wird zurückgegeben.
    }
}

if (!function_exists('makeTable')) {
    // Überprüfen, ob die Funktion 'makeTable' bereits existiert. Falls nicht, wird sie definiert.
    function makeTable($query)
    {
        global $con; // Die globale Datenbankverbindung wird verwendet.
        $stmt = $con->prepare($query); // Vorbereiten der SQL-Anweisung mit der Datenbankverbindung.
        $stmt->execute(); // Ausführen der vorbereiteten Anweisung ohne zusätzliche Werte.
        $meta = array(); // Ein Array zur Speicherung der Spaltenmetadaten wird erstellt.

        echo '<table class="table">
            <tr>';
        // Spaltenüberschriften werden in der Tabelle ausgegeben.
        for ($i = 0; $i < $stmt->columnCount(); $i++) {
            $meta[] = $stmt->getColumnMeta($i); // Die Metadaten der aktuellen Spalte werden abgerufen und im Array 'meta' gespeichert.
            echo '<td>' . $meta[$i]['name'] . '</td>'; // Die Spaltenüberschrift wird ausgegeben.
        }
        echo '</tr>';
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            // Die Ergebnisdaten werden zeilenweise abgerufen und in einer Tabelle ausgegeben.
            echo '<tr>';
            foreach ($row as $r) {
                echo '<td>' . $r . '</td>'; // Die Daten einer Zeile werden in den entsprechenden Zellen ausgegeben.
            }
            echo '</tr>';
        }
        echo '</table>';
    }
}
?>
