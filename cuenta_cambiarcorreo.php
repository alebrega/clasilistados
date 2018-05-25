<?=$mensaje?>

<p>Introduce el nuevo correo electrónico que quieres asociar a tu cuenta:</p>

<form action="<?=$h->getMiCuentaLink()?>" method="post">
<input type="hidden" name="a" value="pref" >
<input type="hidden" name="cambia" value="correo">

<table cellpadding="5">
	<tr>
		<td class="bloque_borde_gris"  align="right"><b>Dirección de correo actual:</b></td>
		<td><?=$usuario->getEmail()?></td>
	</tr>
	<tr>
		<td class="bloque_borde_gris"  align="right"><b>Nuevo correo:</b></td>

		<td><input type="text" name="correoNuevo" size="40" maxlength="64"></td>
	</tr>
	<tr>
		<td class="bloque_borde_gris" align="right"><b>Reescribe el correo:</b></td>
		<td><input type="text" name="correoNuevo2" size="40" maxlength="64"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>

		<td><input type="submit" value="Envía una dirección nueva de correo" name="cambiarCorreo"></td>
	</tr>
</table>
</form>

<p>¿Tienes preguntas? Por favor <?=$h->getContactanosLink() ?>. </p>

