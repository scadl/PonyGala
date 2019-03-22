<?php 
session_start();
if (!isset($_SESSION['admin'])) { 
    header('Location: index.php');
}

// Your devianArt app credentals
// https://www.deviantart.com/developers/
$da_client = 0000;
$da_secret = 'secret0key';
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PonyArt Gallery (by scadl) [Publishing]</title>
    <link rel="stylesheet" href="admin_forms.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.9.1.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script src="ajax_art_manipulation.js"></script>
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
		<span style='font-size:45pt;'>Big DigitalArt Gallery</span><span style='font-size:8pt;'>v2</span><br>
		<span style='font-size:17pt;'>Publisher desktop</span> <br>
	</span>
		
	<script type="text/javascript">
			var rows=0;	// Current Added Field
			var i=0; 	// Current Processed Field
			var tp=0; 	// Comand type
			
			var pages = 0;
			var arts = 0;
			var new_cursor ="";
			var more = 0; 
			var err = 0;
			var empty_arts=0;
			var arts_arr;
			var faaInt;
			var faaIntInd = 0;
			
			function InitArtSave(){
				
				//Visible('loadArts', 'hide');
				$(this).hide();
				document.getElementById("status").innerHTML="<i>Читаю полученные арты...</i>";
				
				var ajfeed = new XMLHttpRequest();
				ajfeed.onreadystatechange = function(){
					if (ajfeed.readyState==4){
						switch(ajfeed.status){
						case 200: 
							
							arts_arr = "";
							arts_arr = ajfeed.responseText.split('|'); 
							
							document.getElementById("status").innerHTML="<i>Разбираю арты...</i>";											
							document.getElementById("status").innerHTML="<i>Арты загружены в таблицу</i>";
							
							faaIntInd = 0;
							faaInt = setInterval(FileAddArt, 550);
							
						break;
						default: 
							document.getElementById("log").innerHTML="<tr><td colsan='3'>Ошибка при чтении</td></tr>"; 
							document.getElementById("status").innerHTML="<span style='color:red'>Ошибка при чтении артов\n № ошибки: " + ajfeed.status+", "+ajfeed.statusText+" </span>";
						break;
						}
					}
				}
				ajfeed.open('GET', 'dA_integrator2.php?mode=read');
				ajfeed.send(null);				
			}
			
			function FileAddArt(){
				
				if ( faaIntInd < arts_arr.length ){
				
					document.getElementById("status").innerHTML="<i>Добавляю арт "+i+"</i>";				
											
					AJAXAddData(1, 
					arts_arr[faaIntInd],
					0, 
					"<?php print(date("j-m-Y")); ?>",
					"");
				
					$("#head_src").html( "Путь к арту | Осталось: <b>" + (arts_arr.length - faaIntInd) + "</b>");
					faaIntInd++ ;
					
				} else {
					clearInterval(faaInt);
					faaIntInd = 0;
				}
			}
			
									
			function InitArtProcessing(){
				
				document.getElementById("log").innerHTML="<tr><td>Арт</td><td>Категория</td><td>Дата</td></tr>";
				ld = setInterval( function(){ AddArt(true, 0) }, 550);
				tp = 1;
				rows = $("input.pvImg").length;
				
			}
			
			function AddArt(md, aInd){
				
				document.getElementById("status").innerHTML="<i>Добавляю арт "+i+"</i>";
				
				var cI = 0;				
				if ( md ){ 
					cI = i;
					i++;
					if (i > rows) { 
						clearInterval(ld); 
						rows=-1; i=0; tp=0; 
					}
				} else { 
					cI = aInd; 
					tp = 1; 
				}
				
				//alert(document.getElementById("artFullName_"+cI).value + ' ' + document.getElementById("cbCat_"+cI).options[document.getElementById("cbCat_"+cI).selectedIndex].value + ' ' + document.getElementById("artDate_"+cI).value );
				
				AJAXAddData(tp, 
					document.getElementById("artFullName_"+cI).value,
					document.getElementById("cbCat_"+cI).options[document.getElementById("cbCat_"+cI).selectedIndex].value, 
					document.getElementById("artDate_"+cI).value,
					"");
				
				$("#artrow_"+i).remove();
				$("#head_src").html( "Путь к арту | Осталось: <b>" + $("input.pvImg").length + "</b>");
				
			}
						
			function AddRows(data){
				rows=rows+1;
				$("#starter:first").after(
					'<tr id="artrow_'+rows+'"  height="20">'+
					'<td align="center"> <input type="text" id="artFullName_'+rows+'" class="pvImg" size="100%" style="width:100%;" value="'+data+'"> </td>'+
					'<td align="center"> <select onchange="" id="cbCat_'+rows+'" class="imgCat" > <?php SerchCategories(); ?> </select> </td>'+
					'<td align="center"> <input type="text" id="artDate_'+rows+'" value="'+$('#artDate_etal').val()+'" style="width:80px;">  </td>'+
					'<td align="center"> <div id="del_'+rows+'" class="imgDel" style="color:red; cursor:pointer;">(X)</div> </td>'+
					'</tr>'
				);
				
				$(function() {
					
					$("select.imgCat").change(function (){
						
					$("#zoom").fadeOut(100);
					var curInd = $(this).attr("id").split("_")[1] ;
								
					document.getElementById("status").innerHTML="<i>Добавляю арт.</i>";
					AddArt( false, Number(curInd) );
					$("#artrow_"+curInd).remove();
								
					});
					
					$('div.imgDel').click(function(){
						var delInd = $(this).attr("id").split("_")[1];
						$("#artrow_"+delInd).remove();
					});
				
				});
				
			}
			
			function AJGetArts(type){
				
				Visible('loadArts', 'hide');
				document.getElementById("status").innerHTML="<br><br><i>Читаю полученные арты...</i>";
				
				var ajfeed = new XMLHttpRequest();
				ajfeed.onreadystatechange = function(){
					if (ajfeed.readyState==4){
						switch(ajfeed.status){
						case 200: 
						
							var arts_arr = ajfeed.responseText.split('|'); 
							document.getElementById("status").innerHTML="<i>Разбираю арты...</i>";
							
							$("#artFullName_0").val(arts_arr[0]);
							//$("#artThumb_0").attr("src", arts_arr[0]);
							
							for (artInd = 0; artInd < arts_arr.length-1; artInd++){
								AddRows(arts_arr[artInd]);
							}
							
							$(function() {
				
	
							$("input.pvImg").mouseenter(function(ev) {
								//alert('enter');
								$("#zoom").attr('width', '90%' );
								$("#zoom").attr("style", "border:solid 3px lightgrey; "+ "background-color: white; " + "overflow: visible" );
								$('td').css("background", "#eee");
								$(this).parent("td").parent("tr").children().each(function(){
									$(this).css("background", "#c38e8e");									
								});		
								
								var aurl = $(this).val();
								$("#zoom").hide().attr('src', aurl);
								$("#zoom_wait").show();
								$("#zoom").bind({
									load: function() {
										$("#zoom_wait").hide();
										$("#zoom").fadeIn(100).show();
										
    								},
    								error: function() {
        								alert('Error thrown, image didn\'t load, probably a 404.');
    								}
								});

								
							});
				
							$("input.pvImg").click(function() {
								$("#zoom").fadeOut(100);
								$(this).parent("td").parent("tr").children().each(function(){
									$(this).css("background", "#eee");
								});
							});
										
							});
							
							document.getElementById("status").innerHTML="<br><br><i>Арты загружены в таблицу</i>";
							
						break;
						default: 
							document.getElementById("log").innerHTML="<tr><td colsan='3'>Ошибка при чтении</td></tr>"; 
							document.getElementById("status").innerHTML="<span style='color:red'>Ошибка при чтении артов\n № ошибки: " + ajfeed.status+", "+ajfeed.statusText+" </span>";
						break;
						}
					}
				}
				ajfeed.open('GET', 'dA_integrator2.php?mode=read');
				ajfeed.send(null);
				
			}
			
			var maxart=0;
			
			function IniTdAFeed(in_token){
				//alert(in_token);
				daInt = setTimeout( function(){ AJasKdA(in_token) }, 100);				
				//maxart = document.getElementById("max_arts").value;
			}
			
			function AJasKdA(token){
			clearTimeout(daInt);
			var ajda = new XMLHttpRequest();
				ajda.onreadystatechange = function(){
					if (ajda.readyState==4){
						switch(ajda.status){
						case 200: 
													
							//document.getElementById("log").innerHTML=document.getElementById("log").innerHTML+ajda.responseText;
							var old_cursor = new_cursor;
							document.getElementById("log").innerHTML=ajda.responseText;
							pages = Number (document.getElementById("pages").innerHTML) + pages;
							arts = Number (document.getElementById("arts").innerHTML) + arts;
							new_cursor = document.getElementById("cursor").innerHTML;
							more = Number( document.getElementById("more").innerHTML );
							document.getElementById("status").innerHTML="<br>Получено страниц: "+pages+" <br>"+
							"Получено артов: "+arts+" <br>"+
							"<br>"
							//+ old_cursor + "<br>" + new_cursor
							;
							
							if ( empty_arts > 3 || err > 60 ){
								document.getElementById("daStatus").innerHTML="<i><span style='color:grey; font-weight:bold;'>Чтение dA завершено</span></i>";
								document.getElementById("loadArts").style.display = "initial";
								document.getElementById("saveArts").style.display = "initial";
							} else {
								if ( more == -1) {
									document.getElementById("daStatus").innerHTML="<i>Ошибка связи с dA:<br> <span style='color:red'>Жду уже " +err+ " из 60 сек.</span></i>"; 
									err++;
								} else {
									document.getElementById("daStatus").innerHTML="<br><i><span style='color:green'>Читаю ленту на dA</span></i>";		err = 0;
									if ( Number (document.getElementById("arts").innerHTML) == 0 ){ empty_arts++; } else { empty_arts=0; }
								}
								IniTdAFeed(token);
							}
							
						break;
						default: 
							document.getElementById("log").innerHTML="<tr><td colsan='3'>Ошибка!</td></tr>"; 
							document.getElementById("status").innerHTML="<span style='color:red'>Ошибка при обработке запроса </span>"; 
						break;
						}
					}
				}
							
				ajda.open('GET', 'dA_integrator2.php?mode=askart&token='+token+'&cursor='+new_cursor);
				ajda.send(null);	
								
			}
			
			function dAaJAuth(token){
				
				document.getElementById("log").innerHTML='Authoorizing'
				
				var aJdaAuth = new XMLHttpRequest();
				aJdaAuth.onreadystatechange = function(){
					if (aJdaAuth.readyState==4){
						switch(aJdaAuth.status){
						case 200: 
													
							document.getElementById("log").innerHTML=aJdaAuth.responseText;
							
						break;
						case 403:
						
							document.getElementById("log").innerHTML="<tr><td colsan='3'>Forbidden - 403!</td></tr>"; 
						
						break;
						default: 
						
							document.getElementById("log").innerHTML="<tr><td colsan='3'>Ошибка! № ошибки: " + aJdaAuth.status + ", " + aJdaAuth.statusText + '</td></tr>'; 

						break;
						}
					}
				}
							
				aJdaAuth.open('GET', 'https://www.deviantart.com/oauth2/token?grant_type=authorization_code&client_id=<?PHP print($da_client); ?>&client_secret=<?PHP print($da_secret); ?>&redirect_uri=http://scadsdnd.ddns.net/myphp/ponygalai/add-art.php&code='+token);
				aJdaAuth.send(null);	
				
			}
						
	</script>

	<?php
	function SerchCategories(){
		if (file_exists("art-db.sqlite")){
			$avtd = new SQLite3("art-db.sqlite");
			$req = $avtd -> query("SELECT * FROM art_categories");
			//$row = $req -> fetchArray(SQLITE3_NUM);
			print('<option value="0" selected> - Выберите категорию - </option>');
			while ($row = $req -> fetchArray(SQLITE3_NUM)){
				print('<option value="'.$row[0].'"> '.$row[1].' </option>');
			}
			if ( $req -> fetchArray(SQLITE3_NUM) == '' ) {
				print('<option value="0"> Не найдено категорий в БД </option>');
			}
		} else {
			print('<option value="0"> Не найдена таблица категорий </option>');
		}	
	}
	
	/*
	$avtd = new SQLite3("art-db.sqlite");
	$req = $avtd -> query("SELECT * FROM art_categories");
	echo '"'.$req -> fetchArray(SQLITE3_NUM).'"';
	*/
	
	?>
	
	<br>
	
	<!-- <img id="zoom" src='img/stack_of_photos.png' style="position:absolute; z-index:3; top:0px; left:0px; display:none;"> -->
		
	<span align="center" style="border-radius:5px; background: #eee; padding:5px;"> 
		Имя новой категории: 
		<input type="text" id="catFullName" size="50%"> 
		<input type="button" value="Добавить" onclick="AJAXAddData(2,'','','','')" >
	</span>
	&emsp;
	<span style="border-radius:5px; background: #eee; padding:5px;">
		<input type="button" style="font-weight:bold; color:blue;" value="+" onclick="AddRows('')" >
	</span>
	<hr>
	<table border="0" width="100%" cellspacing="5">
		<tr id="starter" height="20">
			<td width="45%" rowspan='999' valign='top' >
				Предпросмотр <br><br>
				<span id='zoom_text'>  </span>
				<img id="zoom" src='' width='100%' style="display:none;"><br>
				<img id="zoom_wait" src='img/nine_blocks_128px.gif' width='35%' style="display:none; padding:30px;">
			</td>
			<td width="50%" id='head_src'>Путь к арту</td>
			<td>Категория арта</td>
			<td>
				<span style="font-size:5pt;"> Дата добавления </span><br>
				<input type="text" class="datepicker" id="artDate_etal" value="<?PHP print(date("j-m-Y")); ?>" style='width:80px;'>
			</td>
			<td><span title="Удалить арт">D</span></td>
		</tr>
		<tr id="artrow_0" height='20'>
			<td align="center"> <input type="text" id="artFullName_0" size="100%" style="width:100%;" value="" class="pvImg"> </td>
			<td align="center"> <select onchange="" id="cbCat_0" class="imgCat" > <?php SerchCategories(); ?> </select> </td>
			<td align="center"> <input type="text" id="artDate_0" value="<?PHP print(date("j-m-Y")); ?>" style='width:80px;'>  </td>
			<td align="center"> <div id="del_0" class="imgDel" style="color:red; cursor:pointer;">(X)</div>  </td>
		</tr>
		<tr valign='top'>
			<td colspan="6" align="center">
				<input type="button" style="font-weight:bold;" value="Добавить арты в БД" onclick="InitArtProcessing()" >
				<input type="button" style="font-weight:bold;" value="+" onclick="AddRows('')" >
			</td>
		</tr>
	</table>
	
	<br>
	<div><b>Статус: </b>	
	<div id='daStatus'>
	</div>	
	
	<span id="status">
	
	<!-- <br><br> Прочитать не больше 
	<input id="max_arts" type="number" value="0" style="width:50px;"> артов. -->
	
	<?php
		if ( isset( $_GET['code'] ) ) { 
		
			printf("<span style='color:grey; font-weight:bold;'>Получен dA-код:</span> <br>" . $_GET['code'] . "");
			
			
			$opts = [
				'http' => [
					'method' => 'GET',
					'header' => implode("\n", ['Accept-Encoding: gzip,deflate', 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'] ]),
				]
			];
			$context = stream_context_create($opts);
			
			if ( $context ){
									
				$content = file_get_contents('https://www.deviantart.com/oauth2/token?grant_type=authorization_code&client_secret=227108d8fe261b4c89dbc3ade3fa6e5b&client_id=3286&client_secret=227108d8fe261b4c89dbc3ade3fa6e5b&redirect_uri=http://scadsdnd.ddns.net/myphp/ponygalai/add-art.php&code='.$_GET['code'].'', false, $context);		
			
				//$json = gzinflate(substr($content,10));
				$json = $content;			
									
				//print('<input type="button" onclick="dAaJAuth(\''.$_GET['code'].'\');" value="dAaJAuth">');
			} else {
				Print('<span style="color:red">Error in dA OAuth2 token</span>');
			}
			
		
		} elseif ( isset( $_GET['credent'] ) ) {
			
			$json = file_get_contents('https://www.deviantart.com/oauth2/token?grant_type=client_credentials&client_id=3286&client_secret=227108d8fe261b4c89dbc3ade3fa6e5b');	
			
		} 
		
		if ( isset( $json ) ) {
		
			$obj = json_decode($json, true); // GOT assoc array		
		
			printf("<br><span style='color:grey; font-weight:bold;'>Получены dA-токены:</span> <br><span title='Acccess Token'>" . $obj['access_token'] . "</span><br>");
			if ( isset ( $obj['refresh_token'] ) ) { printf("<span title='Refresh token'>" . $obj['refresh_token'] . "</span><br><br>"); }
			printf("
				<button style='font-weight:bold;' onclick='IniTdAFeed(\"".$obj['access_token']."\")'> Читать ленту с dA</button>
				");
			//print("<script type='text/javascript'> document.getElementById('dabutton').style.display='none'; </script>");
		
			//var_dump(json_decode($json, true));
		
		}
		
	?>

<!--	
	<script type="text/javascript">
	
			if (window.location.hash != ''){
				
				var parsed = window.location.hash.split('&');
				var token = parsed[0].split('=');
				//alert(token[1]);
				
				
				document.write("<br>Получен dA-токен: <br><span title='Acccess Token'>" + token[1] + "</span><br><br>");
				document.write("<input type='button' style='font-weight:bold;' value='Читать ленту с dA' onclick='IniTdAFeed(\"" + token[1] +"\")'> <br>");
			}
	
	</script>
	-->
	
	</span></div>
	
	<?php if ( !isset( $_GET['code'] ) ) { ?>
	
	<a 
	href="https://www.deviantart.com/oauth2/authorize?
	response_type=code&
	client_id=3286&
	redirect_uri=http://scadsdnd.ddns.net/myphp/ponygalai/add-art.php&
	scope=feed"
	title="(The Authorization Code Grant)"
	id='dabutton'>
	<br>
	<button>
	Соединиться с dA
	</button>
	</a>
	
	<?php } ?>
	
	<button id='loadArts' onclick='AJGetArts(1)' style='display:none'>Загрузить арты в список</button>
	<button id='saveArts' onclick='InitArtSave()' style='display:none'>Записать арты в БД</button>
	
	<table id="log"></table>	
	<!--<iframe id="daFrame" src="dA_integrator.php?mode=auth" width="580" height="210" frameborder="0"></iframe><br> -->
	
	<br>
	<span style="color:grey; font-size:7pt;">	
	Scripting and Design: <b>scadl</b><br>
	<i><a href="http://scadsdnd.sytes.net/" target="_blank">SCAD's Design & Develop</a> - Aug 2013</i>
	</span>

    </div>
	
</body>
</html>
