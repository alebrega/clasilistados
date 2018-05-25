<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<title>clasilistados - enviar anuncios de <?=$categoria->getCategoriaNombre()?></title>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/metas/noindexnofollow.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header.inc.php");
?>
</head>
<?php flush(); ?>
<body id="body_especial">
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/headerayuda.inc.php");
?>
<a name="arriba"></a>

<h3>especificaciones técnicas para que puedas enviarnos tus anuncios a la categoría "<?=$categoria->getCategoriaNombre()?>"</h3>

<h4>formato y codificación del archivo</h4>
<p>el archivo con todos tus anuncios debe estar en formato .xml (preferentemente) o .csv (valores separados por coma).</p>

<h4>contenido</h4>


<h5>hay dos maneras de enviar los anuncios:</h5>
	<p><b>1. un archivo con todos los anuncios:</b>
	<br />el archivo debe contener todos los anuncios. cuando un anuncio nuevo aparece, se inserta en <?=NOMBRE_SITIO?>. cuando un anuncio deje de aparecer, será eliminado. esta opción es la mas practica si sus anuncios no requieren actualización diaria.
	<p><b>2. un archivo con todos los anuncios para una fecha (no mas de 10.000 anuncios por dia):</b>
<br />el archivo contendrá los anuncios para esa fecha específica, se debe añadir al nombre del archivo "_fecha_" seguido de la fecha del día donde se van a publicar nuevos anuncios, por ejemplo: clasilistados_fecha_2009-12-20.xml. esta opción permitirá editar o eliminar un anuncio con mas rapidez.
<h4>frecuencia de actualización</h4>
<p>el archivo se procesará cada 24 horas para los archivos por fecha, y semanalmente para los archivos que contengan todos los anuncios.
igualmente se podrá cambiar a pedido la frecuencia de actualización.</p>

<h4>ubicación</h4>
<p>puedes alojar tus archivos y el equipo técnico de clasilistados lo descargara del enlace provisto. Si prefieres, podemos configurarte una cuenta ftp para que subas tus archivos y clasilistados lo alojara.</p>

<h3>campos del anuncio</h3>

<table class="tabla_borde_gris" cellpadding="3" width="100%">
	<tbody><tr>
		<td>
			<b>campo</b>
		</td>

		<td>
			<b>tipo</b>
		</td>
		<td>
			<b>descripción</b>
		</td>
	</tr>
	<tr>
		<td>titulo</td>
		<td>&lt; ! [ CDATA[   ]] &gt;</td>

		<td>título del anuncio. </td>
	</tr>
	<tr>
		<td>descripción</td>
		<td>&lt; ! [ CDATA[   ]] &gt;</td>
		<td>descripción del anuncio. Puede enviarnos la descripcion en HTML o texto plano.</td>

	</tr>
	<tr>

		<td>fecha de vencimiento</td>
		<td>yyyy-mm-dd (hh:mm:ss)</td>
		<td>La fecha en que el anuncio debe ser eliminado. Formato aaaa-mm-dd (hh:mm:ss opcional)</td>
	</tr>
	<tr>
		<td>correo electrónico</td>
		<td>&lt; ! [ CDATA[   ]] &gt;</td>

		<td>email de contacto. Se utilizará en la página del anuncio, cuando un usuario responda al anuncio.</td>
	</tr>
	<tr>
		<td>ubicación del anuncio - url de la pagina principal</td>
		<td>&lt; ! [ CDATA[   ]] &gt;</td>
		<td>url de la pagina principal donde desea que se encuentre su anuncio (Ej: para san francisco del estado de california, la url seria <b>http://clasilistados.org/california/sanfrancisco</b> ). </td>
	</tr>
	<?php
	for($i=1;$i<=IMAGENES_ANUNCIO;$i++){
		?>
		<tr>
			<td>imagen <?=$i?> (opcional)</td>
			<td>&lt; ! [ CDATA[   ]] &gt;</td>
			<td>URL donde se encuentra la imagen para el anuncio. Vamos a descargar la imagen de esa URL. </td>
		</tr>
		<?php
	}
	?>
	
</tbody></table>

<br />

<h3>Envia tus anuncios a la categoria "<?=$categoria->getCategoriaNombre()?>"</h3>

<?php
if (!empty($errores)){
	?>
	<b>No has ingresado toda la información obligatoria.</b>
	<ul>
	<?php
	foreach ($errores as $e){
		?>
		<li><?=$e?></li>
		<?php
	}
	?>
	</ul>
	<?php
}elseif (!empty($_POST['submit'])){
	echo "<br> Gracias por enviar tus anuncios <br>";	
}
?>

<form action="<?=$h->link_asociarseClasi()?>/enviar-anuncios/cat-<?=$catid?>#form" method="post">
<input type="hidden" name="cat" value="<?=$catid?>" />
<table class="tabla_borde_gris" cellpadding="3">
<tr>
<td>
nombre y apellido
</td>
<td><input type="text" name="nombre" maxlength="90" size="45" value="<?=$_POST['nombre']?>" /></td>
</tr>
<tr>
<td>empresa (opcional)</td>
<td><input type="text" name="empresa" maxlength="90" size="45" value="<?=$_POST['empresa']?>" />
</td>
</tr>
<tr>
<td>correo electrónico</td>
<td><input type="text" name="correo" maxlength="90" size="45" value="<?=$_POST['correo']?>" />
</td>
</tr>
<tr>
<td width="5%">subcategoria de <b>"<?=$categoria->getCategoriaNombre()?>"</b>:</td>
<td width="22%">
<select name="subcat">
<?php
$subcategorias=$categoria->getSubCategorias($catid);
foreach ($subcategorias as $s){
	?>
	<option value="<?=$s['subcatid']?>"><?=$s['nombre']?></option>
	<?php
}
?>
</select>
</td>
<tr>
<tr>
<td width="5%">URL de tu archivo:</td>
<td width="22%">http://<input type="text" name="url_archivo" maxlength="255" size="100" value="<?=$_POST['url_archivo']?>" />
</td>
<tr>
<td>

</td>
<td>
<input type="submit" name="submit" value="enviar anuncios" style="font-size:larger;" />
</td>
</tr>
</table>
<a name="form"></a>
</form>

<br />
<a class="back" href="#arriba">Volver arriba </a>

<p>si tienes preguntas puedes <a href="<?=$h->getContactanosLinkHref()?>" rel="nofollow">contactarnos</a>. </p>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?> 
</body>
</html>