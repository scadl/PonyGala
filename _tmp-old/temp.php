<?php
	
	$rowcn=0;
	
	$artdb = new SQLite3("art-db.sqlite");
	$artdb -> busyTimeout(500);
	
	$roq = $artdb -> query("SELECT category FROM arts WHERE category=5 LIMIT ".$_GET['limit']." OFFSET ".$_GET['ofset']) ;
	while ($rows = $roq -> fetchArray()) { $rowcn++; }
	echo ($rowcn);
	/*
	if ($artdb -> query("SELECT category FROM arts WHERE file_name='http://fc00.deviantart.net/fs71/i/2014/114/4/0/try_by_nekokevin-d7fs0en.png'") -> fetchArray()){
		echo ("data");
		echo ($artdb -> query("SELECT category FROM arts WHERE file_name='http://fc00.deviantart.net/fs71/i/2014/114/4/0/try_by_nekokevin-d7fs0en.png'") -> fetchArray()[0]);
	} else {
		echo ("no data");
	}
	*/
	
	//echo("answer");
	
?>