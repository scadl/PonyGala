<?php

$percent = 0.30;
$rectmod = 8;
$rectofset = 4;
$slimit = 130;

function GenPNGFrame($imgfl){
 
// ���� � ����� ������
$filename = $imgfl;
global $percent, $rectmod, $rectofset, $slimit;

// ��� �����������
//header('Content-Type: image/jpeg');

// ��������� ������ �������
list($width, $height) = getimagesize($filename);
if ($width < 210) { $newwidth = $width*3; $newheight = $height*3; } 
elseif ($height < 170) { $newwidth = $width*3; $newheight = $height*3; } 
else { $newwidth = $width * $percent; $newheight = $height * $percent; }

while ($newheight > $slimit){
$newwidth = $newwidth * 0.99;
$newheight = $newheight * 0.99;
}

while ($newwidth > $slimit){
$newwidth = $newwidth * 0.99;
$newheight = $newheight * 0.99;
}

// ��������
$thumb = imagecreatetruecolor($newwidth+$rectmod, $newheight+$rectmod);
//imagealphablending($thumb, false);
//imagesavealpha($thumb,true);
$source = imagecreatefrompng($filename);

global $tbimgwd;
$tbimgwd=$newwidth+$rectmod;
global $tbimghg;
$tbimghg=$newheight+$rectmod;
global $tbimgwdot; 
$tbimgwdot=$newwidth+$rectmod; 
global $tbimghgot;
$tbimghgot=$newheight+$rectmod;

imagefill($thumb, 0, 0, imagecolorallocate($thumb, 240, 240, 240));

  imagerectangle($thumb, 0, 0, $newwidth+$rectmod-1, $newheight+$rectmod-1, imagecolorallocate($thumb, 150, 150, 150));
  imagerectangle($thumb, $rectofset-1, $rectofset-1, $newwidth+$rectofset, $newheight+$rectofset, imagecolorallocate($thumb, 150, 150, 150));

// ��������� �������
imagecopyresized($thumb, $source, $rectofset, $rectofset, 0, 0, $newwidth, $newheight, $width, $height);

// �����
//imagejpeg($thumb);
return $thumb;
}

function GenJPGFrame($imgfl){   
 
// ���� � ����� ������
$filename = $imgfl;
global $percent, $rectmod, $rectofset, $slimit;

// ��� �����������
//header('Content-Type: image/jpeg');

// ��������� ������ �������
list($width, $height) = getimagesize($filename);
$newwidth = $width * $percent;
$newheight = $height * $percent;

// ��������
$thumb = imagecreatetruecolor($newwidth+$rectmod, $newheight+$rectmod);
$source = imagecreatefromjpeg($filename);

while ($newheight > $slimit){
$newwidth = $newwidth * 0.99;
$newheight = $newheight * 0.99;
}

while ($newwidth > $slimit){
$newwidth = $newwidth * 0.99;
$newheight = $newheight * 0.99;
}

global $tbimgwd;
$tbimgwd=$newwidth+$rectmod;
global $tbimghg;
$tbimghg=$newheight+$rectmod;
global $tbimgwdot; 
$tbimgwdot=$newwidth+$rectmod; 
global $tbimghgot;
$tbimghgot=$newheight+$rectmod;

imagefill($thumb, 0, 0, imagecolorallocate($thumb, 240, 240, 240));

imagerectangle($thumb, 0, 0, $newwidth+$rectmod-1, $newheight+$rectmod-1, imagecolorallocate($thumb, 150, 150, 150));
//imagerectangle($thumb, 4, 4, $newwidth+15, $newheight+15, imagecolorallocate($thumb, 100, 100, 100));
imagerectangle($thumb, $rectofset-1, $rectofset-1, $newwidth+$rectofset, $newheight+$rectofset, imagecolorallocate($thumb, 150, 150, 150));

// ��������� �������
imagecopyresized($thumb, $source, $rectofset, $rectofset, 0, 0, $newwidth, $newheight, $width, $height);

// �����
//imagejpeg($thumb);
return $thumb;

//echo "thum_ok";
}


function GenGIFFrame($imgfl){ 
$filename = $imgfl;
global $percent, $rectmod, $rectofset, $slimit;

// ��� �����������
//header('Content-Type: image/gif');

// ��������� ������ �������
list($width, $height) = getimagesize($filename);
if ($width < 210) { $newwidth = $width*3; $newheight = $height*3; } 
elseif ($height < 170) { $newwidth = $width*3; $newheight = $height*3; } 
else { $newwidth = $width * $percent; $newheight = $height * $percent; }

// ��������
// �������� �������
//$thumb = imagecreatetruecolor($newwidth, $newheight);
//��������� ���������
//$source = imagecreatefromgif($filename);


while ($newheight > $slimit){
$newwidth = $newwidth * 0.99;
$newheight = $newheight * 0.99;
}

while ($newwidth > $slimit){
$newwidth = $newwidth * 0.99;
$newheight = $newheight * 0.99;
}

// ��������
$thumb = imagecreatetruecolor($newwidth+$rectmod, $newheight+$rectmod);
//imagealphablending($thumb, false);
//imagesavealpha($thumb,true);
$source = imagecreatefromgif($filename);
//�������� ���������� ����
$transparent_source_index=imagecolortransparent($source);

global $tbimgwd;
$tbimgwd=$newwidth+$rectmod;
global $tbimghg;
$tbimghg=$newheight+$rectmod;
global $tbimgwdot; 
$tbimgwdot=$newwidth+$rectmod; 
global $tbimghgot;
$tbimghgot=$newheight+$rectmod;

imagefill($thumb, 0, 0, imagecolorallocate($thumb, 240, 240, 240));

  imagerectangle($thumb, 0, 0, $newwidth+$rectmod-1, $newheight+$rectmod-1, imagecolorallocate($thumb, 150, 150, 150));
//imagerectangle($thumb, 4, 4, $newwidth+15, $newheight+15, imagecolorallocate($thumb, 100, 100, 100));
  imagerectangle($thumb, $rectofset-1, $rectofset-1, $newwidth+$rectofset, $newheight+$rectofset, imagecolorallocate($thumb, 150, 150, 150));

// ��������� �������
imagecopyresized($thumb, $source, $rectofset, $rectofset, 0, 0, $newwidth, $newheight, $width, $height);

// �����
//imagejpeg($thumb);
return $thumb;
}

function GenSWFFrame($imgfl){   
 
// ���� � ����� ������
$filename = $imgfl;
global $percent, $rectmod, $rectofset, $slimit;

// ��� �����������
//header('Content-Type: image/jpeg');

// ��������� ������ �������
list($width, $height) = getimagesize($filename);
$newwidth = $width * 1;
$newheight = $height * 1;

// ��������
$thumb = imagecreatetruecolor($newwidth+$rectmod, $newheight+$rectmod);
$source = imagecreatefrompng($filename);


while ($newheight > $slimit){
$newwidth = $newwidth * 0.99;
$newheight = $newheight * 0.99;
}

while ($newwidth > $slimit){
$newwidth = $newwidth * 0.99;
$newheight = $newheight * 0.99;
}

global $tbimgwd;
$tbimgwd=$newwidth+$rectmod;
global $tbimghg;
$tbimghg=$newheight+$rectmod;
global $tbimgwdot; 
$tbimgwdot=$newwidth+$rectmod; 
global $tbimghgot;
$tbimghgot=$newheight+$rectmod;

imagefill($thumb, 0, 0, imagecolorallocate($thumb, 240, 240, 240));

imagerectangle($thumb, 0, 0, $newwidth+$rectmod-1, $newheight+$rectmod-1, imagecolorallocate($thumb, 150, 150, 150));
imagerectangle($thumb, $rectofset-1, $rectofset-1, $newwidth+$rectofset, $newheight+$rectofset, imagecolorallocate($thumb, 150, 150, 150));

// ��������� �������
imagecopyresized($thumb, $source, $rectofset, $rectofset, 0, 0, $newwidth, $newheight, $width, $height);

// �����
//imagejpeg($thumb);
return $thumb;

//echo "thum_ok";
}

?>