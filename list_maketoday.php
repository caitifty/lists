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

echo '<br>';

echo '<form action="list_maketoday_clean.php" method="POST">';

echo '<table class="twocol">';

$query = "SELECT itemuid, outcome, dotoday FROM items WHERE completed=0 AND periodic=0 AND timeout<=CURRENT_DATE() ORDER BY bindecui DESC;";
$result = mysqli_query($mysqli_link, $query);

while($val = mysqli_fetch_array($result)) {

    if($val['dotoday'] == date('Y-m-d')) $checked = "checked"; else $checked = "";

    echo '<tr>';
    echo '<td style="font-weight:bold">'.$val['outcome'].'</td>';
    echo '<td><label class = "containerbox"><input type = "checkbox" name = "'.$val['itemuid'].'" value = "1" '.$checked.'><span class = "checkmarkbox"></span></label></td>';
    echo '</tr>';

    echo '<tr><td colspan="2">&nbsp;</td></tr>';

}

mysqli_free_result($result);

echo '<tr><td>&nbsp;</td><td align="right"><input type="submit" value="Update"></td></tr>';

echo '</table>';

echo '</form>';

echo '<br>';

mysqli_close($mysqli_link);

include 'footer_plain.html'; 


?>