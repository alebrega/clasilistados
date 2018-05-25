<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php 
if ($usuario->estaLogueado()){
	?>
	<title>clasilistados - cuenta - <?=$usuario->getEmail()?></title>
	<script type="text/javascript" src="<?php echo version($h->getHost(true).'/js/cuentas.js');?>"></script>
	<?php
}elseif ($cambiarContrasena){
?>
<title>clasilistados - elige una nueva contraseña</title>
<?php
}else{
	?>
	<title>clasilistados - entrar en la cuenta</title>
	<?php
}
?>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/metas/noindexnofollow.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/headerssl.inc.php");
?>
</head>
<?php flush(); ?>
<body style="font-family:Bookman Old Style,sans-serif;">
<?php
require_once($pagina);
?>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body>
</html>

