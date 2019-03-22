<?php
	switch ( $_GET['mode'] ){
		case 0:
			
			chdir("vault/_cache/");
			foreach ( glob("{*.jpg,*.gif,*.png}", GLOB_BRACE | GLOB_NOSORT) as $curthumb ){
				print($curthumb.'|');
			}
			//chdir("C:/xampp/htdocs/MyPHP/PonyGalaI/");
			
		break;
		case 1:
			
			$arts=array();
			$nmb = 0;
			$prs = 0;
			$del = 0;
			$inside=false;
			
			$artsdb = new SQLite3("art-db.sqlite");
			$artq = $artsdb -> querySingle("SELECT COUNT(file_name) AS files FROM arts WHERE file_name LIKE '%".$_GET['check']."'");
			//while($art = $artq -> fetchArray(SQLITE3_NUM)){ $arts[]=$art[0]; }
			//$art_rows = $artq -> fetchArray();
			if ( $artq > 0 ){
				print('1');
			} else 
			{
				print('0');
				unlink ( "vault/_cache/".$_GET['check'] );
			}

			
		break;
		
	}
			
?>