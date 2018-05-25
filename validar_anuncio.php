	<br />
	<span style="font-size: 0.9em;">
	<?php
	echo $anuncio->mostrarErrores();
	?>
	</span>
	<br />
			<form action="<?=$form_action?>" method="post" name="form">
				<?php 
				if (($intentos+1)>MAX_INTENTOS_CAPTCHA){
					$suma=$captcha_1+$captcha_2;
					?>
					<b <?= $anuncio->enRojo('palabraverif')? 'class="err"': 'class="req"' ?> >Suma de verificación</b>
					<br><br>
						<table border="0">
						<tr>
						<td><img src="<?=$h->getCaptchaLink($crypt->encrypt($captcha_1),$crypt->encrypt($captcha_2));?>" /></td>
						<td><input type="text" size="3" name="captcha" value="" /></td>
						</tr>
						</table>
						<br><br>
						<?php 
				}else{
				?>
					<b <?= $anuncio->enRojo('palabraverif')? 'class="err"': 'class="req"' ?> >Palabras de verificación</b>
					<br><br>
					<?php
						echo $captcha->imprimir(false);
					?>
					<br><br>
				<?php 
				}
				?>
				<b <?= $anuncio->enRojo('condiciones')? 'class="err"': 'class="req"' ?>>Terminos y Condiciones</b>
				<br><br>
				<textarea cols="90" rows="20" readonly="readonly" class="fondoBlancoBordeGris">
				<?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"]."/terminosycond.php"); ?>
				</textarea>
	
			<br><br>
	
	<?php
	$anuncio->traerCamposOcultosaInsertar($noEncriptarCampos);
	?>
				<input type="hidden" value="<?=$crypt->encrypt($_POST['intentos'])?>" name="intentos"/>
				<input type="hidden" value="<?=$crypt->encrypt($suma)?>" name="suma" />
				<input type="hidden" name="val" value="<?=$crypt->encrypt(1)?>" />
				<div id="b">
					<input type="submit" name="acepto" id="submit" value="ACEPTO las condiciones de uso"  OnClick="document.getElementById('b').style.display='none'; document.getElementById('t').style.display='block';">&nbsp;&nbsp;
					<input type="submit" name="rechazo" id="submit" value="RECHAZO las condiciones de uso">
				</div>
				<div style="display: none; font-size:1em;" id="t"><b>Procesando...</b></div>
				
			</form>
			
			<br><br>