<div id="bloqueSuscrip">
	<h3>¿Publicas anuncios con mucha frecuencia?</h3>
	<p>si publicas anuncios con mucha frecuencia, la suscripción es un

paquete que te permitirá publicar, de manera <b>ilimitada</b> y solo en esta

categoría, tantos anuncios como puedas subir en un plazo de hasta <?=$suscripcion->getdiasValidez()?> dias.</p>
<?php 
if (empty($_POST['suscribirme'])){
	echo $bloqueSuscrip;
}
?>
</div>