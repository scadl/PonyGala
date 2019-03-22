<?php

if (file_exists('./config.php' )){
    require './config.php';
} else {
    require './../config.php';
}

// DB Connection init
$link = mysqli_connect($db_server, $db_user, $db_password, $db_database);
if(!$link){
    print('<div align="center">Проблемы с подключением к базе! Пните админа! )))</div>');
} else {
    mysqli_query($link, "SET sql_mode = ''");
    mysqli_query($link, 'SET NAMES utf8');
}

