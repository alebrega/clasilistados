<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<title>clasilistados - código de promoción de ventas</title>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/metas/noindexnofollow.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header.inc.php");
?>
</head>
<?php flush(); ?>
<body>
<blockquote>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/headerayuda.inc.php");
?>
<p>

Este codigo permite identificar una campaña que <?=NOMBRE_SITIO?> esta ofreciendo con una promocion de descuento, pero tambien puede servir para simplemente identificar una operacion normal sin descuento alguno. <br />Al ser ingresado en su lugar correspondiente, este codigo permite comunmente reconocer un descuento otorgado a un cliente final por parte de <?=NOMBRE_SITIO?> y/o sus asociados, o bien permite reconocer una transaccion con una persona responsable de la misma. Este codigo es alfanumerico y contiene 6 cifras.
</p>
</blockquote>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body></html>