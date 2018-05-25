<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>clasilistados - creación de cuenta</title>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/metas/noindex.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/headerssl.inc.php");
?>
</head>
<?php flush(); ?>
<body style="font-family:Bookman Old Style,sans-serif;">
<h3>clasilistados: crear una cuenta nueva</h3>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/crearcuenta.inc.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body>
</html>