	
	<?php
	
	if ( isset($_GET['mode']) )	{
	
		if ($_GET['mode']=='auth'){
	
	?>
	
	<style>
	a:link{text-decoration:none;}
	a:hover{text-decoration:none; font-weight:bold;}
	a:visited{text-decoration:none;}
	</style>
	
	<div align="center">
	<a href="https://www.deviantart.com/oauth2/authorize?
	response_type=code&
	client_id=3286&
	redirect_uri=http://scadsdnd.ddns.net/myphp/ponygalai/dA_integrator.php&
	scope=feed&
	state=DaIntegrator-Querry">
	Connect to dA...
	</a>
	</div>
	
	<?php
	
		} elseif ($_GET['mode']=='read'){

		print( file_get_contents('da_arts.txt') );
	
		} 
	}
	
	if ( isset( $_GET['code'] ) ) { 
	
			// var_dump(extension_loaded('curl'));
		
		/*
		$curl_obj = curl_init(); //  Initiate curl		
		curl_setopt($curl_obj, CURLOPT_URL, 'https://www.deviantart.com/oauth2/token?client_id=3286&client_secret=227108d8fe261b4c89dbc3ade3fa6e5b&grant_type=authorization_code&code='.$_GET['code'].'&redirect_uri=https://scadsdnd.ddns.net/myphp/ponygalai/add-art.php');	// Set the url
		curl_setopt($curl_obj, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, 1); // Will return the response, if false it print the response		
		$curl_resp = curl_exec($curl_obj); // Execute
		curl_close($curl_obj); // Closing
		var_dump(json_decode($curl_resp, true)); // Will dump a beauty json :3
		*/		
	
		$json = file_get_contents('https://www.deviantart.com/oauth2/token?client_id=3286&client_secret=227108d8fe261b4c89dbc3ade3fa6e5b&grant_type=authorization_code&code='.$_GET['code'].'&redirect_uri=http://scadsdnd.ddns.net/myphp/ponygalai/dA_integrator.php');		
		$obj = json_decode($json, true); // GOT assoc array
		
		print("<br><br>");			
	
		printf("dA Code: " . $_GET['code'] . "<br>");
		printf("dA Token: " . $obj['access_token'] . "");
		
		print("<br><br>");	
		
		//----------------------------
		
		$more = true;
		//$old_cursor = "";
		$i = 0; $j=0; $ar = 0;
		
		//$feed_json = file_get_contents("https://www.deviantart.com/api/v1/oauth2/feed/home?mature_content=true&access_token=".$obj['access_token']);			
		//$feed_obj = json_decode($feed_json, true); // GOT assoc array
		
		//$feed_json = file_get_contents("https://www.deviantart.com/api/v1/oauth2/feed/notifications?access_token=".$obj['access_token']);	
		//$feed_obj = json_decode($feed_json); // GOT php object
		
		if (file_exists('da_arts.txt')) {
			unlink('da_arts.txt');
		}
		
		while ($more){
			if ( isset( $feed_obj['cursor'] ) ) {
				$feed_json = file_get_contents("https://www.deviantart.com/api/v1/oauth2/feed/home?mature_content=true&access_token=".$obj['access_token']."&cursor=".$feed_obj['cursor']);
				//$old_cursor = $feed_obj['cursor'];
			} else { 
				$feed_json = file_get_contents("https://www.deviantart.com/api/v1/oauth2/feed/home?mature_content=true&access_token=".$obj['access_token']);			
			}
			
			$feed_obj = json_decode($feed_json, true);
			
			//print ("Page: ".$i."<br>"); 
			$i++;
			
			foreach ($feed_obj['items'] as $items ){
			if ( isset( $items['deviations'] ) ) {
				foreach ( $items['deviations'] as $deviations ){
					$j++; $ar++;
					//print ("<img src='".$deviations['content']['src']."' width='90'> &emsp; ");		
					//print ($deviations['content']['src']." | ");
					file_put_contents('da_arts.txt', $deviations['content']['src']."|", FILE_APPEND | LOCK_EX);
					}			
				}
			} 			
			
			//print("<hr>");		
			
			//if ($j = 0) { $more = false; }
			if ($i = 30) { $more = false; }
			$j=0; sleep(1);
		}
		
		//print ("Loaded pages: ".$i."<br>");
		//print ("Loaded artworks: ".$ar."<br>");
		//print("<br><br>");	
		
		//echo $feed_obj -> items -> deviations -> content -> src ;
		//print_f($feed_obj);
	
	} 
	  
?>

