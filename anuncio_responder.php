<?php
if ($_SERVER["PHP_SELF"]==ANUNCIO_PAGINA){
	if ((!$anuncio->tieneRespuestaViaEnlace())){
?> 
<div id="contacto">

<a id="responder_anuncio"></a>		
<strong style="font-size:18px;">responder al anuncio:</strong>

<div class="container">


	<form class="form" onsubmit="return validarRespuesta(this, 20);" method="post" action="<?=$h->getHost().$_SERVER['REQUEST_URI']?>">
	<input type="hidden" value="<?=$crypt->encrypt($captcha_1)?>" name="c1"/>
	<input type="hidden" value="<?=$crypt->encrypt($captcha_2) ?>" name="c2"/>
	
		<p>
			<label for="email" style="font-size:14px; font-weight:bold;">tu correo electrónico:</label>
			<input type="text" size="20" value="<?=$_POST['email']?>" name="email" style="background:#FBFBFB none repeat scroll 0 0; border: 1px solid black; font-weight:bold; width:50% ; font-size:1.3em;" />
		</p>
		
		<p>
			<label for="comentarios" style="font-size:16x; font-weight:bold;">mensaje:</label>
			<textarea cols="22" rows="12" style="width: 97%; background:#FBFBFB none repeat scroll 0 0; border:1px solid #CCCCCC;" name="comentarios"/><?php if (!empty($_POST['comentarios']) && !$respuestaEnviada){ echo $_POST['comentarios'];} elseif(!$respuestaEnviada) { echo $comentariosAutoComplete; } ?></textarea>
			
			
		</p>
		<table border="0">
		<tr>
		<td width="100%"><span style="font-size:14px; font-weight:bold;" >por favor responde a esta pregunta: </span></td>
		</tr>
		<tr>
		</table>
		<table border="0">
		<tr>
		<td><img src="<?=$h->getCaptchaLink($captcha_1,$captcha_2)?>" /></td>
		<td><input type="text" size="3" name="captcha" style="background:#FBFBFB none repeat scroll 0 0; border: 1px solid black; font-weight:bold; font-size:1.3em;" /></td>
		</tr>
		</table>


		<p>
			<input type="submit" value="Enviar" name="submit" style="font-size:18px; font-weight:bold; background:#FBFBFB none repeat scroll 0 0; border: 1px solid black;"/>
		</p>

	</form>
<p style="font-size:14px;">
enviando el mensaje estas aceptando los <?=$h->getTermCondicionesLink();?> de clasilistados. tu mensaje solo será enviado al correo electrónico de quien publicó este anuncio.</p>

	</div>
</div>
<?php
	}
}
?>
