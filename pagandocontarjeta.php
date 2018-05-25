<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<title>clasilistados - pagando mediante tarjeta de credito</title>
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
clasilistados acepta 
 <?php 
          $i=0;
          while ($i<count($tarjetas_aceptadas)){
          	echo strtolower($tarjetas_aceptadas[$i]);
          	$i++;
          	if ($i<count($tarjetas_aceptadas)){
          		echo ', ';
          	}
          	         	
          }
 ?>
 (solo aceptamos tarjetas de los estados unidos de america / no aceptamos tarjetas de credito internacionales)<br />

tu conexion a nuestro formulario de pago por tarjeta de credito esta protegido por estandares maximos requeridos en la industria con una encripcion segura de 128 bits. la informacion de tu tarjeta de credito pasa a traves de una conexion segura hasta llegar a la compania de procesamiento de tarjetas de credito que clasilistados utiliza; esta compania es la que ultimamente va a cobrarte a tu cuenta de tarjeta de credito. ademas, clasilistados no almacena ninguna informacion de tu tarjeta de credito en sus servidores ni en ningun otro lado.
<br />
<br />
si aun tienes preguntas puedes contactarnos haciendo clic <a href="<?=$h->getContactanosLinkHref()?>" rel="nofollow">aqui</a>.
</p>
</blockquote>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body></html>