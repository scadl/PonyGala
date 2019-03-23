<?php

if (file_exists('./config.php' )){
    require './config.php';
} else {
	if (file_exists('./../config.php' )){
		require './../config.php';
	} else {
		die('config.php - не найден. Проверьте целостность репозитория');
	}
}

// DB Connection init
$link = mysqli_connect($db_server, $db_user, $db_password, $db_database);
if(!$link){
    print('<div align="center">Проблемы с подключением к базе! Пните админа! )))</div>');
} else {
	
	// Setting read mode to unicode
    mysqli_query($link, "SET sql_mode = ''");
    mysqli_query($link, 'SET NAMES utf8');
	
	// -----------------------------------------
	// Building empty tables, if not exist in db
	
	// The main arts table
	mysqli_query($link, "CREATE TABLE IF NOT EXISTS `arts_pub` (
  `aid` int(11) NOT NULL,
  `title` text,
  `file_name` text NOT NULL,
  `thumb` text,
  `da_page` text,
  `author` text,
  `category` int(11) NOT NULL DEFAULT '1',
  `rating` int(11) NOT NULL DEFAULT '0',
  `addate` text NOT NULL,
  `da_id` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

	// Virtual categories table
	mysqli_query($link, 'CREATE TABLE IF NOT EXISTS `categories` (
  `cat_id` int(11) NOT NULL,
  `cat_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

	// Subscribers table (urrently not used in code)
	mysqli_query($link, 'CREATE TABLE IF NOT EXISTS `subscribers` (
  `uid` int(11) NOT NULL,
  `email` text,
  `subscribed` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

	// Vistors tracking table (used to store statistic only)
	mysqli_query($link, 'CREATE TABLE IF NOT EXISTS `visitors` (
  `ip` text,
  `visits` int(11) DEFAULT NULL,
  `date` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

