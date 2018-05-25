<?php
require_once ("controller.fw.php");
require_once ("pagecontroller.fw.php");
require_once ("modulecontroller.fw.php");
//presentes en casi todas las paginas
$session=new Session();
$cookie= new Cookie();
$registro= new Registro();
$registro->set("db",db::getInstance());
$registro->set("cookie",$cookie);
$registro->set("session",$session);
$helper=new Helper();
$registro->set("helper",$h);
array_walk($_POST, 'limpia');
array_walk($_GET, 'limpia');
$crypt = new Crypt();
$crypt->Mode = Crypt::MODE_HEX;
$salt='!@#$%&*()_+?:';
$crypt->Key  = $salt;
$registro->set("crypt",$crypt);
?>