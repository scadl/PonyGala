<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <title>DeviantArt_Integrator</title>
</head>
<body>
    <!-- Insert your content here -->
    <a href="https://www.deviantart.com/oauth2/authorize?
	response_type=code&
	client_id=3286&
	redirect_uri=http://scadsdnd.ddns.net/myphp/ponygalai/dA_integrator.php&
	scope=feed&
	state=DaIntegrator-Querry">
	dA Autohorize <br>
	get Token <br>
	read Feed
	</a><br>
	    
    <?php
      if ( isset( $_GET['code'] ) ) {   
			
		$json = file_get_contents('https://www.deviantart.com/oauth2/token?client_id=3286&client_secret=227108d8fe261b4c89dbc3ade3fa6e5b&grant_type=authorization_code&code='.$_GET['code'].'&redirect_uri=http://scadsdnd.ddns.net/myphp/ponygalai/dA_integrator.php');		
		$obj = json_decode($json, true); // GOT assoc array
		
		print("<br><br>");			
	
		printf("dA Code: " . $_GET['code'] . "<br>");
		printf("dA Token: " . $obj['access_token'] . "");
		
		print("<br><br>");	
		
		//----------------------------
		
		$more = true;
		$old_cursor = "";
		$i = 0; $j=0;
		
		//$feed_json = file_get_contents("https://www.deviantart.com/api/v1/oauth2/feed/home?mature_content=true&access_token=".$obj['access_token']);			
		//$feed_obj = json_decode($feed_json, true);
		
		//$feed_json = file_get_contents("https://www.deviantart.com/api/v1/oauth2/feed/notifications?access_token=".$obj['access_token']);	
		//$feed_obj = json_decode($feed_json); // GOT php object
		
		while ($more){
			if ( isset( $feed_obj['cursor'] ) ) {
			$feed_json = file_get_contents("https://www.deviantart.com/api/v1/oauth2/feed/home?mature_content=true&access_token=".$obj['access_token']."&cursor=".$feed_obj['cursor']);
			$old_cursor = $feed_obj['cursor'];
			} else { 
			$feed_json = file_get_contents("https://www.deviantart.com/api/v1/oauth2/feed/home?mature_content=true&access_token=".$obj['access_token']);			
			}
			$feed_obj = json_decode($feed_json, true);
			
			print ("Page: ".$i."<br>"); $i++;
			
			foreach ($feed_obj['items'] as $items ){
			if ( isset( $items['deviations'] ) ) {
				foreach ( $items['deviations'] as $deviations ){
					print ("<img src='".$deviations['content']['src']."' width='90'> &emsp; ");		$j++;
					}			
				}
			} 			
			print("<hr>");		
			if ($j <= 0) { $more = false; }
			$j=0; 	sleep(1);
		}
		
		print("<br><br>");	
		
		//echo $feed_obj -> items -> deviations -> content -> src ;
		//print_f($feed_obj);
				
      } else {
        printf("<br><br> dA Code: NULL " . "<br>");
      }
	  
    ?>
</body>
</html>
