<?php  
echo '<h2>Alle PLZ (aufsteigend) ausgeben</h2>';

$query = '
select plz_nr
from PLZ
order by plz_nr';
makeTable($query);
?>