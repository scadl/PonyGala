<?php
// Login scheme
$master = 'master_password';
//$hash = password_hash($master, PASSWORD_DEFAULT);



if (isset($_POST['mpass'])) {
    //if ( password_verify ( $_POST['mpass'], $hash ) ){
    if ($_POST['mpass'] == $master) {
        //print('alert("Пароль ок!");');                
        SetCookie("ponygalai_admin", "true");     
    } else {
        print('alert("Пароль не верный!");');        
    }
}

session_start();
if ( isset($_COOKIE['ponygalai_admin']) ){
    $_SESSION['admin'] = true;
    //print('admin on');
}

if (isset($_GET['logout'])) {
    unset($_SESSION['admin']);
    SetCookie("ponygalai_admin", "");
    header('Location: index.php');
}
// Login Scheme END

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <?php if (isset($_GET['date'])) { ?>	
            <title> DigitalArt Gallery v2 (by scadl) [<?PHP print($_GET['date']); ?>] </title>			
        <?php } else { ?>
            <title> DigitalArt Gallery v2 (by scadl) [Full Archive]</title>			
        <?php } ?>	
        <style type="text/css">
            @font-face{
                font-family:CelestiaRedux;
                src: url(CelestiaMediumRedux1.55.ttf);
            }
            .cellstyle{
                width: 250px;            
                text-align: center;
                font-family: sans-serif;
                padding: 5px;
                padding-bottom: 15px;
                border-radius: 5px;
                background: #ccc;
            }
            a:link{
                color: #000;
                text-decoration: none;
            }
            a:visited{
                color: #000;
                text-decoration: none
            }
            a:hover{
                color: grey;
                text-decoration: none;
            }
            .subdesc{
                font-size:10pt;
            }
            .desc-nm{
                font-size:12pt;
                font-weight:bold;
            }
            .desc-gr{
                color:#999;
            }
            .tech_lab{
                background:#eee;
                border-radius:5px;
                font-family: sans-serif;
            }
            #head_link :hover{
                color:black;
            }
            .block_tmb{
                width: 240px;
                height: 250px;
            }
            .footer_txt{
                font-family: sans-serif;
            }
            .btn_left{
                display: inline-block;
                margin-left: 5px;
            }
            .btn_right{
                display: inline-block;
                margin-right: 5px;
                padding: 5px;
            }
            .divider{
                border-right:#fff 3px solid
            }
        </style>
        <link rel="stylesheet" href="jquery-ui/css/custom-theme/jquery-ui-1.10.4.custom.css">
        <script src="jq/jquery-1_9_1.js"></script>
        <script src="jq/jquery-ui-1_10_4.js"></script>
        <script type="text/javascript">
            function Visible(target, action) {
                if (action == 'hide') {
                    document.getElementById(target).setAttribute("style", "display:none;");
                }
                if (action == 'show') {
                    document.getElementById(target).setAttribute("style", "display:normal; padding:5px;");
                }
            }

            function StartDate() {
                //alert(document.getElementById('datepicker').value);
                window.location = 'index.php?date=' + document.getElementById('datepicker').value;
            }

<?PHP


$publication = "";
if (file_exists("art-db.sqlite")) {

    $artdb = new SQLite3("art-db.sqlite");

    $req = $artdb->query("SELECT file_name, addate FROM arts GROUP BY addate");
    while ($row = $req->fetchArray(SQLITE3_NUM)) {
        $publication = $publication . '"' . $row[1] . '",';
        //$lastpub[]=$row[1];
    }
} else {
    $publication = "";
}
unset($artdb);
unset($req);
unset($row);
?>

            var availableDates = [<?php print( rtrim($publication, ',')); ?>];
            function available(date) {
                month = date.getMonth() + 1;
                if (month < 10) {
                    month = '0' + month;
                }
                dmy = date.getDate() + "-" + month + "-" + date.getFullYear();
                console.debug('date: ' + date + ' dmy: ' + dmy);
                //console.debug(dmy+' : '+($.inArray(dmy, availableDates)));
                if ($.inArray(dmy, availableDates) != -1) {
                    return [true, "", "БЫЛА Подборка! Щёлкни, чтобы посмотерть ;)"];
                } else {
                    return [false, "", "В это день подборки НЕ было..."];
                }
            }

            $(function () {

                $("#dtbtn").click(function () {
                    $("#datepicker").datepicker("show");                   
                });

                $("#datepicker").datepicker({
                    showOtherMonths: false,
                    selectOtherMonths: false,
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "d-mm-yy",
                    regional: "ru",
                    showWeek: false,
                    firstDay: 1,
                    beforeShowDay: available,
                    numberOfMonths: 1,
                    showButtonPanel: false,
                    //showOn: "button",
                    //buttonImage: "img/calendar1-20.png",
                    //buttonImageOnly: true,
                    onSelect: function (dateText, inst) {
                        window.location = 'index.php?date=' + dateText;
                    }
                });

                $("#AddMail").click(function () {
                    //alert("click");
                    $("#subsDialog").dialog({
                        resizable: false,
                        //modal:true,
                        height: 250,
                        buttons: {
                            "Ага, ок!": function () {
                                //var umail = $('#umail').val();
                                if ($('#umail').val().indexOf('@') > 0) {
                                    $.ajax({
                                        url: 'add-art-cat.php?type=6',
                                        type: 'post',
                                        data: {
                                            email: $('#umail').val()
                                        },
                                        success: function (data) {
                                            alert(data);
                                        }
                                    });
                                    $(this).dialog("close");
                                } else {
                                    alert('Не смешно, совсем не смешно...\nВведи нормальный e-mail, пожалуйста.');
                                }
                            },
                            "Эм, нет.": function () {
                                $(this).dialog("close");
                            }
                        }
                    });
                });

                $("#MLogin").click(function () {
                    //alert("click");
                    $("#modDialog").dialog({
                        resizable: false,
                        modal: true,
                        buttons: {
                            "Пустите меня!": function () {
                                //var umail = $('#umail').val();
                                if ($('#mpass').val() != '') {
                                    //window.location = 'index.php?pass=' + $('#mpass').val();
                                    document.getElementById("modDialog").submit();
                                    $(this).dialog("close");
                                } else {
                                    alert('Пустой пароль не допустим');
                                }
                            }
                        }
                    });
                });

                $("#MLogOut").click(function () {
                    window.location = 'index.php?logout';
                });

                $("#btnDisq").click(function () {
                    $("#disqus_thread").dialog({
                        title: 'Обсуждение галереи',
                        resizable: false,
                        modal: true,
                        width: 700,
                        height: 800
                    });
                });

            });

        </script>
    </head>
    <body>
        <div style="display:none; text-align:center; font-size:13pt;" title="Подпишись ;)" id="subsDialog">
            Чтобы получать оповещения о новых подборках, введи свой e-mail, поняша:<br>
            <input type="text" id="umail">
        </div>
        <form method="POST" action="" style="display:none; text-align:center; font-size:13pt;" title="Модерация" id="modDialog">
            Введите мастер-пароль<br>
            <input type="password" id="mpass" name="mpass">
        </form>

        <div id="disqus_thread" style='display:none'></div>
        <script>

            /**
             *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
             *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
            /*
             var disqus_config = function () {
             this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
             this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
             };
             */
            (function () { // DON'T EDIT BELOW THIS LINE
                var d = document, s = d.createElement('script');
                s.src = 'https://ponyartgalleryachive.disqus.com/embed.js';
                s.setAttribute('data-timestamp', +new Date());
                (d.head || d.body).appendChild(s);
            })();
        </script>
        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

        <div align="center">

            <span id='head_link' style='font-family:CelestiaRedux;'>
                <span style='font-size:45pt;'>
                    <?php if (isset($_GET['date'])) { ?>	
                        <a href="index.php" target="_self" title="Смотреть всю коллекцию">Big DigitalArt Gallery</a><span style='font-size:8pt;'>v2.3</span>
                    <?php } else { ?>
                        <a href="index.php?date=<?PHP print(date("j-m-Y")); ?>" target="_self" title="Смотреть сегодняшнюю подборку">Big DigitalArt Gallery</a><span style='font-size:8pt;'>v2</span>
                    <?php } ?></span><br>
                <span style='font-size:17pt;'> Для всех, кто любит качественные рисунки фэндомов<br>
                    <span style="font-size:13px">(Создано <a href="http://vk.com/scadl" target="_blank" title="Моя страничка в ВК">scadl</a> для <a href="http://4pda.ru/forum/index.php?showtopic=403239" target="_blank" title="Добро пожаловать в клуб!">Bronies4PDA</a>) </span> 
                    <br>
                </span>

                <?php
                //$lastpub=explode(',',$publication);
                //if (isset($_GET['date'])){ $dtsrl="index.php?date=".$lastpub[1]; } else { $dtsrl="index.php"; }
                if (isset($_GET['date'])) {
                    $dtsrl = "index.php?date=" . date("j-m-Y");
                } else {
                    $dtsrl = "index.php";
                }
                unset($lastpub);

                if (isset($_GET['date'])) {
                    if (preg_match('#^\d{1,2}\-\d{1,2}\-\d{4}$#', $_GET['date'])) {
                        //echo ('Regexp ok');
                    } else {
                        echo ('<br><span style="color:red">Поняша, зачем ты пытаешься взломать мою галерею?!<br>
			<strong>Разве я тебя чем-то обидел??</strong></span><br><br>
			<a href="http://scadsdnd.sytes.net/myphp/ponygalai/' . $dtsrl . '" style="color:#blue; font-size:10pt;"> > Вернуться к подборке < </a><br><br>
			');
                        $arts_nm = 0;
                        goto fool;
                    }
                }

                include "visitors.php";

                $arts_nm = 0;
                $vote_nm = 0;

                if (file_exists("art-db.sqlite")) {

                    $artdb = new SQLite3("art-db.sqlite");

                    $req = $artdb->query("SELECT * FROM arts");
                    while ($row = $req->fetchArray(SQLITE3_NUM)) {
                        $vote_nm = $vote_nm + $row[3];
                        $vote_nm = $vote_nm + $row[4];
                        $vote_nm = $vote_nm + $row[5];
                        $vote_nm = $vote_nm + $row[6];
                        $vote_nm = $vote_nm + $row[7];
                        $arts_nm += 1;
                    }
                } else {
                    //print("NO db");
                    $vote_nm = 0;
                    $artdb = new SQLite3("art-db.sqlite");
                    $artdb->query('CREATE TABLE 
			arts (file_name TEXT, category NUMBER, addate TEXT, like NUMBER, dislike NUMBER, old NUMBER, goodnold NUMBER, middle NUMBER)');
                    $artdb->query('CREATE TABLE 
			art_categories (cat_id INTEGER PRIMARY KEY AUTOINCREMENT, cat_name TEXT)');
                }


                $req = $artdb->query("SELECT email FROM subscribers");
                $sbt = 0;
                while ($row = $req->fetchArray(SQLITE3_NUM)) {
                    $sbt++;
                } unset($req);
                unset($row);
                ?>


                <style type="text/css"> .divider{border-right:#fff 3px solid} </style>	

                <table border="0" width="100%"><tr>

                        <td align="left">
                            <table border="0" cellpadding="3" class="tech_lab" style="float:left;"><tr>
                                    <td class="divider">Статистика:</td>
                                    <td>	<a href="visitors.php?act=log&mdate=<?php print(date("m")); ?>&ydate=<?php print(date("Y")); ?>" target="_blank">	<img src="img/user.png" width="16" title="Посетителей: Всего (За сегодня) [Подписалось]"> 	</a> 	</td>
                                    <td class="divider" title="Посетителей: Всего (За сегодня) [Подписалось]"> <a href="visitors.php?act=log&mdate=<?php print(date("m")); ?>&ydate=<?php print(date("Y")); ?>" target="_blank"> <?php print($ips . ' (' . $tip . ') [' . $sbt . ']'); ?> </a>  </td>
                                    <td><img src="img/page3.png" width="16" title="Просмотров"></td>
                                    <td class="divider"><?php print($visits); ?></td>
                                    <td><img src="img/pic.png" width="15" title="Артов"></td>
                                    <td class="divider"><span id="arts-nm"></span></td>
                                    <td><img src="img/vote.png" width="15" title="Голосов"></td>
                                    <td><?php print($vote_nm); ?></td>
                                </tr></table>
                            <?php if (!isset($_SESSION['admin'])) { ?>
                                <div class="tech_lab btn_left" id="MLogin">
                                    <span title="Вход модератора" style="cursor:pointer;">
                                        <img src="img/key-house.png" height="20" border="0" style="padding:5px 7px 3px;">
                                    </span>
                                </div>	
                            <?php } else { ?>
                                <div class="tech_lab btn_left" id="MLogOut">
                                    <span title="Выход модератора" style="cursor:pointer;">
                                        <img src="img/exit.png" height="20" border="0" style="padding:5px 7px 3px;">
                                    </span>
                                </div>	
                            <?php } ?>                            
                        </td>


                        <?php if (isset($_SESSION['admin'])) { ?>
                            <td>
                                <span class="tech_lab" style="padding:5px;">
                                    <a href="add-art.php" target="_blank" title="Add Art"> 
                                        <img src="img/add_art.png" width="20" border="0" style="position:relative; top:5px; left:3px;"> 
                                    </a>
                                </span> 
                                <span class="divider"> </span>
                                <span class="tech_lab" style="padding:5px;">
                                    <span onclick="Recache()" title="Refresh Stacks" style="cursor:pointer;">
                                        <img src="img/refrash_stack.png" width="20" border="0" style="position:relative; top:5px; left:3px;">
                                    </span>
                                </span> 
                                <span class="divider"> </span>
                                <span class="tech_lab" style="padding:5px;">
                                    <a href="cache_cleaner.php" target="_blank" title="Clean GalThumbs">
                                        <img src="img/clean_thumb.png" width="20" border="0" style="position:relative; top:5px; left:3px;">
                                    </a>
                                </span> 
                                <span class="divider"> </span>
                                <span class="tech_lab" style="padding:5px;">
                                    <a href="thum-stack-tb-day.php<?php
                                    if (isset($_GET['date'])) {
                                        print("?date=" . $_GET['date']);
                                    }
                                    ?>" target="_blank" title="Generate Preview Stack">
                                        <img src="img/stack_of_photos.png" width="20" border="0" style="position:relative; top:5px; left:3px;">
                                    </a>
                                </span> 
                                <span class="divider"> </span>

                                <span class="tech_lab" style="padding:5px;">
                                    <span onclick="AlertSubscribers()" target="_blank" title="Submit Notifications" style="cursor:pointer;">
                                        <img src="img/send_all.png" width="20" border="0" style="position:relative; top:5px; left:0px;">
                                        <span id="totalsubmit"></span>
                                    </span>                                        
                                </span>

                            </td>
                        <?php } ?>

                            <td align="right" valign="middle">	
                            <!--
                            <span class="tech_lab" id="" style="padding:5px; cursor:pointer;" title="Поиск артов (по всей коллекции)" onclick="ArchiveFrame()">
                            <img src="img/67.png" width="20" border="0" style="position:relative; top:3px; left:3px;">
                            </span>
                            -->
                            
                            <?php print("<a href='http://scadsdnd.serveftp.com/myphp/ponygalai/" . $dtsrl . "' style='color:white; font-size:10pt; cursor:default;' target='_self'>go global</a>"); ?>                            
                            <!--
<span class="tech_lab" style="padding:5px;" id="AddMail">
    <span title="Подпишись на обновления - не пропускай подборки!" style="cursor:pointer;">
        <img src="img/mail_add.png" width="20" border="0" style="position:relative; top:3px; left:3px;">
    </span>
</span>	
                            -->

                            <span class="tech_lab btn_right" id="btnDisq" 
                                  style="position: relative; top: 2px; cursor:pointer;">
                                <span title="Обсуждение галереи!">
                                    <img src="img/animat-pencil.gif" width="20" border="0">
                                </span>
                            </span>	

                            <span class="tech_lab btn_right" style="position: relative; top: 2px;">
                                <a title="Архив артов 09.2013 - 09.2014" style="cursor:pointer;" 
                                   href="https://cloud.mail.ru/public/LvnN/CdvGjpMnM" target="_blank">
                                    <img src="img/cloud_disk.png" width="20" border="0">
                                </a>
                            </span>

                            <?php
                            if (isset($_GET['date'])) {
                                ?>	
                                <span class="tech_lab btn_right" 
                                      style="position: relative; top: 2px;">
                                    <a href="index.php" target="_self" title="Смотреть всю коллекцию">
                                        <img src="img/gallery.png" width="20" border="0">
                                    </a>
                                </span>         
                            
                                <span class="tech_lab btn_right" id="dtbtn" 
                                      style="position: relative; top: 2px; cursor:pointer;" title="Выбрать день публикации" >                                    
                                    <input type="hidden" id="datepicker" value="<?PHP print($_GET['date']); ?>">
                                    <img src="img/calendar1.png" width="20" border="0"/>                                    
                                </span>

                                <span class="tech_lab btn_right" width="100" 
                                      style="position: relative; bottom: 2px;" > 
                                    Публикация: <?PHP print($_GET['date']); ?>
                                </span> 
                                <?PHP
                            } else {
                                ?>                            
                            
                                <span class="tech_lab btn_right" id="dtbtn" 
                                      style="position: relative; top: 2px; cursor:pointer;" title="Выбрать день публикации" >                                    
                                    <input type="hidden" id="datepicker" >
                                    <img src="img/calendar1.png" width="20" border="0"/>                                    
                                </span>
                            
                                <span class="tech_lab btn_right" width="100" 
                                      style="position: relative; bottom: 2px;"> 
                                    Полный архив
                                </span>
                                <?PHP
                            }
                            ?> 	
                        </td>	

                    </tr></table>

                <div id="poupup_wnd" style="display: none;">
                    <div style="background: #aaa; padding: 5px; border-radius:10px; border:solid 3px #555; text-align: center;"><b>Задайте условия поиска</b></div>
                    <form action="gallery.php" method="get">
                        <table table border="0" cellspacing="3" width="90%" style="margin: 10px;"><tr>
                                <td style="background: #ddd; padding: 10px; border-radius:5px;"> 
                                    Ключевое слово <input size="50" type="text" value="twilight" name="keyword">
                                </td>
                            </tr>
                            <tr>
                                <td style="background: #ddd; padding: 10px; border-radius:5px;"> 
                                    Искать в: 
                                    <input type="checkbox" name="fname" checked>Названии арта, 
                                    <input type="checkbox" name="auth">Нике автора
                                </td>
                            </tr>
                            <tr>
                                <td style="background: #ddd; padding: 10px; border-radius:5px; ">
                                    Искать в категориях:<br>
                                    <!--
                                    <table><tr><td width="57%" valign="top">
                                    <input type="checkbox" value="0" name="1" checked>Один персонаж с фоном<br>
                                    <input type="checkbox" value="1" name="2">Один персонаж без фона<br>
                                    <input type="checkbox" value="2" name="3">Два персонажа <НЕ шиппинг><br>
                                    <input type="checkbox" value="3" name="4">Три и более персонажей<br>
                                    <input type="checkbox" value="4" name="5">Мрачные и грустные ПониАрты<br>
                                    <input type="checkbox" value="5" name="6">Антагонисты<br>
                                    <input type="checkbox" value="6" name="7">ПониВоины<br>
                                    <input type="checkbox" value="7" name="8">ПониПортреты<br>
                                    <input type="checkbox" value="8" name="9">Хуманизации, Антро<br>
                                    </td><td width="43%" valign="top">			
                                    <input type="checkbox" value="9" name="10">Шиппинг и Романтика<br>
                                    <input type="checkbox" value="10" name="11">ПониОбои<br>
                                    <input type="checkbox" value="11" name="12">Equestria Girls<br>
                                    <input type="checkbox" value="12" name="13">Комиксы<br>
                                    <input type="checkbox" value="13" name="14">SFM, GIF, SWF<br>
                                    <input type="checkbox" value="14" name="15">Бэкграунды<br>
                                    <input type="checkbox" value="15" name="16">Ресурсы<br>
                                    <input type="checkbox" value="16" name="17">Пиксельный арт<br>
                                    </td></tr></table>-->
                                </td>
                            </tr>
                            <tr>
                                <td align="center"> 
                                    <input type="submit" value="Начать поиск">
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>

                <div id="frm_bg" style="display: none;" onclick="HideFrame()"></div>
                <script type="text/javascript" src="frame_handler.js"></script>	   	    

                <script type="text/javascript">
                    var wdallow = Math.floor(window.innerWidth / 220) - 1;
                    var tmbic = 0;
                </script> 

                <script type="text/javascript">
                    function AJAXLoadStack(rqaddr, ind) {
                        //alert(rqaddr+' '+ind);
                        var ajobj = new XMLHttpRequest();
                        ajobj.onreadystatechange = function () {
                            if (ajobj.readyState == 4) {
                                switch (ajobj.status) {

                                    case 200:
                                        document.getElementById("blid_" + ind).innerHTML =
                                                "<img src='_cache-thumbs/thumb-stack-CatN" + rqaddr + "_" + "<?php
                            if (isset($_GET['date'])) {
                                print($_GET['date']);
                            } else {
                                print("vault");
                            }
                            ?>" + ".png' border='0' width='245'>";
                                        break;

                                        //default: alert("Ошибка при генерации стопки\n № ошибки: " + ajobj.status+', '+ajobj.statusText); break;
                                    default:
                                        document.getElementById("blid_" + ind).innerHTML = "<div style='padding:25px; font-size:8pt; height:200px; vertical-align:middle;'>" +
                                                "Упс, что-то не так, с генерацией стопки...<br><br>" +
                                                "Хм, похоже сервер слегка перегружен...<br><br>" +
                                                "Обновите пожалуйста страничку ;) </div>";
                                        break;

                                }
                            }
                        }
                        ajobj.open('GET', 'thum-stack-tb.php?catn=' + rqaddr + '<?php
                            if (isset($_GET['date'])) {
                                print('&date=' . $_GET['date']);
                            }
                            ?>');
                        ajobj.send(null);
                    }
                </script>

<?php
$thumbs = 0;
$artstot = 0;
print("<table border='0' cellspacing='5'><tr>");
$req = $artdb->query("SELECT * FROM art_categories");

while ($row = $req->fetchArray(SQLITE3_NUM)) {

    /*
      if (isset($_GET['date'])){
      $catarts=0;
      $reqct = $artdb -> query("SELECT file_name FROM arts WHERE category=".$row[0]." AND addate='".$_GET['date']."'");
      while($rus = $reqct -> fetchArray ( SQLITE3_NUM ) ) { $catarts+=1; }
      if ($catarts <= 0) { goto endcat; }
      }
     */
    $thumbs++;

    $artscat = 0;
    if (isset($_GET['date'])) {
        $reqi = $artdb->query("SELECT file_name FROM arts WHERE category=" . $row[0] . " AND addate='" . $_GET['date'] . "'");
    } else {
        $reqi = $artdb->query("SELECT file_name FROM arts WHERE category=" . $row[0]);
    }
    while ($rowat = $reqi->fetchArray(SQLITE3_NUM)) {
        $artscat += 1;
    }
    if (isset($_SESSION['admin'])) {
        if ($artscat <= 0) {
            goto endcat;
        }
        $limit = 300;
    } else {
        if ($artscat <= 0 || $row[0] == 0) {
            goto endcat;
        }
        $limit = 90;
    }
    ?>
                    <td class="cellstyle">
                        <a href="gallery.php?catn=<?php echo $row[0]; ?>&artstot=<?php
                if ($artscat > $limit) {
                    echo($limit);
                } else {
                    echo ($artscat);
                }
    ?>&limit=<?php echo $limit; ?>&ofset=0<?php
                           if (isset($_GET['date'])) {
                               print('&date=' . $_GET['date']);
                           }
                           ?>" target="_self">
                            <div class="block_tmb" >

                                <script>
                                    AJAXLoadStack('<?php print($row[0]); ?>', '<?php print($thumbs); ?>');
                                </script>			

                                <div id="blid_<?php print($thumbs); ?>" wnd="<?php print($thumbs); ?>" catn="<?php print($row[0]); ?>" class="block_tmb">
                                    <img src="img/pinkie_pie__s_partytime_clock_gadget_by_redsearcher-d4t9ab0.png" width="150" border="0" style="padding-top:25px;"><br>
                                    <img src="img/loading10.gif" width="100" border="0" >
                                </div>			

                            </div>

                            <span class="desc-nm"><?php echo $row[1] ?></span></a><br><br>
                        <span class="subdesc"><i>Артов в категории: <b>
                                    <?php
                                    echo $artscat;
                                    $artstot = $artstot + $artscat;
                                    ?>
                                </b></i></span>
                    </td>
                    <script type='text/javascript'>
                        tmbic++;
                        if (tmbic == wdallow) {
                            document.writeln('</tr><tr>');
                            tmbic = 0;
                        }
                    </script>

                    <?php
                    endcat:
                }
                print("</tr></table><br>");
                print("<span style='color:#ccc; font-size:10pt;' class='footer_txt'>Артов в подборке: <strong>" . $artstot . "</strong></span><br><br>");

                fool:
                ?>

                <span class='footer_txt'>
                    Scripting and Design: <b>scadl</b><br>
                    <i><a href="http://scadsdnd.ddns.net/" target="_blank">SCAD's Design & Develop</a> - Aug 2013</i>
                </span>

                <?php
                print("<script type='text/javascript'>
	document.getElementById('arts-nm').innerHTML=" . $arts_nm . ";
	</script>");
                ?>	

        </div>

<?php if (isset($_SESSION['admin'])) { ?>
            <script type="text/javascript">
                function Recache() {
                    $('div[id*="blid_"]').html('<img src="img/pinkie_pie__s_partytime_clock_gadget_by_redsearcher-d4t9ab0.png" width="150" border="0" style="padding-top:15px;"><br>' +
                            '<img src="img/loading10.gif" width="100" border="0" >');
                    $.ajax({
                        url: 'add-art-cat.php?type=5&date=<?php
    if (isset($_GET['date'])) {
        echo($_GET['date']);
    } else {
        echo("vault");
    }
    ?>',
                        type: 'get',
                        success: function (res) {
                            //alert(res); 
                            setTimeout(function () {
                                $('div[id*="blid_"]').each(function (ind) {
                                    AJAXLoadStack($(this).attr('catn'), $(this).attr('wnd'));
                                });
                            }, 3000);
                        }
                    });
                }

    <?php
    if (isset($_GET['date'])) {
        $dtsnd = $_GET['date'];
    } else {
        $dtsnd = date("j-m-Y");
    }
    ?>
                function AlertSubscribers() {
                    $('#totalsubmit').text('Wait...');
                    $.ajax({
                        url: 'add-art-cat.php?type=7&artn=<?php print($artstot); ?>&date=<?php print($dtsnd); ?>',
                        type: 'post',
                        success: function (data) {
                            $('#totalsubmit').text(data);
                        }
                    });
                }
            </script>	
<?php } ?>

    </body>
</html>
