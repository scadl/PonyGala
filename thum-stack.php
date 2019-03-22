<?php
	
		//header('Content-Type: image/png');
		
		if (isset( $_GET['date'] )) { $dtstr=$_GET['date']; } else { $dtstr="vault"; }
		
		if ( !file_exists('_cache-thumbs/thumb-stack-CatN'.$_GET['catn'].'_'.$dtstr.'.png') ){
		
		include "thumb_frame.php";
		
		$posx=-23;
		$posy=-23;
		//$angval=45;
		$stepx=23;
		$stepy=23;
		
		$finalobj=imagecreatetruecolor(300, 300);
		imagefill($finalobj, 0, 0, imagecolorallocate($finalobj, 200, 200, 200));
		
		$indreal=0;
		
		$avtd = new SQLite3("art-db.sqlite");
		if ( isset( $_GET['date'] ) ){
			$reqts = $avtd -> query("SELECT file_name FROM arts WHERE category=".$_GET['catn']." AND addate='".$_GET['date']."' ORDER BY random() LIMIT 7");
		} else {			
			$reqts = $avtd -> query("SELECT file_name FROM arts WHERE category=".$_GET['catn']." ORDER BY random() LIMIT 7");
		}
		while ($row = $reqts -> fetchArray(SQLITE3_NUM)) { 		
			
            if (stripos($row[0], '.png')!=false){
					$imgobj=GenPNGFrame($row[0]);
					imagecopyresized($finalobj, $imgobj, $posx+=$stepx, $posy+=$stepy, 0, 0, $tbimgwdot, $tbimghgot, $tbimgwd, $tbimghg);	
					$indreal++;
            }
			elseif (stripos($row[0], '.jpg')!=false){
					$imgobj=GenJPGFrame($row[0]);
					imagecopyresized($finalobj, $imgobj, $posx+=$stepx, $posy+=$stepy, 0, 0, $tbimgwdot, $tbimghgot, $tbimgwd, $tbimghg);
					$indreal++;
            }
			elseif (stripos($row[0], '.gif')!=false){
					$imgobj=GenGIFFrame($row[0]);
					imagecopyresized($finalobj, $imgobj, $posx+=$stepx, $posy+=$stepy, 0, 0, $tbimgwdot, $tbimghgot, $tbimgwd, $tbimghg);
					$indreal++;
			}
			elseif (stripos($row[0], '.swf')!=false){
					$imgobj=GenSWFFrame("big_mac_adobe_flash_icon_BW_by_tomcat94-d5l1g07.png");
					imagecopyresized($finalobj, $imgobj, $posx+=$stepx, $posy+=$stepy, 0, 0, $tbimgwdot, $tbimghgot, $tbimgwd, $tbimghg);
					$indreal++;
			}
			
			//if ($indreal > 7){ break; }
			
		}
		
		imagecolortransparent($finalobj, imagecolorallocate($finalobj, 200, 200, 200));
		
		// bool imagepng ( resource $image [, string $filename [, int $quality [, int $filters ]]] )
		
		//imagepng($finalobj);
		imagepng($finalobj, '_cache-thumbs/thumb-stack-CatN'.$_GET['catn'].'_'.$dtstr.'.png' , 7);
		imagedestroy($finalobj);
	}
?>