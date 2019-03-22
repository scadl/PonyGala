	
	<?php
	
	//error_reporting(0);
	error_reporting(E_ALL & ~E_NOTICE);
	
	if ( isset($_GET['mode']) )	{
	
		if ($_GET['mode']=='read'){

			print( file_get_contents('da_arts.txt') );
			unlink('da_arts.txt');
	
		} elseif ( $_GET['mode']=='askart' ) {
		
		//$more = true;
		$i = 0; 
		//$j=0; 
		$ar = 0;
		$more_stat = 0;
		$json_ask = "";
		$cursor = "";
					
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
						//print ("<img src='".$deviations['content']['src']."' width='90'> &emsp; ");		
						//print ($deviations['content']['src']." | ");
						file_put_contents('da_arts.txt', $deviations['content']['src']."|", FILE_APPEND | LOCK_EX);
						}			
					}
				} 	
			
			} else {				
				
				$more_stat = -1;
				$cursor = $_GET['cursor'];
				
				//print( json_decode( file_get_contents($json_ask) , true)['error'] );
				//print('Error 403');
				
			}
		
		print ("<tr style='display:none'>
		<td style='' id='pages'>".$i."</td>
		<td style='' id='arts'>".$ar."</td>
		<td style='' id='more'>".$more_stat."</td>
		<td style='display:none' id='cursor'>".$cursor."</td>
		</tr>");
		
		}
	
	} 
	  
?>

