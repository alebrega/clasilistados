<h2><?=stripslashes($anuncio->renderTitulo())?> </h2>
<hr>
<span>Fecha: <?=$h->getFecha($anuncio->getMaketime())?></span><br>
<?php 
if ($anuncio->tieneRespuestaViaEnlace()){
	?>
	<div>
	<div class="container">
	<form class="form"  target="_blank" method="get" action="<?=$h->getTrackingUrl()?>">
	<input type="hidden" value="<?=$anuncio->getFeedId()?>" name="fid"/>
	<input type="hidden" value="<?=$anuncio->getLink()?>" name="url"/>
	<input type="submit" value="responder al anuncio" name="submit" />
	</form>
	</div>
	</div>
	<?php 
}elseif ($_SERVER["PHP_SELF"]==ANUNCIO_PAGINA){
	echo '<a href="#responder_anuncio" rel="nofollow" class="bookman">Responder al anuncio</a>';
}
?>

<hr>
<br>
<div id="userbody">
<?php 
echo strip_tags(stripslashes($anuncio->getDescripcion()),$tags_permitidos); 
echo $imagenes->getHTML(IMAGENES_ANUNCIO);
?>
<br /><br />
<?php 
$html='';
	if ($anuncio->validarLongitudMayorCero($anuncio->getLugar())){
		$html.='<li>Localización: '.$anuncio->getLugar().'</li>';	
	} 
	if ($anuncio->gatosOk()){
		$html.='<li>Aceptamos Gatos</li>';
	}
	if ($anuncio->perrosOk()){
		$html.='<li>Aceptamos Perros</li>';
	}
	if ($categoria->esEmpleo()){
		if ($anuncio->validarLongitudMayorCero($anuncio->getRetribucion())){
			$html.='<li>Retribución: '.$anuncio->getRetribucion().'</li>';	
		} 
		if (empty($_GET['id'])){
			//va a buscarlo al POST
			if  (!empty($_POST['teletrabajo'])){
				$html.='<li>Aceptamos Teletrabajo.</li>';	
			}
			if  (!empty($_POST['tiempo_parcial'])){
				$html.='<li>Es un trabajo a tiempo parcial.</li>';	
			}
			if  (!empty($_POST['contrato'])){
				$html.='<li>Es un trabajo por contrato y obra.</li>';	
			}
			if  (!empty($_POST['org_sinlucro'])){
				$html.='<li>Es un trabajo sin fines de lucro.</li>';	
			}
			if  (!empty($_POST['pasantia'])){
				$html.='<li>Es una pasantía.</li>';	
			}
			if  (!empty($_POST['agencia_busquedas'])){
				$html.='<li>Aceptamos contacto directo por agencias de búsqueda de personal.</li>';	
			}
			if  (!empty($_POST['recibir_llamados'])){
				$html.='<li>Aceptamos recibir llamados telefonicos sobre este puesto.</li>';	
			}
		}else
		{
			//obtiene la info del anuncio desde la BD
			if ($anuncio->getCampo('teletrabajo')==1){
				$html.='<li>Aceptamos Teletrabajo.</li>';	
			}
			if ($anuncio->getCampo('tiempo_parcial')==1){
				$html.='<li>Es un trabajo a tiempo parcial.</li>';	
			}
			if ($anuncio->getCampo('contrato')==1){
				$html.='<li>Es un trabajo por contrato y obra.</li>';	
			}
			if ($anuncio->getCampo('org_sinlucro')==1){
			$html.='<li>Es un trabajo sin fines de lucro.</li>';	
			}
			if ($anuncio->getCampo('pasantia')==1){
				$html.='<li>Es una pasantía.</li>';	
			}
			if ($anuncio->getCampo('agencia_busquedas')==1){
				$html.='<li>Aceptamos contacto directo por agencias de búsqueda de personal.</li>';	
			}
			if ($anuncio->getCampo('recibir_llamados')==1){
				$html.='<li>Aceptamos recibir llamados telefonicos sobre este puesto.</li>';	
			}
 
			
		}
	}
	if ($categoria->esTTemporal() && ($anuncio->validarLongitudMayorCero($anuncio->getPago()))){
		$html.='<li>Compensación: '.$anuncio->getPago().'</li>';	
	}

if ($anuncio->contactoComercialOK()){
	$html.='<li>Puedes contactar al dueño del anuncio por otros servicios, productos u ofertas de cualquier índole.</li>';
}else{
	$html.='<li>Por favor, NO contactes al dueño del anuncio por otros servicios, productos u ofertas de cualquier índole.</li>';
}
if (strlen($html)>0){
	echo '<ul>'.$html.'</ul>';
}
if ($_SERVER["PHP_SELF"]==ANUNCIO_PAGINA){
	
	$adsense=new Adsense($registro);
	$adsense->setMedidas('728x90');
	$html=$adsense->getHTML('anuncio');	
	if (strlen($html)>0){
		echo '<hr>';
		echo $html;
		echo '<hr>';	
	}
}
?>
</div>
<?php require_once($_SERVER["DOCUMENT_ROOT"]."/anuncio_responder.php"); ?>
