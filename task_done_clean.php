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


require_once 'db.inc';
require_once 'functions_php.inc';

// set $testing to 1 to test, 0 for production

$testing=0;

if($testing==1) include 'header.html';


$uid = $_GET['uid'];
$itemuid = $_GET['itemuid'];
$location = $_GET['location'].".php#".$itemuid;

$statement[] = "UPDATE tasks SET completed=1, completeddate=now() WHERE uniqueid=$uid;";


$tables = array ("tasks");
fn_mysqli_executestatements ($testing, $mysqli_link, $tables, $statement, basename(__FILE__));


if($testing==1) {
  include 'footer_plain.html';
  exit();
} else {
  header("Location: $location");
}

?>