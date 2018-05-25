 <fieldset>
<legend><span class="req" style="font-size: 1.1em">Mejorar la visibilidad de tu anuncio (Opcional)</span>
<font size="2">(<a href="<?=$h->getHost()?>/legal-abusos-ayuda/mejorar-visibilidad-anuncio" target="_blank">¿Qué es esto?</a>)</font>
<br></legend>
<span class="text_destaque">Al seleccionar un anuncio como destacado o urgente, tu publicación tendrá 
mayor exposición al aparecer a lo mas arriba, o bien resaltado en el 
listado. De esta manera, tu publicación se mostrará mas visible para todos 
los usuarios y así aumentará tus posibilidades de éxito.</span>
<br>
<br>
<input tabindex="1" id="destacado_1" name="destacado_1" value="<?=ANUNCIO_DESTACADO?>" type="checkbox" onClick="checkDestacado(this);" <?=(!empty($_POST['destacado_1'])) ? 'checked' : ''?> >
	<label for="destacado_1"><?=$h->getMensajeDestacado()?> - <?=formatMoney(PRECIO_DESTACADO,2); ?></label>
<br>
<input tabindex="1" id="destacado_2" name="destacado_2" value="<?=ANUNCIO_URGENTE?>" type="checkbox" onClick="checkUrgente(this);"  <?=(!empty($_POST['destacado_2'])) ? 'checked' : ''?> >
	<label for="destacado_2"><?=$h->getMensajeUrgente()?> - <?=formatMoney(PRECIO_URGENTE,2); ?></label>

<div id="resdes"></div>
</fieldset>