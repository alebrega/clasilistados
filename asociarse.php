<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<title>clasilistados - como asociarse con clasilistados</title>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/metas/noindexnofollow.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header.inc.php");
?>
</head>
<?php flush(); ?>
<body >
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/headerayuda.inc.php");
?>
		<h4>si quieres publicar muchos anuncios en <?=NOMBRE_SITIO?>, tan sólo tienes que seguir los siguientes pasos:</h4>
		<ul>
		    <li> envíanos un archivo .xml o .csv con el formato descripto más abajo en cada categoria.</li>
		    <li>envíanos el enlace donde se encuentra el archivo que has creado, por ejemplo:<br />
		      <b>http://www.tusitioweb.com/clasilistados_anuncios.xml</b></li>
		    <li>desde clasilistados descargaremos el archivo e introduciremos todos tus 
		    anuncios para que puedan ser encontrados tan pronto como sea posible.</li>
		    <li>a modo infomativo, recibirás un correo electrónico cuando todos tus anuncios estén publicados.</li>
		</ul>
			<h5 style="font-size: 120%;">enviar anuncios de:</h5>
		    <ul>
		    	<?php
		    	foreach ($cats as $catNombre=>$catid){
		    		?>
		    		<li><a href="<?=$h->link_asociarseClasi()?>/enviar-anuncios/cat-<?=$catid?>" rel="nofollow"><b><?=$catNombre?></b></a></li>
		    		
		    		<?php
		    	}
		    	?>
		    </ul>
		<br>
		<p>si tienes mas preguntas puedes <a href="<?=$h->getContactanosLinkHref()?>" rel="nofollow">contactarnos</a>. </p>

<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?> 
</body>
</html>