<?php
	
		//header('Content-Type: image/png'); 
		error_reporting(0);
		
		if (isset( $_GET['date'] )) { $dtstr=$_GET['date']; } else { $dtstr="vault"; }
		
		if ( !file_exists('_cache-thumbs/thumb-stack-CatN'.$_GET['catn'].'_'.$dtstr.'.png') ){
		
		include "thumb_frame.php";
		
		$posx=-23;
		$posy=-23;
		$angstrt=0;
		$angval=3;
		$stepx=50;
		$stepy=25;
		
		$finalobj = imagecreatetruecolor(300, 300);
		$bgcolor = imagecolorallocatealpha($finalobj, 255,255,255,127);
		//$bgcolor = imagecolorallocate($finalobj, 255,255,255);
		imagefill($finalobj, 0, 0, $bgcolor);
		
		function ProcessImage(){
			global $imgobj, $angval, $angstrt, $bgcolor;
			//$imgobj = imagerotate ($imgobj, $angval+=15, 0);
			global $finalobj, $posx, $stepx, $posy, $stepy, $tbimgwdot, $tbimghgot, $tbimgwd, $tbimghg;
			$finalobj = imagerotate ($finalobj, $angstrt+=$angval, $bgcolor);
			//imagecopyresampled($finalobj, $imgobj, $posx+=$stepx, $posy+=$stepy, 0, 0, $tbimgwdot, $tbimghgot, $tbimgwd, $tbimghg);				
			imagecopyresized($finalobj, $imgobj, ((imagesx($finalobj)/2) - ($tbimgwdot/2)) + rand(-100,100), ((imagesy($finalobj)/2) - ($tbimghgot/2)) + rand(-50,50), 0, 0, $tbimgwdot, $tbimghgot, $tbimgwd, $tbimghg);
			//imagecopyresized($finalobj, $imgobj, ((imagesx($finalobj)/2) - ($tbimgwdot/2)) + $posx+=$stepx, ((imagesy($finalobj)/2) - ($tbimghgot/2)) + $posy+=$stepy, 0, 0, $tbimgwdot, $tbimghgot, $tbimgwd, $tbimghg);
			//imagecopyresized($finalobj, $imgobj, ((imagesx($finalobj)/2) - ($tbimgwdot/2)), ((imagesy($finalobj)/2) - ($tbimghgot/2)), 0, 0, $tbimgwdot, $tbimghgot, $tbimgwd, $tbimghg);
			//$indreal++;
			//print ( (imagesx($finalobj)/2) . " - " . ($tbimghgot/2) ." = ". ((imagesx($finalobj)/2) - ($tbimghgot/2)) . " ");
			//print ( (imagesy($finalobj)/2) . " - " . ($tbimgwdot/2) ." = ". ((imagesy($finalobj)/2) - ($tbimgwdot/2)) . "<br>");
						
		}
				
		$avtd = new SQLite3("art-db.sqlite");
		if ( isset( $_GET['date'] ) ){
			$reqts = $avtd -> query("SELECT file_name FROM arts WHERE category=".$_GET['catn']." AND addate='".$_GET['date']."' ORDER BY random() LIMIT 13");
		} else {			
			$reqts = $avtd -> query("SELECT file_name FROM arts WHERE category=".$_GET['catn']." ORDER BY random() LIMIT 13");
		}
		while ($row = $reqts -> fetchArray(SQLITE3_NUM)) { 		
			//print($row[0]."<br>");
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
		
		//imagecolortransparent($finalobj, imagecolorallocate($finalobj, 0,0,0));
		
		// bool imagepng ( resource $image [, string $filename [, int $quality [, int $filters ]]] )
		if (imagesx($finalobj)/2 < 220) { $divider=320; } else { $divider=450; }
		$finalobj = imagecrop($finalobj, array('x' => (imagesx($finalobj)/2)-($divider/2) , 'y' => (imagesy($finalobj)/2)-($divider/2), 'width' => $divider, 'height'=> $divider));
		imageline($finalobj, 0, imagesy($finalobj), imagesx($finalobj), imagesx($finalobj), imagecolorallocatealpha($finalobj, 200,200,200,0));
		imageline($finalobj, 0, imagesy($finalobj)-1, imagesx($finalobj)-1, imagesx($finalobj)-1, imagecolorallocatealpha($finalobj, 200,200,200,0));
		//imagepng($finalobj);
		imagepng($finalobj, '_cache-thumbs/thumb-stack-CatN'.$_GET['catn'].'_'.$dtstr.'.png' , 7);
		imagedestroy($finalobj);
	}
?>