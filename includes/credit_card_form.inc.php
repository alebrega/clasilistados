<div style="float: left;">
		<form name="form1" method="post" action="<?=$h->getPublicacionLink($categoria->getCategoriaId(),$parametrosSubcategoria)?>">
		<?php
		$anuncio->traerCamposOcultosaInsertarEncripNombre();
		if (!empty($_REQUEST['accion'])){
			?>
			<input type="hidden" name="<?='accion'?>" value="<?=$_REQUEST['accion']?>" />
			<?php 
		}
		if (!empty($_POST['destacado_1'])){
			?>
			<input type="hidden" name="<?='destacado_1'?>" value="<?=ANUNCIO_DESTACADO?>" />
			<?php 
		}
		if (!empty($_POST['destacado_2'])){
			?>
			<input type="hidden" name="<?='destacado_2'?>" value="<?=ANUNCIO_URGENTE?>" />
			<?php 
		}
		if (!empty($_POST['suscribirme'])){
			?>
			<input type="hidden" name="<?=$crypt->encrypt('suscribirme')?>" value="1" />
			<?php 
		}else{
			?>
				
			<input type="hidden" name="<?=$crypt->encrypt('acepto')?>" value="1" />
			<?php 
		}
		?>
		<input type="hidden" name="<?=$crypt->encrypt('val')?>" value="1" />
		<input type="hidden" name="<?=$crypt->encrypt('chargetotal')?>" value="<?=empty($_POST['chargetotal'])?$total_a_pagar:$_POST['chargetotal']?>" />
		<input type="hidden" name="<?=$crypt->encrypt('ordertype')?>" value="SALE" />
		<input type="hidden" name="<?=$crypt->encrypt('ip')?>" value="<?=$_SERVER['REMOTE_ADDR']?>" />
		<fieldset id="ccinfo" style="background:#F0F7F9 none repeat scroll 0 0;">
          <legend><b>Por favor ingrese los datos de su tarjeta de crédito/débito:</b>   (<a href="<?=$h->getHost()?>/legal-abusos-ayuda/pagando-con-tarjeta-de-credito" target="_blank">pagando mediante tarjeta de crédito</a>)</legend>
      <table>
        <tbody>
        <tr>
        	
          <td align="right"><b><span style="color: green;">Número de Tarjeta:</span></b></td>
          <td><input type="text" value="<?=$_POST['cardnumber']?>" id="cardnumber" name="<?=$crypt->encrypt('cardnumber')?>" maxlength="20" size="20"/>
          <b><span style="color: green;">Número de Verificación de la Tarjeta:</span></b>
          <input type="text" value="<?=$_POST['cvmvalue']?>" name="<?=$crypt->encrypt('cvmvalue')?>" maxlength="4" size="4"/>
          <font size="2">(<a href="<?=$h->getHost()?>/legal-abusos-ayuda/numeros-de-verificacion-tarjeta-de-credito" target="_blank">¿Qué es esto?</a>)</font></td>
        </tr>
        <tr>
          <td> </td>
          <td><small>(Aceptamos 
          <?php 
          $i=0;
          while ($i<count($tarjetas_aceptadas)){
          	echo $tarjetas_aceptadas[$i];
          	$i++;
          	if ($i<count($tarjetas_aceptadas)){
          		echo ', ';
          	}
          	         	
          }
          ?>
          - <b>¡No se aceptan tarjetas de regalo o tarjetas pre-pagas de crédito!</b>)</small></td>
        </tr>
        <tr>
          <td align="right"><b>Expiración <span style="color: green;">Mes</span> / <span style="color: green;">Año:</span></b></td>
          <td><select name="<?=$crypt->encrypt('cardexpmonth')?>">
          <option value="Mes" <?=empty($_POST['cardexpmonth'])?'selected=""':''?>>Mes</option>
          <?php 
          $meses=12;
          $i=1;
          while ($i<=$meses){
          	$num=sprintf("%02d", $i);
          	if ($num==$_POST['cardexpmonth']){
          		$selected='selected=""';
          	}else{
          		$selected='';
          	}
          	echo '<option value="'.$num.'" '.$selected.'>'.$num.'</option>';
          	$i++;
          }
          ?>
			</select> / <select name="<?=$crypt->encrypt('cardexpyear')?>"><option <?=empty($_POST['cardexpyear'])?'selected=""':''?> value="Ano">Año</option>
<?php 
$i=0;
while ($i<=10){
	$anoMostrar=(date('Y')+$i);
	$ano=sprintf("%02d",(date('y')+$i));
	if ($ano==$_POST['cardexpyear']){
    	$selected='selected=""';
    }else{
    	$selected='';
    }
	echo '<option value="'.$ano.'" '.$selected.'>'.$anoMostrar.'</option>';
	$i++;
}
?>
</select></td>
        </tr>
        <tr>
          <td align="right"><b><span style="color: green;">Nombre del titular de la tarjeta:</span></b></td>
          <td><input type="text" value="<?=$_POST['name']?>" name="<?=$crypt->encrypt('name')?>" maxlength="160" size="40"/></td>
        </tr>
        <tr>
          <td align="right"><b>Dirección de facturación <span style="color: green;">Dirección:</span></b></td>
          <td><input type="text" value="<?=$_POST['address1']?>" name="<?=$crypt->encrypt('address1')?>" maxlength="80" size="50"/></td>
        </tr>
        <tr>
          <td align="right"><b><span style="color: green;">Ciudad:</span></b></td>
          <td><input type="text" value="<?=$_POST['city']?>" name="<?=$crypt->encrypt('city')?>" maxlength="80" size="15"/>
          <b><span id="ccst" style="color: green;">Estado:</span></b> <input type="text" value="<?=$_POST['state']?>" name="<?=$crypt->encrypt('state')?>" maxlength="2" size="2"/>
		  <b><span id="ccpc" style="color: green;">Zip/Código postal:</span></b> <input type="text" value="<?=$_POST['zip']?>" name="<?=$crypt->encrypt('zip')?>" maxlength="20" size="5"/></td>
        </tr>
		<tr>
          <td align="right"><b><span style="color: green;">País:</span></b></td>
		  <td><label><input type="radio" <?=((($_POST['country']=='US') || (empty($_POST['country'])))?'checked="checked"':'')?> value="US" name="<?=$crypt->encrypt('country')?>" />US</label>
			<label><input type="radio" value="CA" <?=($_POST['country']=='CA')?'checked="checked"':''?> name="<?=$crypt->encrypt('country')?>"/>Canada</label>
	</td>
		</tr>
		</tbody></table>
		</fieldset>


<table>
	<tbody><tr>
		<td colspan="2"><b>¿A quién deberíamos contactar si tenemos preguntas sobre tu anuncio?</b></td>
	</tr>
	<tr>
		<td align="right"><b><span style="color: green;">Nombre:</span></b></td>
		<td><input type="text"  value="<?=$_POST['nombre']?>" name="<?=$crypt->encrypt('nombre')?>" maxlength="80" size="20"/></td>
	</tr>
	<tr>
		<td align="right"><b><span style="color: green;">Telefono:</span></b></td>
		<td><input type="text" value="<?=$_POST['telefono']?>" name="<?=$crypt->encrypt('telefono')?>" maxlength="80" size="16"/></td>
	</tr>
	<tr>
		<td align="right"><b><span style="color: green;">Correo electrónico:</span></b></td>
		<td><input type="text" value="<?=(empty($_POST['email_cc'])?$_POST['email']:$_POST['email_cc'])?>" name="<?=$crypt->encrypt('email_cc')?>" maxlength="80" size="30"/></td>
	</tr>
</tbody></table>
<table>
	<tbody>
	<tr>
		<td colspan="2"></td>
	</tr>
	<tr>
		<td colspan="2"></td>
	</tr>
	<tr>
		<td colspan="2"><b>¿Cómo nos conoció?</b></td>
	</tr>
	<tr>
		<td>
		<select name="<?=$crypt->encrypt('nosconocio')?>">
                                      <option value="0">Seleccione como nos conoció</option>
                                      <option value="<?=NOSCONOCIO_BUSTOS?>" <?=('Bustos'==$_POST['nosconocio'])?'selected=""':''?>>Radios Ke Buena o La Gran D</option>
                                      <option value="<?=NOSCONOCIO_PUBLICIDAD?>" <?=('Recomendacion'==$_POST['nosconocio'])?'selected=""':''?>>Recomendación</option>
                                      <option value="<?=NOSCONOCIO_BUSCADOR?>" <?=('Publicidad'==$_POST['nosconocio'])?'selected=""':''?>>Publicidad</option>
                                      <option value="<?=NOSCONOCIO_RECOMENDACION?>" <?=('Buscador'==$_POST['nosconocio'])?'selected=""':''?>>Buscador</option>
                                      <option value="<?=NOSCONOCIO_OTRO?>" <?=('Otro'==$_POST['nosconocio'])?'selected=""':''?>>Otro</option>
      	</select>
		</td>
	</tr>
	<tr>
		<td colspan="2"></td>
	</tr>
	<tr>
	<?php
	if ($_POST['codigo_prom']=='BUSTOS'){
		$_POST['codigo_prom']='';
	}
	?>
		<td colspan="2"><b>Código de promoción:</b> <input type="text" value="<?=$_POST['codigo_prom']?>" name="<?=$crypt->encrypt('codigo_prom')?>" maxlength="6" size="6" style="font-size: 22px; font-weight: bold; text-transform:uppercase;"/>
	<font size="2">(<a href="<?=$h->getHost()?>/legal-abusos-ayuda/codigo-promocion" target="_blank">¿Qué es esto?</a>)</font></td>
	</tr>
</tbody></table>

<hr/>

<em>(Por favor haz clic UNA SOLA VEZ, este paso puede llegar a durar unos 60 segundos.)</em>
<div id="b">
	<input type="submit" value="Enviar pago con tarjeta de crédito/débito" OnClick="document.getElementById('b').style.display='none'; document.getElementById('t').style.display='block';" name="<?=$crypt->encrypt('credit_card_form')?>" id="submit">
</div>

<div style="display: none;" id="t"><b>Procesando...</b></div>
</form>

</div>