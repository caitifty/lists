<?php
// display navbar

echo '<ul>';

	echo '<li><a href="item_add.php">Add item</li>';

	if($page == "list_universal" and $pagetype == "all") $classtype = 'class="active"'; else $classtype = "";
	echo '<li><a '.$classtype.' href="list_universal.php?type=all">All</a></li>';

	if($page == "list_universal" and $pagetype == "due") $classtype = 'class="active"'; else $classtype = "";
	echo '<li><a '.$classtype.' href="list_universal.php?type=due">All due</a></li>';

	if($page == "list_universal" and $pagetype == "ui") $classtype = 'class="active"'; else $classtype = "";
	echo '<li><a '.$classtype.' href="list_universal.php?type=ui">Important/urgent</a></li>';

	if($page == "list_universal" and $pagetype == "suspend") $classtype = 'class="active"'; else $classtype = "";
	echo '<li><a '.$classtype.' href="list_universal.php?type=suspend">Suspended</a></li>';

	if($page <> "list_today") { 
		echo '<li><a href="list_today.php">Today</a></li>'; 
	} else {
		echo '<li><a class="active" href="list_today.php">Today</a></li>'; 
		echo '<li><a href="list_maketoday.php">Create today list</li>';
	}

	if($page == "list_maketoday") echo '<li><a class="active" href="list_maketoday.php">Create today list</a></li>';

	if($page == "list_recode") $classtype = 'class="active"'; else $classtype = "";
	echo '<li><a '.$classtype.' href="list_recode.php">Recode</a></li>';

	if($page == "notes_static") $classtype = 'class="active"'; else $classtype = "";
	echo '<li><a '.$classtype.' href="notes_static.php">Notes</a></li>';

    // tag pulldown (see https://stackoverflow.com/questions/31066314/reload-page-on-change-of-dropdown-and-pass-that-value)

    if(isset($_GET['id']))
    {
        $tagnavlocation=@$_GET['id'];

    echo '<script>
            var tagnav = document.getElementById("tagnav");
            tagnav.options.selectedIndex = <?php echo $_GET["pos"];
        </script>';
    } else {
        $tagnavlocation = "";
    }

	if($page == "list_universal") {
	    echo '<li>';
	    echo '<select id="tagnav" name="location" onchange="window.location=\'list_universal.php?type='.$pagetype.'\&id=\'+this.value+\'&pos=\'+this.selectedIndex;">';
	        if($tagnavlocation != "") echo '<option value="'.$tagnavlocation.'">'.$tagnavlocation.'</option>';
	        echo '<option value="">All</option>';
	        foreach($taglist as $tag) echo '<option value="'.$tag.'">'.$tag.'</option>';
	    echo '</select>';
	    echo '</li>';
	}

	echo '<li style="float:right"><a href="logout.php">Logout</a></li>';
echo '</ul>';

echo '<br><br>';
?>