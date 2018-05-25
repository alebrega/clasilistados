<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<title>clasilistados - formulario de contacto</title>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/metas/noindex.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header.inc.php");
?>
</head>
<?php flush(); ?>
<body>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/headerayuda.inc.php");

if ((strlen ($mensaje)==0) && !empty($_POST['submit'])){
	?>
	<b>gracias por contactarnos. tu mensaje ha sido enviado exitosamente. a la brevedad alguien se contactará contigo para darte una respuesta.</b>
	<br />gracias nuevamente!<br />
	<br />el equipo de clasilistados
	<?php
}else{
	if (strlen ($mensaje)>0){
		echo $mensaje;
	}

?>
<form method="post" action="/contacto">

<table cellpadding="4" class="contactanos">
<tr>
	<td class="head">
		<font size="+1"><b>tipo de reclamo</b><br></font><font size="-1">(escoge uno solo)</font>
	</td>

	<td>
		<?php
		foreach ($enviarA as $codigo=>$valor){
			$radioSelec='';
			if ($_POST['enviarA']==$codigo){
				$radioSelec='checked';
			}
			echo '<label><input type="radio" name="enviarA" value="'.$codigo.'" '.$radioSelec.'> '.$valor.'</label><br>';
		}
		?>
	</td>

</tr>

<tr id="namefield"><td class="head">nombre completo</td><td><input type=text size="25" maxlength="50" name="nombre" value="<?=$_POST['nombre']?>"></td></tr>

<tr><td class="head" id="emailfield">correo electronico</td><td><input type="text" size="35" maxlength="50" name="email" value="<?=$_POST['email']?>"></td></tr>

<tr><td class="head">verifica correo electronico</td><td><input type="text" size="35" maxlength="50" name="email2" value="<?=$_POST['email2']?>"></td></tr>

<tr><td class="head">tu ubicación</td><td>
<select name="ubicacion">
<option disabled="disabled" value="">ciudades</option>
<option disabled="disabled" value="">---</option>
<?php
$ciudadesMasImp=$locacion->getCiudadesDestacadasdelPais();
foreach ($ciudadesMasImp as $ciudad){
	$seleccionado='';
	if ($_POST['ubicacion']==$ciudad['ciudad']){
		$seleccionado='SELECTED';
	}
	echo '<option value="'.$ciudad['ciudad'].'" '.$seleccionado.' >'.$ciudad['ciudad'].'</option>';	
}
?>
<option disabled="disabled" value="">---</option>
<option disabled="disabled" value="">estados</option>
<option disabled="disabled" value="">---</option>
<?php
$estados=$locacion->getEstados();
foreach ($estados as $estado){
	$seleccionado='';
	if ($_POST['ubicacion']==$estado['estado']){
		$seleccionado='SELECTED';
	}
	echo '<option value="'.$estado['estado'].'" '.$seleccionado.' >'.$estado['estado'].'</option>';	
}
if ($_POST['ubicacion']=="otra"){
	$seleccionado='SELECTED';
}
echo '<option disabled="disabled" value="">---</option>';
echo '<option value="otra" '.$seleccionado.' >otra</option>';
?>
</select></td></tr>

<tr><td class="head">asunto</td><td><input type="text" size="35" maxlength="50" name="asunto" value="<?=$_POST['asunto']?>"></td></tr>

<tr>
	<td class="head">describenos tu reclamo<br><font size="-1">(por favor proveer <br> numero de identificación<br>  del anuncio y su categoria,<br>error de mensaje, etc)</font></td>

	<td><textarea rows="5" cols="55" name="reclamo"><?=$_POST['reclamo']?></textarea></td>

</tr>
<tr><td  class="head">Palabras de verificación:</td>
<td>
					<?php
						
						echo $captcha->imprimir(false);
					?>
					
</td></tr>
</table>

<input type="submit" value="enviar correo electronico" name="submit">

</form>
<br />
<p>Tambi&eacute;n puedes contactarnos llamando a la l&iacute;nea de Servicio de Atenci&oacute;n al Cliente: (650) 690-7728
<br />
<br />
</p>
<?php
}
?>
<p>volver a <a href="<?=$h->getHost()?>">clasilistados</a></p>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/ga.inc.php");
?>
</body>
</html>