<?php  
echo '<h2>Alle Adressen ausgeben</h2>'; // Zeige eine Überschrift auf der Seite an, um anzuzeigen, dass alle Adressen ausgegeben werden.

$query = '
select str_name as "Strasse",
    plz_nr as "PLZ",
    ort_name as "Ort"
from ort natural join ort_plz
         natural join plz
         natural join strasse_ort_plz
         natural join strasse
order by PLZ, Strasse';
// Die oben gezeigte SQL-Abfrage wird in der Variable $query gespeichert. Diese Abfrage ruft Daten aus verschiedenen Tabellen ab, darunter "strasse", "plz", "ort", "strasse_ort_plz" und "ort_plz". Sie wählt Spalten aus und verwendet NATURAL JOIN, um Daten aus diesen Tabellen zu verknüpfen. Die Abfrage ordnet die Ergebnisse nach Postleitzahl (PLZ) und Straßenname (Strasse).

makeTable($query);
// Hier wird die Funktion "makeTable" aufgerufen, um die Ergebnisse der SQL-Abfrage in einer Tabelle auf der Seite anzuzeigen. Die Funktion wird mit der SQL-Abfrage ($query) als Argument aufgerufen.
?>
