<?php
			/*
			if ($galpos){
				$visitordb = new SQLite3($dirpt."/../visitors.db");
			} else {
				$visitordb = new SQLite3($dirpt."visitors.db");
			}
			*/
			
			$visitordb = new SQLite3("vault/visitors.db");
			
			$visitordb->exec('CREATE TABLE 
			IF NOT EXISTS 
			visitors (ip TEXT, visits NUMBER, date TEXT)'); 			
			
			$req=$visitordb->query("SELECT ip FROM visitors WHERE ip='".$_SERVER['REMOTE_ADDR']."'");
			$row=$req->fetchArray(); //$row is Array!
			if ($row[0]==""){ 
				$visitordb->exec("INSERT INTO visitors (ip, visits, date) VALUES ('".$_SERVER['REMOTE_ADDR']."', 1, ".date("j-m-Y").")"); 
			} else {
				$visitordb->exec("UPDATE visitors SET visits=visits+1,date='".date("j-m-Y")."' WHERE ip='".$_SERVER['REMOTE_ADDR']."'");
			}
			
			$visits=0;
			$req=$visitordb->query("SELECT ip,visits FROM visitors");
			while ($row=$req->fetchArray(SQLITE3_NUM)){
				$ips_data[]=$row[0];
				$visits=$visits+$row[1];
			}
			$ips=count($ips_data);
			
			$tip=0;
			$req=$visitordb->query("SELECT ip FROM visitors WHERE date='".date("j-m-Y")."'");
			while ($row=$req->fetchArray(SQLITE3_NUM)){
				$tip++;
			}
			
			if (isset($_GET['act']) && $_GET['act']=='log') {
				
				if ( !isset($_GET['mdate']) ) { 
					$fl="-"; 
					$st_m = date("m");
					$st_y = date("Y");
				} else { 
					$fl=$_GET['mdate'].'-'.$_GET['ydate'];
					$st_m = $_GET['mdate'];
					$st_y = $_GET['ydate'];
				}
				
				?>
				<head>
				<title>Statistics_Index</title>
				<style>
				.prcell{
					display:inline-block; 
					width:2px; 
					height:2px; 
					margin:0.5px;
				}
				</style>
				<script src="chart/Chart.bundle.min.js"></script>
				<body>
				<div align="center">
				<h2>Statistics_Index</h2> <form> <input type="hidden" name="act" value="log">
				Date MM-YYYY: 
				<input type="number" name="mdate" value="<?php print($st_m); ?>" style="width:50px;">
				<input type="number" name="ydate" value="<?php print($st_y); ?>" style="width:70px;">
				<input type="submit" value="Filter"> </form>
								
				<?php
				
				
				//Print('<table border="0" cellpadding="10" cellspacing="0">	<th>Date</th><th></th><th>Visits</th><th></th><th>Hosts</th>');
				
				$chart_labels = "";
				$chart_datavisit = "";
				$chart_datauser = "";
				$chart_bgcolor_v = "";
				$chart_brcolor_v = "";
				$chart_bgcolor_u = "";
				$chart_brcolor_u = "";
				
				$req=$visitordb->query("SELECT date FROM visitors WHERE date LIKE '%".$fl."%' GROUP BY date");
				while ($row=$req->fetchArray(SQLITE3_NUM)){
					if ($row[0]!=''){
						$tip=0; $visip=0;
						$req2=$visitordb->query("SELECT ip, visits FROM visitors WHERE date='".$row[0]."'");
						while ($row2=$req2->fetchArray(SQLITE3_NUM)){
							$tip++; $visip=$visip+$row2[1];
						}
						
						/*
						$rclr=rand(50,230).','.rand(50,230).','.rand(50,230);
						print('<tr><td width="25" align="center" class="rwcolor" style="color:rgb('.$rclr.');">'.$row[0].'</td>');
						print('<td>'); for ($I=0; $I<$visip; $I++){ print('<div class="prcell" style="background:rgb('.$rclr.');"></div>'); } print('</td>');
						print('<td width="25" align="center" class="rwcolor" style="color:rgb('.$rclr.');">'.$visip.'</td>');
						print('<td>'); for ($I=0; $I<$tip; $I++){ print('<span style="color:rgb('.$rclr.');">|</span>'); } print('</td>');
						print('<td width="10" align="center" class="rwcolor" style="color:rgb('.$rclr.');">'.$tip.'</td></tr>');
						//print($row[0].'<br>');
						*/
						
						$rclr_min = 70;
						$rclr_max = 230;
						
						$chart_labels = $chart_labels . '"'.$row[0].'",';
						
						$rclr=rand($rclr_min, $rclr_max).','.rand($rclr_min, $rclr_max).','.rand($rclr_min, $rclr_max);
						$chart_datavisit = $chart_datavisit . $visip . ',';
						$chart_bgcolor_v = $chart_bgcolor_v . "'rgba(".$rclr.", 0.5)',";
						$chart_brcolor_v = $chart_brcolor_v . "'rgba(".$rclr.", 1)',";
						
						$rclr=rand($rclr_min, $rclr_max).','.rand($rclr_min, $rclr_max).','.rand($rclr_min, $rclr_max);
						$chart_datauser = $chart_datauser . $tip . ',';
						$chart_bgcolor_u = $chart_bgcolor_u . "'rgba(".$rclr.", 0.5)',";
						$chart_brcolor_u = $chart_brcolor_u . "'rgba(".$rclr.", 1)',";
						
						//print(rtrim($chart_labels, ','));
					}
					
				}
				?> </table></div></body> 
				
				<script src="Chart.js"></script>
				
				<center>
				<table border="0" width="90%">
				<tr><td height="50%">
				
					<canvas id="ipChart" width="300" height="170"></canvas>
	<script>
var ctx_u = document.getElementById("ipChart");
var myChart_u = new Chart(ctx_u, {
    type: 'bar',
    data: {
        labels: [<?php print(rtrim($chart_labels, ',')); ?>],
        datasets: [{
            data: [<?php print(rtrim($chart_datauser, ',')); ?>],
            backgroundColor: [<?php print(rtrim($chart_bgcolor_u, ',')); ?>],
            borderColor: [<?php print(rtrim($chart_brcolor_u, ',')); ?>],
            borderWidth: 0
        }]
    },
    options: {
        animation:{
            animateScale:true
        },
		legend:{
			display:false
		},
		title:{
			display: true,
			fontSize: 22,
			text: 'Hosts in <?php print($_GET['mdate'].'-'.$_GET['ydate']); ?>'
		}
    }
});
</script>
				

				</td></tr>
				<tr><td height="50%">
				
				<canvas id="visitChart" width="300" height="170"></canvas>
				<script>
var ctx_v = document.getElementById("visitChart");
var myChart_v = new Chart(ctx_v, {
    type: 'bar',
    data: {
        labels: [<?php print(rtrim($chart_labels, ',')); ?>],
        datasets: [{
            data: [<?php print(rtrim($chart_datavisit, ',')); ?>],
            backgroundColor: [<?php print(rtrim($chart_bgcolor_v, ',')); ?>],
            borderColor: [<?php print(rtrim($chart_brcolor_v, ',')); ?>],
            borderWidth: 0
        }]
    },
    options: {
        animation:{
            animateScale:true
        },
		legend:{
			display:false
		},
		title:{
			display: true,
			fontSize: 22,
			text: 'Visits in <?php print($_GET['mdate'].'-'.$_GET['ydate']); ?>'
		}
    }
});
</script>
				</td></tr>
							
				</table>
				</center>
				
				<?php
			}
?>