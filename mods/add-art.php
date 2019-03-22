<?php 

error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
if (!isset($_SESSION['admin'])) { 
    header('Location: ../index.php');
}

require '../preinit.php';
require '../db_init.php';

?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PonyArt Gallery (by scadl) [dA Import]</title>
    <link rel="stylesheet" href="../libs/admin_forms.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.9.1.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script src="../libs/ajax_art_manipulation.js"></script>
	<script src="../libs/functions_add.js"
	<script type="text/javascript">
		function Visible(target, action){
			if (action=='hide') { document.getElementById(target).setAttribute("style","display:none;"); }
			if (action=='show') { document.getElementById(target).setAttribute("style","display:normal; padding:5px;"); }
		}
			
	$(function() {
		
		$( ".datepicker" ).datepicker({
			showOtherMonths: true,
			selectOtherMonths: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: "d-mm-yy"
		});
	});
	
	</script>
	<style>
	.pvImg{
		cursor:default;
	}
	</style>
</head>
<body>

	<div align="center">
		
	<span style='font-family:CelestiaRedux;'>
		<span style='font-size:45pt;'>Big DigitalArt Gallery</span><br>
		<span style='font-size:17pt;'>devianArt Integrator UI</span> <br>
	</span>


	<hr style="width: 300px">
	<br>

	<div><b>Статус: </b>	
	<div id='daStatus'>
	</div>	
	
	<span id="status">
		
	<?php
		if ( isset( $_GET['code'] ) ) { 
		
			printf("<br><span style='color:grey; font-weight:bold;'>Получен dA-код:</span> <br>" . $_GET['code'] . "");			
			
			$opts = [
				'http' => [
					'method' => 'GET',
					'header' => implode("\n", ['Accept-Encoding: gzip,deflate', 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'] ]),
				]
			];
			$context = stream_context_create($opts);
			
			if ( $context ){
									
				$content = file_get_contents('https://www.deviantart.com/oauth2/token?grant_type=authorization_code&'.
				'client_secret='.$da_secret.'&client_id='.$da_client.'&client_secret='.$da_secret.'&'.
				'redirect_uri='.$redirect_url.'&code='.$_GET['code'].'', false, $context);		

				if ($content===FALSE){
					file_get_contents('https://www.deviantart.com/oauth2/revoke');
					print('Logging out now!');
				} else {
					//$json = gzinflate(substr($content,10));
					$json = $content;			
				}					
				//print('<input type="button" onclick="dAaJAuth(\''.$_GET['code'].'\');" value="dAaJAuth">');
			} else {
				Print('<span style="color:red">Error in dA OAuth2 token</span>');
			}
			
		
		} elseif ( isset( $_GET['credent'] ) ) {
			
			$json = file_get_contents('https://www.deviantart.com/oauth2/token?grant_type=client_credentials&client_id='.$da_client.'&'.$da_secret);	
			
		} 
		
		if ( isset( $json ) ) {
		
			$obj = json_decode($json, true); // GOT assoc array		
		
			printf("<br>
			<br><span style='color:grey; font-weight:bold;'>Получены dA-токены:</span> 
			<br><span title='Acccess Token'>" . $obj['access_token'] . "</span><br>");
			if ( isset ( $obj['refresh_token'] ) ) { printf("<span title='Refresh token'>" . $obj['refresh_token'] . "</span><br><br>"); }
			printf("<button style='font-weight:bold;' onclick='IniTdAFeed(\"".$obj['access_token']."\")'> Читать ленту с dA</button>");		
			//var_dump(json_decode($json, true));
		
		}
		
	?>
	
	</span></div>
	
	<?php if ( !isset( $_GET['code'] ) ) { ?>
	
	<a 
	href="https://www.deviantart.com/oauth2/authorize?
	response_type=code&
	client_id=<?php print($da_client); ?>&
	redirect_uri=<?php print($redirect_url); ?>&
	scope=feed"
	title="(The Authorization Code Grant)"
	id='dabutton'>
	<br>
	<button>
	Соединиться с dA
	</button>
	</a>
	
	<?php } ?>
		
	<table id="log"></table>	
	
	<br>
	<span style="color:grey; font-size:7pt;">	
	Scripting and Design: <b>scadl</b><br>
	<i><a href="http://scadsdnd.net/" target="_blank">SCAD's Design & Develop</a> - Aug 2013</i>
	</span>

    </div>
	
</body>
</html>
