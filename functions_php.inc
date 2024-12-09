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

##########################################
#
#   Some global variables
#

$static_weight_family = 1;
$static_weight_career = 1;
$static_weight_social = 1;
$static_weight_personal = 1;
$static_weight_fiveyears = 2;





##########################################
#
#   Functions
#


// fn_universalscore

function fn_universalscore ($test, $link) {

  $weight_fiveyears = 2;
  $weight_urgency_hard = 2.5;


  mysqli_query($link, "LOCK TABLES items WRITE;");

  $query = "SELECT uniqueid, duedate, urgency, importance, impact_fiveyears FROM items WHERE completed=0;";
  $result = mysqli_query($link, $query);

  while($value = mysqli_fetch_array($result)) {
      if($value['duedate'] <> "") {
          $days = (strtotime($value['duedate']) - strtotime(date('Y-m-d')))/86400; # number of days between due date and today
          if($days > 90) $days = 90;
          $score = ceil((91-$days)/18);
          $score = $score * $weight_urgency_hard;
      } else {
          $score = 2.5;
      }

      $score = $score + ($value['impact_fiveyears'] * $weight_fiveyears); 

      if($value['urgency'] == 1) $score = $score + 10;
      if($value['importance'] == 1) $score = $score + 10;

      mysqli_query($link, "UPDATE items SET universalsortscore=$score WHERE uniqueid=$value[uniqueid];");

  }

  mysqli_free_result($result);

  mysqli_query($link, "UNLOCK TABLES;");

}

// fn_mysqli_executestatements

function fn_mysqli_executestatements ($test, $link, $tables, $stmnt, $filenm) {
  /*
    Lock affected tables, run statements, unlock tables

    $test: $testing (0 or 1)
    $link = $mysqli_link
    $tables = array of tables which will be written to
    $stmnt = array of statements
    $filenm = name of file the function being called from, usually basename(__FILE__)

  */

  if($test == 0) {

#    include (__DIR__.'/db.inc');

    $tablestring = "LOCK TABLES ";
    foreach ($tables as $val) $tablestring = $tablestring . $val . " WRITE, ";
    $tablestring = trim($tablestring, ", ");
    $tablestring = $tablestring . ";";
    mysqli_query($link, $tablestring);

    foreach($stmnt as $value) {
      if(!mysqli_query($link, $value)) {
        mysqli_query($link,"UNLOCK TABLES;");
        mysqli_close($link);
        include(__DIR__.'/header.html');
#        echo '<br><br><p align="center">'.$static_mainerrormessage.'</p><br><br>';
        echo '<p>A mysql statement died in '.$filenm.'.</p><br>';
        echo "<p>$value</p><br>";
        include(__DIR__.'/footer_plain.html');
        exit();
      } 
    }
    mysqli_query($link,"UNLOCK TABLES;");
    mysqli_close($link);

  } else {

    if(isset($stmnt)) {
      foreach($stmnt as $value) {
        echo "<p>$value</p><br>";
      } 
    }

  }

}

//----fn_mysqlinullorquote----//

// returns string as NULL if empty and single quoted otherwise (eg for strings)

function fn_mysqlinullorquote($var){
  if($var=="") { 
    $var="NULL"; 
  } else { 
    $var="'$var'"; 
  }
  return $var;
}

// returns string as 'MISSING' if empty and single quoted otherwise (eg for strings)

function fn_mysqlimissingorquote($var){
  if($var=="") { 
    $var="'MISSING'"; 
  } else { 
    $var="'$var'"; 
  }
  return $var;
}


// returns string as 'No' if empty and single quoted otherwise (eg for yes/no input where value=yes)

function fn_mysqlinoorquote($var){
  if($var=="") { 
    $var="'No'"; 
  } else { 
    $var="'$var'"; 
  }
  return $var;
}


//----fn_mysqlinull----//

// returns string as NULL if empty and unaltered otherwise (eg for numeric)

function fn_mysqlinull($var){
  if($var=="") $var="NULL"; 
  return $var;
}

//----fn_mysqlizero----//

// returns string as 0 (zero) if empty and unaltered otherwise (eg for numeric with not null)

function fn_mysqlizero($var){
  if($var=="") $var=0; 
  return $var;
}


//----fn_mysqliresult----//

// gets a single row result back from a query

function fn_mysqliresult($res,$row=0,$col=0){
	if (mysqli_num_rows($res) && $row <= (mysqli_num_rows($res)-1) && $row >= 0){
		mysqli_data_seek($res,$row);
		$resrow = mysqli_fetch_row($res);
		if (isset($resrow[$col])){
			return $resrow[$col];
		}
	}
	return false;
}



//----fn_mysqldate_mdy----// 

// converts mysql date (YYYY-MM-DD) to m/d/yyyy string
 

function fn_mysqldate_mdy($val) {
	if(!empty($val)) {
	  $arr=trim($val);
	  $arr=explode("-",$val);
	  $arr[0]=substr($arr[0],2);
	  $arr[1]=(int)$arr[1];
	  $arr[2]=(int)$arr[2];
	  return ($arr[1].'/'.$arr[2].'/'.$arr[0]);
	} else {
	  return ("");
	}
}

//----fn_STANDARDTOMYSQLI----//

function fn_standardtomysqli_date($val) {
      $arr=trim($val);
      $arr=explode("/",$val);
   $arr[0]=preg_replace('/\D/', '', $arr[0]);
   $arr[1]=preg_replace('/\D/', '', $arr[1]);
   $arr[2]=preg_replace('/\D/', '', $arr[2]);
      if ($arr[2]>30 and $arr[2]<100) $arr[2]=$arr[2]+1900;
      if ($arr[2]<=30) $arr[2]=$arr[2]+2000;
      $arr[0]=str_pad($arr[0],2,"0",STR_PAD_LEFT);
      $arr[1]=str_pad($arr[1],2,"0",STR_PAD_LEFT);
      $arr[2]=str_pad($arr[2],4,"0",STR_PAD_LEFT);
      return ($arr[2].'-'.$arr[0].'-'.$arr[1]);
}









?>