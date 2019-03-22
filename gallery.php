<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>DigitalArt Gallery v.2 - Category View (by scadl)</title>
    <style type="text/css">
		@font-face{
			font-family:CelestiaRedux;
			src: url(CelestiaMediumRedux1.55.ttf);
		}
		@font-face{
			font-family:RateSegoeUI;
			src: url(segoeuil.ttf);
		}
		@font-face{
			font-family:SymbolWebdings;
			src: url(WINGDNG2.TTF);
		}
		.glow{
			color:black; 
			font-size:13pt; 
			font-weight:bold; 
			text-shadow: 1px 1px 2px white, -1px -1px 2px white, 1px -1px 2px white, -1px 1px 2px white;
		}
        .thumb-frame{
            height:300px;
            width:210px;
            background:#ccc;
            float: left;
            border-radius: 15px;
            border:#AAA 2px solid;
            padding:5px;
        }
        .thumb{
            border-radius:15px;
            border:#777 3px solid;
        }
        #thumb-wd{
            width: 200px;
        }
        #thumb-hg{
            height: 200px;
        }
        .thumb-lab{
            width:200px;
            padding:3px;
            font-weight:500;
        }
        .thum-auth{
            font-size:9pt;
			font-weight:200;
        }
		.thum-date{
            font-size: 7pt; 
			font-style: italic;
			font-family: sans-serif;
        }
		.like_btn{
			
		}
		.vote_panel_light{
			background:#bbb;
			opacity: 0.6;
			border-radius:0px 0px 10px 10px;
			box-shadow: 0 0 5px #bbb;
			z-index:3;
			position:relative;
		}
		.vote_panel_dark{
			background: rgb(153, 153, 153); 
			opacity: 0.9;
			color: #000; 
			border-radius:0px 0px 10px 10px;
			border-top:solid 1.5px #aaa; 
			border-bottom:solid 2px #aaa;
			position:relative;
			top: 3px;
		}
		a:link{
			color:#000;
			text-decoration:none;
		}
		a:hover{
			color:#eee;
			text-decoration:none;
		}
		a:visited{
			color:#000;
			text-decoration:none;
		}
		.pgs{
			background:#ddd; 
			padding:5px; 
			margin:5px; 
			border-radius:5px;
			font-family: sans-serif;
			text-align:center;
			float:left;
			display: block;
			border: solid 2px #eee;
		}
		.pgsl{
			background:#bbb; 
			padding:5px; 
			margin:5px; 
			border-radius:5px;
			font-family: sans-serif;
			text-align:center;
			float:left;
			display: block;
			font-weight: bold;
			border: solid 1px #aaa;
		}
		.pbSecR{
			background:#765959;
		}
		
		.selected{
			background: grey;
		}
    </style>
		
<script type="text/javascript" src="highslide/highslide-with-gallery.js"></script>
<script type="text/javascript" src="highslide/highslide.config.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="highslide/highslide.css" />
	
</head>
<body style="font-family: CelestiaRedux, sans-serif;">
    
	<table width="100%" border="0">
	<tr>
	<td width='105'> 
	<a href='index.php<?php if (isset($_GET['date'])){ print('?date='.$_GET['date']); } ?>' target='_self'><img src='img/Back_btn.png' width='100' border='0'></a>
	
	<?php
	
	// ADMIN_IP !!!!
	//$admin_ip = "192.168.137.1";
        session_start();
	
	function Fool($debug){
			print('</td><td style="text-align:center; font-family: sans-serif;"><br>
			<span style="color:red">Поняша, ну честное слово...!<br>
			<strong>Неужели так интересно ломать чужу работу??</strong></span><br><br>
			<span style="color:purple; font-weight:bold;"> Займись-ка лучше чем-нибудь созидательным. ;) </span>
			</div><br>');
                        Print($debug);
	}
	
	if ( isset($_GET['catn']) && isset($_GET['artstot']) && isset($_GET['limit']) && isset($_GET['ofset']) ){
		
		if ( preg_match('#^\d{1,2}$#', $_GET['catn']) 
		&& preg_match('#^\d{1,5}$#', $_GET['artstot'])
		&& preg_match('#^\d{1,4}$#', $_GET['limit']) 
		&& preg_match('#^\d{1,7}$#', $_GET['ofset'])
		) {
			if ( isset( $_GET['pnm'] ) ){ if ( preg_match('#^\d{1,5}$#', $_GET['pnm']) ){ } else { Fool('pnm_GET_VAR_COMPROMISED'); goto fool; } }
			if ( isset( $_GET['date'] ) ){ if ( preg_match('#^\d{1,2}\-\d{1,2}\-\d{4}$#', $_GET['date']) ){ } else { Fool('date_GET_VAR_COMPROMISED'); goto fool; } }
		} else {
			Fool('Main_GET_VARS_COMPROMISED'); 
			goto fool;
		}
		
	} else {
		Fool('Main_GET_VARS_NA'); 
		goto fool;
	}
	
	?>
	
	</td>	
	<td>
	<div align="center">
	<img src="img/header_gallery.png" height="30"><br>
	<span style="font-size:20pt; font-weight:normal;"><?php
	if (isset($_GET['keyword'])){
		print ('Результаты поиска по: '.$_GET['keyword']);
	} else {
		$avtd = new SQLite3("art-db.sqlite");
		$req = $avtd -> query("SELECT * FROM art_categories WHERE cat_id=".$_GET['catn']);
		$row = $req -> fetchArray(SQLITE3_NUM);
		print($row[1]);		 
		unset($avtd);
		unset($req);
		unset($row);
	}
	
	?></span><br>
	<!--<span style="font-size:17pt; font-weight:bold; color:red; font-family: sans-serif;" id="loader_note"> Подождите пока все рисунки подгрузятся! </span>-->
	
	<br>
	<table id="progressbar" width="100%" cellspacing="10" border="0"><tr>
		<td style="font-family: sans-serif; color:#a85050; width:400px;" align="right">Подождите, изображения загружаются: <span id="poslb">0</span> из <?php echo $_GET['artstot']; ?> &#8594; </td>
		<td >
			<table border="0" width="100%" cellspacing="0" style="border: #bbb 1px solid; border-radius:5px; height:20px;"><tr>
				<script>
	pbPos=0;
	for (var ipb=0; ipb < <?php echo $_GET['artstot']; ?>; ipb++){
		document.write("<td id='pbsec_"+ipb+"'></td>");
	}
			</script>
			</tr></table>
		</td>
	</tr></table>
	
	<br>
		
	</div>
	</td>
	<?php if ( !isset( $_GET['date'] ) ){ ?>
	<td width='105' align='center' style='padding:10px; background:#eee; font-family: sans-serif; font-size:8pt; border-radius:5px;'> 
	<script type="text/javascript">
	
	function ChangeLimit(){	
		window.location=location.protocol+'//'+location.hostname+location.pathname+'?'+
		'<?php print('catn='.$_GET['catn']); ?>'+
		'<?php if (isset($_GET['date'])) { print('&date='.$_GET['date']); } ?>'+
		'&limit=' + document.getElementById("lmchb").options[document.getElementById("lmchb").selectedIndex].value + 
		'&artstot=' + document.getElementById("lmchb").options[document.getElementById("lmchb").selectedIndex].value + 
		'<?php if (isset($_GET['ofset'])) { print('&ofset='.$_GET['ofset']); } else { print('&ofset=0'); } ?>'+
		'<?php if (isset($_GET['pnm'])) { print('&pnm='.$_GET['pnm']); } else { print('&pnm=1'); } ?>';
	}
	
	</script>
	Страница:<br><span style="font-size: 12pt; font-weight:bold;">
	<?php if (isset($_GET['pnm'])){ print($_GET['pnm']); } else { print('1'); } ?>
	</span>
	<hr>
	Листать по
	<select onchange="ChangeLimit()" id="lmchb">
		<option <?php if( isset($_GET['limit']) && $_GET['limit']=='5' ){ print("selected"); } ?> value="5"> 5 </option>
		<option <?php if( isset($_GET['limit']) && $_GET['limit']=='10' ){ print("selected"); } ?> value="10"> 10 </option>
		<option <?php if( isset($_GET['limit']) && $_GET['limit']=='15' ){ print("selected"); } ?> value="15"> 15 </option>
		<option <?php if( isset($_GET['limit']) && $_GET['limit']=='30' ){ print("selected"); } ?> value="30"> 30 </option>
		<option <?php if( isset($_GET['limit']) && $_GET['limit']=='60' ){ print("selected"); } ?> value="60"> 60 </option>
		<option <?php if( isset($_GET['limit']) && $_GET['limit']=='90' ){ print("selected"); } ?> value="90"> 90 </option>
		<option <?php if( isset($_GET['limit']) && $_GET['limit']=='120' ){ print("selected"); } ?> value="120"> 120 </option>
		<option <?php if( isset($_GET['limit']) && $_GET['limit']=='240' ){ print("selected"); } ?> value="240"> 240 </option>
		<option <?php if( isset($_GET['limit']) && $_GET['limit']=='480' ){ print("selected"); } ?> value="480"> 480 </option>
		<!--<option <?php if( isset($_GET['limit']) && $_GET['limit']=='960' ){ print("selected"); } ?> value="960"> 960 </option>
		<option <?php if( isset($_GET['limit']) && $_GET['limit']>='9999999' ){ print("selected"); } if( !isset($_GET['limit']) ){ print("selected"); }  ?> value="9999999"> All </option>-->
	</select> 
	артов 
	</td>
	<?php } ?>
	</tr></table>
	
    <script type="text/javascript">
        //alert( Math.floor(window.innerWidth/200)-1 );
        var wdallow = Math.floor(window.innerWidth/200)-1;
        var tmbic = 0;
		
		var cur_pt = location.protocol+'//'+location.hostname+location.pathname;
		cur_ar = cur_pt.split('/');
		cur_pt = '';
		for (var irr=0; irr<cur_ar.length-1; irr++){
			cur_pt = cur_pt + cur_ar[irr] + '/';
		}
    </script>   

	<script type="text/javascript">
	
			function AJAXLoadThunmb(rqaddr, ind, aid, anm, atl, aadr){
			var ajobj = new XMLHttpRequest();
				ajobj.onreadystatechange = function(){
					if (ajobj.readyState==4){
						switch(ajobj.status){
						case 200: 
							document.getElementById("blid_"+ind).innerHTML = "<a href='"+aadr+"' class='highslide'"+
							"style='height:200px; vertical-align:middle;'"+
							"title='"+atl+"'"+
							"onclick='return hs.expand(this, config1, { myAlert: 8 })'>"+
							"<img onclick='HsFStart("+ind+")' class='thumb' hgid='"+ind+"' id='"+aid+"' src='"+location.protocol+"//"+location.hostname+"/myphp/ponygalai/vault/_cache/"+anm+"'><br>"+
							"</a>"; 
							
						break;
						default: 
							//alert("Ошибка при генерации миниатюрки\n № ошибки: " + ajobj.status+', '+ajobj.statusText);
							document.getElementById("blid_"+ind).innerHTML = "<div style='padding:25px; font-size:8pt; height:200px; vertical-align:middle;'>"+
							"Упс, что-то не так, с генерацией миниатюрки...<br><br>"+
							"Обновите пожалуйста страничку ;) </div>";							
						break;
						}
						document.getElementById("pbsec_"+pbPos).setAttribute("class", "pbSecR"); 
						document.getElementById("poslb").innerHTML=pbPos+1;
						pbPos++;
						if (pbPos >= <?php echo $_GET['artstot']; ?>) {
							document.getElementById("progressbar").innerHTML="";
							document.getElementById("cat_cont").setAttribute("style","display:normal;");
							PrepearListeners();
						}
					} //else { document.write(ajobj.status+', '+ajobj.statusText); }
				}
				ajobj.open('GET', rqaddr);
				ajobj.send(null);
			
			}
			
		function VoteAJAX(vote, tfile, tlab){		
		var ajax_vote_obj=new XMLHttpRequest();		
			ajax_vote_obj.onreadystatechange=function(){
				if (ajax_vote_obj.readyState==4){
					switch(ajax_vote_obj.status){
						case 200: 
							//alert("Запрос обработан успешно!\n" + ajax_vote_obj.responseText); 
							//document.getElementById(tlab).innerHTML = parseFloat(document.getElementById(tlab).innerHTML) + (1 + parseFloat(Math.random().toFixed(1)));
							document.getElementById(tlab).innerHTML = Number(document.getElementById(tlab).innerHTML) + 1;
							//document.getElementsByClassName(tlab)[1].innerHTML = Number(document.getElementsByClassName(tlab)[1].innerHTML)+1;
						break;
						case 404: alert("Не найден скрипт обработки голоса..."); break;
						default: alert("Ошибка обработки голоса\n Ошибка: " + ajax_vote_obj.status); break;
					}
				}
			}
			ajax_vote_obj.open('POST','ajax-vote.php?vote='+vote+'&file='+tfile, true);
			ajax_vote_obj.send(null);
			//alert(vote);
		}
	</script>	
	
	
	<?php
	// ADMIN TOOLS FOR GAllERY - START
	if (isset($_SESSION['admin'])){
		
	function SerchCategories(){
		if (file_exists("art-db.sqlite")){
			$avtd = new SQLite3("art-db.sqlite");
			$req = $avtd -> query("SELECT * FROM art_categories");
			//$row = $req -> fetchArray(SQLITE3_NUM);
			while ($row = $req -> fetchArray(SQLITE3_NUM)){
				print('<option value="'.$row[0].'" id="catid_'.$row[0].'"> '.$row[1].' </option>');
			}
			if ( $req -> fetchArray(SQLITE3_NUM) == '' ) {
				print('<option selected value="0"> Не найдено категорий в БД </option>');
			}
		} else {
			print('<option selected value="0"> Не найдена таблица категорий </option>');
		}
		unset($avtd); unset($req); unset($row);
	}
	?>
	
	<script>
	
	var old_art="";	
	var intind = 0;
	
	function MassUpdate(act){
		
		if ( intind == document.getElementsByClassName('selected').length ){
			
			//alert( intind + " " + document.getElementsByClassName('selected').length );
			if (intind == 0) { clearInterval(artInt); }
			
			var object = document.getElementsByClassName('selected')[intind-1];
			//alert( object.getAttribute('id') );
			object.setAttribute("style","display:none;");
			object.className = object.className.replace(" selected", "");
			document.getElementById('selected_count').innerHTML = document.getElementsByClassName('selected').length;
			
			switch(act){
				case 4:
					AJAXAddData(act, object.getAttribute('art'));			
				break;
				case 3:
					//object.style.background = "green";
					AJAXAddData(3, 
						object.getAttribute('art'), 
						document.getElementById('cbCat_0').options[document.getElementById('cbCat_0').selectedIndex].value,
						document.getElementById('updtdate').value, 
						object.getAttribute('art')
					);
				break;
			}
			
			intind-- ;
			
			
		} else {
			/*
			for (var i=0; i <= document.getElementsByClassName('selected').length; i++ ){
				var object = document.getElementsByClassName('selected')[i];
				object.className = object.className.replace(" selected", "");
				document.getElementById('selected_count').innerHTML = document.getElementsByClassName('selected').length;
			}*/
			
			//intind = -1;
			clearInterval(artInt);
			
		}
		
		
	}
	
	function DelData(obj){
		
		intind = document.getElementsByClassName('selected').length;
		artInt = setInterval( function(){ MassUpdate(4) }, 150);
		
	}
	
	function UpdtData(){
		
		intind = document.getElementsByClassName('selected').length;
		artInt = setInterval( function(){ MassUpdate(3) }, 150);
		
		
		//document.getElementById("tbl_"+document.getElementById('objid').innerHTML).setAttribute("style","display:none;");	
		document.getElementById('log').innerHTML=''; 
		document.getElementById('status').innerHTML='';
	}
	
	/*
	function FillData(obj){
		//alert ( obj.getAttribute('date') );
		old_art=obj.getAttribute('art');
		document.getElementById('updtnm').value=old_art;
		document.getElementById('catid_<?php echo $_GET['catn'] ?>').setAttribute("selected","");
		document.getElementById('updtdate').value=obj.getAttribute('date');
		document.getElementById('objid').innerHTML=obj.getAttribute('id');
		
	}
*/	
	
	function MarkArt(event){
		obj = document.getElementById(event.target.id);
		
		//alert(event.target.id);
		if ( event.target.id.indexOf("rt_") < 1 ){ return; }
		
		if ( obj.className.indexOf(" selected") > 0 ){
			obj.className = obj.className.replace(" selected", "");
		} else {
			obj.className = obj.className + " selected";
		}
		
		document.getElementById('selected_count').innerHTML = document.getElementsByClassName('selected').length;
	}
	
	function PrepearListeners(){
		//alert('Listeners adding..');
		for (var i=0; i< document.getElementsByClassName('thumb-frame').length; i++ ) {
			document.getElementsByClassName('thumb-frame')[i].addEventListener("click", MarkArt);
		}
	}
		
	</script>
	
	<script type="text/javascript" src="frame_handler.js"></script>	 
	<script type="text/javascript" src="ajax_art_manipulation.js"></script>
	
	<div id="poupup_wnd" style="display: none;">
	<div style="background: #aaa; padding: 5px; border-radius:10px; border:solid 3px #555; text-align: center;"><b>Редактируем арт (ID: <span id="objid">0</span>)</b></div>
	<div style="padding:15px;">
	<div id="status" style="width:565px;"></div> 
	<div id="log" style="width:565px;"></div>
	<!--
	Путь к арту: <input type="text" style="width:565px;" id="updtnm"><br><br>
	Категория: <select onchange="" style="width:565px;" id="cbCat_0" > <?php SerchCategories(); ?> </select><br><br>
	Дата добавления: <input type="text" style="width:565px;" id="updtdate"><br><br>
	<div align="center">
	<input type="button" value="Обновить данные" onclick="UpdtData()">
	</div>
	-->
	</div>
	</div>
		
	<div id="frm_bg" style="display: none;" onclick="HideFrame()"></div>
	
	<script type="text/javascript">
		
	</script>
	
	<div style="position:fixed; top:5px; left: 50%; z-index:90">
	<div align="center" style="
		border-radius:5px; border: solid 1px grey;
		background: silver; padding:5px; font-family: sans-serif; 
		position: relative; left: -50%;"> 
		Выбрано: <span id='selected_count' style='font-weight:bold;'>0</span> |
		Категория: <select onchange="" style="width:265px;" id="cbCat_0" > <?php SerchCategories(); ?> </select>
		Дата: <input type="text" style="width:165px;" id="updtdate" value="<?php print($_GET['date']); ?>">		
		<input type="button" value="Обновить" onclick="UpdtData()">	|
		<input type="button" value="Удалить" onclick="DelData()">	
	</div>
	</div>
	
	<!-- // ADMIN TOOLS FOR GAllERY - end -->
	<?php } ?>
	
	<script type="text/javascript">
	function CalcRate(good,bad,id){
			var rate=0.0; 
			//rate=<?php //print($votes[1]); ?> - (<?php //print($votes[5]); ?> * 0.5) - <?php //print($votes[2]); ?> + parseFloat(Math.random().toFixed(1));
			//rate = good - bad + parseFloat(Math.random().toFixed(1));
			rate = good - bad;
			ratestr = "<span style='font-family: RateSegoeUI;' class='glow' title='[&#8593;" + good + "|" + bad + "&#8595]' id='" + id + "'>" + rate + " </span> ";
			//ratestr = "<span style='font-family: RateSegoeUI;' class='glow'>" + "&#8593;" + good + " | " + bad + "&#8595;" + " </span> ";
			return ratestr;
	}	
	</script>
	
	<script type="text/javascript" src="hs_handler.js" charset="utf-8"></script>
    
	<div class="highslide-gallery">
    <center><table width="" border="0" cellspacing="5" id="cat_cont" style="display:none;"><tr>
    <?php
		   
        function ThumbGenerator($thumb, $type, $id, $date){
					
			print("
				<td id='tbl_".$id."'>
					<div align='center' class='thumb-frame' id='art_".$id."' art='".$thumb."'>
				");
			
			$votedb = new SQLite3("art-db.sqlite");		
						
			$vote_obj = $votedb -> query("SELECT file_name, like, dislike, old, goodnold, middle FROM arts WHERE file_name='".$thumb."'");
			$votes = $vote_obj -> fetchArray(SQLITE3_NUM);
			
			/*
			if (isset($_GET['keyword'])){
				$hsaddr=str_replace(" ", "%20", $dirfull."/".$cdir."/".$thumb);
			} else {
				$hsaddr=str_replace(" ", "%20", $dirfull."/".$_GET['dirsf']."/".$thumb);
			}
			*/
			$hsaddr=str_replace(" ", "%20", $thumb);			
			
			?>    
				<span id="vote_<?php print($id) ?>" class="<?php print($hsaddr); ?>" style='position:relative; z-index:3; top:3px;'>
				<table border='0' class='vote_panel_light'><tr>
					<td style='padding:0px 5px; cursor:pointer;' onclick='VoteAJAX(1, "<?php print($thumb) ?>", "<?php print($id) ?>")' title='Очень Понравилось!'> 
					<span style="font-family: RateSegoeUI; font-size:13pt; position:relative; top:-3px; font-weight:bold;" class='glow'> + </span>
					</td>
					<!-- <td style='border-right:solid 3px #aaa; padding-right:5px;'> <span id='like_num'> <span class="<?php print($id) ?>_1"> <?php print($votes[1]); ?> </span> </td> 
					<td style='padding:3px; cursor:pointer;' onclick='VoteAJAX(5, "<?php print($thumb) ?>", "<?php print($id) ?>_5")'> <img src='img/middle.png' width='15' title='Вызывает противоречивые ощущения...'> </td> -->
					<td style='padding:0px 5px;' id='rate_val' title='Рейтинг рисунка'>
					<script> 		document.write( CalcRate(<?php print($votes[1]); ?>, <?php print($votes[2]); ?>, <?php print($id) ?>) ); 		</script> 
					</td>
					<!-- <td style='border-right:solid 3px #aaa; padding-right:5px;'> <span id='middle_num'> <span class="<?php print($id) ?>_5"> <?php print($votes[5]); ?> </span> </td> -->
					<td style='padding:0px 5px; cursor:pointer;' onclick='VoteAJAX(2, "<?php print($thumb) ?>", "<?php print($id) ?>")' title='Совсем НЕ нравится!'>
					<span style="font-family: RateSegoeUI; font-size:13pt; position:relative; top:-3px; font-weight:bold;" class='glow'> - </span>
					</td>
					<!-- <td style='border-right:solid 3px #aaa; padding-right:5px;'> <span id='dislike_num'> <span class="<?php print($id) ?>_2"> <?php print($votes[2]); ?> </span> </td> 
					<td style='padding:3px; cursor:pointer;' onclick='VoteAJAX(3, "<?php print($thumb) ?>", "<?php print($id) ?>_3")'> <img src='img/old.png' width='15' title='Уже где-то было - баян'> </td>
					<td style='border-right:solid 3px #aaa; padding-right:5px;'> <span id='old_num'> <span class="<?php print($id) ?>_3"> <?php print($votes[3]); ?> </span> </td>
					<td style='padding:3px; cursor:pointer;' onclick='VoteAJAX(4, "<?php print($thumb) ?>", "<?php print($id) ?>_4")'> <img src='img/good_n_old.png' width='15' title='Хоть и повтор, но того стоит.'> </td>
					<td style=''> <span id='gdnl_num'> <span class="<?php print($id) ?>_4"> <?php print($votes[4]); ?> </span> </td> -->
				</tr></table>
				</span>
			   
			<?php
            //$imgsz = getimagesize($thumb);
			if (file_exists( 'vault/_cache/'.substr($thumb, strripos($thumb, '/')+1) )){
				$imgsz = getimagesize('vault/_cache/'.substr($thumb, strripos($thumb, '/')+1));
			} else {
				$imgsz = getimagesize($thumb);
			}
			
			$srcfnm=str_replace("_", " ", $thumb);
			$strlen=strlen($srcfnm);
			
			$possl=strripos($srcfnm, '/');
            $posby=stripos($srcfnm, 'by');
            $posmn=strripos($srcfnm, '-');
			
			$aunmf=substr($srcfnm, $posby);
			
			$nameln=$posby-$possl-2;
			$autnln=$posmn-$posby-3;
			
            $nanmst=substr($srcfnm, $possl+1, $nameln);
            $autnnm=substr($srcfnm, $posby+3, $autnln);
			
			if($nanmst==""){ $nanmst="DigitalArt N".rand(0,150); $autnnm="unknown_author_".rand(0,1000); }
			//print("autnm: '".$nanmst."'");
			
			if ($type=="jpg"){ $tmb_addr=$type; }
			if ($type=="png"){ $tmb_addr=$type; }
			
			if ($imgsz[0] > $imgsz[1]){ $thumb_orient_id = "thumb-wd"; }
								 else { $thumb_orient_id = "thumb-hg"; }
			
				if ($type=="swf"){
				?>
				<script> 
					document.getElementById("progressbar").innerHTML="";
					document.getElementById("cat_cont").setAttribute("style","display:normal;");
				</script>
				<span style="cursor:pointer" id="swf_<?php print($id) ?>" onclick="LoadSFW('<?php print($hsaddr); ?>','<?php print($id) ?>')">
				<img src="img/big_mac_adobe_flash_icon_BW_by_tomcat94-d5l1g07.png" width="170" title="Нажмите, чтобы воспроизвести анимацию"><br>
				<div style="position:relative; top: -70px; height: 0px; width:110px; font-family: sans-serif; color:white; font-size:10pt; font-weight:bold;">
					Ткни тут, чтобы воспроизвести анимацию </div>
				</span>		
				<span id="swf_controls_<?php print($id) ?>" style="display:none">[
				<span style="cursor:pointer" onclick="StopSFW('<?php print($id) ?>')" title="Приостановить воспроизведение"> <img src="img/stop.png" width="12"> </span>|
				<a href="<?php print($hsaddr); ?>" target="_blank" title="Скачать анимацию / Открыть в нвовм окне"><img src="img/save.png" width="12"></a>
				<?php 	if (isset($_SESSION['admin'])){ ?>| <span style="cursor:pointer" onclick="ShowFlashFrame('<?php print($id) ?>')" title="Посмотреть анимацию в крупном масшатабе"><img src="img/expand.png" width="12"></span> <?php } ?>
				]</span><br>
				<?php
				} else {
			//<div style='height:200px; vertical-align:middle;'></div>	
				if ($type=="gif") { $nanmst = "&#9658; " . ucwords($nanmst); }
				//print ($nanmst);
			print("															
					<script>
					AJAXLoadThunmb('gala-thumb.php?type=".$type."&imgfl=".$thumb."',
									'".$id."',
									'".$thumb_orient_id."',
									'".substr($thumb, strripos($thumb, '/')+1)."',
									'<b>".$nanmst."</b><br><i>".$autnnm."</i>',
									'".$hsaddr."'
									);
					</script>
			
					<div id='blid_".$id."' height='200' style='position:relative; top:-25px;'>
					<img src='img/pinkie_pie__s_partytime_clock_gadget_by_redsearcher-d4t9ab0.png' width='150' border='0'><br>
					<img src='img/loading10.gif' width='100' border='0' >
					</div>
					
						");
					}
					
			?>

			<?php
			/*
			if (isset($_SESSION['admin']) || $_SERVER['REMOTE_ADDR']=="localhost"){ 
				print("<span onclick='ArchiveFrame(); FillData(this);' art='".$thumb."' date='".$date."' id=".$id." style='font-family:sans-serif; cursor:pointer; color:red;' title='Редактировать'>
				[&#9998;]
				</span>	<span style='font-family:sans-serif; cursor:pointer; color:red;' onclick='DelData(this);' art='".$thumb."' id='".$id."' title='Удалить'> [&#9747;] </span>	<br>"); 
			}
			*/
			
            print("<span class='thumb-lab'>".ucwords($nanmst)."</span><br>
			<span class='thum-auth'><i><a href='http://".str_replace(" ", "-", $autnnm).".deviantart.com/' target='_blank'>".$autnnm."</a></i></span><br>
			<!--<span class='thum-date'>".$date."</span>-->
			<br><br>
			   </div>
			   </td>
			   <script type='text/javascript'>		   
					tmbic++;
                    if (tmbic == wdallow){
						document.writeln('</tr><tr>');
						tmbic=0;
                    }
               </script> ");
		}
		     
		
		if (isset($_GET['keyword'])){
		/*
			$getpos=0;
			if(isset($_GET['fname'])){ $sqname=$_GET['keyword']; } else { $sqname=' '; }
			if(isset($_GET['auth'])){ $sqauth=$_GET['keyword']; } else { $sqauth=' '; }	
			if(isset($_GET['fname'])==false && isset($_GET['auth'])==false){ print('<span style="font-family: sans-serif; font-size:15pt;">Вы не указали,<br> в какой части имени арта<br>искать ключевое слово!<br></span>'); return; }
			$results=0;
		
		
			include "dir-vars.php";
			//print("---<br>".count($_GET)."<br>");
			foreach ($_GET as $i5 => $i5val){
				if ($i5>$getpos){
				//print($dir_nm[$i5val]."<br>");
				$cdir=$dir_nm[$i5val];
				chdir("vault/".$dir_nm[$i5val]); 
				$dirpt="vault/".$dir_nm[$i5val];
		
				$newdir=getcwd();        
				$content=scandir($newdir);
				foreach ($content as $ind => $fold){
				
				$srcfnm=str_replace("_", " ", $fold);
				$strlen=strlen($srcfnm);
				$posby=stripos($srcfnm, 'by');
				$aunmf=substr($srcfnm, $posby);
				$strds=stripos($aunmf, '-');
				$nanmst=substr($srcfnm, 0, $posby);
				$autnnm=substr($aunmf, 0, $strds);
								
				if ($fold=='.' || $fold=='..' || $fold=='Desktop.ini') { print(""); }
				elseif (stripos($fold, '.jpg')!=false && stripos($nanmst, $sqname)!=false && stripos($autnnm, $sqauth)!=false){ ThumbGenerator($fold, "jpg", $ind); $results++;}
				elseif (stripos($fold, '.png')!=false && stripos($nanmst, $sqname)!=false && stripos($autnnm, $sqauth)!=false){ ThumbGenerator($fold, "png", $ind); $results++;}
				elseif (stripos($fold, '.gif')!=false && stripos($nanmst, $sqname)!=false && stripos($autnnm, $sqauth)!=false){ ThumbGenerator($fold, "gif", $ind); $results++;}
				elseif (stripos($fold, '.swf')!=false && stripos($nanmst, $sqname)!=false && stripos($autnnm, $sqauth)!=false){ ThumbGenerator($fold, "swf", $ind); $results++;}
				}
				}
			} if ($results==0){ print('<span style="font-family: sans-serif; font-size:15pt;">Извините, ничего не найдено... <br> Попробуйте изменить условия поиска ;) </span>'); }
			unset($i5val);
		*/
		} else {
		
		include "visitors.php";	

		$limitsql=""; $ofsetsql="";
		if ( isset( $_GET['limit'] ) ) { $limitsql=" LIMIT ".$_GET['limit']." OFFSET ".$_GET['ofset']; }
		
		$avtd = new SQLite3("art-db.sqlite");
		$avtd -> busyTimeout(100);
		if ( isset( $_GET['date'] ) ){
			$req = $avtd -> query("SELECT file_name, addate FROM arts WHERE category=".$_GET['catn']." AND addate='".$_GET['date']."'".$limitsql);
		} else {			
			$req = $avtd -> query("SELECT file_name, addate FROM arts WHERE category=".$_GET['catn'].$limitsql);
		}
				
		$ind=0;
        while ($row=$req->fetchArray(SQLITE3_NUM))
			{ 
			
				//if ($fold=='.' || $fold=='..' || $fold=='Desktop.ini') { print(""); }
					if (stripos($row[0], '.jpg')!=false){ ThumbGenerator($row[0], "jpg", $ind, $row[1]); }
				elseif (stripos($row[0], '.png')!=false){ ThumbGenerator($row[0], "png", $ind, $row[1]); }
				elseif (stripos($row[0], '.gif')!=false){ ThumbGenerator($row[0], "gif", $ind, $row[1]); }
				elseif (stripos($row[0], '.swf')!=false){ ThumbGenerator($row[0], "swf", $ind, $row[1]); }
				print('');
				$ind++;
				
			}
		
		}
    ?>
    </tr></table>
	</center>	
    </div>
	
		<?php if ( !isset( $_GET['date'] ) ){ ?>
		
		<div style="padding:15px; display:block; float:right;">
		<?php	
		
		if ( isset( $_GET['limit'] ) ) { 
		
		$ind=0;
		if ( isset( $_GET['date'] ) ){
			$req = $avtd -> query("SELECT file_name, addate FROM arts WHERE category=".$_GET['catn']." AND addate='".$_GET['date']."'");
		} else {			
			$req = $avtd -> query("SELECT file_name, addate FROM arts WHERE category=".$_GET['catn']);
		}				
        while ($row=$req->fetchArray(SQLITE3_NUM)){ $ind++; }
		
		$pages = round ($ind / $_GET['limit'], PHP_ROUND_HALF_ODD);
		for ($i7=0; $i7 <= $pages; $i7++){
		
			if (isset($_GET['keyword'])){} else {
								
					$rowcn=0;
					$roq = $avtd -> query("SELECT category FROM arts WHERE category=".$_GET['catn']." LIMIT ".$_GET['limit']." OFFSET ".($i7 * $_GET['limit'])) ;
					while ($rows = $roq -> fetchArray()) { $rowcn++; }					
								
					print("<a target='_self' href='?catn=".$_GET['catn']."&limit=".$_GET['limit']."&ofset=".($i7 * $_GET['limit'])."&pnm=".($i7+1)."&artstot=".$rowcn."'> <div class='pgs' id='btn_".($i7+1)."'> ".($i7+1)." </div> </a>");
					
					
			}
		}
		
		 }
			
		?>
		</div>
		<script type="text/javascript">
			document.getElementById("btn_<?php if (isset($_GET['pnm'])) { print($_GET['pnm']); } else { print('1'); } ?>").setAttribute("class","pgsl");
			
			//$('#loader_note').toggle(300);
		</script>
			
		<?php } ?>
	
	<script type="text/javascript" src="FlashFrame.js"></script>
	
	<?php fool: ?>
	<p align="center" style="font-family: sans-serif;">
	Scripting and Design: <b>scadl</b><br>
	<i><a href="http://scadsdnd.ddns.net/" target="_blank">SCAD's Design & Develop</a> - Aug 2013</i>
	</p>	
		
</body>
</html>
