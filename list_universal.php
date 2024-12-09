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

$pagetype = @$_GET['type'];
if($pagetype == "") $pagetype = "all";

// get current tag plus list of possible tags

$query = "SELECT tag FROM tags GROUP BY tag;";
$result = mysqli_query($mysqli_link, $query);
while($temp = mysqli_fetch_array($result)) $taglist[]=$temp[0];
mysqli_free_result($result);

// nav bar

include 'navbar.php';


// recalculate universalsortscore for every non-completed item

fn_universalscore (0, $mysqli_link);

echo '<br>';


echo '<table class="twocol">';
#echo '<table rules="all" width="60%">';

$today = "'".date('Y-m-d')."'";


// periodics (show all due regardless of pagetype and tag *except* pagetype suspended)

if($pagetype <> "suspend") {
	$itemstatement = "SELECT itemuid, outcome, timeout FROM items WHERE periodic=1 AND timeout<=$today AND completed=0 ORDER BY universalsortscore DESC;";
	$itemresult = mysqli_query($mysqli_link, $itemstatement);
	while($itemvalue = mysqli_fetch_array($itemresult)) {
	    echo '<tr>';
	        echo '<td id="'.$itemvalue['itemuid'].'"><b><a href="item_edit.php?itemuid='.$itemvalue['itemuid'].'&location='.$page.'">'.$itemvalue['outcome'].'</a></b></td>';
	        echo '<td><a href="item_timeout.php?uid='.$itemvalue['itemuid'].'&location='.$page.'">'.$itemvalue['timeout'].'</a></td>';
	    echo '</tr>';
	    $taskstatement = "SELECT uniqueid, task FROM tasks WHERE itemuniqueid = $itemvalue[itemuid] AND completed=0 ORDER BY taskorder ASC;";
	    $taskresult = mysqli_query($mysqli_link, $taskstatement);
	    while($taskvalue = mysqli_fetch_array($taskresult)) {
	        echo '<tr><td colspan="2">'.$taskvalue['task'].'</td></tr>';
	    }
	    mysqli_free_result($taskresult);
	    echo '<tr><td colspan="2">&nbsp;</td></tr>';
	}
	mysqli_free_result($itemresult);

	echo '<tr><td colspan="2"><hr></td></tr>';
	echo '<tr><td colspan="2">&nbsp;</td></tr>';
}

// regular (show list based on pagetype)

$varlist = "itemuid, outcome, duedate, timeout, urgency, importance, impact_fiveyears, universalsortscore, suspended, suspenddate, suspendreason";

switch ($pagetype) {

    case "due":
        if($tagnavlocation == "") {
            $itemstatement = "SELECT $varlist FROM items WHERE (suspended=0 OR (suspended=1 AND suspenddate>=$today)) AND periodic=0 AND timeout<=$today AND duedate IS NOT NULL AND duedate<=$today AND completed=0 ORDER BY universalsortscore DESC;";
        } else {
            $itemstatement = "SELECT $varlist FROM items, tags WHERE items.itemuid=tags.itemuniqueid AND tag='$tagnavlocation' AND (suspended=0 OR (suspended=1 AND suspenddate>=$today)) AND periodic=0 AND timeout<=$today AND duedate IS NOT NULL AND duedate<=$today AND completed=0 ORDER BY universalsortscore DESC;";
        }
        $_SESSION['pagetype'] = "due";
        break;

    case "ui":
        if($tagnavlocation == "") {
            $itemstatement = "SELECT $varlist FROM items WHERE (suspended=0 OR (suspended=1 AND suspenddate>=$today)) AND periodic=0 AND (urgency=1 or importance=1) AND completed=0 ORDER BY universalsortscore DESC;";
        } else {
            $itemstatement = "SELECT $varlist FROM items, tags WHERE items.itemuid=tags.itemuniqueid AND tag='$tagnavlocation' AND (suspended=0 OR (suspended=1 AND suspenddate>=$today)) AND periodic=0 AND (urgency=1 or importance=1) AND completed=0 ORDER BY universalsortscore DESC;";
        }
        $_SESSION['pagetype'] = "ui";
        break;

    case "suspend":
        if($tagnavlocation == "") {
            $itemstatement = "SELECT $varlist FROM items WHERE suspended=1 AND periodic=0 AND (urgency=1 or importance=1) AND completed=0 ORDER BY universalsortscore DESC;";
        } else {
            $itemstatement = "SELECT $varlist FROM items, tags WHERE items.itemuid=tags.itemuniqueid AND tag='$tagnavlocation' AND suspended=1 AND periodic=0 AND (urgency=1 or importance=1) AND completed=0 ORDER BY universalsortscore DESC;";
        }
        $_SESSION['pagetype'] = "suspend";
        break;


    default:
        $_SESSION['pagetype'] = "all";
        if($tagnavlocation == "") {
            $itemstatement = "SELECT $varlist FROM items WHERE periodic=0 AND completed=0 ORDER BY universalsortscore DESC;";
        } else{
            $itemstatement = "SELECT $varlist FROM items, tags WHERE items.itemuid=tags.itemuniqueid AND tag='$tagnavlocation' AND periodic=0 AND completed=0 ORDER BY universalsortscore DESC;";
        }

}

$itemresult = mysqli_query($mysqli_link, $itemstatement);
while($itemvalue = mysqli_fetch_array($itemresult)) {

#    if($itemvalue['duedate'] == "") $duedate = ""; else $duedate = $itemvalue['duedate'];
    if($itemvalue['timeout'] == "1970-01-01" or $itemvalue['timeout'] == "1969-12-31") $timeout = "Timeout"; else $timeout = "T: ".$itemvalue['timeout'];
	if($itemvalue['suspended'] == 1) $suspended = "S: ".$itemvalue['suspenddate']; else $suspended = "Suspend";

	if($pagetype <> "suspend") {

	    echo '<tr>';
	        echo '<td><a href="item_edit.php?itemuid="'.$itemvalue['itemuid'].'"><b><a href="item_edit.php?itemuid='.$itemvalue['itemuid'].'&location='.$page.'">'.$itemvalue['outcome'].'</a></b></td>';
	        echo '<td>';
		        echo '<a href="item_done_clean.php?uid='.$itemvalue['itemuid'].'&location='.$page.'">D</a> ';
			    echo '<a href="item_timeout.php?uid='.$itemvalue['itemuid'].'&location='.$page.'">T</a> ';
			    echo '<a href="item_suspend.php?uid='.$itemvalue['itemuid'].'&location='.$page.'">S</a>';
		    echo '</td>';
	    echo '</tr>';

	    if($itemvalue['suspended'] == 1) {

		    echo '<tr><td>Waiting for: '.$itemvalue['suspendreason'].'</td><td>&nbsp;</td></tr>';

	    } else {

		    $taskstatement = "SELECT uniqueid, task FROM tasks WHERE itemuniqueid = $itemvalue[itemuid] AND completed=0 ORDER BY taskorder ASC;";
		    $taskresult = mysqli_query($mysqli_link, $taskstatement);
		    while($taskvalue = mysqli_fetch_array($taskresult)) {
		        echo '<tr>';
		            echo '<td>'.$taskvalue['task'].'</td>';
		            echo '<td><a href="task_done_clean.php?uid='.$taskvalue['uniqueid'].'&location='.$page.'&itemuid='.$itemvalue['itemuid'].'">d</a></td>';
		        echo '</tr>';
		    }
		    mysqli_free_result($taskresult);

	    }


	} else {

	    echo '<tr>';
	        echo '<td><a href="item_edit.php?itemuid="'.$itemvalue['itemuid'].'"><b><a href="item_edit.php?itemuid='.$itemvalue['itemuid'].'&location='.$page.'">'.$itemvalue['outcome'].'</a></b></td>';
		    echo '<td>';
			    echo '<a href="item_suspend.php?uid='.$itemvalue['itemuid'].'&location='.$page.'">'.$suspended.'</a>';
		    echo '</td>';
	    echo '</tr>';

	    echo '<tr><td>Waiting for: '.$itemvalue['suspendreason'].'</td><td>&nbsp;</td></tr>';

	}

    echo '<tr><td colspan="2">&nbsp;</td></tr>';


}
mysqli_free_result($itemresult);



echo '</table>';

echo '<br>';

mysqli_close($mysqli_link);

include 'footer_plain.html'; 


?>