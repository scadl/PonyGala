<?php

require './../db_init.php';
	
	switch ($_GET['type']){
		case 3: // Update art
			mysqli_query($link, "UPDATE arts_pub SET category=".$_GET['cat'].", addate='".$_GET['date']."' WHERE aid=".$_GET['aid']);			
			print("Обновлёно ".$_GET['aid']); 
		break;
		case 4: // Remove art
			mysqli_query($link, "DELETE FROM arts_pub WHERE aid=".$_GET['aid']);
			print("Удалён: ".$_GET['aid']);
		break;
	
	}

	mysqli_close($link);
	
	//sleep(1);	
?>