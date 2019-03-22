<?php 
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
if (!isset($_SESSION['admin'])) { 
    header('Location: ../index.php');
}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PonyArt Gallery (by scadl) [Selection Preview]</title>
    <link rel="stylesheet" href="../libs/main.css">
</head>
<body>

	<div align="center">
		
	<span style='font-family:CelestiaRedux;'>
		<span style='font-size:45pt;'>Big DigitalArt Gallery</span><br>
		<span style='font-size:17pt;'>Selection Preview</span> <br>
	</span>


	<hr style="width: 300px">
	<br>
		
    <div class="cellstyle" style="width:500px; height:500px; padding:10px;">
        <div class="block_tmb" style="width:480px; height:480px;">
	<?php
		require './../db_init.php';

        if (isset($_GET['date'])) {
            $rq = "SELECT thumb FROM arts_pub WHERE addate='" . $_GET['date'] . "' ORDER BY rand() LIMIT 13";
        } else {
            $rq = "SELECT thumb FROM arts_pub ORDER BY rand() LIMIT 13";
        }
        
        $zindex = 0;
        $sql = mysqli_query($link, $rq);
        while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
            print("<img src='".$row['thumb']."' height='100' style='top:".rand(0, 55)."px; left:".rand(-5, 35)."px; z-index:".$zindex."'>");
            $zindex++;
        }
    ?>
        </div>
    </div>
	
	<br><br>
	<span style="color:grey; font-size:7pt;">	
	Scripting and Design: <b>scadl</b><br>
	<i><a href="http://scadsdnd.net/" target="_blank">SCAD's Design & Develop</a> - Aug 2013</i>
	</span>

    </div>
	
</body>
</html>
