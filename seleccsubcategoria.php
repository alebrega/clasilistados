<script type="text/javascript">
<!-- Begin
	function countChecks() {
		var total = 0;
		var checks = document.getElementsByName('subcategoriaPublicacion[]');
		for (var i = 0; i < checks.length; i++) {
			if (eval("checks"+"[" + i + "].checked") == true) {
				total += parseFloat(checks[i].className);
			}
		}
		<?php 
		if ($haySuscripcionCategoria){
		?>
		checkSuscribirme=document.getElementsByName('suscribirme');
		if (checkSuscribirme[0].checked==true && checkSuscribirme[1].checked==true){
			return false;
		}
		<?php 
		}
		?>
		document.getElementById('priceCalc').innerHTML  = "Total a pagar: $" + total;
		document.getElementById('priceCalc2').innerHTML = "Total a pagar: $" + total;
	}
	function Suscribirme(boton){
		var checks = document.getElementsByName('suscribirme');
		for (var i = 0; i < checks.length; i++) {
				checks[i].checked=boton.checked;
		}
		document.getElementById('priceCalc').innerHTML  = "Total a pagar: $" + boton.value;
		document.getElementById('priceCalc2').innerHTML = "Total a pagar: $" + boton.value;
		if (checks[0].checked==false){
			activarCheckBoxes(false);
			countChecks();
		}else{
			activarCheckBoxes(true);
			document.getElementById('submitBlock').style.display='block';
		}
	}
	function activarCheckBoxes(activar){
		var checksSubcategorias = document.getElementsByName('subcategoriaPublicacion[]');
		for (var i = 0; i < checksSubcategorias.length; i++) {
			checksSubcategorias[i].disabled=activar;
		}
	}
//  End -->
</script>
<?php
echo '<blockquote>';
$html='';
if($esCategoriaPaga){
	$textoSuscribite='Suscríbete y publica anuncios <em>ilimitados</em> durante <em>un año (365 días)</em>.';
	
	$html.='<form method="post" action="'.$h->getPublicacionLink($_GET['cat']).'" name="catform">';
	$html.='<table border="0" width="100%"><tr><td width="55%" height="100%" align="left">';
	if ($resultpago && $haySuscripcionCategoria && $suscripcion->usuarioTieneSuscripcion()){
		//pago la suscripcion ahora
		$html.= '<br /><br />';
		$html.= '<b>Tu pago se ha realizado correctamente. </b><br /> Su codigo de pago es: <b style="color: green">'.$publicacion->getTransaccionId().'</b>';
		$html.=require_once($_SERVER["DOCUMENT_ROOT"]."/includes/descripcion_precio.inc.php");
		$html.= '<br /><br />';
	}elseif(!$suscripcion->usuarioTieneSuscripcion()){
		$precioMensaje='<b>Precio: '.formatMoney($publicacion->getPrecio($_GET['cat']),2).' por categoría seleccionada</b>';
		$html.='<span id="priceCalc" class="highlight">'.$precioMensaje.'</span>';
	}
	if ($haySuscripcionCategoria && $suscripcion->usuarioTieneSuscripcion()){
		$html.='<span id="priceCalc" class="remarcar">Con tu suscripción puedes publicar anuncios ilimitadamente.</span>';	
	}
	if ($haySuscripcionCategoria && !$suscripcion->usuarioTieneSuscripcion()){
		$html.='<br /><br />';
		$bloqueSuscrip='<span id="priceCalc" class="remarcar"><label><input type="checkbox" name="suscribirme" onClick="Suscribirme(this)" value="'.$publicacion->getPrecio($_GET['cat'],'suscripcion').'" class="bigCheckBox"/>'.$textoSuscribite.'</label></span>';
		$html.=$bloqueSuscrip;
		$html.='<div style="display:none; margin-top: 15px;" id="submitBlock"><input type="submit" value="Siguiente" name="submit" id="submit" /></div>';
	}
	$html.='<h4>Seleccione una o mas categorías:</h4>';
	
}else{
	echo '<h4>Por favor, elija una categorí­a:</h4>';
	$html.='<ul>';
}
$subcategorias = $categoria->getSubCategorias($_GET['cat']);

foreach ($subcategorias as $sub){
	$checkedSubcat='';
	if($esCategoriaPaga){
		if ($suscripcion->usuarioTieneSuscripcion()){
			$onclick='';
		}else{
			$onclick='onClick="countChecks();document.getElementById(\'submitBlock\').style.display=\'block\';"';	
		}
		if ($_GET['subcategoriaCheck']==$sub['subcatid']){
			$checkedSubcat=' checked ';
		}
		$html.='<label><input type="checkbox" '.$onclick.' class="'.$publicacion->getPrecio($_GET['cat']).'" value="'.$sub['subcatid'].'" name="subcategoriaPublicacion[]" '.$checkedSubcat.' /> '.$sub["nombre"].'</label>';
	}elseif($publicacion->esSubCategoriaPaga($_GET['cat'],$sub['subcatid']) && ( $publicacion->deboCobrarEstaUbicacion($_GET['cat']))){
		$html.='<li>'.$h->getPostingSubCategoriaLink($_GET['cat'],$sub['subcatid'],$sub["nombre"]).' (<span>anunciar en esta categoría cuesta '.formatMoney($publicacion->getPrecio($_GET['cat']),2).'</span>)</li>';
	}else{
		$html.='<li>'.$h->getPostingSubCategoriaLink($_GET['cat'],$sub['subcatid'],$sub["nombre"]).'</li>';
	}
	if (!$esCategoriaPaga){
		$html.='<br />';
	}else{
		$html.='<br />';
	}
	
}
if($esCategoriaPaga){
	$html.='<br />';
	if ($haySuscripcionCategoria && !$suscripcion->usuarioTieneSuscripcion()){
		$html.=$bloqueSuscrip;
		$html.='<br /><br />';
	}
	if (!$suscripcion->usuarioTieneSuscripcion()){
		$html.='<span id="priceCalc2" class="highlight">'.$precioMensaje.'</span>';
		$html.='<br />';
		$html.='<br />';
	}
	if($suscripcion->getdiasValidez()==365){
		$textoSuscribite='Suscripción Anual e Ilimitada';
	}else{
		$textoSuscribite='Suscribirse';
	}
	
	$bloqueSuscrip='<span id="priceCalc" style="border:1px solid green; padding: 4px; background:#FBFBFB none repeat scroll 0 0;"><label><input type="checkbox" name="suscribirme" onClick="Suscribirme(this)" value="'.$publicacion->getPrecio($_GET['cat'],'suscripcion').'" class="bigCheckBox"/>'.$textoSuscribite.'</label></span>';
	$html.='<input type="submit" value="Siguiente" name="submit" id="submit" />';
	$html.='</td><td valign="top">';
	if ($haySuscripcionCategoria && !$suscripcion->usuarioTieneSuscripcion()){
		$html.='<div id="bloqueSuscrip">
	<h3>¿Publicas anuncios con mucha frecuencia?</h3>
	<p>si publicas anuncios con mucha frecuencia, la suscripción es un

paquete que te permitirá publicar, de manera <b>ilimitada</b> y solo en esta

categoría, tantos anuncios como puedas subir en un plazo de hasta <b>'.$suscripcion->getdiasValidez().' dias</b>.</p>'.$bloqueSuscrip.'
</div>';
	}
	$html.='</td></tr></table></form>';
}else{
	$html.='</ul>';
}
echo $html;
echo '<br />';
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/footerposting.inc.php");
echo '</blockquote>';
?>