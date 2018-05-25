<table width="100%">
  <tbody><tr>
    <td>

      <table width="100%" bgcolor="#ffffff" border="0">
        <tbody>
          <tr>
            <td><?php require_once($_SERVER["DOCUMENT_ROOT"]."/cuenta_header.php"); ?></td>

            <td align="right">
              <form action="<?=$h->getPublicarAnunciosLink()?>" method="post"><font size="2">anúnciate en:&nbsp;
           <?php require_once($_SERVER["DOCUMENT_ROOT"]."/cuenta_combo_ciudades_estados.php"); ?>
<input value="ir" type="submit" name="cuenta_admin_publicar"></font></form>
            </td>

          </tr>
        </tbody>
      </table>
    </td>
  </tr>
</tbody></table>

<blockquote>



<p>&nbsp;</p>

<table width="100%" cellpadding="2">

  

  <tbody>
  <?php
  if (empty($_REQUEST['buscarAnuncios'])){
  ?>
  <tr><td>se estan mostrando anuncios de los ultimos <?=CANT_DIAS_MENOS_LISTADO_USUARIOS?> dias, puedes buscar abajo otros anuncios mas antiguos</td></tr>
    <tr><td></td></tr>
  <?php
  }
  ?>
  <tr>
    <td>
    
<fieldset><legend>buscar anuncios<br></legend>
<table>
  <tbody><tr>
    <td>&nbsp;&nbsp;desde el<br>&nbsp;&nbsp;hasta </td>

    <td>
      <form action="<?=$h->getMiCuentaLink()?>" method="get">
      <table>
        <tbody><tr>
          <th>dia</th>
          <th>&nbsp;</th>

          <th>mes</th>
          <th>&nbsp;</th>
          <th>año</th>
	  <th>&nbsp;</th>
        </tr>
        <tr>             
	  <td><input class="df" name="diaDesde" size="2" maxlength="2" value="<?=$diaDesde?>" type="text"> </td>
	    <td>&nbsp;/&nbsp;</td>     
	     <td><input name="mesDesde" size="2" maxlength="2" value="<?=$mesDesde?>" type="text"></td>
	      <td>&nbsp;/&nbsp;</td>       
	      <td><input name="anioDesde" size="4" maxlength="4" value="<?=$anioDesde?>" type="text"></td>
	  <td rowspan="2">   
	    <select onchange="seleccFecha(this);">
	    <option value="">elige un rango de tiempo
	    </option><option value="ultimos3meses">ultimos 3 meses
</option><option value="3-6meses">3 - 6 meses
</option><option value="6-9meses">6 - 9 meses
</option><option value="9-12meses">9 - 12 meses

	    </option><option value="clear">limpiar fechas
	    </option></select>
	  </td>

	</tr>
	<tr> 
	   <td><input name="diaHasta" size="2" maxlength="2" value="<?=$diaHasta?>" type="text"></td>
	   <td>&nbsp;/&nbsp;</td>
	  <td><input name="mesHasta" size="2" maxlength="2" value="<?=$mesHasta?>" type="text"></td>
	  <td>&nbsp;/&nbsp;</td>
	   <td><input name="anioHasta" size="4" maxlength="4" value="<?=$anioHasta?>" type="text"></td>
	</tr>

	<tr>
	  <td colspan="6"><input value="buscar tus anuncios publicados" type="submit" name="buscarAnuncios"></td>
	</tr>
      </tbody></table>

    </form></td>
  </tr>
</tbody></table>
</fieldset>

    </td>
  </tr>


  <tr>
    <td align="left">
      <table>
        <tbody><tr>
          <td>[&nbsp;</td>
         <td bgcolor="<?=COLOR_ADMIN_ACTIVO ?>">activo</td>
          <td bgcolor="<?=COLOR_ADMIN_PENDIENTE ?>">pendiente</td>
          <td bgcolor="<?=COLOR_ADMIN_BORRADO_POR_MI ?>">borrado</td>
          <td bgcolor="<?=COLOR_ADMIN_VENCIDO ?>">vencido</td>
          <td bgcolor="<?=COLOR_ADMIN_BORRADO_MARCADO ?>">marcado</td>
          <td>&nbsp;]</td>

        </tr>
      </tbody></table>
    </td>
  </tr>
  <tr>
    <td colspan="3"></td>
  </tr>
  <tr>

    <td colspan="3"><font size="2"><i>todas las horas son de la costa oeste de EE.UU.</i></font></td>
  </tr>
</tbody></table>
<p>
</p>
<?php
if ($usuario->getCantidadAnuncios()>0){
?>
<p>cantidad de anuncios encontrados: <?=$usuario->getCantidadAnuncios()?></p>
  <table width="100%" border="0" cellpadding="3">
    <tbody><tr>
      <th class="bloque_borde_gris">id del anuncio</th>
      <th class="bloque_borde_gris">fecha</th>

      <th class="bloque_borde_gris">sitio</th>
      <th class="bloque_borde_gris">categoría</th>
      <th class="bloque_borde_gris">título</th>
      <th class="bloque_borde_gris">costo</th>
    </tr>
					<?php
						echo $html_anuncios_usuarios;
					?>	
  </tbody></table>
<?php
}else{
	?>
	<p>no se han encontrado anuncios.</p>
	<?php
}
?>
<p>

</p></blockquote>
