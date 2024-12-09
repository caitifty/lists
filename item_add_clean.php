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

$outcome=filter_var(trim($_POST["outcome"]), FILTER_SANITIZE_ADD_SLASHES );
$periodic = $_POST['periodic'];

$duedate=date('Y-m-d',strtotime($_POST["duedate"])); # cleans and sanitizes regardless of datepicker vs text (for Safari and IE)
if($duedate <= "1970-01-01") $duedate = "NULL"; else $duedate = "'$duedate'";

$timeout = date('Y-m-d',strtotime($_POST["timeout"])); # cleans and sanitizes regardless of datepicker vs text (for Safari and IE)
if($timeout == "") $timeout = "'1970-01-01'"; else $timeout = "'$timeout'";

$urgency = fn_mysqlizero(@$_POST['urgency']);
$importance = fn_mysqlizero(@$_POST['importance']);
$bindecui = bindec($urgency.$importance);

$tag = filter_var(trim(@$_POST["tag"]), FILTER_SANITIZE_ADD_SLASHES);
$tags = explode(",", $tag);

$impact_family = fn_mysqlizero(@$_POST["impact_family"]);
$impact_social = fn_mysqlizero(@$_POST["impact_social"]);
$impact_career = fn_mysqlizero(@$_POST["impact_career"]);
$impact_personal = fn_mysqlizero(@$_POST["impact_personal"]);
$impact_fiveyears = fn_mysqlizero(@$_POST["impact_fiveyears"]);

$step0 = filter_var(trim(@$_POST["step0"]), FILTER_SANITIZE_ADD_SLASHES);
$step1 = filter_var(trim(@$_POST["step1"]), FILTER_SANITIZE_ADD_SLASHES);
$step2 = filter_var(trim(@$_POST["step2"]), FILTER_SANITIZE_ADD_SLASHES);
$step3 = filter_var(trim(@$_POST["step3"]), FILTER_SANITIZE_ADD_SLASHES);
$step4 = filter_var(trim(@$_POST["step4"]), FILTER_SANITIZE_ADD_SLASHES);
$step5 = filter_var(trim(@$_POST["step5"]), FILTER_SANITIZE_ADD_SLASHES);
$step6 = filter_var(trim(@$_POST["step6"]), FILTER_SANITIZE_ADD_SLASHES);

// get next itemuid

$query = "SELECT itemuid FROM items ORDER BY itemuid DESC LIMIT 1;";
$result = mysqli_query($mysqli_link, $query);
while($value = mysqli_fetch_array($result)) $itemuid = $value['itemuid'];
mysqli_free_result($result);
$itemuid++;

// create statements

if($periodic == "Regular") {
$statement[] = "INSERT INTO `items` 
(itemuid, outcome, duedate, impact_family, impact_social, impact_career, impact_personal, impact_fiveyears, urgency, importance, bindecui, timeout) VALUES (
$itemuid, 
'$outcome', 
$duedate, 
$impact_family, 
$impact_social, 
$impact_career, 
$impact_personal, 
$impact_fiveyears, 
$urgency, 
$importance, 
$bindecui,
$timeout);";
} else {
  $statement[] = "INSERT INTO items (itemuid, outcome, timeout, periodic) VALUES ($itemuid, '$outcome', $duedate, 1);";
}

if($step0 <> "") $statement[] = "INSERT INTO `tasks` (itemuniqueid, task, taskorder, completed) VALUES ($itemuid, '$step0', 0, 0);";
if($step1 <> "") $statement[] = "INSERT INTO `tasks` (itemuniqueid, task, taskorder, completed) VALUES ($itemuid, '$step1', 1, 0);";
if($step2 <> "") $statement[] = "INSERT INTO `tasks` (itemuniqueid, task, taskorder, completed) VALUES ($itemuid, '$step2', 2, 0);";
if($step3 <> "") $statement[] = "INSERT INTO `tasks` (itemuniqueid, task, taskorder, completed) VALUES ($itemuid, '$step3', 3, 0);";
if($step4 <> "") $statement[] = "INSERT INTO `tasks` (itemuniqueid, task, taskorder, completed) VALUES ($itemuid, '$step4', 4, 0);";
if($step5 <> "") $statement[] = "INSERT INTO `tasks` (itemuniqueid, task, taskorder, completed) VALUES ($itemuid, '$step5', 5, 0);";
if($step6 <> "") $statement[] = "INSERT INTO `tasks` (itemuniqueid, task, taskorder, completed) VALUES ($itemuid, '$step6', 6, 0);";

if(!empty($tags)) {
  foreach($tags as $tg) {
    $tg = trim($tg);
    $statement[] = "INSERT INTO tags (itemuniqueid, tag) VALUES ($itemuid, '$tg');";
  }
}

// Lock affected tables, run statements, unlock tables

$tables = array ("items", "tasks", "tags");
fn_mysqli_executestatements ($testing, $mysqli_link, $tables, $statement, basename(__FILE__));


if($testing==1) {
  include 'footer_plain.html';
  exit();
} else {
  header("Location: list_universal.php?type=all");
}

?>
