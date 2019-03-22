<?php
	
		header('Content-Type: image/png'); 
		//error_reporting(1);
		
		if (isset( $_GET['date'] )) { $dtstr=$_GET['date']; } else { $dtstr="vault"; }
		
		//if ( !file_exists('_cache-thumbs/thumb-stack-CatN'.$_GET['catn'].'_'.$dtstr.'.png') ){
		
		include "thumb_frame.php";
		
		$posx=-55;
		$posy=45;
		$angstrt=0;
		$angval=3;
		$stepx=90;
		$stepy=165;
		
		$finalobj = imagecreatetruecolor(550, 550);
		$bgcolor = imagecolorallocatealpha($finalobj, 250,250,250,127);
		imagefill($finalobj, 0, 0, $bgcolor);
		
		function ProcessImage(){
			global $imgobj, $angval, $angstrt, $bgcolor;
			//$imgobj = imagerotate ($imgobj, $angval+=15, 0);
			global $finalobj, $posx, $stepx, $posy, $stepy, $tbimgwdot, $tbimghgot, $tbimgwd, $tbimghg;
			//$finalobj = imagerotate ($finalobj, $angstrt+=$angval, $bgcolor);
			
			if ( $posx >= 350 ){ $posy+=$stepy; $posx=-55+rand(-20,20); }
			imagecopyresampled($finalobj, $imgobj, $posx+=$stepx, $posy+rand(-30,30), 0, 0, $tbimgwdot, $tbimghgot, $tbimgwd, $tbimghg);
			
			//imagecopyresized($finalobj, $imgobj, 10, 10, 0, 0, $tbimgwdot, $tbimghgot, $tbimgwd, $tbimghg);			
			//imagecopyresized($finalobj, $imgobj, ((imagesx($finalobj)/2) - ($tbimgwdot/2)) + rand(-200,200), ((imagesy($finalobj)/2) - ($tbimghgot/2)) + rand(-200,200), 0, 0, $tbimgwdot, $tbimghgot, $tbimgwd, $tbimghg);
			//imagecopyresized($finalobj, $imgobj, ((imagesx($finalobj)/2) - ($tbimgwdot/2)) + $posx+=$stepx, ((imagesy($finalobj)/2) - ($tbimghgot/2)) + $posy+=$stepy, 0, 0, $tbimgwdot, $tbimghgot, $tbimgwd, $tbimghg);
			//imagecopyresized($finalobj, $imgobj, ((imagesx($finalobj)/2) - ($tbimgwdot/2)), ((imagesy($finalobj)/2) - ($tbimghgot/2)), 0, 0, $tbimgwdot, $tbimghgot, $tbimgwd, $tbimghg);
			//$indreal++;
			//print ( (imagesx($finalobj)/2) . " - " . ($tbimghgot/2) ." = ". ((imagesx($finalobj)/2) - ($tbimghgot/2)) . " ");
			//print ( (imagesy($finalobj)/2) . " - " . ($tbimgwdot/2) ." = ". ((imagesy($finalobj)/2) - ($tbimgwdot/2)) . "<br>");
						
		}
				
		$avtd = new SQLite3("art-db.sqlite");
		if ( isset( $_GET['date'] ) ){
			$reqts = $avtd -> query("SELECT file_name FROM arts WHERE addate='".$_GET['date']."' ORDER BY random() LIMIT 15");
		} else {			
			$reqts = $avtd -> query("SELECT file_name FROM arts ORDER BY random() LIMIT 15");
		}
		while ($row = $reqts -> fetchArray(SQLITE3_NUM)) { 		
			
            if (stripos($row[0], '.png')!=false){
					$imgobj=GenPNGFrame($row[0]);
					ProcessImage();
            }
			elseif (stripos($row[0], '.jpg')!=false){
					$imgobj=GenJPGFrame($row[0]);
					ProcessImage();
            }
			elseif (stripos($row[0], '.gif')!=false){
					$imgobj=GenGIFFrame($row[0]);
					ProcessImage();
			}
			elseif (stripos($row[0], '.swf')!=false){
					$imgobj=GenSWFFrame("big_mac_adobe_flash_icon_BW_by_tomcat94-d5l1g07.png");
					ProcessImage();
			}
						
		}
		
		imagecolortransparent($finalobj, $bgcolor);
		
		// bool imagepng ( resource $image [, string $filename [, int $quality [, int $filters ]]] )
		//$finalobj = imagecrop($finalobj, array('x' =>3275 , 'y' => 3275, 'width' => 540, 'height'=> 540));
		imagepng($finalobj);
		
		//imagepng($finalobj, '_cache-thumbs/thumb-stack-CatN'.$_GET['catn'].'_'.$dtstr.'.png' , 7);
		//imagedestroy($finalobj);
	//}
?>