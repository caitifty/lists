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

require 'db.inc';
require 'functions_php.inc';

// login.php - performs validation, creates the session variables

// authenticate using form variables

$user=filter_var(trim($_POST["user"]), FILTER_SANITIZE_STRING);
$pass=filter_var(trim($_POST["pass"]), FILTER_SANITIZE_STRING);

$query = "SELECT pass, disabled FROM users WHERE username = '$user';";
$result = mysqli_query($mysqli_link,$query);

$location="index.php?fail=1";


if (mysqli_num_rows($result) == 1) {  

	$value=mysqli_fetch_array($result);

	if(password_verify($pass, $value['pass']) and $value['disabled']==0) {

        $location="list_universal.php?type=all";

	}
}
	
mysqli_free_result($result);
mysqli_close($mysqli_link);
header("Location: $location");
exit();


?>
