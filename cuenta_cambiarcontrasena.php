<p><font color="red">&nbsp;<?=$mensajesErrores?></font></p>

<p><?=$titulo_cambioContrasena?></p>

<form action="<?=$h->getMiCuentaLink()?>" method="post">
<input type="hidden" name="id" value="<?=$id_usuario?>">
<input type="hidden" name="cod_seguridad" value="<?=$cod_seguridad?>">
<input type="hidden" name="cambiaContrasena" value="1">
<input type="hidden" name="email" value="<?=$email?>">
<?php 
if (!empty($_REQUEST['ir'])){
	?>
	<input type="hidden" name="ir" value="<?=$_REQUEST['ir']?>">
	<?php 
}
echo $masCamposhidden;
?>
<table cellpadding="5">
	<tr>
		<td class="bloque_borde_gris" align="right"><b>Contraseña:</b></td>
		<td><input type="password" name="password" size="50" maxlength="50"></td>
	</tr>
	<tr>

		<td class="bloque_borde_gris" align="right"><b>Reescribe la contraseña:</b></td>
		<td><input type="password" name="password2" size="50" maxlength="50"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="<?=$valorSubmitCambioContrasena ?>" name="cambiaContrasena"></td>
	</tr>
</table>
</form>

<p>Si aun tienes preguntas por favor <?=$h->getContactanosLink() ?>. </p>