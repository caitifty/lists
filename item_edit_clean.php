<?php

 /*   
    Copyright (C) 2021-2023 Peter J. Davidson

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

require_once 'db.inc';
require_once 'functions_php.inc';

// set $testing to 1 to test, 0 for production

$testing = 0;

if($testing==1) include 'header.html';


// clean incoming variables

$button = $_POST['button'];

if($testing == 1) echo "<p>button: $button</p>";

$itemuid = $_POST['itemuid'];

$location = $_POST['location'].".php#$itemuid";

$outcome=filter_var(trim($_POST["outcome"]), FILTER_SANITIZE_ADD_SLASHES);

$tag = filter_var(trim(@$_POST["tag"]), FILTER_SANITIZE_ADD_SLASHES);
$tags = explode(",", $tag);


$duedate=$_POST["duedate"];
$duedate=date('Y-m-d',strtotime($duedate)); # cleans and sanitizes regardless of datepicker vs text (for Safari and IE)
if($duedate <= "1970-01-01") $duedate = "NULL"; else $duedate = "'$duedate'";

$timeout = date('Y-m-d',strtotime($_POST["timeout"])); # cleans and sanitizes regardless of datepicker vs text (for Safari and IE)
if($timeout == "") $timeout = "'1970-01-01'"; else $timeout = "'$timeout'";


$impact_family = fn_mysqlizero(@$_POST["impact_family"]);
$impact_social = fn_mysqlizero(@$_POST["impact_social"]);
$impact_career = fn_mysqlizero(@$_POST["impact_career"]);
$impact_personal = fn_mysqlizero(@$_POST["impact_personal"]);
$impact_fiveyears = fn_mysqlizero(@$_POST["impact_fiveyears"]);


$newstep = filter_var(trim($_POST["newstep"]), FILTER_SANITIZE_ADD_SLASHES );
$steporder = filter_var(trim($_POST["steporder"]), FILTER_SANITIZE_NUMBER_INT );

$urgency = $_POST['urgency'];
$importance = $_POST['importance'];
$bindecui = bindec($urgency.$importance);

$suspended = $_POST['suspended'];


// create statement

if($button == "Edit") {
$statement[] = "UPDATE items SET 
outcome='$outcome', 
duedate=$duedate, 
timeout=$timeout, 
impact_family=$impact_family, 
impact_social=$impact_social, 
impact_career=$impact_career, 
impact_personal=$impact_personal, 
impact_fiveyears=$impact_fiveyears, 
urgency=$urgency, 
importance=$importance, 
bindecui=$bindecui, 
suspended=$suspended 
WHERE itemuid=$itemuid;";
} else {
  $statement[] = "UPDATE items SET completed=1, completeddate=now() WHERE itemuid=$itemuid;";
}





if($newstep <> "") {
  if($steporder <> "") {
    $query = "SELECT uniqueid, taskorder FROM tasks WHERE itemuniqueid=$itemuid AND taskorder>$steporder ORDER BY taskorder ASC;";
    $result = mysqli_query($mysqli_link, $query);
    while($val = mysqli_fetch_array($result)) {
      $newto = $val['taskorder'] + 1;
      $statement[] = "UPDATE tasks SET taskorder = $newto WHERE uniqueid=$val[uniqueid];";
    }
    mysqli_free_result($result);
    $newto = $steporder + 1;
  } else {
    $query = "SELECT taskorder FROM tasks WHERE itemuniqueid=$itemuid ORDER BY taskorder DESC limit 1;";
    $result = mysqli_query($mysqli_link, $query);
    if(mysqli_num_rows($result) == 0) {
      $newto = 0;
    } else {
      while($val = mysqli_fetch_array($result)) $newto = $val['taskorder'] + 1;
    }
    mysqli_free_result($result);
  }
  $statement[] = "INSERT INTO `tasks` (itemuniqueid, task, taskorder, completed) VALUES ($itemuid, '$newstep', $newto, 0);";
}


if(!empty($tags) and $button == "Edit") {
  $statement[] = "DELETE FROM tags WHERE itemuniqueid=$itemuid;";
  foreach($tags as $tg) {
    $tg = trim($tg);
    $statement[] = "INSERT INTO tags (itemuniqueid, tag) VALUES ($itemuid, '$tg');";
  }
}


$tables = array ("items", "tasks", "tags");
fn_mysqli_executestatements ($testing, $mysqli_link, $tables, $statement, basename(__FILE__));


if($testing==1) {
  include 'footer_plain.html';
  exit();
} else {
  header("Location: $location");
}

?>
