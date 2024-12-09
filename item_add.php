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
require_once 'functions_javascripts.inc';

// get tag list and load into datalist html5 element (see https://www.kodingmadesimple.com/2015/08/autocomplete-textbox-html5-datalist-php-mysql-example.html)

$query = "SELECT tag FROM tags GROUP BY tag;";
$result = mysqli_query($mysqli_link, $query);
echo '<datalist id="taglist">';
while($row = mysqli_fetch_array($result)) echo '<option value="'.$row['tag'].'">'.$row['tag'].'</option>';
echo '</datalist>';
mysqli_free_result($result);

echo '<br><br><br>';

echo '<form action="item_add_clean.php" method="POST">';

echo '<table class="twocol">';

echo '<tr><td colspan="2" align="center"><H3>New item</H3></td></tr>';
echo '<tr><td colspan="2" align="center">To add a new item, fill in the fields below and click \'Add\' at the end.</td></tr>';
echo '<tr><td colspan="2">&nbsp;</td></tr>';

echo '<tr>';
  echo '<td style="font-weight:bold">Outcome</td>';
  echo '<td><input size="36" type="text" autocomplete="off" autocorrect="off" autocapitalize="on" spellcheck="true" name="outcome" autofocus></td>';
echo '</tr>';
echo '<tr><td colspan="2">&nbsp;</td></tr>';


echo '<tr>';
  echo '<td align="right" style="font-weight:bold">Periodic or regular</td>';
  echo '<td>';
    echo '<label class="container">Regular<input type="radio" name="periodic" value="Regular" checked><span class="checkmark"></span></label>';
    echo '<label class="container">Periodic<input type="radio" name="periodic" value="Periodic"><span class="checkmark"></span></label>';
#    echo '<input type="radio" name="periodic" value="Regular" checked> Regular ';
#    echo '<input type="radio" name="periodic" value="Periodic"> Periodic ';
  echo '</td>';
echo '</tr>';
echo '<tr><td colspan="2">&nbsp;</td></tr>';

echo '<tr>';
  echo '<td style="font-weight:bold">Due date</td>';
  echo '<td><input size="16" maxlength="46" type="date" name="duedate"></td>';
echo '</tr>';
echo '<tr><td colspan="2">&nbsp;</td></tr>';

echo '<tr>';
  echo '<td style="font-weight:bold">Not before / timeout date</td>';
  echo '<td><input size="16" maxlength="46" type="date" name="timeout"></td>';
echo '</tr>';
echo '<tr><td colspan="2">&nbsp;</td></tr>';

echo '<tr>';
  echo '<td style="font-weight:bold">Tag/s</td>';
  echo '<td><input type="text" id="tag" name="tag" autocomplete="off" list="taglist" placeholder="Comma-separated tag/s"></td>';
echo '</tr>';
echo '<tr><td colspan="2">&nbsp;</td></tr>';


echo '<tr>';
  echo '<td align="right" style="font-weight:bold">Is it <i>urgent</i>?</td>';
  echo '<td>';
    echo '<input type="radio" name="urgency" value="1"> Yes &nbsp; ';
    echo '<input type="radio" name="urgency" value="0"> No';
  echo '</td>';
echo '</tr>';

echo '<tr>';
  echo '<td align="right" style="font-weight:bold">Is it <i>important</i>?</td>';
  echo '<td>';
    echo '<input type="radio" name="importance" value="1"> Yes &nbsp; ';
    echo '<input type="radio" name="importance" value="0"> No';
  echo '</td>';
echo '</tr>';
echo '<tr><td colspan="2">&nbsp;</td></tr>';

/*
echo '<tr id="hidethis4" style="display:none;">';
  echo '<td align="right" style="font-weight:bold">Impact on family</td>';
  echo '<td>';
    echo '<input type="radio" name="impact_family" value="1">&nbsp;';
    echo '<input type="radio" name="impact_family" value="2">&nbsp;';
    echo '<input type="radio" name="impact_family" value="3">&nbsp;';
    echo '<input type="radio" name="impact_family" value="4">&nbsp;';
    echo '<input type="radio" name="impact_family" value="5">&nbsp;';
  echo '</td>';
echo '</tr>';

echo '<tr id="hidethis5" style="display:none;">';
  echo '<td align="right" style="font-weight:bold">Impact on social relations</td>';
  echo '<td>';
    echo '<input type="radio" name="impact_social" value="1">&nbsp;';
    echo '<input type="radio" name="impact_social" value="2">&nbsp;';
    echo '<input type="radio" name="impact_social" value="3">&nbsp;';
    echo '<input type="radio" name="impact_social" value="4">&nbsp;';
    echo '<input type="radio" name="impact_social" value="5">&nbsp;';
  echo '</td>';
echo '</tr>';

echo '<tr id="hidethis6" style="display:none;">';
  echo '<td align="right" style="font-weight:bold">Impact on career</td>';
  echo '<td>';
    echo '<input type="radio" name="impact_career" value="1">&nbsp;';
    echo '<input type="radio" name="impact_career" value="2">&nbsp;';
    echo '<input type="radio" name="impact_career" value="3">&nbsp;';
    echo '<input type="radio" name="impact_career" value="4">&nbsp;';
    echo '<input type="radio" name="impact_career" value="5">&nbsp;';
  echo '</td>';
echo '</tr>';

echo '<tr id="hidethis7" style="display:none;">';
  echo '<td align="right" style="font-weight:bold">Impact on personal wellbeing</td>';
  echo '<td>';
    echo '<input type="radio" name="impact_personal" value="1">&nbsp;';
    echo '<input type="radio" name="impact_personal" value="2">&nbsp;';
    echo '<input type="radio" name="impact_personal" value="3">&nbsp;';
    echo '<input type="radio" name="impact_personal" value="4">&nbsp;';
    echo '<input type="radio" name="impact_personal" value="5">&nbsp;';
  echo '</td>';
echo '</tr>';
*/

echo '<tr>';
  echo '<td align="right" style="font-weight:bold">Impact in five years</td>';
  echo '<td>';
    echo '<input type="radio" name="impact_fiveyears" value="1">&nbsp;';
    echo '<input type="radio" name="impact_fiveyears" value="2">&nbsp;';
    echo '<input type="radio" name="impact_fiveyears" value="3">&nbsp;';
    echo '<input type="radio" name="impact_fiveyears" value="4">&nbsp;';
    echo '<input type="radio" name="impact_fiveyears" value="5">&nbsp;';
  echo '</td>';
echo '</tr>';
echo '<tr><td colspan="2">&nbsp;</td></tr>';


$step = "step0";
echo '<tr>';
  echo '<td style="font-weight:bold">First step</td>';
  echo '<td><input size="36" type="text" autocomplete="off" autocorrect="off" autocapitalize="on" spellcheck="true" name="'.$step.'"></td>';
echo '</tr>';

$step = "step1";
echo '<tr>';
  echo '<td style="font-weight:bold">Next step</td>';
  echo '<td><input size="36" type="text" autocomplete="off" autocorrect="off" autocapitalize="on" spellcheck="true" name="'.$step.'"></td>';
echo '</tr>';

$step = "step2";
echo '<tr>';
  echo '<td style="font-weight:bold">Next step</td>';
  echo '<td><input size="36" type="text" autocomplete="off" autocorrect="off" autocapitalize="on" spellcheck="true" name="'.$step.'"></td>';
echo '</tr>';

$step = "step3";
echo '<tr>';
  echo '<td style="font-weight:bold">Next step</td>';
  echo '<td><input size="36" type="text" autocomplete="off" autocorrect="off" autocapitalize="on" spellcheck="true" name="'.$step.'"></td>';
echo '</tr>';

$step = "step4";
echo '<tr>';
  echo '<td style="font-weight:bold">Next step</td>';
  echo '<td><input size="36" type="text" autocomplete="off" autocorrect="off" autocapitalize="on" spellcheck="true" name="'.$step.'"></td>';
echo '</tr>';

$step = "step5";
echo '<tr>';
  echo '<td style="font-weight:bold">Next step</td>';
  echo '<td><input size="36" type="text" autocomplete="off" autocorrect="off" autocapitalize="on" spellcheck="true" name="'.$step.'"></td>';
echo '</tr>';

$step = "step6";
echo '<tr>';
  echo '<td style="font-weight:bold">Next step</td>';
  echo '<td><input size="36" type="text" autocomplete="off" autocorrect="off" autocapitalize="on" spellcheck="true" name="'.$step.'"></td>';
echo '</tr>';


echo '<tr><td colspan="2"><input type="submit" value="Add"></td></tr>';

echo '</table>';

echo '</form>';


include 'footer_plain.html';


?>
