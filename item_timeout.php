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

$location = $_GET['location'].".php";
$uid = $_GET['uid'];

$page = basename(__FILE__, '.php');
$pagetype = "";

include 'navbar.php';


echo '<br><br><br>';

#echo "<p>location: $location</p>";
#echo "<p>uid: $uid</p>";

echo '<form action="item_timeout_clean.php" method="POST">';

echo '<input type="hidden" name="uid" value="'.$uid.'">';
echo '<input type="hidden" name="location" value="'.$location.'">';

echo '<table class="twocol">';

echo '<tr><td align="center"><H3>Timeout until when</H3></td></tr>';
echo '<tr><td>&nbsp;</td></tr>';

echo '<tr><td colspan="2"><input size="16" maxlength="46" type="date" name="timeoutdate" value="'.date('Y-m-d', strtotime("+1 day")).'" required></td></tr>';
echo '<tr><td colspan="2">&nbsp;</td></tr>';

echo '<tr><td>&nbsp;</td></tr>';

echo '<tr><td><input type="submit" value="Submit"></td></tr>';

echo '</table>';

echo '</form>';


include 'footer_plain.html';


?>
