	
	<?php

require '../preinit.php';
require '../db_init.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
	
	//error_reporting(0);
	error_reporting(E_ALL & ~E_NOTICE);
	
	if ( isset($_GET['mode']) )	{
	
		if ($_GET['mode']=='read'){

			print( file_get_contents('da_arts.txt') );
			unlink('da_arts.txt');
	
		} elseif ( $_GET['mode']=='askart' ) {
		

		$i = 0; 
		$ar = 0;
		$more_stat = 0;
		$json_ask = "";
		$cursor = "";
		$dups = 0;
		$output = array();
					
			if ( $_GET['cursor'] != "" ) {
				
				$json_ask = "https://www.deviantart.com/api/v1/oauth2/feed/home?mature_content=true&access_token=".$_GET['token']."&cursor=".$_GET['cursor'];				
			} else {
				
				$json_ask = "https://www.deviantart.com/api/v1/oauth2/feed/home?mature_content=true&access_token=".$_GET['token'];			
			}
			
			// Create a stream
			$opts = [
				'http' => [
					'method' => 'GET',
					'header' => implode("\n", ['Accept-Encoding: gzip,deflate', 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'] ]),
				]
			];
			$context = stream_context_create($opts);
			$content = @file_get_contents($json_ask, false, $context);
								
			if ( $content ) {
			
				//$feed_json = gzinflate(substr($content,10));
				$feed_json = $content;
				$feed_obj = json_decode($feed_json, true);
				$cursor = $feed_obj['cursor'];
				
				$more_stat = count($feed_obj['items']);
						
				//print ("Page: ".$i."<br>"); 
				$i++;
			
				foreach ($feed_obj['items'] as $items ){
				if ( isset( $items['deviations'] ) ) {
					foreach ( $items['deviations'] as $deviations ){

							$ar++;
							//file_put_contents('da_arts.txt', $deviations['content']['src']."|", FILE_APPEND | LOCK_EX);

							$rqchk = "SELECT * FROM arts_pub WHERE file_name='".$deviations['content']['src']."'";
							$sqlchk = mysqli_query($link, $rqchk);
							if (mysqli_num_rows($sqlchk) == 0){

								$recrq = "INSERT INTO arts_pub (title, file_name, thumb, da_page, author, addate, da_id) VALUES ".
								"('".$deviations['title']."', '".$deviations['content']['src']."', '".$deviations['thumbs'][1]['src']."', '".$deviations['url']."', ".
								"'".$deviations['author']['username']."', '".date("j-m-Y")."', '".$deviations['deviationid']."')";
								$sqlreq = mysqli_query($link, $recrq);								

								$stylec = 'color:blue';
							} else {

								$dups++;
								$stylec = 'color:red';

								/*$recrq = "UPDATE arts_pub SET (title, file_name, thumb, da_page, author, addate, da_id) VALUES ".
								"title='".$deviations['title']."', file_name='".$deviations['content']['src']."', thumb='".$deviations['thumbs'][1]['src']."', da_page='".$deviations['url']."', ".
								"author='".$deviations['author']['username']."', addate='".date("j-d-Y")."', da_id=".$deviations['deviationid']." ".
								"WHERE file_name =".$deviations['content']['src'];*/
							}	

							$output['log'] .= "<tr style='".$stylec."'>
							<td><img src=".$deviations['thumbs'][0]['src']." height='50'></td>
							<td>".$deviations['title']."</td>
							<td>".$deviations['author']['username']."</td>
							</tr>";
						}			
					}
				} 	
			
			} else {				
				
				$more_stat = -1;
				$cursor = $_GET['cursor'];
				
				//print( json_decode( file_get_contents($json_ask) , true)['error'] );
				//print('Error 403');
				
			}
		
		// SCRIPTED in AJasKdA(token)
		$output['pages']=$i;
		$output['arts']=$ar;
		$output['more']=$more_stat;
		$output['dups']=$dups;
		$output['cursor']=$cursor;

		print(json_encode($output));
		
		}
	
	} 
	  
?>

