<?php
require './preinit.php';
require './db_init.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>

    <meta charset="UTF-8">

    <!-- SEO minaml params-->
    <meta name="description" content="Big DigitalArt Gallery v3 (by scadl)">
    <meta name="keywords" content="brony, art, fanart, ponyart, pony, landscape, portrait, подборка, пони, арт, пейзаж, портрет, фанарт">
    <meta name="author" content="SCADL and Moora">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="libs/main.css">

    <!-- The Open Graph protocol parameters (facebook, vk, etc link params)-->

    <meta property="og:url" content="https://artgala.scadsdnd.net/" />
    <meta property="og:title" content="Big DA Gallery [Весь архив]" />
    <meta property="og:description" content="Крупнейшая галерея фанарта с 2013г" />

    <meta property="og:image" content="img/GalleryLogoQB.png" />
    <meta property="og:image:width" content="512" />
    <meta property="og:image:height " content="512" />
    <meta property="og:type" content="website" />

    <!-- Chrome WebApp Params -->
    <link rel="icon" href="/libs/favicon.ico">
    <link rel="manifest" href="/libs/manifest.json">

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title> Big DigitalArt Gallery v3 (by scadl) [Index]</title>

    <?php include "mods/ads_header.php" ?>


</head>

<body>

    <!-- Центрирование всей страницы -->
    <div align="center">

        <span id='head_link' style='font-family:CelestiaRedux;'>
            <span style='font-size:45pt;'>
                <a href="/" target="_self" title="Смотреть всю коллекцию">Big DigitalArt Gallery</a><span style='font-size:8pt;'>v3</span>
            </span>
            <br>
            <span style='font-size:17pt;'> Для всех, кто любит качественные рисунки фэндомов<br>

                <span style="font-size:13px">(Создано <a href="https://4pda.to/forum/index.php?showuser=750053" target="_blank" title="Моя страничка в 4PDA">scadl</a>
                    для <a href="http://4pda.to/forum/index.php?showtopic=403239" target="_blank" title="Добро пожаловать в клуб!">Bronies4PDA</a>)
                </span>

                <br>
            </span>
        </span>

        <br><br><br><br><br>

        Веб-версия нашего проекта безвременно закрыта. 😟<br>
        <i>(Используйте <a href='https://play.google.com/store/apps/details?id=net.scadsdnd.ponygala'>а</a>лтерна<a href='https://t.me/BigDAGalaBot'>т</a>ивные спосбы просмотра нашей галереи)</i> 😉<br>

        <br><br><br><br><br>

        <div class='footer_txt'>
            Scripting and Design: <b>scadl</b><br>
            <i><a href="http://scadsdnd.net/" target="_blank">SCAD's Design & Develop</a> - Aug 2013</i>
        </div>

        <!-- Yandex.Metrika counter -->
        <script type="text/javascript">
            (function(m, e, t, r, i, k, a) {
                m[i] = m[i] || function() {
                    (m[i].a = m[i].a || []).push(arguments)
                };
                m[i].l = 1 * new Date();
                k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
            })
            (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

            ym(71443834, "init", {
                clickmap: true,
                trackLinks: true,
                accurateTrackBounce: true,
                webvisor: true
            });
        </script>
        <noscript>
            <div><img src="https://mc.yandex.ru/watch/71443834" style="position:absolute; left:-9999px;" alt="" /></div>
        </noscript>
        <!-- /Yandex.Metrika counter -->

</body>

</html>