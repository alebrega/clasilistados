<blockquote>
<form action="<?=$h->getSearchAction()?>" method="get">
<?php
if ($locacion->esCiudad()){
	?>
	<input type="hidden" name="ciudad" value="<?=$locacion->getCiudadURL()?>" />
	<?php
}else{
	?>
	<input type="hidden" name="estado" value="<?=$locacion->getEstadoURL()?>" />
	<?php
}
if ($categoria->esEvento()){
	if (!empty($_GET['fecha'])){
		?>
		<input type="hidden" name="fecha" value="<?=$_GET['fecha']?>" />
		<?php
	}else{
		?>
		<input type="hidden" name="fecha" value="<?=date("d").'-'.date("n").'-'.date("Y")?>" />
		<?php
	}	
}

?>
<table  cellpadding="2" class="tbuscador">
		<tbody><tr>
			<td width="1" align="right">buscar:</td>
			<td width="30%">
			<input type="text" size="30" id="query" name="busqueda" value="<?=$_GET['busqueda']?>"/> en:
<select id="cat" name="cat">	
	<?php
	$categorias=$categoria->getCategorias();
	
	foreach ($categorias as $categoria_array){
			$selected='';	
			if ($categoria->getCategoriaId()==$categoria_array['catid']){
					$selected='selected="selected"';
			}
			?>
			<option value="<?=$categoria_array['catid']?>" <?=$selected?>>todo <?=$categoria_array['nombre'] ?></option>
			<?php
			if ($categoria->getCategoriaId()==$categoria_array['catid']){
				
			?>
				<option disabled="disabled" value="">--</option>
			<?php
				$subcategorias=$categoria->getSubCategorias($categoria_array['catid']);
				foreach ($subcategorias as $s){
					if ($s['subcatid']==$_GET['subcat']){
						$selected='selected="selected"';
					}else{
						$selected='';	
					}
					?>
					<option value="<?=$categoria_array['catid'].'---'.$s['subcatid']?>" <?=$selected?>><?=$s['nombre'] ?></option>
					<?php 
				}
				?>
				<option disabled="disabled" value="">--</option>
				<?php 
			
			}
	}
	?>
</select>
				<input value="Buscar" type="submit">
			</td>
			<td>
				<label><input name="busqTipo" value="busq_tit" title="selecciona aquí si solo quieres buscar en el título" type="checkbox" <?=(!empty($_GET['busqTipo']))?'checked':'' ?>> buscar solo títulos</label>
			</td>
		</tr>
	
		<tr>
			<?php
			if ($categoria->esEmpleo()){
				?>
				<td></td>
				<td>
				<label> <input name="teletrabajo" value="1" type="checkbox" <?=(!empty($_GET['teletrabajo']))?'checked':'' ?>> teletrabajo</label>
				<label> <input name="contrato" value="1" type="checkbox" <?=(!empty($_GET['contrato']))?'checked':'' ?>> contrato</label>
				<label> <input name="pasantia" value="1" type="checkbox" <?=(!empty($_GET['pasantia']))?'checked':'' ?>> pasantía</label>
				<label> <input name="tiempo_parcial" value="1" type="checkbox" <?=(!empty($_GET['tiempo_parcial']))?'checked':'' ?>> tiempo parcial</label>
				<label> <input name="org_sinlucro" value="1" type="checkbox" <?=(!empty($_GET['org_sinlucro']))?'checked':'' ?>> org. sin lucro</label>
				</td>
				<?php 
			}elseif ($categoria->esCompraVenta() || $categoria->esVehiculos() || $categoria->esVivienda()){
				if ($categoria->esVivienda()){
					?>
					<td width="1" align="right">alquiler:</td>
					<?php 
				}else{
					?>
					<td width="1" align="right">precio:</td>
					<?php
				}
			?>
			<td><input style="color: rgb(170, 170, 170);" name="precio_min" id="precio_min" class="min" size="6" value="<?=(empty($_GET['precio_min']))?'min':$_GET['precio_min']?>" onClick="vaciarCampo(this.id,'min')">&nbsp;<input style="color: rgb(170, 170, 170);" name="precio_max" class="max" id="precio_max" size="6" value="<?=(empty($_GET['precio_max']))?'max':$_GET['precio_max']?>" onClick="vaciarCampo(this.id,'max')" >&nbsp;
			<?php 
			if ($categoria->esVivienda()){
			?>
			&nbsp;&nbsp;&nbsp;&nbsp;<label> <input name="gatos_ok" value="1" type="checkbox" title="selecciona aquí si quieres anuncios que acepten gatos" <?=(!empty($_GET['gatos_ok']))?'checked':'' ?>> gatos</label>
			<label> <input name="perros_ok" value="1" type="checkbox" title="selecciona aquí si quieres anuncios que acepten perros" <?=(!empty($_GET['perros_ok']))?'checked':'' ?>> perros</label>
			<?php 
			}
			?>
			</td>
			<?php 	
			}else{
				?>
				<td width="1" align="right"></td>
				<td></td>
				<?php 
			}
			?>
			<td align="left">
				<label> <input name="busq_img" value="1" type="checkbox" title="selecciona aquí si solo quieres anuncios con foto" <?=(!empty($_GET['busq_img']))?'checked':'' ?>> anuncio con foto</label>
				
			<label> <input name="buscarPorPais" value="1" type="checkbox" title="selecciona aquí si quieres anuncios de <?=HOME_PAIS?>" <?=(!empty($_GET['buscarPorPais']))?'checked':'' ?>> en todo <?=HOME_PAIS?></label>
			<label> <input name="urg" value="1" type="checkbox" title="selecciona aquí si quieres anuncios urgentes" <?=(!empty($_GET['urg']))?'checked':'' ?>> <span class="urgentetit">urgente!</span></label>
			
			</td>
		</tr>
		
		</tbody></table></form></blockquote>