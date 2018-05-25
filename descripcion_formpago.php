<div style="float: left;">
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/descripcion_precio.inc.php");
?>
<hr/>
<div class="remarcar"><div class="remarcar">
<?php 
if(!empty($_POST['credit_card_form'])){
	$errores=$publicacion->getErrores();
	if ((count($errores)<=0) && strlen($resultpago)>0){
		$errorFirstData=split(':',$resultpago);
		echo 'Error: '.trim($errorFirstData[1]);	
	}else{
		echo '<span class="err">';
		echo 'La información que requerimos no se encuentra o es incorrecta. <br />';
		echo 'Por favor corrija los campos marcados con verde.<br />';
		echo '</span>';
		echo 'Error: ';
		foreach ( $errores as $error){
			echo $error.'<br />';	
		}
	}
}else{
	echo 'Los campos obligatorios estan en <b style="color: green;">verde</b>.<br/>La dirección de facturación de la tarjeta de crédito/débito debe ser <em>IGUAL</em> a la dirección donde recibes el resumen mensual de tu tarjeta de crédito/débito.<br />';
}
?>
<br/></div></div>
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/credit_card_form.inc.php");
?>
</div>