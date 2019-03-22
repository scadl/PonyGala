<?php   
// тип содержимого
//header('Content-Type: image/jpeg');

$filename = substr($_GET['imgfl'], strripos($_GET['imgfl'], '/')+1);

print ("Generating: ".$filename);

if (!file_exists('vault/_cache/'.$filename)){

	if ($_GET['type']=='jpg'){ GenJPG($filename, 0.20); }
	if ($_GET['type']=='png'){ GenPNG($filename, 0.20); }
	if ($_GET['type']=='gif'){ GenGIF($filename, 0.15); }
	//if ($_GET['type']=='swf'){ GenSWF(); }

}

function GenJPG($filename, $percent){

// получение нового размера
list($width, $height) = getimagesize($_GET['imgfl']);
$newwidth = $width * $percent;
$newheight = $height * $percent;

// загрузка
$thumb = imagecreatetruecolor($newwidth, $newheight);
$source = imagecreatefromjpeg($_GET['imgfl']);

// изменение размера
imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

// вывод
//imagejpeg($thumb);
imagejpeg($thumb,'vault/_cache/'.$filename,75);
}

function GenPNG($filename, $percent){

// тип содержимого
//header('Content-Type: image/png');

// получение нового размера
list($width, $height) = getimagesize($_GET['imgfl']);
if ($width < 300) { $newwidth = $width*3; } else { $newwidth = $width * $percent; }
if ($height < 300) { $newheight = $height*3; } else { $newheight = $height * $percent; }

// загрузка
$thumb = imagecreatetruecolor($newwidth, $newheight);
imagealphablending($thumb, false);
imagesavealpha($thumb,true);
$source = imagecreatefrompng($_GET['imgfl']);

// изменение размера
imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

// Сделаем фон прозрачным
//$black = imagecolorallocate($thumb, 0, 0, 0);
//imagecolortransparent($thumb, $black);

// вывод
// imagepng($thumb);

imagepng($thumb,'vault/_cache/'.$filename,6);

}

function GenGIF($filename, $percent){

// тип содержимого
//header('Content-Type: image/gif');

// получение нового размера
list($width, $height) = getimagesize($_GET['imgfl']);
if ($width < 321) { $newwidth = $width*3; $newheight = $height*3; } 
elseif ($height < 241) { $newwidth = $width*3; $newheight = $height*3; } 
else { $newwidth = $width * $percent; $newheight = $height * $percent; }

// загрузка
// создание полотна
$thumb = imagecreatetruecolor($newwidth, $newheight);
//подгрузка оригинала
$source = imagecreatefromgif($_GET['imgfl']);

//Получаем прозрачный цвет
$transparent_source_index=imagecolortransparent($source);

//На всякий случай заливаем фон этим цветом
	imagefill($thumb, 0, 0, imagecolorallocate($thumb, 204, 204, 204));

// изменение размера
imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

// вывод
//imagegif($thumb);
imagegif($thumb,'vault/_cache/'.$filename);

}

?>