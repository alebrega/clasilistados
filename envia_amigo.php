<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<title>clasilistados - envia a un amigo</title>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/metas/noindex.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header.inc.php");
?>
</head>
<?php flush(); ?>
<body>
<div id="sc">
	<div id="banner">
		<div id="logo">

			<a href="<?=$h->getHost()?>">clasilistados</a><br>
		</div>
		
	</div>
	<div id="content">
		<?php
		if ($fueEnviado){
			?>
			<h2>anuncio enviado</h2>
			<?php
		}else{
			?>
			<h2>envíar anuncio</h2>
			<?php
		}
		?>
		

		<div>

		

<hr class="hrstyle">
<br />
<?php
if (!$fueEnviado){
?>

<form action="<?=$h->getEnvioAmigoLink($adid,$categoria_id)?>" method="post">
<input type="hidden" name="adid" value="<?=$adid?>">
<input type="hidden" name="catid" value="<?=$categoria_id?>">
<input type="hidden" name="urlAnuncio" value="<?=$urlAnuncio?>">

<table cellpadding="4">
  <tr>
    <td align="right">tu dirección de correo electrónico</td>
    <td><input type="text" id="S" name="email" size="25" value=""></td>
  </tr>

  <tr>
    <td align="right">dirección de correo electrónico de destino</td>
    <td><input type="text" id="D" name="destino" size="25"></td>
  </tr>

  <tr>
    <td align="right">&nbsp;</td>
    <td><input type="submit" value="enviar correo" name="submit" /></td>

  </tr>
  <tr>
  <td><br /><br /><br /><a href="<?=$urlAnuncio?>">Volver al anuncio</a></td>
  </tr>
</table>
</form>

<?php
}else{
	?>
	<p>correo enviado a <?=$_POST['destino']?></p>
	<br /><br /><a href="<?=$urlAnuncio?>">Volver al anuncio</a>
	<?php
}
?>
<script type="text/javascript">window.onload=function() { document.getElementById("S").focus(); } </script>


		</div>

	</div>
</div>

<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body>
</html>