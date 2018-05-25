<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>

<title>clasilistados - lo mejor de clasilistados</title>
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
<br>
<font size="+1">antes de utilizar o votar los mejores anuncios de clasilistados <span style="color:red;">por
favor ten en cuenta</span> que:</font>
<br>
<ul>
<li>todos los
anuncios son nominados por los lectores de clasilistados y no son
necesariamente respaldados por el equipo de clasilistados
</li>
<li>
ciertos anuncios pueden incluir contenido explícitamente sexual,
escatológico, ofensivo, gráfico, de mal gusto, y/o no producirte gracia
alguna</li>
<li>si
encuentras material que esta legalmente protegido por derechos de autor
y no pertenece a quien lo publico ni a clasilistados por favor háznoslo
saber de inmediato así lo eliminamos del sitio
</li>
<li>
si tienes
menos de 18 años de edad, por favor, utiliza el botón "Atrás" y activa
la funcionalidad de “control paternal”
</li>
<li>si
continuas avanzando en esta sección del sitio es porque reconoces ser
mayor de 18 años de edad y como tal liberas a clasilistados de
cualquier tipo de responsabilidad derivada del uso que pueda surgir de
lo mejor de clasilistados
</li>
</ul>
<br />
<?php
echo $listados->getLoMejor($t_flags,$h);
?>
</blockquote>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body></html>