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

$testing=0;

if($testing==1) include 'header.html';


// clean incoming variables

$query = "SELECT itemuid FROM items WHERE completed=0;";
$result = mysqli_query($mysqli_link, $query);
while($val = mysqli_fetch_array($result)) {

	$uv = $val['itemuid']."_urgency";
	$urgency = $_POST[$uv];

	$iv = $val['itemuid']."_importance";
	$importance = $_POST[$iv];

	$bindecui = bindec($urgency.$importance);

	$statement[] = "UPDATE items SET importance=$importance, urgency=$urgency, bindecui=$bindecui WHERE itemuid=$val[itemuid];";

}

$location = $_POST['location'].".php";


// Lock affected tables, run statements, unlock tables

$tables = array ("items");
fn_mysqli_executestatements ($testing, $mysqli_link, $tables, $statement, basename(__FILE__));


if($testing==1) {
  include 'footer_plain.html';
  exit();
} else {
  header("Location: $location");
}

?>
