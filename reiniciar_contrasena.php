<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>clasilistados - reinciar la contraseña de la cuenta</title>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/headerssl.inc.php");
?>
</head>
<?php flush(); ?>
<body style="font-family:Bookman Old Style,sans-serif;">

<?php
if (!$envioExitoso){
?>
<h3>clasilistados: reiniciar contraseña</h3>

<p><em>&nbsp;<?=$mensajesErrores?></em></p>

<p>ingresa el correo electrónico asociado a la cuenta que desees reiniciar:</p>

<form action="<?=$h->getReiniciarPasswordLink()?>" method="post" name="reinicio">
<table cellpadding="5">
	<tr>
		<td class="bloque_borde_gris"><b>Correo:</b></td>
		<td><input type="text" name="email" value="<?=$_POST['email'] ?>" size="40" maxlength="64" id="email"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>

		<td><input type="submit" value="Reiniciar Contraseña" name="reiniciarPassword"></td>
	</tr>
</table>
</form>

<p>para mayor información acerca de una cuenta, por favor haz clic en <?=$h->getContactanosLink() ?>.</p>

<script language="javascript">
document.reinicio.email.focus();
</script>
<?php
}else{
?>
<h3>Se te acaba de enviar un enlace para que elijas tu nueva contraseña a la siguiente dirección: <?=$email?></h3>
<p>Si tienes alguna pregunta, por favor <?=$h->getContactanosLink() ?>.</p>
<p>¡Gracias por usar clasilistados!</p>
<?php
}
?>
<p>volver a <a href="<?=$h->getHost()?>">clasilistados</a>.</p>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body>
</html>
