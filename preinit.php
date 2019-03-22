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
}

session_start();

// Login scheme
if (isset($_POST['mpass'])) {
    $hash = password_hash($master, PASSWORD_DEFAULT);
    if (password_verify($_POST['mpass'], $hash)) {
        //print('alert("Пароль ок!");');                
        SetCookie("ponygalai_admin", "true");  
        $_SESSION['admin'] = true;   
    } else {
        print('');        
    }
}

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