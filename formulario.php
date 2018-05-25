<?php
echo $posting_form->getInputHiddenTag(array("name"=>"categoria_id","value"=>$categoria->getCategoriaId()));
echo $posting_form->getInputHiddenTag(array("name"=>"ciudad_id","value"=>$locacion->getCiudadId()));

if ( ($_REQUEST['accion']=="editar")  && ($anuncio->puedoModifAnuncio($adid,$catid)) ){
	echo $posting_form->getInputHiddenTag(array("name"=>"accion","value"=>$_REQUEST['accion']));
	echo $posting_form->getInputHiddenTag(array("name"=>"id","value"=>$adid));
	echo $posting_form->getInputHiddenTag(array("name"=>"cat","value"=>$catid));
	echo $posting_form->getInputHiddenTag(array("name"=>"subcat","value"=>$_REQUEST['subcat']));
	echo $posting_form->getInputHiddenTag(array("name"=>"fechaHora","value"=>$fechaHora));
	echo $posting_form->getInputHiddenTag(array("name"=>"c","value"=>$codSeguridad));
	echo $posting_form->getInputHiddenTag(array("name"=>"confirmacion_email","value"=>$anuncio->getEmail()));
	if ($anuncio->esteAnuncioTieneImagenes()){
		echo $posting_form->getInputHiddenTag(array("name"=>"tieneImagenes","value"=>1));
	}
	
}
if ( ($_REQUEST['accion']!="editar") && ($_REQUEST['accion']!="reenviar") ){
	echo $posting_form->getInputHiddenTag(array("name"=>"subcat","value"=>$categoria->getSubCategoriaId()));
}
if ($_REQUEST['accion']=="reenviar") {
	echo $posting_form->getInputHiddenTag(array("name"=>"accion","value"=>$_REQUEST['accion']));
}
if ($usuario->estaLogueado()){
	echo $posting_form->getInputHiddenTag(array("name"=>"usuario_id","value"=>$usuario->getUsuarioId()));
}
?>
<?php
if (!$anuncio->pulsoContinuar()){
	?>
	<br />
<span>Los datos requeridos se encuentran resaltados en <span class="req">negrita</span> - Por favor evita sí­mbolos innecesarios.</span>
	<?php
}
?>

<br />
<?php
if ($categoria->esPersonales()){
	echo '<span>'.$h->getAdvPosting().'</span>';
}
?>
<br />
<span style="font-size: 0.9em;">
<?=$anuncio->mostrarErrores()?>
</span>

<table><tbody><tr><td>

	

	<br>
	
	<?php if ($categoria->esEvento()){ ?>
	<fieldset>
  <table>
    <tbody><tr align="left">
      <th><span <?=$anuncio->enRojo('fechaInicio')? 'class="err"': 'class="req"'?>>Fecha de inicio del evento:</span></th>

	<th>Día</th><th>Més</th>
	<th>Año</th>
    </tr>

    <tr>
      <td><font color="green" size="2">(La duración máxima es de 14 días)</font></td>
	
	<td><input type="text" value="<?=$anuncio->getCampo('eventoDiaInicio')?>" maxlength="2" size="2" name="eventoDiaInicio" tabindex="1"/> - </td>
      		<td><input type="text" value="<?=$anuncio->getCampo('eventoMesInicio')?>" maxlength="2" size="2" name="eventoMesInicio" tabindex="1"/> - </td>
      <td><input type="text" value="<?php if ($anuncio->validarLongitudMayorCero($anuncio->getCampo('eventoAnoInicio'))) echo $anuncio->getCampo('eventoAnoInicio');  else  echo date('Y');?>" maxlength="4" size="4" name="eventoAnoInicio" tabindex="1"/></td>
    </tr>

    <tr> <td colspan="4"><hr/></td> </tr>

    <tr align="left">
      <th><span <?=$anuncio->enRojo('fechaFinal')? 'class="err"': 'class=""'?>>Fecha de fin del evento:</span></th>

	<th>Día</th><th>Més</th>
	<th>Año</th>

    </tr>
    <tr>
      <td><font color="green" size="2">(Déjalo en blanco si es solo un dí­a)</font></td>
	<td><input type="text" value="<?=$anuncio->getCampo('eventoDiaFin')?>" maxlength="2" size="2" name="eventoDiaFin" tabindex="1"/> - </td>
                <td><input type="text" value="<?=$anuncio->getCampo('eventoMesFin')?>" maxlength="2" size="2" name="eventoMesFin" tabindex="1"/> - </td>
      <td><input type="text" value="<?php if ($anuncio->validarLongitudMayorCero($anuncio->getCampo('eventoAnoFin'))) echo $anuncio->getCampo('eventoAnoFin');  else  echo date('Y');?>" maxlength="4" size="4" name="eventoAnoFin" tabindex="1"/></td>
    </tr>
  </tbody></table>

</fieldset>
<br />
<?php
	}
	?>
	<fieldset>	
	<table summary="posting form">
			<tbody>
			<tr>
				<?php if ($anuncio->esCompraVivienda() || ($categoria->esVehiculos())) { $h->renderPrecio(); }?>
				<?php if ($anuncio->esAlquilerVivienda()) { $h->renderCampoAlquilerVivienda(); }?>
				<td valign="top"><span <?=$anuncio->enRojo('titulo')? 'class="err"': 'class="req"'?>>Tí­tulo del anuncio:</span><br/>
					<input type="text" value="<?=$anuncio->getTitulo()?>" id="titulo" class="titulo" name="titulo" maxlength="70" size="30" tabindex="1" onBlur="pegarTitulo(this)"/><br/>
				</td>
				
				<?php if ($categoria->esCompraVenta()){ $h->renderCampoPrecioCompraVenta(); }?>
				<?php if ($categoria->esPersonales()){ $h->renderCampoEdad(); } ?>
				<td valign="top"><span class="std">Especifica el lugar:</span><br> - <input tabindex="1" name="lugar"  class="titulo" size="20" maxlength="40" value="<?=$anuncio->getLugar()?>"></td>
				<?php
				if ( ($_REQUEST['accion']=="editar") || ($_REQUEST['accion']=="reenviar") ){
				?>
						<td valign="top" align="left">&nbsp;&nbsp;
					<b>categoría:</b><br/>
					&nbsp;&nbsp;<select name="subcat">
						<?php
							$subcategorias=$categoria->getSubCategorias($categoria->getCategoriaId());
							foreach ($subcategorias as $subcategoria){
								$selected='';
								if ($subcategoria['subcatid']==$categoria->getSubCategoriaId()){
									$selected='SELECTED';
								}
								?>
								<option value="<?=$subcategoria['subcatid']?>" <?=$selected?>><?=$subcategoria['nombre'] ?></option>
								<?php
							}
							?>
					</select>
					</td>
				<?php
				}else{
						?>
						<td valign="top" align="right"></td>
						<?php
				}
				?>
				
			</tr>
		</tbody></table>

		<span <?=$anuncio->enRojo('descripcion')? 'class="err"': 'class="req"'?>><b>Descripción del anuncio:</b></span>
		
		<br>
		

		<textarea name="descripcion" id="descripcion" rows="10" style="width: 97%;" cols="120" tabindex="1"><?=stripslashes($anuncio->getDescripcion())?></textarea>
		
			<?php
			if ($categoria->esEmpleo()){
				$h->renderCampoRetribucion();
			}
		?>
		<?php
			if ($categoria->esTTemporal()){
				echo $h->renderCampoPago();
			}
		?>
		<br>
		 
	</fieldset>
	<?php
			if ($categoria->esEmpleo()){
				$h->renderMasDetallesEmpleo();
			}
		?>
	
	<?php
	if ($usuario->estaLogueado()){
	?>
	<fieldset>
		<legend>
			<span <?=$anuncio->enRojo('email')? 'class="err"': 'class="req"'?>>tu correo electrónico:</span>
			 <br>
		</legend>
		<input tabindex="1" name="email" size="80" value="<?=$usuario->getEmail()?>" maxlength="60" type="text" readonly="readonly" style="background: #DDDDDD">
	<br> <label class="email_no_public">Su dirección de email no será publicada.</label>
	</fieldset>
	
<?php
	}else{
		?>
	<fieldset>
		<legend>
		<span <?=$anuncio->enRojo('email')? 'class="err"': 'class="req"'?>>ingresa aquí tu correo electrónico:</span>
		 <br>
		</legend>
		<input tabindex="1" name="email" size="80" value="<?=$anuncio->getEmail()?>" maxlength="60" type="text">
		
		<br>
		<span <?=$anuncio->enRojo('email')? 'class="err"': 'class="req"'?>>aquí nuevamente tu correo electrónico:</span><br>
		<?php 
		if(empty($_POST['confirmacion_email']) && (($_REQUEST['accion']=="editar") || $_REQUEST['accion']=="reenviar")){
			?>
			<input tabindex="1" name="confirmacion_email" size="80" value="<?=$anuncio->getEmail()?>" maxlength="60" type="text">
			<?php
		}else{
			?>
			<input tabindex="1" name="confirmacion_email" size="80" value="<?=$anuncio->getConfirmacionEmail()?>" maxlength="60" type="text">
			<?php
		}?>
		<br> 
		<label class="email_no_public">tu dirección de correo electrónico no será publicado ni utilizado de ninguna manera sin tu previa autorización.</label>
	</fieldset>
		<?php	
	}
	?>
	<br>
	
	<?php 
	if ($anuncio->esAlquilerVivienda()){
	?>
	<fieldset>
		<label><input type="checkbox" name="gatosOK" value="gatosOK" <?=( $anuncio->gatosOk() )? 'checked' : ''?> tabindex="1"/>Acepto Gatos</label>
	
		<label><input type="checkbox" name="perrosOK"  value="perrosOK" <?=( $anuncio->perrosOk() ) ? 'checked' : ''?> tabindex="1"/>Acepto Perros</label>
	    <br/>
    </fieldset>
	<?php
	}
	if ($categoria->getTieneImagenes()){
    ?>
  
	<fieldset>
	<legend style="cursor: pointer;">
		<button type="button" onclick="s=document.getElementById('imgtbl').style; s.display=(s.display == 'none' ? 'block' : 'none');" class="" >Agregar / Editar imágenes</button>

	</legend>
<table <?php if ($imagenes->tengo()){ echo 'style="display:block;"'; } else { echo 'style="display: none;"'; } ?> id="imgtbl">
	<tbody><tr><td colspan="2" style="font-size: x-small;">
		<em>¿Las fotos tardan demasiado en subir?</em>    Intenta reducir el tamaño con algún programa de edición gráfica.
	</td></tr>
	<tr>
		<td width="350" align="center">
		<?php
		if ($imagenes->estaSeteada(1)){
			echo '<span class="imageLabel" id="spanimg1"><b>ya hay una imagen subida</b> <a class="puntero" onClick="quitarImagen(\'img1\')">quitar imagen</a></span>';
		}else{
			 echo '<span class="imageLabel" id="spanimg1">aún no hay ninguna imágen</span>';
		}
		?>
		</td>
<td width="350" align="center">
		<?php
		if ($imagenes->estaSeteada(2)){
			echo '<span class="imageLabel" id="spanimg2"><b>ya hay una imagen subida</b> <a class="puntero" onClick="quitarImagen(\'img2\')">quitar imagen</a></span>';
		}else{
			 echo '<span class="imageLabel" id="spanimg2">aún no hay ninguna imágen</span>';
		}
		?>
		</td> 
	</tr>
	<tr>
		<td align="center">
			Imagen 1:
			<input type="hidden" name="img1" id="img1" value="<?=$imagenes->get(1)?>" />
				<input id="img1" name="img1" onchange="" type="file">
			</td>

<td align="center">
				Imagen 2:
				<input type="hidden" name="img2" id="img2" value="<?=$imagenes->get(2)?>" />
				<input id="img2" name="img2" onchange="" type="file">
			</td>
	</tr>
	<tr>
		<td width="350" align="center">
		<?php
		if ($imagenes->estaSeteada(3)){
			echo '<span class="imageLabel" id="spanimg3"><b>ya hay una imagen subida</b> <a class="puntero" onClick="quitarImagen(\'img3\')">quitar imagen</a></span>';
		}else{
			 echo '<span class="imageLabel" id="spanimg3">aún no hay ninguna imágen</span>';
		}
		?>
		</td>
		<td width="350" align="center">
			<?php
		if ($imagenes->estaSeteada(4)){
			echo '<span class="imageLabel" id="spanimg4"><b>ya hay una imagen subida</b> <a class="puntero" onClick="quitarImagen(\'img4\')">quitar imagen</a></span>';
		}else{
			 echo '<span class="imageLabel" id="spanimg4">aún no hay ninguna imágen</span>';
		}
		?>
	    </td>
	</tr>
	<tr>
		<td align="center">
				Imagen 3:
				<input type="hidden" name="img3" id="img3" value="<?=$imagenes->get(3)?>" />
				<input id="img3" name="img3" onchange="" type="file">
			</td>
		<td align="center">
				Imagen 4:
				<input type="hidden" name="img4" id="img4" value="<?=$imagenes->get(4)?>" /> 
				<input id="img4" name="img4" onchange="" type="file">
			</td> 


	</tr>
</tbody></table>
</fieldset>

<?php
	}
	 ?>

 <fieldset>
<legend><span class="std">Permisos:</span><br></legend>

<?php
if ($categoria->esEmpleo()){
	$h->renderPermisosEmpleo();
}
?>
<input tabindex="1" id="contact_comercial" name="contact_comercial" value="contact_comercial" type="checkbox" <?=(!empty($_POST['contact_comercial'])) ? 'checked' : ''?> >
	<label for="contact_comercial">acepto contactos por otros servicios, productos u ofertas de cualquier índole.</label>
<br>
</fieldset>

<?php 
//require_once($_SERVER["DOCUMENT_ROOT"]."/includes/destacadoform.inc.php");
?>

	<br><br>
<?php
if ($_REQUEST['accion']=="editar"){
	?>
	<input tabindex="1" name="continuar" value="Guardar cambios" type="submit" id="submit">
	<?php
}else {
	?>
	<input tabindex="1" name="continuar" value="Continuar" type="submit" id="submit">
	<?php
}
?>
	


</td></tr></tbody></table>
<?=$posting_form->getFormCloseTag()?>
<script type="text/javascript">
function pegarTitulo(titulo){
	link='<p><a href="#">'+titulo.value+'</a></p>';
	document.getElementById('resdes').innerHTML=link;
}
function checkDestacado(checkbox){
	if (checkbox.checked==true){
		document.getElementById('resdes').className='destacadoLink';
	}else{
		document.getElementById('resdes').className='';
	}	
}
function checkUrgente(checkbox){
	titulo=document.getElementById('titulo');
	if (checkbox.checked==true){
		link='<p><span class="urgente">urgente!</span> <a href="#">'+titulo.value+'</a></p>';
	}else{
		link='<p><a href="#">'+titulo.value+'</a></p>';
	}
	document.getElementById('resdes').innerHTML=link;	
}
document.getElementById('titulo').focus();
pegarTitulo(document.getElementById('titulo'));
checkDestacado(document.getElementById('destacado_1'));
checkUrgente(document.getElementById('destacado_2'));
/*document.getElementById('resdes').className='destacadoLink';*/
</script>