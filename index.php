<?php
require './preinit.php';
require './db_init.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>

    <meta charset="UTF-8">
    <meta name="description" content="Big DigitalArt Gallery v3 (by scadl)">
  <meta name="keywords" content="brony, art, fanart, ponyart, pony, landscape, portrait, подборка, пони, арт, пейзаж, портрет, фанарт">
  <meta name="author" content="SCADL and Moora">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <?php if (isset($_GET['date'])) { ?>	
            <title> Big DigitalArt Gallery v3 (by scadl) [<?PHP print($_GET['date']); ?>] </title>			
        <?php } else { ?>
            <title> Big DigitalArt Gallery v3 (by scadl) [Full Archive]</title>			
        <?php } ?>	
        <link rel="stylesheet" href="libs/main.css">
        <link rel="stylesheet" href="libs/jquery-ui/css/custom-theme/jquery-ui-1.10.4.custom.css">
        <script src="libs/jq/jquery-1_9_1.js"></script>
        <script src="libs/jq/jquery-ui-1_10_4.js"></script>
        <script src="libs/functions.js"></script>
        <?PHP
        
        $publication = "";
        $sql = mysqli_query($link, "SELECT file_name, addate FROM arts_pub GROUP BY addate");
        
        if ($sql !== FALSE) {                    
            while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
                $publication = $publication . '"' . $row['addate'] . '",';                
            }            
        } else {
            $publication = "";
        }

        ?>

        <script type="text/javascript">
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
        </script>

    <script data-ad-client="ca-pub-1505117965346309" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    </head>
    
    <body>
        
        <!-- Контейнеры диалогов -->
        <div style="display:none; text-align:center; font-size:13pt;" title="Подпишись ;)" id="subsDialog">
            Чтобы получать оповещения о новых подборках, введи свой e-mail, поняша:<br>
            <input type="text" id="umail">
        </div>
        
        <form method="POST" action="" style="display:none; text-align:center; font-size:13pt;" title="Модерация" id="modDialog">
            Введите мастер-пароль<br>
            <input type="password" id="mpass" name="mpass">
        </form>

        <!-- Центрирование всей страницы -->
        <div align="center">

            <span id='head_link' style='font-family:CelestiaRedux;'>
                <span style='font-size:45pt;'>
                    <?php if (isset($_GET['date'])) { ?>	
                        <a href="/" target="_self" title="Смотреть всю коллекцию">Big DigitalArt Gallery</a><span style='font-size:8pt;'>v3</span>
                    <?php } else { ?>
                        <a href="index.php?date=<?PHP print(date("j-m-Y")); ?>" target="_self" title="Смотреть сегодняшнюю подборку">Big DigitalArt Gallery</a><span style='font-size:8pt;'>v2</span>
                    <?php } ?>
                </span>
                <br>
                <span style='font-size:17pt;'> Для всех, кто любит качественные рисунки фэндомов<br>                
                    <!--
					<span style="font-size:13px">(Создано <a href="http://facebook.com/scadl" target="_blank" title="Моя страничка в FB">scadl</a> 
                        для <a href="http://4pda.ru/forum/index.php?showtopic=403239" target="_blank" title="Добро пожаловать в клуб!">Bronies4PDA</a>) 
                    </span>
					-->
                <br>
                </span>
            </span>

                <?php

                // Поиск повреждения перменных - реакция
                if (isset($_GET['date'])) {
                    $dtsrl = "?date=" . date("j-m-Y");
                } else {
                    $dtsrl = "";
                }
                unset($lastpub);

                if (isset($_GET['date'])) {

                    if (preg_match('#^\d{1,2}\-\d{1,2}\-\d{4}$#', $_GET['date'])) {
                        //print('Regexp ok');
                    } else {
                        print ('<br><span style="color:red">Поняша, зачем ты пытаешься взломать мою галерею?!<br>'.
                        '<strong>Разве я тебя чем-то обидел??</strong></span><br><br>'.
                        '<a href="http://scadsdnd.sytes.net/myphp/ponygalai/index.php' . $dtsrl . '" style="color:#blue; font-size:10pt;"> > Вернуться к подборке < </a><br><br>');
                        
                        $arts_nm = 0;
                        goto fool;
                    }
                } 

                // Грузим статистику для левой части тулбара
                require "./visitors.php";

                $arts_nm = 0;
                $vote_nm = 0;
                
                $sql = mysqli_query($link, "SELECT * FROM arts_pub");
                $arts_nm = mysqli_num_rows($sql);
        
                $sql = mysqli_query($link, "SELECT SUM(rating) as rating FROM arts_pub");
                if ($sql !== FALSE) {
                    while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
                        $vote_nm = $row['rating'];
                    }
                } else {
                    print("NO db". mysqli_error($link));                    
                }

                $sql = mysqli_query($link, "SELECT email FROM subscribers");
                $sbt = mysqli_num_rows($sql);
                
                ?>
                	
                <!-- Тулбар -->
                <table border="0" width="100%"><tr>
                        
                        <!-- Левая часть тулбара -->
                        <td align="left">
                            <table border="0" cellpadding="3" class="tech_lab" style="float:left;"><tr>
                                    <td class="divider">Статистика:</td>
                                    <td> 
                                        <a href="visitors.php?act=log&mdate=<?php print(date("m")); ?>&ydate=<?php print(date("Y")); ?>" target="_blank">
                                            <img src="img/user.png" width="16" title="Посетителей: Всего (За сегодня) [Подписалось]"> 	</a> 	
                                    </td>
                                    <td class="divider" title="Посетителей: Всего (За сегодня) [Подписалось]"> 
                                        <a href="visitors.php?act=log&mdate=<?php print(date("m")); ?>&ydate=<?php print(date("Y")); ?>" target="_blank"> 
                                            <?php print($ips . ' (' . $tip . ')'); ?> 
                                        </a>  
                                    </td>
                                    <td>
                                        <img src="img/page3.png" width="16" title="Просмотров">
                                    </td>
                                    <td class="divider">
                                        <?php print($visits); ?>
                                    </td>
                                    <td>
                                        <img src="img/pic.png" width="15" title="Артов">
                                    </td>
                                    <td >
                                        <?php print($arts_nm); ?>
                                    </td>                                    
                                </tr></table>
                            <?php if (!isset($_SESSION['admin'])) { ?>
                                <div class="tech_lab btn_left" id="MLogin" style="padding:5px;">
                                    <span title="Вход модератора" style="cursor:pointer;">
                                        <img src="img/key-house.png" height="20" border="0" >
                                    </span>
                                </div>	
                            <?php } else { ?>
                                <div class="tech_lab btn_left" id="MLogOut" style="padding:5px;">
                                    <span title="Выход модератора" style="cursor:pointer;">
                                        <img src="img/exit.png" height="20" border="0" >
                                    </span>
                                </div>	
                            <?php } ?>                            
                        </td>


                        
                        <?php if (isset($_SESSION['admin'])) { ?>
                        <!-- Центральная часть тулбара -->
                            <td>
                                 <span class="tech_lab" style="padding:5px;">
                                    <a href="mods/dA_add-art.php" target="_blank" title="Add Art from dA"> 
                                        <img src="img/deviantart.png" width="20" border="0" style="position:relative; top:5px; left:3px;"> 
                                    </a>
                                </span> 
                                <span class="divider"> </span>
                                <span class="tech_lab" style="padding:5px;">
                                    <a href="mods/add-art.php" target="_blank" title="Add Art"> 
                                        <img src="img/add_art.png" width="20" border="0" style="position:relative; top:5px; left:3px;"> 
                                    </a>
                                </span> 
                                <span class="divider"> </span>                               
                                <span class="tech_lab" style="padding:5px;">
                                    <a href="mods/cache_cleaner.php" target="_blank" title="Clean and Fix GalaThumbs ">
                                        <img src="img/clean_thumb.png" width="20" border="0" style="position:relative; top:5px; left:3px;">
                                    </a>
                                </span>
                                <span class="divider"> </span>
                                <span class="tech_lab" style="padding:5px;">
                                    <a href="mods/thum-selection.php<?php print($dtsrl); ?>" target="_blank" title="SelectionPreview">
                                        <img src="img/stack_of_photos.png" width="20" border="0" style="position:relative; top:5px; left:3px;">
                                    </a>
                                </span> 
                                <span class="divider"> </span>
                                <span class="tech_lab" style="padding:5px;">
                                    <a href="mods/cat_manager.php" target="_blank" title="Category Manager">
                                        <img src="img/category_settings.png" width="20" border="0" style="position:relative; top:3px; left:1px;">
                                    </a>
                                </span> 
                            </td>
                        <?php } ?>

                        <!-- Правая часть тулбара -->
                        <td align="right" valign="middle">	
						
						    <span class="tech_lab btn_right" id="" title="Оставь свой коментарий (анонимно)"  style="cursor:pointer;">
								<a href="#disqus_thread" target="_self">
									<img src="img/animat-pencil.gif" width="20" border="0">
								</a>
                            </span>    
                           
                            <span class="tech_lab btn_right" id="ArchiveFrame" title="Поиск артов (по всей коллекции)"  style="cursor:pointer;">
                                <img src="img/67.png" width="20" border="0">
                            </span>                         
                                                       
                            <span class="tech_lab btn_right" title="Подпишись на обновления - не пропускай подборки!" style="cursor:pointer;">
                                <a href='https://www.facebook.com/groups/655548101805005/' target="_blank">
                                    <img src="img/like.png" width="20" border="0" >
                                </a>
                            </span>	      

                            <span class="tech_lab btn_right" title="Хочешь свою галерею? Присоединяйся - вместе, значит лучше!" style="cursor:pointer;">
                                <a href='https://github.com/scadl/PonyGala' target="_blank">
                                    <img src="img/GitHub-Mark-32px.png" width="20" border="0" >
                                </a>
                            </span>	  							

                            <span class="tech_lab btn_right" title="Архив артов 09.2013 - 09.2014" style="cursor:pointer;">
                                <a href="https://cloud.mail.ru/public/LvnN/CdvGjpMnM" target="_blank">
                                    <img src="img/cloud_disk.png" width="20" border="0">
                                </a>
                            </span>

                            <?php
                            if (isset($_GET['date'])) {
                                ?>	
                                <span class="tech_lab btn_right" 
                                      style="position: relative; top: 2px;">
                                    <a href="/" target="_self" title="Смотреть всю коллекцию">
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

                <!-- Контейнер поискового диалога -->
                <div id="poupup_wnd" style="display: none;">                    
                    <form action="gallery.php" method="get">
                        <table table border="0" cellspacing="3" width="100%"><tr>
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
                                    <table><tr>
                                            <td width="50%" valign="top">                                                
                                            <?php
                                            $sql = mysqli_query($link, "SELECT * FROM categories");
                                            $catnmr = mysqli_num_rows($sql)/2; $cind = 0;
                                            while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
                                                $cind += 1;
                                                if ($row['cat_id'] != 1){
                                                print('<input type="checkbox" value="'.$row['cat_id'].'" checked>'.$row['cat_name'].'<br>');
                                                    if( $cind == $catnmr ){
                                                        print(' </td><td width="50%" valign="top">');
                                                    }
                                                }
                                            }                                                                                     
                                            ?>
                                            <td>
                                        </tr></table>
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
                
                <!-- Тайлы категорий -->
                <div style="text-align: center; vertical-align: top;">                   
	                    
                <?php
                
                $thumbs = 0;
                $artstot = 0;

                if (!isset($_SESSION['admin'])) {
                    $filter = " WHERE cat_id <> 1";
                } else {
                    $filter = "";
                }
                               
                $sql = mysqli_query($link, "SELECT * FROM categories".$filter." ORDER BY cat_name");
                while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {

                    $thumbs++;

                    $artscat = 0;
                    if (isset($_GET['date'])) {
                        $reqi = mysqli_query($link, "SELECT file_name FROM arts_pub WHERE category=" . $row['cat_id'] . " AND addate='" . $_GET['date'] . "'");
                    } else {
                        $reqi = mysqli_query($link, "SELECT file_name FROM arts_pub WHERE category=" . $row['cat_id']);
                    }
                    $artscat = mysqli_num_rows($reqi);
                    
                    if ($artscat > 0) {                    
                                        
                    $artstot_url = 0;
                        
                    $date_url = '';
                    if (isset($_GET['date'])) {
                        $date_url = '&date=' . $_GET['date'];
                    }
                           
                    $artstot = $artstot + $artscat;
                    
                    ?>
													
                    <div class="cellstyle" >
                        <a href="gallery.php?catn=<?php print($row[0].$date_url); ?>" target="_self">
                            <div class="block_tmb" >
                                <script>
                                    AJAXLoadStack('<?php print($row[0]); ?>', '<?php print($date_url); ?>', '<?php print($thumbs); ?>');
                                </script>
                                <div id="blid_<?php print($thumbs); ?>" class="block_tmb"> </div>                              
                            </div>            
                            <div class="desc-nm"><?php echo $row[1] ?></div>                
                        </a>
                        <span class="subdesc">
                            <i>Артов в категории: <b> <?php  echo $artscat; ?></b></i>
                        </span>                        
                    </div>                    
                    
                    <?php
                    }
                }

                mysqli_close($link);
                ?>
                </div>
				                
                <!-- Подвал -->
				<br> 
				
					<!-- Контейнер disqus-диалога -->              
					<style  type="text/css">
					#disqus_thread {
						width:600px; 
						height:inherit; 
						text-align: center; 
						vertical-align: top;
						border-radius: 5px; 
						/*background: #ccc;*/
						
					}
					</style>			
					<div id="disqus_thread" ></div>
					
					<script>
						/**
						*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
						*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables    */
						
						var disqus_config = function () {
						//this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
						this.page.identifier = '/ponygala-disqus/'; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
						};
						
						
						(function() { // DON'T EDIT BELOW THIS LINE
						var d = document, s = d.createElement('script');
						s.src = 'https://ponyartgalleryachive.disqus.com/embed.js';
						s.setAttribute('data-timestamp', +new Date());
						(d.head || d.body).appendChild(s);
						})();
					</script>
					<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
				
                   
                <div style='color:#ccc; font-size:10pt;' class='footer_txt'>
                    Артов в подборке: <strong><?php echo $artstot ?></strong>
                </div>                
                <br>

                <?php fool: ?>

                <div class='footer_txt'>
                    Scripting and Design: <b>scadl</b><br>
                    <i><a href="http://scadsdnd.ddns.net/" target="_blank">SCAD's Design & Develop</a> - Aug 2013</i>
                </div>
                
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
