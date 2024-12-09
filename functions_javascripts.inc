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


/*
on1(), off1(): hidethis1 hidethis2..

on2(), off2(): hidethat1 hidethat2..

on3(), off3(): hidetheother1 hidetheother2..

on4(), off4(): hideanother1 hideanother2..

on5(), off5(): hideonemore1 hideonemore2..

on6(), off6(): hidejustonemore1 hidejustonemore2..
*/


echo '<script type="text/javascript">
function off1() {
  var e, i = 1;
  do {
    e = document.getElementById("hidethis" + i++);
    if (e) e.style.display = \'none\';
  } while (e);
}
function on1() {
  var e, i = 1;
  do {
    e = document.getElementById("hidethis" + i++);
    if (e) {
      try { e.style.display = \'table-row\'; } // DOM
      catch (err) { e.style.display = \'block\'; } // IE
    }
  } while (e);
}
</script>';

echo '<script type="text/javascript">
function off2() {
  var e, i = 1;
  do {
    e = document.getElementById("hidethat" + i++);
    if (e) e.style.display = \'none\';
  } while (e);
}
function on2() {
  var e, i = 1;
  do {
    e = document.getElementById("hidethat" + i++);
    if (e) {
      try { e.style.display = \'table-row\'; } // DOM
      catch (err) { e.style.display = \'block\'; } // IE
    }
  } while (e);
}
</script>';

echo '<script type="text/javascript">
function off3() {
  var e, i = 1;
  do {
    e = document.getElementById("hidetheother" + i++);
    if (e) e.style.display = \'none\';
  } while (e);
}
function on3() {
  var e, i = 1;
  do {
    e = document.getElementById("hidetheother" + i++);
    if (e) {
      try { e.style.display = \'table-row\'; } // DOM
      catch (err) { e.style.display = \'block\'; } // IE
    }
  } while (e);
}
</script>';

echo '<script type="text/javascript">
function off4() {
  var e, i = 1;
  do {
    e = document.getElementById("hideanother" + i++);
    if (e) e.style.display = \'none\';
  } while (e);
}
function on4() {
  var e, i = 1;
  do {
    e = document.getElementById("hideanother" + i++);
    if (e) {
      try { e.style.display = \'table-row\'; } // DOM
      catch (err) { e.style.display = \'block\'; } // IE
    }
  } while (e);
}
</script>';

echo '<script type="text/javascript">
function off5() {
  var e, i = 1;
  do {
    e = document.getElementById("hideonemore" + i++);
    if (e) e.style.display = \'none\';
  } while (e);
}
function on5() {
  var e, i = 1;
  do {
    e = document.getElementById("hideonemore" + i++);
    if (e) {
      try { e.style.display = \'table-row\'; } // DOM
      catch (err) { e.style.display = \'block\'; } // IE
    }
  } while (e);
}
</script>';

echo '<script type="text/javascript">
function off6() {
  var e, i = 1;
  do {
    e = document.getElementById("hidejustonemore" + i++);
    if (e) e.style.display = \'none\';
  } while (e);
}
function on6() {
  var e, i = 1;
  do {
    e = document.getElementById("hidejustonemore" + i++);
    if (e) {
      try { e.style.display = \'table-row\'; } // DOM
      catch (err) { e.style.display = \'block\'; } // IE
    }
  } while (e);
}
</script>';


#######################################################################################
#
#	Example use
#

/*

echo '<tr><td colspan="2"><b>In the last 30 days did you spend one or more nights in jail or prison?</b></td></tr>';
  echo '<tr><td colspan="2">';
	echo '<input type="radio" name="edit_incarcerated30days" value="yes" onClick="on1();"> Yes<br>';
	echo '<input type="radio" name="edit_incarcerated30days" value="no" onClick="off1();"> No<br>';
  echo '</td></tr>';
#echo '<tr><td colspan="2">Help notes.</td></tr>';
echo '<tr><td colspan="2">&nbsp;</td></tr>';

echo '<tr id="hidethis1" style="display:none;"><td colspan="2"><b>Did you get any naloxone when you left?</b></td></tr>';
  echo '<tr id="hidethis2" style="display:none;"><td colspan="2">';
	echo '<input type="radio" name="edit_nxfromjail" value="yes"> Yes<br>';
	echo '<input type="radio" name="edit_nxfromjail" value="no"> No<br>';
  echo '</td></tr>';
echo '<tr id="hidethis3" style="display:none;"><td colspan="2">LA County Jail now has a naloxone dispensing machine in discharge waiting areas.</td></tr>';
echo '<tr id="hidethis4" style="display:none;"><td colspan="2">&nbsp;</td></tr>';

echo '<tr><td colspan="2"><b>Have you ever overdosed?</b></td></tr>';
  echo '<tr><td colspan="2">';
	echo '<input type="radio" name="edit_everod" value="yes" onClick="on2();"> Yes<br>';
	echo '<input type="radio" name="edit_everod" value="no" onClick="off2();"> No<br>';
  echo '</td></tr>';
#echo '<tr><td colspan="2">Help notes.</td></tr>';
echo '<tr><td colspan="2">&nbsp;</td></tr>';

echo '<tr id="hidethat1" style="display:none;"><td colspan="2"><b>Have you ever been Narcaned?</b></td></tr>';
  echo '<tr id="hidethat2" style="display:none;"><td colspan="2">';
	echo '<input type="checkbox" name="edit_evernarcaned_no" value="yes"> No<br>';
	echo '<input type="checkbox" name="edit_evernarcaned_nonmedical" value="yes"> Yes, by a non-medical person (friend, partner/spouse etc)<br>';
	echo '<input type="checkbox" name="edit_evernarcaned_emt" value="yes"> Yes, by EMT/paramedics<br>';
	echo '<input type="checkbox" name="edit_evernarcaned_erstaff" value="yes"> Yes, by ER staff<br>';
	echo '<input type="checkbox" name="edit_evernarcaned_lawenforcement" value="yes"> Yes, by law enforcement<br>';
	echo '<input type="checkbox" name="edit_evernarcaned_otheragency" value="yes"> Yes, by staff of some other agency (eg shelter, treatment agency etc)<br>';
  echo '</td></tr>';
#echo '<tr id="hidethat3" style="display:none;"><td colspan="2">Help notes.</td></tr>';
echo '<tr id="hidethat3" style="display:none;"><td colspan="2">&nbsp;</td></tr>';

echo '<tr><td colspan="2"><b>Have you ever been there when someone else overdosed?</b></td></tr>';
  echo '<tr><td colspan="2">';
	echo '<input type="radio" name="edit_everwitnessedod" value="yes" onClick="on3();"> Yes<br>';
	echo '<input type="radio" name="edit_everwitnessedod" value="no" onClick="off3();"> No<br>';
	echo '<input type="radio" name="edit_everwitnessedod" value="unsure" onClick="off3();"> Not sure<br>';
  echo '</td></tr>';
#echo '<tr><td colspan="2">Help notes.</td></tr>';
echo '<tr><td colspan="2">&nbsp;</td></tr>';

echo '<tr id="hidetheother1" style="display:none;"><td colspan="2"><b>Have you ever used naloxone/Narcan before?</b></td></tr>';
  echo '<tr id="hidetheother2" style="display:none;"><td colspan="2">';
	echo '<input type="radio" name="edit_everusednarcan" value="yes"> Yes<br>';
	echo '<input type="radio" name="edit_everusednarcan" value="no"> No<br>';
	echo '<input type="radio" name="edit_everusednarcan" value="dontknow"> Don\'t know<br>';
  echo '</td></tr>';
#echo '<tr id="hidetheother3" style="display:none;"><td colspan="2">Help notes.</td></tr>';
echo '<tr id="hidetheother3" style="display:none;"><td colspan="2">&nbsp;</td></tr>';

*/

?>
