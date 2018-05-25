<?php
//display_errors','1');
//error_reporting(E_ALL);

//require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
//$a=$crypt->decrypt($_GET['c1']);
//$b=$crypt->decrypt($_GET['c2']);
$a=$_GET['c1'];
$b=$_GET['c2'];
/*
//$image = imagecreatetruecolor(80, 35);
//$fondo = imagecolorallocate($image, 0, 0, 0);
//$im = imagecreate(80, 30);
$im = imagecreate(80, 30) or die('Cannot initialize new GD image stream');

// fondo blanco y texto azul
$fondo = imagecolorallocate($im, 255, 255, 255);
$color_texto = imagecolorallocate($im, 0, 0, 0);
imagestring($im,5,15, 10, "$a + $b = ",$color_texto);
header("Content-type: image/png");
imagepng($im);
*/
header ('Content-type: image/png');
$im = @imagecreatetruecolor(80, 30) or die('Cannot Initialize new GD image stream');
$text_color = imagecolorallocate($im, 255, 255,255);
imagestring($im, 5, 15, 10,"$a + $b = ", $text_color);
imagepng($im);


?>
