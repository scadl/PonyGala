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

    <!-- The Open Graph protocol parameters (facebook, vk, etc link params)-->
    <?php if (isset($_GET['date'])) { ?>	
    <!-- <meta property="og:url"                content="https://artgala.scadsdnd.net/gallery.php?catn=<?PHP print($_GET['catn']); ?>&date=<?PHP print(date("j-m-Y")); ?>" /> -->                       
    <meta property="og:title"              content="Big DA Gallery [<?PHP print($_GET['date']); ?>]" />
    <meta property="og:description"        content="Подборка за <?PHP print(date("j-m-Y")); ?>, просмотр категории" />
    <?php } else { ?>
    <!-- <meta property="og:url"                content="https://artgala.scadsdnd.net/gallery.php?catn=<?PHP print($_GET['catn']); ?>" /> -->
    <meta property="og:title"              content="Big DA Gallery [Весь архив]" />
    <meta property="og:description"        content="Просмотр категории без даты" />
    <?php } ?>                    
    <meta property="og:image"               content="img/GalleryLogoQB.png" />        
    <meta property="og:image:width"         content="512" />        
    <meta property="og:image:height "       content="512" />        
    <meta property="og:type"               content="website" />        

<!-- Chrome WebApp Params -->
    <link rel="icon" href="/libs/favicon.ico">
    <link rel="manifest" href="/libs/manifest.json">

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

        <?php include "mods/ads_header.php" ?>


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

    function SerchCategories($mode=0) {
        global $link;

        $no_categories = "Не найдено категорий в БД";
        $no_db_table = "Не найдена таблица категорий";

        $req = mysqli_query($link, "SELECT * FROM categories ORDER BY cat_name");
        switch ($mode) {
            case 0:
                # Selectable dropbox mode
                if ($req == TRUE) {
                    while ($row = mysqli_fetch_array($req, MYSQLI_ASSOC)) {
                        print('<option value="' . $row['cat_id'] . '"> ' . $row['cat_name'] . ' </option>');
                    }
                    if (mysqli_num_rows($req) == 0) {
                        print('<option selected value="0"> '.$no_categories.' </option>');
                    }
                } else {
                    print('<option selected value="0"> '.$no_db_table.' </option>');
                }
                break;

            case 1:
                # Div block mode
                if ($req == TRUE) {
                    while ($row = mysqli_fetch_array($req, MYSQLI_ASSOC)) {
                        print('<div class="admin_cell_cat" val="' . $row['cat_id'] . '"> ' . $row['cat_name'] . ' </div>');
                    }
                    if (mysqli_num_rows($req) == 0) {
                        print('<div class="admin_cell_cat" val="0"> '.$no_categories.' </div>');
                    }
                } else {
                    print('<option class="admin_cell_cat" val="0"> '.$no_db_table.' </div>');
                }
                break;
            
            default:
                # Other modes
                break;
        }
        unset($req);
        unset($row);
    }
    ?>

    <!--<script type="text/javascript" src="libs/functions_g.js"></script>
    <script type="text/javascript" src="libs/ajax_art_manipulation.js"></script>-->

    <div align="center" class="admin_tb"> 
        <div>
        <div class="admin_cell" > Выбрано: </div>
        <div class="admin_cell" id="nSelected" align="center" style="width: 20px; font-weight:bold; color:DarkBlue; "> 0 </div>
        <div class="admin_cell" >; Обрабатано: </div>
        <div class="admin_cell" id="cAction" align="center" style="width: 20px; font-weight:bold; color:maroon; "> 0 </div>
        <div class="admin_cell">; Категория: </div>
        <div class="admin_cell"> <select style="width:265px;" id="cbCat" > <?php SerchCategories(); ?> </select> </div>
        <div class="admin_cell"> Дата: </div>
        <div class="admin_cell"> <input type="text" style="width:165px;" id="updtdate" value="<?php print(date("j-m-Y")); ?>">  </div>
        <div class="admin_cell"> <input type="checkbox" id="dateUpdCk" title="обновлять дату для выбранных артов" /> Обновить дату </div>
        <div class="admin_cell"> <button type="button" id="updtBtn" style='color:blue'>Обновить</button>	 </div>
        <div class="admin_cell"> <button type="button" id="delBtn"  style='color:red'>Удалить</button>  </div>
        <div class="admin_cell"> <button type="button" id="dropZoneSwitch"  style='color:Green' title="Показать\Скрыть дроп-зону категорий"> &crarr; </button>  </div>
        </div>
        <div class="dropZone" style="display:none">
            <?php SerchCategories(1); ?>
        </div>
    </div>

    <div class="dropZone" style="display:none;" > </div>

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
        "<div style='display: inline-block; margin:5px' id='art_" . $row['aid'] . "' title='".$row['da_tags']."'>".
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
    print("<a target='_self' title=' В начало ' href='?".$searchvar.$catvar.$datevar."'> 
        <div class='pgsl'> &laquo; </div> 
    </a>");
    print("<a target='_self' title=' Назад ' href='?".$searchvar.$catvar.$datevar."&ofset=".($ofsetvar - 90)."'> 
        <div class='pgsl'> &lsaquo; </div> 
    </a>");
}

$pageSQL = "SELECT * FROM arts_pub WHERE ". $catsql. $sqname. $sqauth. $datesql;
$reqPage = mysqli_query($link, $pageSQL);
$pagenum = mysqli_num_rows($reqPage);
$pagemark = 1;
for($i = 0; $i < $pagenum; $i+=90){
    $ofsetvar==$i ? $sel='pgsl_sel' : $sel='pgsl'; 
    print("<a target='_self' href='?".$searchvar.$catvar.$datevar."&ofset=".$i."'>".
        "<div class='".$sel."'> ".$pagemark." </div>".
    "</a>");
    $pagemark+=1;
    $lastpage = $i;
}

if (mysqli_num_rows($reqArs)==90) { 
    print("<a target='_self' title=' Вперед ' href='?".$searchvar.$catvar.$datevar."&ofset=".($ofsetvar + 90)."'> 
        <div class='pgsl'> &rsaquo; </div> 
    </a>");
    print("<a target='_self' title=' В конец ' href='?".$searchvar.$catvar.$datevar."&ofset=".$lastpage."'> 
        <div class='pgsl'> &raquo; </div> 
    </a>");
}
mysqli_close($link);
?>
</div>

<?php include 'mods/ads_footer.php' ?>

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
