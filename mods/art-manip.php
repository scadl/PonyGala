<?php

require './../db_init.php';
	
	switch ($_GET['type']){
		case 3: // Update art
                        $date_sql = "";
                        if($_GET['dateupd']=='true'){
                            $date_sql=", addate='".$_GET['date']."'";
                        }
			mysqli_query($link, "UPDATE arts_pub SET category=".$_GET['cat'].$date_sql." WHERE aid=".$_GET['aid']);			
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