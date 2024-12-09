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

$testing = 0;

if($testing==1) include 'header.html';


// clean incoming

$uid = $_POST['uid'];

$suspenddate = date('Y-m-d',strtotime($_POST['suspenddate'])); # cleans and sanitizes regardless of datepicker vs text (for Safari and IE)

$suspendreason = filter_var(trim(@$_POST["suspendreason"]), FILTER_SANITIZE_ADD_SLASHES);

$location = $_POST['location'];


// create statement

$statement[] = "UPDATE items SET suspended=1, suspenddate='$suspenddate', suspendreason='$suspendreason' WHERE itemuid=$uid;";


// execute statement

$tables = array ("items");
fn_mysqli_executestatements ($testing, $mysqli_link, $tables, $statement, basename(__FILE__));



if($testing==1) {
  include 'footer_plain.html';
  exit();
} else {
  header("Location: $location");
}

?>