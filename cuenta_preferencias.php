<?php require_once($_SERVER["DOCUMENT_ROOT"]."/cuenta_header.php"); ?>
<?=$mensaje?>

<table cellpadding="5">
	<tr>
		<td class="bloque_borde_gris" align="right"><b>dirección de correo:</b></td>
		<td><?=$usuario->getEmail()?> <font size=2>[<a href="<?=$h->getMiCuentaLink().'?a=pref&cambia=correo'?>">cambiar</a>]</font></td>

	</tr> 
	<tr>
		<td class="bloque_borde_gris" align="right">&nbsp;</td>
		<td><font size=2>[<a href="<?=$h->getMiCuentaLink().'?a=pref&cambia=password'?>">cambiar contraseña</a>]</font></td>
	</tr>
	<tr>
		<td class="bloque_borde_gris" align="right"><b>sitio por defecto:</b></td>

		<td><form action="<?=$h->getMiCuentaLink()?>" method="get">
		<input type="hidden" name="a" value="pref" >
		<input type="hidden" name="cambia" value="sitio" >
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/cuenta_combo_ciudades_estados.php"); ?>
		&nbsp;<input type="submit" value="cambia"></form></td>
	</tr>
	<tr>
		<td class="bloque_borde_gris" align="right"><b>mantenme conectado durante:</b></td>

		<td><form action="<?=$h->getMiCuentaLink()?>" method="get">
		<input type="hidden" name="a" value="pref" >
		<input type="hidden" name="cambia" value="sesion">
		<select name="duracionSesion">
<option value="900" <?=($usuario->getDuracionSesion()==900)?'selected':''; ?>>15 minutos
<option value="1800" <?=($usuario->getDuracionSesion()==1800)?'selected':''; ?>>30 minutos
<option value="3600" <?=($usuario->getDuracionSesion()==3600)?'selected':''; ?>>1 hora
<option value="21600" <?=($usuario->getDuracionSesion()==21600)?'selected':''; ?>>6 horas
<option value="43200" <?=($usuario->getDuracionSesion()==43200)?'selected':''; ?>>12 horas
<option value="86400" <?=($usuario->getDuracionSesion()==86400)?'selected':''; ?>>24 horas
<option value="604800" <?=($usuario->getDuracionSesion()==604800)?'selected':''; ?>>1 semana
</select>
<input type="submit" value="cambia"></form></td>
	</tr>
</table>
