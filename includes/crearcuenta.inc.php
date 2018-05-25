<?php
if (!$usuarioCreado){
	
if (!empty($mensajesErrores)){
?>
<p><em>&nbsp;<?=$mensajesErrores?></em></p>
<?php 	
}
if(CREARCUENTA!=$_SERVER["PHP_SELF"]){
	if(empty($_POST['catid'])){
		$categoria_id=$categoria->getCategoriaId();
	}else{
		$categoria_id=$_POST['catid'];
	}
}
?>
<form action="<?=$h->getCrearCuentaHref()?>" method="post">

	<?php 
	if(!empty($_POST['suscribirme'])){
	?>
	<input name="suscribirme" value="1" type="hidden">
	<input name="ir" value="<?=$h->getPublicacionLink($categoria_id)?>" type="hidden">
	<input name="catid" value="<?=$categoria_id?>" type="hidden">
	<?php 
	}
	?>
	<input name="city_id" value="<?=$locacion->getCiudadId()?>" type="hidden">
	<input name="state_id" value="<?=(!empty($ciudad_id) && !empty($state_id))?0:$state_id;?>" type="hidden">
	<p>para crear una cuenta nueva, debes ingresar tu correo electrónico y tipear las dos palabras de verificación
		<sup>(<a target="_blank" href="<?=$h->getAyudaLinkHref() ?>">¿qué es esto?</a>)</sup></p>

	<table>

		<tbody><tr>
			<td class="bloque_borde_gris">correo electrónico:</td>
			<td><input tabindex="1" name="email" value="<?=$_POST['email']?>" style="width: 98%;" maxlength="64" type="text">
		</td></tr>

		


		<tr>
			<td class="bloque_borde_gris">palabras de verificación:<br>
			</td>
			<td>
			<?php
			echo $captcha->imprimir(false);
			?>
			</td>
		</tr>


		<tr>
			<td>&nbsp;</td>
			<td><input tabindex="1" value="crear cuenta" type="submit" name="submit"></td>
		</tr>

	</tbody></table>



	</form>
<?php
}else{
?>
<h3>Gracias por crear una cuenta en clasilistados.</h3>
<p>Se te acaba de enviar un enlace para activar tu cuenta a la dirección: <?=$email?><br />
Si tienes alguna consulta, por favor <?=$h->getContactanosLink() ?>.<br />
</p>
<p><a href="<?=$h->getHost()?>">Volver a clasilistados</a></p>
<?php
}
?>
