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


require 'header.html';

echo '<br><br><br>';


@$fail=$_GET['fail'];
if(isset($fail)) {
	echo '<h3 align="center" style="color:Tomato">This username and password did not work.  Please try again.</h3>';
	echo '<p align="center">Or call/text/email Pete for assistance.</p>';	
	echo '<br>';
}


echo '<form action="login_clean.php" method="POST">';
echo '<table border="0" cellspacing="5" cellpadding="0" class="center">';
echo '	<tr><td><input size="10" maxlength="20" type="text" name="user" autocomplete="off" autocapitalize="off" spellcheck="false" placeholder="Username"autofocus></td></tr>';
echo '	<tr><td align="center">&nbsp;</td></tr>';
echo '	<tr><td><input size="10" maxlength="20" type="password" name="pass" placeholder="Password"></td></tr>';
echo '	<tr><td align="center">&nbsp;</td></tr>';
echo '	<tr><td align="right"><input type="submit" value="Login"></td></tr>';
echo '	<tr><td align="center">&nbsp;</td></tr>';
echo '</table>';
echo '</form>';


include 'footer_plain.html';
?>