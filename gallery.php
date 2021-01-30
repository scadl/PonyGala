<?php
require './preinit.php';
require './db_init.php';
error_reporting(E_ALL);
ini_set('display_errors', 0);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
    <head>

    <meta charset="UTF-8">
  <meta name="description" content="Big DigitalArt Gallery v.3 - Category View (by scadl)">
  <meta name="keywords" content="brony, art, fanart, ponyart, pony, landscape, portrait, подборка, пони, арт, пейзаж, портрет, фанарт">
  <meta name="author" content="SCADL and Moora">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Big DigitalArt Gallery v.3 - Category View (by scadl)</title>

        <script type="text/javascript" src="libs/highslide/highslide-with-gallery.js"></script>
        <script type="text/javascript" src="libs/highslide/highslide.config.js" charset="utf-8"></script>

        <link rel="stylesheet" type="text/css" href="libs/highslide/highslide.css" />
        <link rel="stylesheet" type="text/css" href="libs/main_g.css" />

        <script type="text/javascript" src="libs/jq/jquery-1_9_1.js"></script>

        <?php if (isset($_SESSION['admin'])) { ?>
        <link rel="stylesheet" href="libs/jquery-ui/css/custom-theme/jquery-ui-1.10.4.custom.css">
        <script type="text/javascript" src="libs/jq/jquery-ui-1_10_4.js"></script>
        <script type="text/javascript" src="libs/functions_g.js" charset="utf-8"></script>
        <?php } ?>

        <script data-ad-client="ca-pub-1505117965346309" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    </head>
    <body style="font-family: CelestiaRedux, sans-serif;">

        <table width="100%" border="0">
            <tr>
                <td width='105'> 
                    <a href='index.php<?php if (isset($_GET['date'])) { print('?date=' . $_GET['date']);} ?>' target='_self'>
                        <img src='img/Back_btn.png' width='100' border='0'>
                    </a>

                    <?php 
                    require 'mods/Fool_detect.php';
                    $breaR = DetectBreak($_GET['catn'], $_GET['artstot'], $_GET['limit'], $_GET['ofset'], $_GET['pnm'], $_GET['date']);
                    if ($breaR){ goto fool; }
                    ?>

                </td>	
                <td>
                    <div align="center">
                        <img src="img/header_gallery.png" height="30"><br>
                        <span style="font-size:20pt; font-weight:normal;"><?php
                            if (isset($_GET['keyword'])) {
                                print ('Результаты поиска по: ' . $_GET['keyword']);
                            } else {
                                $req = mysqli_query($link, "SELECT * FROM categories WHERE cat_id=" . $_GET['catn']);
                                while ($row = mysqli_fetch_array($req, MYSQLI_ASSOC)) {
                                    print($row['cat_name']);
                                }
                            }
                            ?>
                        </span>
                        <br><br>
                    </div>
                </td>
            </tr>
        </table>

<?php
// ADMIN TOOLS FOR GAllERY - START
if (isset($_SESSION['admin'])) {

    function SerchCategories() {
        global $link;
        $req = mysqli_query($link, "SELECT * FROM categories ORDER BY cat_name");
        if ($req == TRUE) {
            while ($row = mysqli_fetch_array($req, MYSQLI_ASSOC)) {
                print('<option value="' . $row['cat_id'] . '"> ' . $row['cat_name'] . ' </option>');
            }
            if (mysqli_num_rows($req) == 0) {
                print('<option selected value="0"> Не найдено категорий в БД </option>');
            }
        } else {
            print('<option selected value="0"> Не найдена таблица категорий </option>');
        }
        unset($req);
        unset($row);
    }
    ?>

    <!--<script type="text/javascript" src="libs/functions_g.js"></script>
    <script type="text/javascript" src="libs/ajax_art_manipulation.js"></script>-->

    <table align="center" cellpadding="5" cellspacing="0" border="0" style="border-radius:5px; background: #eee; font-family: sans-serif; position:fixed; top: 0px; left:130px; z-index:90; border: solid 3px grey"> 
    <tr>
        <td id="cAction" align="center" style="width: 20px; font-weight:bold; background:grey; color:white; border-radius:0px 5px 5px 0px;"> 0 </td>
        <td> Категория: </td>
        <td> <select style="width:265px;" id="cbCat" > <?php SerchCategories(); ?> </select> </td>
        <td> Дата: </td>
        <td> <input type="text" style="width:165px;" id="updtdate" value="<?php print(date("j-m-Y")); ?>">  </td>
        <td> <input type="checkbox" id="dateUpdCk" title="обновлять дату" /> О.Д. </td>
        <td> <button type="button" id="updtBtn" style='color:blue'>Обновить</button>	 </td>
        <td> <button type="button" id="delBtn"  style='color:red'>Удалить</button>  </td>
    </tr>
    </table>

    <!-- // ADMIN TOOLS FOR GAllERY - end -->
<?php } ?>

<!-- <script type="text/javascript" src="libs/hs_handler.js" charset="utf-8"></script> -->
<div class="highslide-gallery" style="text-align: center; vertical-align: top;">
    
    <?php

    include "visitors.php";
    $limitsql = "";
    $catsql = "";
    $sqname='';
    $sqauth=''; 
    $datesql = "";
    $datevar = "";
    $searchvar = "";
    $catvar = "";

    if (isset($_GET['keyword'])) {

        $searchvar = "keyword=".$_GET['keyword'];

        if(isset($_GET['fname'])){ 
            $sqname='title = "'.$_GET['keyword'].'" 
            OR title LIKE "%'.$_GET['keyword'].'" 
            OR title LIKE "%'.$_GET['keyword'].'%" 
            OR title LIKE "'.$_GET['keyword'].'%"'; 
            $searchvar .= "&fname=on";
        } 
        if(isset($_GET['auth'])){ 
            $sqauth='author = "'.$_GET['keyword'].'" 
            OR author LIKE "%'.$_GET['keyword'].'" 
            OR author LIKE "%'.$_GET['keyword'].'%" 
            OR author LIKE "'.$_GET['keyword'].'%"'; 
            $searchvar .= "&auth=on";
        }   
        
        if ( !isset($_GET['fname']) && !isset($_GET['auth']) ){
            print('<div style="color:red; margin:30px; font-size: 25pt">Укажите хотя бы один критерий поиска!</div>');
        }        
                 
    } else {
        $catsql = "category=" . $_GET['catn'];
        $catvar = "catn=".$_GET['catn'];

        if (isset($_GET['date'])) {
            $datesql = " AND addate='" . $_GET['date'] . "'";
            $datevar = '&date=' . $_GET['date'];
        } 
    }            

    // https://dev.mysql.com/doc/refman/8.0/en/select.html                                 
    // MySQL Way ofset
    if (isset($_GET['ofset'])) {
        $limitsql = " LIMIT ".$_GET['ofset'].", 90"; 
        $ofsetvar = $_GET['ofset'];
    } else {
        $limitsql = " LIMIT 90"; 
        $ofsetvar = 0;
    }  

    $artSQL = "SELECT * FROM arts_pub WHERE ". $catsql. $sqname. $sqauth. $datesql. $limitsql;
    $reqArs = mysqli_query($link, $artSQL);
    while ($row = mysqli_fetch_array($reqArs, MYSQLI_ASSOC)) {   

        // Clear out art title
        $nanmst = htmlspecialchars(ucwords($row['title']), ENT_QUOTES);

        if (stripos($row['file_name'], '.gif') != false) {
            $nanmst = "&#9658; " . $nanmst;
        } 
                    
        $atl = "<b>" . $nanmst . "</b><br><i>" . $row['author'] . "</i>";                              
        print(
        "<div style='display: inline-block; margin:5px' id='art_" . $row['aid'] . "'>".
            "<div align='center' class='thumb-frame' art='" . $row['aid'] . "'>".                                    
                "<a href='".$row['file_name']."' class='highslide' style='vertical-align:middle;' 
                    title='".$atl."' id='art_" . $row['aid'] . "' onclick='return hs.expand(this, config1)'>".
                    "<img class='thumb' hgid='".$row['aid']."' src='".$row['thumb']."' alt='".$atl."'><br>".
                "</a>".                                    
                "<span class='thumb-lab'>".
                    "<a href='".$row['da_page']."' target='_blank'>" . ucwords($nanmst) . "</a>".
                "</span><br>".
                "<span class='thum-auth'>".
                    "<i>" . $row['author'] . "</i>".
                "</span>".
                "<br><br><br>".
			"</div>".
		"</div>");                    
    }

    //print($artSQL);

?>
</div>



<div style="padding:15px; text-align: center;" id='pager'>
<?php 
if ($ofsetvar != 0){
    print("<a target='_self' href='?".$searchvar.$catvar.$datevar."&ofset=".($ofsetvar - 90)."'> 
        <div class='pgs'> &larr; Сюда  </div> 
    </a>");
}    
if (mysqli_num_rows($reqArs)==90) { 
    print("<a target='_self' href='?".$searchvar.$catvar.$datevar."&ofset=".($ofsetvar + 90)."'> 
        <div class='pgs'> Туда &rarr;  </div> 
    </a>");
}
mysqli_close($link);
?>
</div>

<?php fool: ?>
<div align="center" style="font-family: sans-serif;">
    Scripting and Design: <b>scadl</b><br>
    <i><a href="http://scadsdnd.net/" target="_blank">SCAD's Design & Develop</a> - Aug 2013</i>
</div>	

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(71443834, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/71443834" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

</body>
</html>
