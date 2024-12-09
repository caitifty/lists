<?php

 /*   
    Copyright (C) 2021-2022 Peter J. Davidson

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published
    by the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program, in the file LICENSE.txt.  If not, see <https://www.gnu.org/licenses/>.
*/


include 'header.html';
require_once 'db.inc';
require_once 'functions_php.inc';

$page = basename(__FILE__, '.php');
$pagetype = "";

include 'navbar.php';

// recalculate universalsortscore for every non-completed item

fn_universalscore (0, $mysqli_link);


echo '<br>';

echo '<table class="twocol">';

$today = "'".date('Y-m-d')."'";

// periodics (show all)

$itemstatement = "SELECT itemuid, outcome, timeout FROM items WHERE periodic=1 AND timeout<=$today AND completed=0 ORDER BY impact_total DESC;";
$itemresult = mysqli_query($mysqli_link, $itemstatement);
while($itemvalue = mysqli_fetch_array($itemresult)) {
    echo '<tr>';
        echo '<td id="'.$itemvalue['itemuid'].'"><b><a href="item_edit.php?itemuid='.$itemvalue['itemuid'].'&location='.$page.'">'.$itemvalue['outcome'].'</a></b></td>';
        echo '<td><a href="item_done_clean.php?uid='.$itemvalue['itemuid'].'&location='.$page.'">Done</a>';
        echo '<td><a href="item_timeout.php?uid='.$itemvalue['itemuid'].'&location='.$page.'">'.$itemvalue['timeout'].'</a></td>';
    echo '</tr>';
    $taskstatement = "SELECT uniqueid, task FROM tasks WHERE itemuniqueid = $itemvalue[itemuid] AND completed=0 ORDER BY taskorder ASC;";
    $taskresult = mysqli_query($mysqli_link, $taskstatement);
    while($taskvalue = mysqli_fetch_array($taskresult)) {
        echo '<tr>';
            echo '<td>'.$taskvalue['task'].'</td>';
            echo '<td colspan="2"><a href="task_done_clean.php?uid='.$taskvalue['uniqueid'].'&location='.$page.'&itemuid='.$itemvalue['itemuid'].'">done</a></td>';
        echo '</tr>';
    }
    mysqli_free_result($taskresult);
    echo '<tr><td colspan="3">&nbsp;</td></tr>';
}
mysqli_free_result($itemresult);

echo '<tr><td colspan="3"><hr></td></tr>';
echo '<tr><td colspan="3">&nbsp;</td></tr>';

// everything else

$itemstatement = "SELECT itemuid, outcome, duedate, timeout, impact_total FROM items WHERE dotoday=CURRENT_DATE() AND timeout<=CURRENT_DATE() AND periodic=0 AND completed=0 ORDER BY impact_total DESC;";
$itemresult = mysqli_query($mysqli_link, $itemstatement);
while($itemvalue = mysqli_fetch_array($itemresult)) {
    if($itemvalue['duedate'] == "") $duedate = ""; else $duedate = $itemvalue['duedate'];
    if($itemvalue['timeout'] == "1970-01-01" or $itemvalue['timeout'] == "1969-12-31") $timeout = "Timeout"; else $timeout = $itemvalue['timeout'];
    echo '<tr>';
        echo '<td id="'.$itemvalue['itemuid'].'"><b><a href="item_edit.php?itemuid='.$itemvalue['itemuid'].'&location='.$page.'">'.$itemvalue['outcome'].'</a></b> '.$duedate.'</td>';
        echo '<td><a href="item_done_clean.php?uid='.$itemvalue['itemuid'].'&location='.$page.'">Done</a></td>';
        echo '<td><a href="item_timeout.php?uid='.$itemvalue['itemuid'].'&location='.$page.'">'.$timeout.'</a></td>';
    echo '</tr>';
    $taskstatement = "SELECT uniqueid, task FROM tasks WHERE itemuniqueid = $itemvalue[itemuid] AND completed=0 ORDER BY taskorder ASC;";
    $taskresult = mysqli_query($mysqli_link, $taskstatement);
    while($taskvalue = mysqli_fetch_array($taskresult)) {
        echo '<tr>';
            echo '<td>'.$taskvalue['task'].'</td>';
            echo '<td colspan="2"><a href="task_done_clean.php?uid='.$taskvalue['uniqueid'].'&location='.$page.'&itemuid='.$itemvalue['itemuid'].'">done</a></td>';
        echo '</tr>';
    }
    mysqli_free_result($taskresult);
    echo '<tr><td colspan="3">&nbsp;</td></tr>';
}
mysqli_free_result($itemresult);




echo '</table>';

echo '<br>';

mysqli_close($mysqli_link);

include 'footer_plain.html'; 


?>