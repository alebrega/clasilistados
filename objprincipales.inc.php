<?php
	$session=new Session();
	$cookie= new Cookie();
	$registro= new Registro();
	$registro->set("db",$db);
	$registro->set("cookie",$cookie);
	$registro->set("session",$session);
	$email=new Email($registro,$t_envios, $smtp_host,$smtp_auth,$smtp_username,$smtp_password);
	$registro->set("email",$email);
	$h=new Helper(); //el helper esta presente en todas las paginas
	$registro->set("helper",$h);
	array_walk($_POST, 'limpia');
	array_walk($_GET, 'limpia');
	$crypt = new Crypt();
	$crypt->Mode = Crypt::MODE_HEX;
	$salt='!@#$%&*()_+?:';
	$crypt->Key  = $salt;
	$configuracion = new Configuracion();
	$configuracion->llenar($configData);
	$registro->set("configuracion",$configuracion);
	$registro->set("crypt",$crypt);
?>