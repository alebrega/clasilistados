<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<title>clasilistados - administra tu anuncio</title>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/headerssl.inc.php");
?>
</head>
<?php flush(); ?>
<body id="pp">
<?php
if ($_GET['republicar']!=1){
	if ((!$tieneCodSeg) && ($usuario->estaLogueado())){
		require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header_admin_anuncio_usuario.inc.php");
	}
	else{
		require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header_admin_anuncio.inc.php");
	}
}else{
	require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header_admin_anuncio.inc.php");
}
require_once($_SERVER["DOCUMENT_ROOT"]."/anuncio.php");
?>
<br />
<p>ID del anuncio: <?=$anuncio->getId()?></p>
<hr>
<br />
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body></html>