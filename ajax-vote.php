<?php
	//print($_GET['vote']);
	
	switch ($_GET['vote']){
		case 1: $vote_col="like"; break;
		case 2: $vote_col="dislike"; break;
		case 3: $vote_col="old"; break;
		case 4: $vote_col="goodnold"; break;
		case 5: $vote_col="middle"; break;
	}
	
	class VoteDBLite extends SQLite3 { 
		function __construct() { 
			$this->open('art-db.sqlite'); 
		}		
	}
	
	$votes = new VoteDBLite();
	$req2 = $votes->exec("UPDATE arts SET ".$vote_col."=".$vote_col."+1 WHERE file_name='".$_GET['file']."'");
	
	//sleep(120);
	
?>