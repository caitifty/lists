<?php

 /*   
    Copyright (C) 2023 Peter J. Davidson

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

// get tag list and load into datalist html5 element (see https://www.kodingmadesimple.com/2015/08/autocomplete-textbox-html5-datalist-php-mysql-example.html)

$query = "SELECT tag FROM tags GROUP BY tag;";
$result = mysqli_query($mysqli_link, $query);
echo '<datalist id="taglist">';
while($row = mysqli_fetch_array($result)) echo '<option value="'.$row['tag'].'">'.$row['tag'].'</option>';
echo '</datalist>';
mysqli_free_result($result);


$itemuid = $_GET['itemuid'];

$location = $_GET['location'];

$query = "SELECT suspended, suspenddate, suspendreason, periodic, outcome, duedate, timeout, impact_family, impact_social, impact_career, impact_personal, impact_fiveyears, impact_urgency, impact_total, urgency, importance FROM items WHERE itemuid=$itemuid;";
$result = mysqli_query($mysqli_link, $query);
while($val = mysqli_fetch_array($result)) {
  $periodic=$val['periodic'];
  $outcome=$val['outcome'];
  $duedate=$val['duedate'];
  $timeout=$val['timeout'];
  $impact_family=$val['impact_family'];
  $impact_social=$val['impact_social'];
  $impact_career=$val['impact_career'];
  $impact_personal=$val['impact_personal'];
  $impact_fiveyears=$val['impact_fiveyears'];
  $impact_urgency=$val['impact_urgency'];
  $impact_total=$val['impact_total'];
  $urgency = $val['urgency'];
  $importance = $val['importance'];
  $suspended = $val['suspended'];
  $suspenddate = $val['suspenddate'];
  $suspendreason = $val['suspendreason'];
}
mysqli_free_result($result);

$tag = "";
$query = "SELECT tag FROM tags WHERE itemuniqueid=$itemuid;";
$result = mysqli_query($mysqli_link, $query);
while($tagval = mysqli_fetch_array($result)) $tags[] = $tagval['tag'];
mysqli_free_result($result);
if(!empty($tags)) {
  foreach($tags as $tg) $tag = $tag.$tg.", ";
  $tag = substr($tag, 0, -2);
} else {
  $tag = "";
}



echo '<br><br><br>';

echo '<form action="item_edit_clean.php" method="POST">';

echo '<input type="hidden" name="itemuid" value="'.$itemuid.'">';

echo '<input type="hidden" name="location" value="'.$location.'">';

echo '<table class="twocol">';

echo '<tr><td colspan="2" align="center"><H3>Edit item</H3></td></tr>';
echo '<tr><td colspan="2">&nbsp;</td></tr>';

echo '<tr>';
  echo '<td style="font-weight:bold">Outcome</td>';
  echo '<td><input size="36" type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="true" name="outcome" value="'.$outcome.'"></td>';
echo '</tr>';

echo '<tr>';
  echo '<td style="font-weight:bold">Due date</td>';
  echo '<td><input size="16" maxlength="46" type="date" name="duedate" value="'.$duedate.'"></td>';
echo '</tr>';

echo '<tr>';
  echo '<td style="font-weight:bold">Not before / timeout date</td>';
  echo '<td><input size="16" maxlength="46" type="date" name="timeout" value="'.$timeout.'"></td>';
echo '</tr>';

echo '<tr>';
  echo '<td style="font-weight:bold">Tag/s</td>';
  if($tag == "") {
      echo '<td><input type="text" id="tag" name="tag" autocomplete="off" list="taglist" placeholder="Comma-separated tag/s"></td>';
  } else {
      echo '<td><input type="text" id="tag" name="tag" autocomplete="off" list="taglist" value="'.$tag.'"></td>';
  }
echo '</tr>';
echo '<tr><td colspan="2">&nbsp;</td></tr>';



function fn_whichcheck($val) {
    $c = array_fill(1,5,"");
    $c[$val] = " checked";
    return $c;
}


if($urgency == 1) {
  $yeschecked = " checked";
  $nochecked = "";
} else {
  $yeschecked = "";
  $nochecked = " checked";
}

echo '<tr>';
  echo '<td align="right" style="font-weight:bold">Is it <i>urgent</i>?</td>';
  echo '<td>';
    echo '<input type="radio" name="urgency" value="1" '.$yeschecked.'> Yes &nbsp; ';
    echo '<input type="radio" name="urgency" value="0" '.$nochecked.'> No';
  echo '</td>';
echo '</tr>';


if($importance == 1) {
  $yeschecked = " checked";
  $nochecked = "";
} else {
  $yeschecked = "";
  $nochecked = " checked";
}

echo '<tr>';
  echo '<td align="right" style="font-weight:bold">Is it <i>important</i>?</td>';
  echo '<td>';
    echo '<input type="radio" name="importance" value="1" '.$yeschecked.'> Yes &nbsp; ';
    echo '<input type="radio" name="importance" value="0" '.$nochecked.'> No';
  echo '</td>';
echo '</tr>';


/*
echo '<tr>';
  echo '<td align="right" style="font-weight:bold">Impact on family</td>';
  echo '<td>';
    $check = fn_whichcheck($impact_family);
    echo '<input type="radio" name="impact_family" value="1"'.$check[1].'>&nbsp;';
    echo '<input type="radio" name="impact_family" value="2"'.$check[2].'>&nbsp;';
    echo '<input type="radio" name="impact_family" value="3"'.$check[3].'>&nbsp;';
    echo '<input type="radio" name="impact_family" value="4"'.$check[4].'>&nbsp;';
    echo '<input type="radio" name="impact_family" value="5"'.$check[5].'>&nbsp;';
  echo '</td>';
echo '</tr>';

echo '<tr>';
  echo '<td align="right" style="font-weight:bold">Impact on social relations</td>';
  echo '<td>';
    $check = fn_whichcheck($impact_social);
    echo '<input type="radio" name="impact_social" value="1"'.$check[1].'>&nbsp;';
    echo '<input type="radio" name="impact_social" value="2"'.$check[2].'>&nbsp;';
    echo '<input type="radio" name="impact_social" value="3"'.$check[3].'>&nbsp;';
    echo '<input type="radio" name="impact_social" value="4"'.$check[4].'>&nbsp;';
    echo '<input type="radio" name="impact_social" value="5"'.$check[5].'>&nbsp;';
  echo '</td>';
echo '</tr>';

echo '<tr>';
  echo '<td align="right" style="font-weight:bold">Impact on career</td>';
  echo '<td>';
    $check = fn_whichcheck($impact_career);
    echo '<input type="radio" name="impact_career" value="1"'.$check[1].'>&nbsp;';
    echo '<input type="radio" name="impact_career" value="2"'.$check[2].'>&nbsp;';
    echo '<input type="radio" name="impact_career" value="3"'.$check[3].'>&nbsp;';
    echo '<input type="radio" name="impact_career" value="4"'.$check[4].'>&nbsp;';
    echo '<input type="radio" name="impact_career" value="5"'.$check[5].'>&nbsp;';
  echo '</td>';
echo '</tr>';

echo '<tr>';
  echo '<td align="right" style="font-weight:bold">Impact on personal wellbeing</td>';
  echo '<td>';
    $check = fn_whichcheck($impact_personal);
    echo '<input type="radio" name="impact_personal" value="1"'.$check[1].'>&nbsp;';
    echo '<input type="radio" name="impact_personal" value="2"'.$check[2].'>&nbsp;';
    echo '<input type="radio" name="impact_personal" value="3"'.$check[3].'>&nbsp;';
    echo '<input type="radio" name="impact_personal" value="4"'.$check[4].'>&nbsp;';
    echo '<input type="radio" name="impact_personal" value="5"'.$check[5].'>&nbsp;';
  echo '</td>';
echo '</tr>';
*/
echo '<tr>';
  echo '<td align="right" style="font-weight:bold">Impact in five years</td>';
  echo '<td>';
    $check = fn_whichcheck($impact_fiveyears);
    echo '<input type="radio" name="impact_fiveyears" value="1"'.$check[1].'>&nbsp;';
    echo '<input type="radio" name="impact_fiveyears" value="2"'.$check[2].'>&nbsp;';
    echo '<input type="radio" name="impact_fiveyears" value="3"'.$check[3].'>&nbsp;';
    echo '<input type="radio" name="impact_fiveyears" value="4"'.$check[4].'>&nbsp;';
    echo '<input type="radio" name="impact_fiveyears" value="5"'.$check[5].'>&nbsp;';
  echo '</td>';
echo '</tr>';


echo '<tr><td colspan="2">&nbsp;</td></tr>';
echo '<tr><td colspan="2" style="font-weight:bold">Tasks</td></tr>';

$query = "SELECT task, taskorder FROM tasks WHERE itemuniqueid=$itemuid AND completed=0 ORDER BY taskorder ASC;";
$result = mysqli_query($mysqli_link, $query);
while($val = mysqli_fetch_array($result)) {
	echo '<tr><td>'.$val['task'].'</td><td>'.$val['taskorder'].'</td></tr>';
}
mysqli_free_result($result);
	
echo '<tr>';
  echo '<td><input size="36" type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="true" name="newstep" placeholder="New task"></td>';
  echo '<td><input size="3" type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="true" name="steporder" placeholder="Insert after.."></td>';
echo '</tr>';


echo '<tr><td colspan="2">&nbsp;</td></tr>';

if($suspended == 1) $checkyes = " checked"; else $checkyes = "";
if($suspended == 0) $checkno = " checked"; else $checkno = "";

echo '<tr>';
  echo '<td style="font-weight:bold">Suspended:</td>';
  echo '<td>';
    echo '<label class="container">Yes<input type="radio" name="suspended" value="1"'.$checkyes.'><span class="checkmark"></span></label>';
    echo '<label class="container">No<input type="radio" name="suspended" value="0"'.$checkno.'><span class="checkmark"></span></label>';
  echo '</td>';
echo '</tr>';



echo '<tr><td colspan="2"><input type="submit" name="button" value="Edit"></td></tr>';
if($periodic == 1) echo '<tr><td colspan="2"><input type="submit" name="button" value="Mark as Done"></td></tr>';

echo '</table>';

echo '</form>';


include 'footer_plain.html';


?>
